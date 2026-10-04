<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Services\InscriptionService;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Export complet (toutes les pages) de la liste des inscriptions, avec les
 * filtres de l'ecran : l'export du navigateur ne verrait que la page affichee.
 */
class ExportInscriptionsController extends Controller
{
    private const COLONNES = [
        'Matricule', 'Nom', 'Prénoms', 'Sexe', 'Date de naissance', 'Lieu de naissance', 'Classe', 'Section',
        'Type', 'Redoublant', 'Classe précédente', 'Établissement précédent', 'Père', 'Téléphone père',
        'Mère', 'Téléphone mère', 'Tuteur', 'Téléphone tuteur', 'Lieu de résidence', 'Urgence', 'Téléphone urgence',
        'Allergies', 'Besoins particuliers', 'Inscrit par', 'Remise 10 %', 'Tranche 1 (FCFA)', 'Source', 'Saisi le',
    ];

    public function __invoke(Request $request, InscriptionService $service): StreamedResponse
    {
        abort_unless($request->user()?->aLeRole('admin', 'inscriptions'), 403);

        $annee = (string) $request->query('annee', InscriptionService::anneeCourante());
        $classes = $service->classes();

        $inscriptions = Inscription::with(['eleve', 'classe.filiere.departement'])
            ->join('eleves', 'eleves.id', '=', 'inscriptions.eleve_id')
            ->select('inscriptions.*')
            ->where('inscriptions.annee_scolaire', $annee)
            ->when($request->query('type'), fn ($q, $type) => $q->where('inscriptions.type', $type))
            ->when($request->query('classe'), fn ($q, $classe) => $q->where('inscriptions.specialite_id', $classe))
            ->when($request->query('section'), fn ($q, $section) => $q->whereIn('inscriptions.specialite_id',
                $classes->filter(fn ($c) => (string) $c->filiere?->departement_id === (string) $section)->pluck('id')))
            ->when(trim((string) $request->query('recherche')) !== '', function ($q) use ($request) {
                $terme = '%'.trim((string) $request->query('recherche')).'%';
                $q->where(fn ($q) => $q->where('eleves.nom', 'like', $terme)
                    ->orWhere('eleves.prenoms', 'like', $terme)
                    ->orWhere('eleves.matricule', 'like', $terme));
            })
            ->orderBy('eleves.nom')->orderBy('eleves.prenoms')
            ->get();

        $lignes = $inscriptions->map(function (Inscription $i) {
            $e = $i->eleve;

            return [
                $e->matricule, $e->nom, $e->prenoms, $e->sexe, $e->date_naissance?->format('d/m/Y'), $e->lieu_naissance,
                $i->classe?->name, $i->classe?->filiere?->departement?->nom,
                $i->type === 'inscription' ? 'Inscription' : 'Réinscription', $i->redoublant ? 'Oui' : 'Non',
                $i->classe_precedente, $i->etablissement_precedent, $e->pere_nom, $e->pere_telephone,
                $e->mere_nom, $e->mere_telephone, $e->tuteur_nom, $e->tuteur_telephone, $e->lieu_residence,
                $e->urgence_nom, $e->urgence_telephone, $e->allergies, $e->besoins_particuliers, $i->inscrit_par,
                $i->remise_10 ? 'Oui' : 'Non', $i->tranche_1 !== null ? (float) $i->tranche_1 : null,
                $i->source === 'import' ? 'Import Excel' : 'En ligne', $i->created_at?->format('d/m/Y'),
            ];
        });

        $fichier = 'inscriptions-'.$annee.'-'.now()->format('Ymd-Hi');

        if ($request->query('format') === 'csv') {
            return response()->streamDownload(function () use ($lignes) {
                $sortie = fopen('php://output', 'w');
                fwrite($sortie, "\xEF\xBB\xBF");
                fputcsv($sortie, self::COLONNES, ';');
                foreach ($lignes as $ligne) {
                    fputcsv($sortie, $ligne, ';');
                }
                fclose($sortie);
            }, $fichier.'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
        }

        $classeur = new Spreadsheet();
        $feuille = $classeur->getActiveSheet();
        $feuille->setTitle('Inscriptions '.$annee);
        $feuille->setCellValue('A1', config('etablissement.nom').' — Inscriptions '.$annee);
        $feuille->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $feuille->fromArray(self::COLONNES, null, 'A3');
        $feuille->fromArray($lignes->all(), null, 'A4', true);

        $derniere = $feuille->getHighestColumn();
        $feuille->getStyle("A3:{$derniere}3")->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $feuille->getStyle("A3:{$derniere}3")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1E3A8A');
        $feuille->setAutoFilter("A3:{$derniere}".max(3, $lignes->count() + 3));
        $feuille->freezePane('C4');
        foreach (range(1, count(self::COLONNES)) as $col) {
            $feuille->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        return response()->streamDownload(
            fn () => (new Xlsx($classeur))->save('php://output'),
            $fichier.'.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }
}
