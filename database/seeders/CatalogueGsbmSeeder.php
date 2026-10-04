<?php

namespace Database\Seeders;

use App\Models\Cycle;
use App\Models\Departement;
use App\Models\Filiere;
use App\Models\Section;
use App\Models\Specialite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Classes du Groupe Scolaire Bilingue La Majestueuse, telles que publiees
 * sur gsbm-ndazoa.com (section francophone et section anglophone).
 *
 * Correspondance avec le vocabulaire du logiciel :
 *   Section     -> Section linguistique (francophone / anglophone)
 *   Cycle       -> Maternelle / Primaire
 *   Departement -> Section linguistique
 *   Filiere     -> Cycle d'une section (ex. Primaire francophone)
 *   Specialite  -> Classe, la ou l'eleve s'inscrit
 *
 * Les saisies d'essai presentes dans la base (filieres « droit », « licence »...)
 * sont desactivees, pas supprimees. Relancer le seeder ne cree aucun doublon.
 */
class CatalogueGsbmSeeder extends Seeder
{
    private const SECTIONS = [
        'SECF' => ['Francophone', 'Section francophone', [
            'Maternelle' => ['FR-MAT', 'Maternelle francophone', [
                'Petite Section (PS) — 3-4 ans', 'Moyenne Section (MS) — 4-5 ans', 'Grande Section (GS) — 5-6 ans',
            ]],
            'Primaire' => ['FR-PRI', 'Primaire francophone', [
                'SIL (Section d\'Initiation au Langage)', 'Cours Préparatoire (CP) — 6-7 ans', 'Cours Élémentaire 1 (CE1) — 7-8 ans', 'Cours Élémentaire 2 (CE2) — 8-9 ans',
                'Cours Moyen 1 (CM1) — 9-10 ans', 'Cours Moyen 2 (CM2) — 10-11 ans',
            ]],
        ]],
        'SECA' => ['Anglophone', 'English section', [
            'Maternelle' => ['EN-NUR', 'Nursery & Kindergarten', [
                'Pre-Nursery', 'Nursery 1 — 3-4 years', 'Nursery 2 — 4-5 years', 'Kindergarten (KG) — 5-6 years',
            ]],
            'Primaire' => ['EN-PRI', 'Primary', [
                'Class 1 — 6-7 years', 'Class 2 — 7-8 years', 'Class 3 — 8-9 years',
                'Class 4 — 9-10 years', 'Class 5 — 10-11 years', 'Class 6 — 11-12 years',
            ]],
        ]],
    ];

    public function run(): void
    {
        DB::transaction(function () {
            $cycles = [
                'Maternelle' => Cycle::updateOrCreate(['name' => 'Maternelle'], ['institution' => 'GSBM', 'status' => 'Success']),
                'Primaire' => Cycle::updateOrCreate(['name' => 'Primaire'], ['institution' => 'GSBM', 'status' => 'Success']),
            ];

            foreach (self::SECTIONS as $code => [$nomSection, $libelle, $parCycle]) {
                Section::updateOrCreate(['code' => $code], [
                    'name' => $nomSection, 'abbreviation' => $code, 'status' => 'Success',
                ]);

                $departement = Departement::updateOrCreate(['code' => 'GSBM-'.$code], [
                    'nom' => $libelle, 'description' => $libelle.' du Groupe Scolaire Bilingue La Majestueuse.',
                    'status' => 'Success',
                ]);

                foreach ($parCycle as $cycle => [$codeFiliere, $nomFiliere, $classes]) {
                    $filiere = Filiere::updateOrCreate(['code' => 'GSBM-'.$codeFiliere], [
                        'name' => $nomFiliere,
                        'institution' => 'GSBM',
                        'description' => $nomFiliere.' : '.count($classes).' classes, 25 élèves au plus par classe.',
                        'cycle_id' => $cycles[$cycle]->id,
                        'departement_id' => $departement->id,
                        'status' => 'Success',
                    ]);

                    foreach ($classes as $classe) {
                        Specialite::updateOrCreate(
                            ['filiere_id' => $filiere->id, 'name' => $classe],
                            ['cycle_id' => $cycles[$cycle]->id, 'status' => 'Success', 'price' => 0]
                        );
                    }
                }
            }

            $this->desactiverSaisiesDEssai();
        });

        $this->command?->info('Classes GSBM : 2 sections, 17 classes.');
    }

    private function desactiverSaisiesDEssai(): void
    {
        $filieres = DB::table('filieres')->where('code', 'like', 'GSBM-%')->select('id');

        DB::table('filieres')->where(fn ($q) => $q->whereNull('code')->orWhere('code', 'not like', 'GSBM-%'))->update(['status' => 'failed']);
        DB::table('cycles')->whereNotIn('name', ['Maternelle', 'Primaire'])->update(['status' => 'failed']);
        DB::table('specialites')->whereNotIn('filiere_id', $filieres)->update(['status' => 'failed']);
        DB::table('ues')->where(fn ($q) => $q->whereNull('filiere_id')->orWhereNotIn('filiere_id', $filieres))->update(['status' => 'failed']);
        DB::table('cours')->where(fn ($q) => $q->whereNull('filiere_id')->orWhereNotIn('filiere_id', $filieres))->update(['status' => 'failed']);
    }
}
