<?php
use function Laravel\Folio\{name, middleware};
use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Specialite;
use App\Models\Cour;

name('specialite.index');
middleware(['auth', 'verified','role']);

new class extends Component {
    public $specialite;
    public $hasSpecialite = false;

    public $totalEtudiants = 0;
    public $totalCours = 0;

    public function mount()
    {
        $user = auth()->user();

        if ($user->specialite_id && $user->specialite && $user->aLeRole('enseignant')) {
            $this->hasSpecialite = true;
            $this->specialite = $user->specialite;

            if ($this->specialite) {
                // Étudiants inscrits dans cette spécialité
                $this->totalEtudiants = \App\Models\Inscription::where('specialite_id', $this->specialite->id)
                    ->where('annee_scolaire', \App\Services\InscriptionService::anneeCourante())
                    ->count();

                // Cours associés à cette spécialité
                $this->totalCours = Cour::where('specialite_id', $this->specialite->id)
                    ->where('status', '!=', 'failed')
                    ->count();
            }
        }
    }
};
?>

<x-layouts.app header="true">
    @volt
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            @if(!$hasSpecialite)
                <!-- Bloc "Aucune classe attribuée" -->
                <div class="min-h-[60vh] flex items-center justify-center">
                    <div class="bg-white rounded-2xl shadow-xl p-10 max-w-lg text-center">
                        <div class="mx-auto w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Aucune classe attribuée</h2>
                        <p class="text-gray-600 mb-6">Vous n'êtes responsable d'aucune classe pour le moment. Contactez l'administration si nécessaire.</p>
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <p class="text-sm text-gray-500">En attendant, vous pouvez consulter votre profil ou contacter le support.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="/specialite/profil" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Voir mon profil
                            </a>
                            @if(config('etablissement.email'))
                            <a href="mailto:{{ config('etablissement.email') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Contacter le support
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Ma classe</h1>
                    <p class="text-gray-500">{{ $specialite->name ?? 'Classe non définie' }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="/specialite/profil" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">Mon profil</a>
                </div>
            </div>

                

                <!-- Statistiques -->
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow p-4">
                        
                        <p class="text-xs uppercase text-gray-500">Élèves inscrits</p>
                        <p class="text-3xl font-bold text-indigo-600">{{ $totalEtudiants }}</p>
                        <p class="text-xs text-gray-500">Dans la classe</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-4">
                        
                        <p class="text-xs uppercase text-gray-500">Matières</p>
                        <p class="text-3xl font-bold text-indigo-600">{{ $totalCours }}</p>
                        <p class="text-xs text-gray-500">Dans la classe</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-4">
                        
                        <p class="text-xs uppercase text-gray-500">Taux de Réussite</p>
                        <p class="text-3xl font-bold text-indigo-600">—</p>
                        <p class="text-xs text-gray-500">À calculer</p>
                    </div>
                </div>

                <!-- Modules -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach ([
                        ['/specialite/etudiant', 'Élèves', 'Liste et fiches des élèves de la classe.'],
                        ['/specialite/cours', 'Matières', 'Matières enseignées dans la classe.'],
                        ['/specialite/examens', 'Évaluations & notes', 'Saisir les notes des évaluations.'],
                    ] as [$lien, $titre, $texte])
                        <a href="{{ $lien }}" class="group rounded-2xl bg-white p-6 shadow-sm border border-gray-100 hover:border-blue-300 hover:shadow">
                            <h2 class="text-lg font-semibold text-gray-900 group-hover:text-blue-800">{{ $titre }} →</h2>
                            <p class="mt-1 text-sm text-gray-500">{{ $texte }}</p>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    @endvolt
</x-layouts.app>