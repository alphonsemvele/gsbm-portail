<?php

namespace App\Services;

use App\Exceptions\DoublonInscriptionException;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Specialite;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

/**
 * Regles des inscriptions scolaires : classes, matricules, doublons, import Excel.
 */
class InscriptionService
{
    /** Libelles utilises par l'ecole (fiches, Excel) => debut du nom de la classe en base. Dans l'ordre d'affichage. */
    public const CLASSES = [
        'PETITE SECTION' => 'Petite Section',
        'MOYENNE SECTION' => 'Moyenne Section',
        'GRANDE SECTION' => 'Grande Section',
        'SIL' => 'SIL',
        'CP' => 'Cours Préparatoire',
        'CE1' => 'Cours Élémentaire 1',
        'CE2' => 'Cours Élémentaire 2',
        'CM1' => 'Cours Moyen 1',
        'CM2' => 'Cours Moyen 2',
        'PRE NURSERY' => 'Pre-Nursery',
        'NURSERY 1' => 'Nursery 1',
        'NURSERY 2' => 'Nursery 2',
        'KINDERGARTEN' => 'Kindergarten',
        'KG' => 'Kindergarten',
        'CLASS 1' => 'Class 1',
        'CLASS 2' => 'Class 2',
        'CLASS 3' => 'Class 3',
        'CLASS 4' => 'Class 4',
        'CLASS 5' => 'Class 5',
        'CLASS 6' => 'Class 6',
    ];

    private ?Collection $classes = null;

    /** Annee scolaire en cours : elle bascule en aout. */
    public static function anneeCourante(): string
    {
        $annee = (int) now()->format('Y');

        return now()->month >= 8 ? $annee.'-'.($annee + 1) : ($annee - 1).'-'.$annee;
    }

    /**
     * Cle de doublon : nom complet en majuscules, sans accents ni ponctuation,
     * mots tries. « Felicie ATANGANA Héloise » et « ATANGANA Heloise Felicie »
     * donnent la meme cle.
     */
    public static function cleDoublon(?string ...$parties): string
    {
        $texte = Str::upper(Str::ascii(implode(' ', array_filter($parties))));
        $mots = preg_split('/\s+/', trim(preg_replace('/[^A-Z0-9]+/', ' ', $texte)), -1, PREG_SPLIT_NO_EMPTY);
        sort($mots);

        return implode(' ', $mots);
    }

    /** « ABENG Ange Gabriel » => ['ABENG', 'Ange Gabriel'] : les mots en capitales forment le nom. */
    public static function separerNomPrenoms(string $complet): array
    {
        $nom = [];
        $prenoms = [];

        foreach (preg_split('/\s+/', trim($complet), -1, PREG_SPLIT_NO_EMPTY) as $mot) {
            $lettres = preg_replace('/[^\p{L}]/u', '', $mot);
            (mb_strlen($lettres) > 1 && mb_strtoupper($lettres) === $lettres) ? $nom[] = $mot : $prenoms[] = $mot;
        }

        if ($nom === []) {
            return [trim($complet), null];
        }

        return [implode(' ', $nom), $prenoms ? implode(' ', $prenoms) : null];
    }

    /** Classes actives de l'ecole, dans l'ordre des sections et des niveaux. */
    public function classes(): Collection
    {
        if ($this->classes === null) {
            $ordre = array_values(array_unique(self::CLASSES));

            $this->classes = Specialite::with('filiere.departement')
                ->where('status', 'Success')
                ->whereHas('filiere', fn ($q) => $q->where('code', 'like', 'GSBM-%'))
                ->get()
                ->sortBy(function (Specialite $classe) use ($ordre) {
                    foreach ($ordre as $rang => $debut) {
                        if ($this->commencePar($classe->name, $debut)) {
                            return $rang;
                        }
                    }

                    return 999;
                })
                ->values();
        }

        return $this->classes;
    }

    public function classeDepuisLibelle(?string $libelle): ?Specialite
    {
        $cle = Str::upper(Str::ascii(trim(preg_replace('/[\s_-]+/', ' ', (string) $libelle))));
        $cle = preg_replace('/^CLASS\s*(\d)$/', 'CLASS $1', $cle);
        $debut = self::CLASSES[$cle] ?? null;

        return $debut ? $this->classes()->first(fn ($c) => $this->commencePar($c->name, $debut)) : null;
    }

    /** M = maternelle francophone, N = nursery, P = primaire (format du registre de l'ecole). */
    public function prefixeMatricule(?Specialite $classe): string
    {
        $code = (string) $classe?->filiere?->code;

        return match (true) {
            str_ends_with($code, '-MAT') => 'M',
            str_ends_with($code, '-NUR') => 'N',
            default => 'P',
        };
    }

    /** Matricules annonces par le fichier en cours d'import : la numerotation automatique ne doit pas les prendre. */
    private array $matriculesReserves = [];

    public function genererMatricule(?Specialite $classe, string $annee): string
    {
        $prefixe = $this->prefixeMatricule($classe).substr($annee, 0, 4).'/';

        $dernier = Eleve::where('matricule', 'like', $prefixe.'%')
            ->pluck('matricule')
            ->merge(array_filter($this->matriculesReserves, fn ($m) => str_starts_with($m, $prefixe)))
            ->map(fn ($m) => (int) substr($m, strlen($prefixe)))
            ->max() ?? 0;

        return $prefixe.($dernier + 1);
    }

    /** Eleves deja enregistres qui ressemblent a celui-ci (meme matricule ou meme nom complet). */
    public function eleveSimilaires(?string $nom, ?string $prenoms, ?string $matricule = null, ?int $sauf = null): Collection
    {
        $cle = self::cleDoublon($nom, $prenoms);

        if ($cle === '' && blank($matricule)) {
            return collect();
        }

        return Eleve::with('inscriptions.classe')
            ->where(function ($q) use ($cle, $matricule) {
                $q->when($cle !== '', fn ($q) => $q->where('cle_doublon', $cle))
                  ->when(filled($matricule), fn ($q) => $q->orWhere('matricule', trim($matricule)));
            })
            ->when($sauf, fn ($q) => $q->whereKeyNot($sauf))
            ->get();
    }

    /**
     * Inscrit ou reinscrit un eleve. Refuse tout doublon : un matricule deja
     * attribue a un autre enfant, un homonyme non confirme, ou une deuxieme
     * inscription la meme annee.
     */
    public function inscrire(array $eleve, array $inscription, string $type, string $source, ?int $eleveId = null, bool $homonymeConfirme = false, ?int $saisiPar = null): Inscription
    {
        return DB::transaction(function () use ($eleve, $inscription, $type, $source, $eleveId, $homonymeConfirme, $saisiPar) {
            $annee = $inscription['annee_scolaire'];
            $eleve = $this->nettoyer($eleve);
            $existant = $eleveId ? Eleve::lockForUpdate()->findOrFail($eleveId) : null;

            if (! $existant && filled($eleve['matricule'] ?? null)) {
                $existant = Eleve::where('matricule', $eleve['matricule'])->first();

                if ($existant && $existant->cle_doublon !== self::cleDoublon($eleve['nom'] ?? '', $eleve['prenoms'] ?? null)) {
                    throw new DoublonInscriptionException("Le matricule {$eleve['matricule']} est déjà attribué à {$existant->nomComplet()}.", $existant);
                }
            }

            if (! $existant && ! $homonymeConfirme) {
                $homonyme = Eleve::where('cle_doublon', self::cleDoublon($eleve['nom'] ?? '', $eleve['prenoms'] ?? null))->first();

                if ($homonyme) {
                    throw new DoublonInscriptionException(
                        "Un élève nommé « {$homonyme->nomComplet()} » existe déjà".($homonyme->matricule ? " (matricule {$homonyme->matricule})" : '').'. '
                        ."S'il s'agit de lui, faites une réinscription ; sinon confirmez qu'il s'agit d'un homonyme.",
                        $homonyme
                    );
                }
            }

            if ($existant && $existant->estInscrit($annee)) {
                throw new DoublonInscriptionException("{$existant->nomComplet()} est déjà inscrit(e) pour l'année {$annee}.", $existant);
            }

            $classe = $this->classes()->firstWhere('id', (int) ($inscription['specialite_id'] ?? 0));
            $fiche = $existant ?? new Eleve();
            $fiche->fill($existant ? array_filter($eleve, fn ($v) => $v !== null) : $eleve);
            $fiche->cle_doublon = self::cleDoublon($fiche->nom, $fiche->prenoms);
            $fiche->matricule = $fiche->matricule ?: $this->genererMatricule($classe, $annee);
            $fiche->save();

            return $fiche->inscriptions()->create($this->nettoyer($inscription) + [
                'type' => $type,
                'source' => $source,
                'saisi_par' => $saisiPar,
            ]);
        });
    }

    /** Met a jour une inscription et la fiche de l'eleve, sans permettre de doublon d'annee. */
    public function modifier(Inscription $inscription, array $eleve, array $donnees): Inscription
    {
        return DB::transaction(function () use ($inscription, $eleve, $donnees) {
            $fiche = $inscription->eleve;

            if ($fiche->estInscrit($donnees['annee_scolaire'], $inscription->id)) {
                throw new DoublonInscriptionException("{$fiche->nomComplet()} est déjà inscrit(e) pour l'année {$donnees['annee_scolaire']}.", $fiche);
            }

            $eleve = $this->nettoyer($eleve);

            if (filled($eleve['matricule'] ?? null) && Eleve::where('matricule', $eleve['matricule'])->whereKeyNot($fiche->id)->exists()) {
                throw new DoublonInscriptionException("Le matricule {$eleve['matricule']} est déjà attribué à un autre élève.");
            }

            $fiche->fill($eleve);
            $fiche->cle_doublon = self::cleDoublon($fiche->nom, $fiche->prenoms);
            $fiche->save();
            $inscription->update($this->nettoyer($donnees));

            return $inscription;
        });
    }

    /**
     * Lit un classeur d'inscriptions (une ou plusieurs feuilles au format du
     * registre de l'ecole), fusionne les lignes d'un meme eleve et compare au
     * registre existant. Sans $appliquer, rien n'est enregistre : c'est l'apercu.
     */
    public function importer(string $chemin, string $annee, bool $appliquer = false, ?int $saisiPar = null): array
    {
        $rapport = [
            'annee' => $annee, 'lignes' => 0, 'eleves' => 0, 'feuilles' => [],
            'doublons_fichier' => [], 'conflits_classe' => [], 'classes_inconnues' => [],
            'deja_inscrits' => [], 'matricules_en_conflit' => [], 'a_importer' => [],
            'importes' => 0, 'erreurs' => [],
        ];

        $eleves = [];

        foreach (IOFactory::load($chemin)->getWorksheetIterator() as $feuille) {
            $colonnes = null;
            $lignesFeuille = 0;

            foreach ($feuille->toArray(null, true, true, false) as $ligne) {
                if ($colonnes === null) {
                    $colonnes = $this->colonnes($ligne);
                    continue;
                }

                $complet = trim(preg_replace('/\s+/', ' ', (string) ($ligne[$colonnes['nom']] ?? '')));

                if ($complet === '') {
                    continue;
                }

                $lignesFeuille++;
                $valeur = fn (string $cle) => isset($colonnes[$cle]) ? (trim((string) ($ligne[$colonnes[$cle]] ?? '')) ?: null) : null;
                $cle = self::cleDoublon($complet);
                $donnees = [
                    'complet' => $complet,
                    'classe' => $valeur('classe'),
                    'matricule' => $valeur('matricule'),
                    'inscrit_par' => $valeur('inscrit_par'),
                    'pere_nom' => $valeur('pere_nom'),
                    'pere_telephone' => $valeur('pere_telephone'),
                    'mere_nom' => $valeur('mere_nom'),
                    'mere_telephone' => $valeur('mere_telephone'),
                    'lieu_residence' => $valeur('residence'),
                    'urgence_nom' => $valeur('urgence_nom'),
                    'urgence_telephone' => $valeur('urgence_telephone'),
                    'remise' => $valeur('remise'),
                    'tranche' => $valeur('tranche'),
                ];

                if (! isset($eleves[$cle])) {
                    $eleves[$cle] = $donnees + ['feuilles' => [$feuille->getTitle()], 'occurrences' => 1];
                    continue;
                }

                // Meme eleve sur plusieurs lignes : on complete, et on signale une classe contradictoire.
                $eleves[$cle]['occurrences']++;
                $eleves[$cle]['feuilles'][] = $feuille->getTitle();

                if ($donnees['classe'] && $eleves[$cle]['classe'] && Str::upper($donnees['classe']) !== Str::upper($eleves[$cle]['classe'])) {
                    $rapport['conflits_classe'][$cle] = ['nom' => $complet, 'classes' => array_values(array_unique(array_merge($rapport['conflits_classe'][$cle]['classes'] ?? [$eleves[$cle]['classe']], [$donnees['classe']])))];
                }

                foreach ($donnees as $champ => $v) {
                    $eleves[$cle][$champ] ??= $v;
                }
            }

            if ($colonnes !== null) {
                $rapport['feuilles'][$feuille->getTitle()] = $lignesFeuille;
                $rapport['lignes'] += $lignesFeuille;
            }
        }

        $rapport['eleves'] = count($eleves);

        // Les matricules deja portes par le fichier sont reserves, et ces eleves passent en premier :
        // la numerotation automatique des autres ne peut plus leur prendre leur numero.
        $this->matriculesReserves = array_values(array_filter(array_column($eleves, 'matricule')));
        uasort($eleves, fn ($a, $b) => (int) blank($a['matricule']) <=> (int) blank($b['matricule']));

        foreach ($eleves as $cle => $e) {
            if ($e['occurrences'] > 1) {
                $rapport['doublons_fichier'][] = ['nom' => $e['complet'], 'occurrences' => $e['occurrences'], 'feuilles' => array_values(array_unique($e['feuilles']))];
            }

            if (isset($rapport['conflits_classe'][$cle])) {
                continue;
            }

            $classe = $this->classeDepuisLibelle($e['classe']);

            if (! $classe) {
                $rapport['classes_inconnues'][] = ['nom' => $e['complet'], 'classe' => $e['classe'] ?? '(vide)'];
                continue;
            }

            $parMatricule = $e['matricule'] ? Eleve::where('matricule', $e['matricule'])->first() : null;

            if ($parMatricule && $parMatricule->cle_doublon !== $cle) {
                $rapport['matricules_en_conflit'][] = ['nom' => $e['complet'], 'matricule' => $e['matricule'], 'deja_attribue_a' => $parMatricule->nomComplet()];
                continue;
            }

            $existant = $parMatricule ?? Eleve::where('cle_doublon', $cle)->first();

            if ($existant?->estInscrit($annee)) {
                $rapport['deja_inscrits'][] = ['nom' => $e['complet'], 'matricule' => $existant->matricule];
                continue;
            }

            [$nom, $prenoms] = self::separerNomPrenoms($e['complet']);
            $type = $existant && $existant->inscriptions()->exists() ? 'reinscription' : 'inscription';
            $rapport['a_importer'][] = ['nom' => $e['complet'], 'classe' => $classe->name, 'matricule' => $existant?->matricule ?? $e['matricule'], 'type' => $type];

            if (! $appliquer) {
                continue;
            }

            try {
                $this->inscrire(
                    [
                        'nom' => $nom, 'prenoms' => $prenoms, 'matricule' => $e['matricule'],
                        'pere_nom' => $e['pere_nom'], 'pere_telephone' => $e['pere_telephone'],
                        'mere_nom' => $e['mere_nom'], 'mere_telephone' => $e['mere_telephone'],
                        'lieu_residence' => $e['lieu_residence'],
                        'urgence_nom' => $e['urgence_nom'], 'urgence_telephone' => $e['urgence_telephone'],
                    ],
                    [
                        'annee_scolaire' => $annee,
                        'specialite_id' => $classe->id,
                        'inscrit_par' => $e['inscrit_par'],
                        'remise_10' => filled($e['remise']) && ! in_array(Str::upper($e['remise']), ['NON', 'NO', '0'], true),
                        'tranche_1' => $this->montant($e['tranche']),
                    ],
                    $type,
                    'import',
                    $existant?->id,
                    true,
                    $saisiPar
                );
                $rapport['importes']++;
            } catch (Throwable $ex) {
                $rapport['erreurs'][] = ['nom' => $e['complet'], 'erreur' => $ex->getMessage()];
            }
        }

        return $rapport;
    }

    /** Repere la ligne d'entete du registre et la position de chaque colonne. */
    private function colonnes(array $ligne): ?array
    {
        $colonnes = [];
        $contacts = [];

        foreach ($ligne as $i => $entete) {
            $e = Str::upper(Str::ascii(trim((string) $entete)));

            match (true) {
                str_contains($e, 'NOMS ET PRENOMS') => $colonnes['nom'] = $i,
                $e === 'CLASSE' => $colonnes['classe'] = $i,
                str_starts_with($e, 'MATRICULE') => $colonnes['matricule'] = $i,
                str_contains($e, 'AYANT INSCRIT') => $colonnes['inscrit_par'] = $i,
                str_contains($e, 'NOM DU PERE') => $colonnes['pere_nom'] = $i,
                str_contains($e, 'NOM DE LA MERE') => $colonnes['mere_nom'] = $i,
                str_contains($e, 'RESIDENCE') => $colonnes['residence'] = $i,
                str_contains($e, 'URGENCE') => $colonnes['urgence_nom'] = $i,
                $e === 'CONTACT2' => $colonnes['urgence_telephone'] = $i,
                $e === 'CONTACT' => $contacts[] = $i,
                str_contains($e, 'REMISE') => $colonnes['remise'] = $i,
                str_contains($e, 'TRANCHE') => $colonnes['tranche'] = $i,
                default => null,
            };
        }

        if (! isset($colonnes['nom'])) {
            return null;
        }

        // Le premier « CONTACT » suit le pere, le second la mere.
        foreach ($contacts as $i) {
            if (isset($colonnes['mere_nom']) && $i > $colonnes['mere_nom']) {
                $colonnes['mere_telephone'] ??= $i;
            } else {
                $colonnes['pere_telephone'] ??= $i;
            }
        }

        return $colonnes;
    }

    private function montant(?string $texte): ?float
    {
        $chiffres = preg_replace('/[^\d]/', '', (string) $texte);

        return $chiffres === '' ? null : (float) $chiffres;
    }

    /** Chaines vides => null, espaces superflus retires. */
    private function nettoyer(array $valeurs): array
    {
        return array_map(fn ($v) => is_string($v) ? (trim($v) === '' ? null : trim($v)) : $v, $valeurs);
    }

    private function commencePar(string $nom, string $debut): bool
    {
        $nom = Str::lower(Str::ascii($nom));
        $debut = Str::lower(Str::ascii($debut));

        return $nom === $debut || str_starts_with($nom, $debut.' ') || str_starts_with($nom, $debut.'—') || str_starts_with($nom, $debut.' —') || str_starts_with($nom, $debut.' (');
    }
}
