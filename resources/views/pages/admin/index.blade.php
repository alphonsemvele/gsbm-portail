<?php
use function Laravel\Folio\{name, middleware};
use Livewire\Volt\Component;
use App\Models\Inscription;
use App\Models\Cour;
use App\Models\Support;
use App\Models\User;
use App\Services\InscriptionService;

name('admin.index');
middleware(['auth', 'verified', 'role']);

new class extends Component {
    public string $annee = '';

    public function mount(): void
    {
        $this->annee = InscriptionService::anneeCourante();
    }

    public function with(InscriptionService $service): array
    {
        $classes = $service->classes();
        $parClasse = Inscription::where('annee_scolaire', $this->annee)
            ->selectRaw('specialite_id, count(*) as n')->groupBy('specialite_id')->pluck('n', 'specialite_id');

        $sections = $classes->groupBy(fn ($c) => $c->filiere?->departement?->nom ?? 'Autres')
            ->map(fn ($liste) => $liste->map(fn ($c) => ['nom' => $c->name, 'effectif' => (int) ($parClasse[$c->id] ?? 0)]));

        return [
            'totalInscrits' => $parClasse->sum(),
            'nouveaux' => Inscription::where('annee_scolaire', $this->annee)->where('type', 'inscription')->count(),
            'anciens' => Inscription::where('annee_scolaire', $this->annee)->where('type', 'reinscription')->count(),
            'matieres' => Cour::where('status', 'Success')->count(),
            'classes' => $classes->count(),
            'sections' => $sections,
            'supportsOuverts' => Support::where('status', 'pending')->count(),
            'dernieres' => Inscription::with(['eleve', 'classe'])->where('annee_scolaire', $this->annee)->latest()->limit(8)->get(),
        ];
    }
};
?>

<x-layouts.app title="Tableau de bord">
    @volt
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Bonjour {{ auth()->user()->name }}</h1>
                <p class="text-gray-500">{{ config('etablissement.nom') }} · année scolaire {{ $annee }}</p>
            </div>
            @if (config('etablissement.modules.inscriptions'))
                <div class="flex flex-wrap gap-2">
                    <a href="/admin/inscriptions/nouvelle?type=inscription" class="inline-flex items-center gap-2 rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-800">+ Inscrire un élève</a>
                    <a href="/admin/inscriptions/nouvelle?type=reinscription" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">↻ Réinscrire</a>
                    <a href="/admin/inscriptions/import" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">⇪ Importer Excel</a>
                </div>
            @endif
        </div>

        {{-- Chiffres clés --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <a href="/admin/inscriptions" class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 hover:border-blue-300">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Élèves inscrits</p>
                <p class="mt-1 text-3xl font-bold text-blue-900">{{ $totalInscrits }}</p>
                <p class="text-xs text-gray-500">{{ $nouveaux }} nouveaux · {{ $anciens }} anciens</p>
            </a>
            <a href="/admin/specialite" class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 hover:border-blue-300">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ config('etablissement.libelles.specialite') }}</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $classes }}</p>
                <p class="text-xs text-gray-500">{{ $sections->count() }} section(s)</p>
            </a>
            <a href="/admin/cours" class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 hover:border-blue-300">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Matières actives</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $matieres }}</p>
                <p class="text-xs text-gray-500">toutes classes confondues</p>
            </a>
            <a href="/admin/support" class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 hover:border-blue-300">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Demandes de support</p>
                <p class="mt-1 text-3xl font-bold {{ $supportsOuverts ? 'text-amber-600' : 'text-gray-900' }}">{{ $supportsOuverts }}</p>
                <p class="text-xs text-gray-500">en attente</p>
            </a>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Effectifs par classe --}}
            <section class="xl:col-span-2 rounded-2xl bg-white shadow-sm border border-gray-100">
                <div class="flex items-center justify-between px-5 pt-5">
                    <h2 class="font-semibold text-gray-900">Effectifs par classe — {{ $annee }}</h2>
                    <a href="/admin/inscriptions" class="text-sm text-blue-700 hover:underline">Voir la liste</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-5">
                    @forelse ($sections as $section => $liste)
                        <div><div class="overflow-x-auto">
                            <table class="w-full text-sm" data-style-compact data-export-titre="Effectifs {{ $section }} {{ $annee }}">
                                <thead><tr class="text-left text-gray-500 border-b"><th class="py-2">{{ $section }}</th><th class="py-2 text-right">Élèves</th></tr></thead>
                                <tbody>
                                    @foreach ($liste as $ligne)
                                        <tr class="border-b border-gray-50">
                                            <td class="py-1.5">{{ $ligne['nom'] }}</td>
                                            <td class="py-1.5 text-right font-medium {{ $ligne['effectif'] ? 'text-gray-900' : 'text-gray-300' }}">{{ $ligne['effectif'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div></div>
                    @empty
                        <p class="text-gray-500">Aucune classe active.</p>
                    @endforelse
                </div>
            </section>

            {{-- Dernières inscriptions --}}
            <section class="rounded-2xl bg-white shadow-sm border border-gray-100">
                <h2 class="font-semibold text-gray-900 px-5 pt-5">Dernières inscriptions</h2>
                <div class="overflow-x-auto p-5">
                    <table class="w-full text-sm" data-style-compact data-export-titre="Dernières inscriptions {{ $annee }}">
                        <thead><tr class="text-left text-gray-500 border-b"><th class="py-2">Élève</th><th class="py-2">Classe</th></tr></thead>
                        <tbody>
                            @forelse ($dernieres as $i)
                                <tr class="border-b border-gray-50">
                                    <td class="py-1.5"><span class="block font-medium">{{ $i->eleve?->nomComplet() }}</span><span class="text-xs text-gray-500">{{ $i->eleve?->matricule }}</span></td>
                                    <td class="py-1.5 text-gray-600">{{ \Illuminate\Support\Str::before($i->classe?->name ?? '—', ' —') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="py-4 text-center text-gray-500">Aucune inscription pour {{ $annee }}.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- Accès rapides : mêmes rubriques que le menu --}}
        <h2 class="mt-8 mb-3 font-semibold text-gray-900">Accès rapides</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach (config('navigation.admin') as $entree)
                @continue(! isset($entree['liens']))
                @continue(isset($entree['module']) && ! config('etablissement.modules.'.$entree['module']))
                <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">{{ $entree['groupe'] }}</p>
                    <ul class="space-y-1 text-sm">
                        @foreach ($entree['liens'] as $lien)
                            <li><a href="{{ $lien['lien'] }}" class="text-blue-800 hover:underline">{{ str_starts_with($lien['titre'], 'libelles.') ? config('etablissement.'.$lien['titre']) : $lien['titre'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
    @endvolt
</x-layouts.app>
