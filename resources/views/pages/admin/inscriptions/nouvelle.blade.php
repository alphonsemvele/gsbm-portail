<?php
use function Laravel\Folio\{name, middleware};
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use App\Exceptions\DoublonInscriptionException;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Services\InscriptionService;

name('admin.inscriptions.nouvelle');
middleware(['auth', 'verified', 'role']);

new class extends Component {
    public ?int $inscriptionId = null;
    public string $type = 'inscription';
    public ?int $eleveId = null;
    public string $recherche = '';
    public bool $homonymeConfirme = false;
    public string $erreur = '';

    public array $eleve = [
        'nom' => '', 'prenoms' => '', 'matricule' => '', 'sexe' => '', 'date_naissance' => '', 'lieu_naissance' => '',
        'nationalite' => 'Camerounaise', 'langue_maternelle' => '', 'lieu_residence' => '',
        'pere_nom' => '', 'pere_profession' => '', 'pere_telephone' => '', 'pere_adresse' => '',
        'mere_nom' => '', 'mere_profession' => '', 'mere_telephone' => '', 'mere_adresse' => '',
        'tuteur_nom' => '', 'tuteur_lien' => '', 'tuteur_telephone' => '',
        'allergies' => '', 'besoins_particuliers' => '', 'urgence_nom' => '', 'urgence_telephone' => '',
    ];

    public array $insc = [
        'annee_scolaire' => '', 'specialite_id' => '', 'redoublant' => false, 'redoublant_classe' => '',
        'etablissement_precedent' => '', 'classe_precedente' => '', 'resultat' => '', 'moyenne_annuelle' => '',
        'raison_changement' => '', 'inscrit_par' => '', 'remise_10' => false, 'tranche_1' => '',
    ];

    public function mount(): void
    {
        $this->insc['annee_scolaire'] = (string) request('annee', InscriptionService::anneeCourante());
        $this->type = request('type') === 'reinscription' ? 'reinscription' : 'inscription';

        if ($id = (int) request('id')) {
            $inscription = Inscription::with('eleve')->findOrFail($id);
            $this->inscriptionId = $inscription->id;
            $this->type = $inscription->type;
            $this->eleveId = $inscription->eleve_id;
            $this->remplirEleve($inscription->eleve);

            foreach (array_keys($this->insc) as $champ) {
                $valeur = $inscription->{$champ};
                $this->insc[$champ] = is_bool($valeur) ? $valeur : (string) ($valeur ?? '');
            }
        } elseif ($eleve = (int) request('eleve')) {
            $this->choisirEleve($eleve);
        }
    }

    #[Computed]
    public function classes()
    {
        return app(InscriptionService::class)->classes()->groupBy(fn ($c) => $c->filiere?->departement?->nom ?? 'Autres');
    }

    #[Computed]
    public function resultatsRecherche()
    {
        $terme = trim($this->recherche);

        if ($this->eleveId || mb_strlen($terme) < 2) {
            return collect();
        }

        return Eleve::with('inscriptions.classe')
            ->where(fn ($q) => $q->where('nom', 'like', "%{$terme}%")
                ->orWhere('prenoms', 'like', "%{$terme}%")
                ->orWhere('matricule', 'like', "%{$terme}%")
                ->orWhere('cle_doublon', 'like', '%'.InscriptionService::cleDoublon($terme).'%'))
            ->orderBy('nom')->limit(10)->get();
    }

    /** Controle des doublons, recalcule a chaque saisie du nom, des prenoms ou du matricule. */
    #[Computed]
    public function alertes(): array
    {
        $annee = $this->insc['annee_scolaire'];
        $alertes = [];

        if ($this->eleveId) {
            $fiche = Eleve::find($this->eleveId);

            if ($fiche?->estInscrit($annee, $this->inscriptionId)) {
                $alertes[] = ['bloquant' => true, 'texte' => "{$fiche->nomComplet()} est déjà inscrit(e) pour l'année {$annee}.", 'eleve' => $fiche];
            }

            return $alertes;
        }

        foreach (app(InscriptionService::class)->eleveSimilaires($this->eleve['nom'], $this->eleve['prenoms'], $this->eleve['matricule']) as $similaire) {
            $dejaInscrit = $similaire->estInscrit($annee);
            $memeMatricule = filled($this->eleve['matricule']) && $similaire->matricule === trim($this->eleve['matricule']);

            $alertes[] = [
                'bloquant' => $dejaInscrit || $memeMatricule,
                'eleve' => $similaire,
                'texte' => match (true) {
                    $dejaInscrit => "{$similaire->nomComplet()} ({$similaire->matricule}) est déjà inscrit(e) pour {$annee} : pas de nouvelle inscription possible.",
                    $memeMatricule => "Le matricule {$similaire->matricule} appartient déjà à {$similaire->nomComplet()}.",
                    default => "Un élève du même nom existe déjà : {$similaire->nomComplet()} ({$similaire->matricule}), dernière classe : "
                        .($similaire->inscriptions->first()?->classe?->name ?? '—').'.',
                },
            ];
        }

        return $alertes;
    }

    public function choisirEleve(int $id): void
    {
        $fiche = Eleve::with('inscriptions.classe')->findOrFail($id);
        $this->eleveId = $fiche->id;
        $this->type = 'reinscription';
        $this->recherche = '';
        $this->remplirEleve($fiche);

        if ($derniere = $fiche->inscriptions->first()) {
            $this->insc['classe_precedente'] = (string) $derniere->classe?->name;
            $this->insc['etablissement_precedent'] = config('etablissement.nom');
        }
    }

    public function changerEleve(): void
    {
        $this->reset('eleveId', 'eleve');
        $this->insc['classe_precedente'] = '';
        $this->insc['etablissement_precedent'] = '';
    }

    public function enregistrer(InscriptionService $service)
    {
        $this->erreur = '';
        $this->validate([
            'eleve.nom' => 'required|string|max:120',
            'eleve.prenoms' => 'nullable|string|max:150',
            'eleve.matricule' => 'nullable|string|max:40',
            'eleve.sexe' => 'nullable|in:M,F',
            'eleve.date_naissance' => 'nullable|date|before:today',
            'eleve.*_telephone' => 'nullable|string|max:40',
            'insc.annee_scolaire' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'insc.specialite_id' => 'required|integer|exists:specialites,id',
            'insc.resultat' => 'nullable|in:admis,redoublant,transfere',
            'insc.moyenne_annuelle' => 'nullable|numeric|between:0,20',
            'insc.tranche_1' => 'nullable|numeric|min:0',
        ], [
            'eleve.nom.required' => "Le nom de l'élève est obligatoire.",
            'insc.specialite_id.required' => 'Choisissez la classe.',
            'insc.annee_scolaire.regex' => "L'année scolaire doit être au format 2026-2027.",
            'eleve.date_naissance.before' => 'La date de naissance doit être passée.',
        ]);

        if ($this->type === 'reinscription' && ! $this->eleveId) {
            $this->erreur = "Pour une réinscription, recherchez et choisissez d'abord l'élève.";
            return;
        }

        try {
            $inscription = $this->inscriptionId
                ? $service->modifier(Inscription::findOrFail($this->inscriptionId), $this->eleve, $this->insc)
                : $service->inscrire($this->eleve, $this->insc, $this->type, 'en_ligne', $this->eleveId, $this->homonymeConfirme, auth()->id());
        } catch (DoublonInscriptionException $e) {
            $this->erreur = $e->getMessage();
            return;
        }

        $fiche = $inscription->eleve;
        session()->flash('inscription_message', ($this->inscriptionId ? 'Inscription mise à jour : ' : ($this->type === 'inscription' ? 'Élève inscrit : ' : 'Élève réinscrit : '))
            ."{$fiche->nomComplet()} — matricule {$fiche->matricule}, {$inscription->classe?->name}, {$inscription->annee_scolaire}.");

        return $this->redirect('/admin/inscriptions?annee='.$inscription->annee_scolaire);
    }

    private function remplirEleve(Eleve $fiche): void
    {
        foreach (array_keys($this->eleve) as $champ) {
            $valeur = $fiche->{$champ};
            $this->eleve[$champ] = $valeur instanceof \DateTimeInterface ? $valeur->format('Y-m-d') : (string) ($valeur ?? '');
        }
    }
};
?>

<x-layouts.app header="true">
    @volt
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @php($champ = 'mt-1 w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500')
        @php($bloquant = collect($this->alertes)->contains('bloquant', true))

        <a href="/admin/inscriptions?annee={{ $insc['annee_scolaire'] }}" class="text-sm text-indigo-600 hover:underline">← Liste des inscriptions</a>
        <h1 class="text-3xl font-bold text-gray-800 mt-2">
            {{ $inscriptionId ? 'Modifier la fiche' : ($type === 'inscription' ? 'Fiche d’inscription' : 'Fiche de réinscription') }}
        </h1>
        <p class="text-gray-500 mb-6">Année scolaire {{ $insc['annee_scolaire'] }} · {{ $type === 'inscription' ? 'nouvel élève' : 'ancien élève' }}</p>

        @unless ($inscriptionId)
            <div class="inline-flex rounded-lg border border-gray-300 overflow-hidden mb-6 text-sm">
                <a href="/admin/inscriptions/nouvelle?type=inscription&annee={{ $insc['annee_scolaire'] }}" class="px-4 py-2 {{ $type === 'inscription' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700' }}">Inscription — nouveaux</a>
                <a href="/admin/inscriptions/nouvelle?type=reinscription&annee={{ $insc['annee_scolaire'] }}" class="px-4 py-2 {{ $type === 'reinscription' ? 'bg-emerald-600 text-white' : 'bg-white text-gray-700' }}">Réinscription — anciens</a>
            </div>
        @endunless

        @if ($type === 'reinscription' && ! $eleveId)
            <div class="bg-white rounded-xl shadow p-6 mb-6">
                <label class="font-semibold text-gray-800">Rechercher l'ancien élève</label>
                <input type="search" wire:model.live.debounce.250ms="recherche" autofocus placeholder="Nom, prénom ou matricule (au moins 2 lettres)" class="{{ $champ }}">
                <div class="mt-3 divide-y divide-gray-100">
                    @foreach ($this->resultatsRecherche as $r)
                        @php($annee = $insc['annee_scolaire'])
                        <div class="py-2 flex items-center justify-between" wire:key="res-{{ $r->id }}">
                            <div>
                                <p class="font-medium text-gray-800">{{ $r->nomComplet() }} <span class="font-mono text-xs text-gray-500">{{ $r->matricule }}</span></p>
                                <p class="text-xs text-gray-500">{{ $r->inscriptions->map(fn ($h) => $h->annee_scolaire.' : '.($h->classe?->name ?? '—'))->implode(' · ') ?: 'Aucune inscription enregistrée' }}</p>
                            </div>
                            @if ($r->estInscrit($annee))
                                <span class="text-xs text-red-600">Déjà inscrit(e) en {{ $annee }}</span>
                            @else
                                <button type="button" wire:click="choisirEleve({{ $r->id }})" class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs">Réinscrire</button>
                            @endif
                        </div>
                    @endforeach
                    @if (mb_strlen(trim($recherche)) >= 2 && $this->resultatsRecherche->isEmpty())
                        <p class="py-3 text-sm text-gray-500">Aucun élève trouvé. S'il s'agit d'un nouvel élève, utilisez « Inscription — nouveaux ».</p>
                    @endif
                </div>
            </div>
        @else
        <form wire:submit="enregistrer" class="space-y-6">
            @if ($eleveId && ! $inscriptionId)
                <div class="flex items-center justify-between rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                    <span>Réinscription de <strong>{{ trim($eleve['nom'].' '.$eleve['prenoms']) }}</strong> ({{ $eleve['matricule'] }}). Vérifiez et complétez la fiche.</span>
                    <button type="button" wire:click="changerEleve" class="underline">Changer d'élève</button>
                </div>
            @endif

            @foreach ($this->alertes as $a)
                <div class="rounded-lg px-4 py-3 text-sm border {{ $a['bloquant'] ? 'bg-red-50 border-red-200 text-red-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
                    <p><strong>{{ $a['bloquant'] ? 'Doublon' : 'Doublon possible' }} :</strong> {{ $a['texte'] }}</p>
                    @if (! $a['bloquant'] && ! $inscriptionId)
                        <div class="mt-2 flex flex-wrap gap-4 items-center">
                            <a href="/admin/inscriptions/nouvelle?type=reinscription&eleve={{ $a['eleve']->id }}&annee={{ $insc['annee_scolaire'] }}" class="underline font-medium">C'est lui : faire une réinscription</a>
                            <label class="inline-flex items-center gap-2"><input type="checkbox" wire:model.live="homonymeConfirme" class="rounded"> C'est un autre enfant (homonyme)</label>
                        </div>
                    @endif
                </div>
            @endforeach

            @if ($erreur)
                <div class="rounded-lg px-4 py-3 text-sm bg-red-50 border border-red-200 text-red-800">{{ $erreur }}</div>
            @endif

            <section class="bg-white rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-800 mb-4">Classe demandée — année {{ $insc['annee_scolaire'] }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Année scolaire <span class="text-red-600">*</span></label>
                        <input type="text" wire:model.live.debounce.400ms="insc.annee_scolaire" class="{{ $champ }}" placeholder="2026-2027">
                        @error('insc.annee_scolaire') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-600">Nouvelle classe <span class="text-red-600">*</span></label>
                        <select wire:model="insc.specialite_id" class="{{ $champ }}">
                            <option value="">— Choisir la classe —</option>
                            @foreach ($this->classes as $section => $classes)
                                <optgroup label="{{ $section }}">
                                    @foreach ($classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('insc.specialite_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-800 mb-4">I. Informations sur l'élève</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Nom <span class="text-red-600">*</span></label>
                        <input type="text" wire:model.live.debounce.400ms="eleve.nom" class="{{ $champ }} uppercase">
                        @error('eleve.nom') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Prénom(s)</label>
                        <input type="text" wire:model.live.debounce.400ms="eleve.prenoms" class="{{ $champ }}">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">N° matricule <span class="text-gray-400">(attribué automatiquement si vide)</span></label>
                        <input type="text" wire:model.live.debounce.400ms="eleve.matricule" class="{{ $champ }} font-mono">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Sexe</label>
                        <select wire:model="eleve.sexe" class="{{ $champ }}"><option value="">—</option><option value="M">Masculin</option><option value="F">Féminin</option></select>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Date de naissance</label>
                        <input type="date" wire:model="eleve.date_naissance" class="{{ $champ }}">
                        @error('eleve.date_naissance') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Lieu de naissance</label>
                        <input type="text" wire:model="eleve.lieu_naissance" class="{{ $champ }}">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Nationalité</label>
                        <input type="text" wire:model="eleve.nationalite" class="{{ $champ }}">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Langue maternelle</label>
                        <input type="text" wire:model="eleve.langue_maternelle" class="{{ $champ }}">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Lieu de résidence</label>
                        <input type="text" wire:model="eleve.lieu_residence" class="{{ $champ }}">
                    </div>
                    <div class="md:col-span-3 flex flex-wrap items-center gap-4">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" wire:model.live="insc.redoublant" class="rounded"> Redoublant</label>
                        @if ($insc['redoublant'])
                            <input type="text" wire:model="insc.redoublant_classe" placeholder="Quelle classe ?" class="border border-gray-300 rounded-lg p-2 text-sm">
                        @endif
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-800 mb-4">II. Scolarité précédente</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if ($type === 'inscription')
                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">Établissement précédent</label>
                            <input type="text" wire:model="insc.etablissement_precedent" class="{{ $champ }}">
                        </div>
                    @endif
                    <div>
                        <label class="text-sm text-gray-600">{{ $type === 'inscription' ? 'Dernière classe suivie' : "Classe suivie l'an dernier" }}</label>
                        <input type="text" wire:model="insc.classe_precedente" class="{{ $champ }}">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Résultat</label>
                        <select wire:model="insc.resultat" class="{{ $champ }}"><option value="">—</option><option value="admis">Admis</option><option value="redoublant">Redoublant</option><option value="transfere">Transféré</option></select>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Moyenne annuelle (/20)</label>
                        <input type="number" step="0.01" min="0" max="20" wire:model="insc.moyenne_annuelle" class="{{ $champ }}">
                        @error('insc.moyenne_annuelle') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    @if ($type === 'inscription')
                        <div class="md:col-span-3">
                            <label class="text-sm text-gray-600">Raison du changement d'école</label>
                            <textarea wire:model="insc.raison_changement" rows="2" class="{{ $champ }}"></textarea>
                        </div>
                    @endif
                </div>
            </section>

            <section class="bg-white rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-800 mb-4">III. Parents / tuteur</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach (['pere' => 'Parent 1 — Père', 'mere' => 'Parent 2 — Mère'] as $p => $titre)
                        <div class="space-y-3">
                            <h3 class="text-sm font-medium text-gray-700">{{ $titre }}</h3>
                            <input type="text" wire:model="eleve.{{ $p }}_nom" placeholder="Nom et prénom" class="{{ $champ }}">
                            <input type="text" wire:model="eleve.{{ $p }}_profession" placeholder="Profession" class="{{ $champ }}">
                            <input type="tel" wire:model="eleve.{{ $p }}_telephone" placeholder="Téléphone" class="{{ $champ }}">
                            <input type="text" wire:model="eleve.{{ $p }}_adresse" placeholder="Adresse" class="{{ $champ }}">
                        </div>
                    @endforeach
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <h3 class="md:col-span-3 text-sm font-medium text-gray-700">Tuteur légal (si différent)</h3>
                        <input type="text" wire:model="eleve.tuteur_nom" placeholder="Nom et prénom" class="{{ $champ }}">
                        <input type="text" wire:model="eleve.tuteur_lien" placeholder="Lien avec l'élève" class="{{ $champ }}">
                        <input type="tel" wire:model="eleve.tuteur_telephone" placeholder="Téléphone" class="{{ $champ }}">
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-800 mb-4">IV. Informations médicales et autres</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="text-sm text-gray-600">Allergies / maladies connues</label><textarea wire:model="eleve.allergies" rows="2" class="{{ $champ }}"></textarea></div>
                    <div><label class="text-sm text-gray-600">Besoin particulier (handicap, trouble DYS…)</label><textarea wire:model="eleve.besoins_particuliers" rows="2" class="{{ $champ }}"></textarea></div>
                    <div><label class="text-sm text-gray-600">Personne à contacter en cas d'urgence</label><input type="text" wire:model="eleve.urgence_nom" class="{{ $champ }}"></div>
                    <div><label class="text-sm text-gray-600">Téléphone d'urgence</label><input type="tel" wire:model="eleve.urgence_telephone" class="{{ $champ }}"></div>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-800 mb-4">V. Suivi administratif</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><label class="text-sm text-gray-600">Personne ayant inscrit l'élève</label><input type="text" wire:model="insc.inscrit_par" class="{{ $champ }}"></div>
                    <div><label class="text-sm text-gray-600">Tranche 1 (FCFA)</label><input type="number" min="0" wire:model="insc.tranche_1" class="{{ $champ }}"></div>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 md:mt-6"><input type="checkbox" wire:model="insc.remise_10" class="rounded"> Remise de 10 %</label>
                </div>
            </section>

            <div class="flex justify-end gap-3 pb-8">
                <a href="/admin/inscriptions?annee={{ $insc['annee_scolaire'] }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm">Annuler</a>
                <button type="submit" wire:loading.attr="disabled"
                    @disabled($bloquant || (! $inscriptionId && collect($this->alertes)->isNotEmpty() && ! $homonymeConfirme))
                    class="px-5 py-2.5 rounded-lg text-white text-sm font-medium disabled:opacity-40 disabled:cursor-not-allowed {{ $type === 'inscription' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                    {{ $inscriptionId ? 'Enregistrer les modifications' : ($type === 'inscription' ? "Inscrire l'élève" : "Réinscrire l'élève") }}
                </button>
            </div>
        </form>
        @endif
    </div>
    @endvolt
</x-layouts.app>
