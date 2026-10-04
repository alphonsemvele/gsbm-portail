<?php
use function Laravel\Folio\{name, middleware};
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use App\Models\Inscription;
use App\Services\InscriptionService;

name('specialite.etudiants');
middleware(['auth', 'verified', 'role']);

new class extends Component {
    public string $annee = '';
    public string $recherche = '';
    public ?int $detailId = null;

    public function mount(): void
    {
        $this->annee = InscriptionService::anneeCourante();
    }

    #[Computed]
    public function classe()
    {
        return auth()->user()->specialite;
    }

    #[Computed]
    public function annees()
    {
        return Inscription::where('specialite_id', $this->classe?->id)->distinct()->pluck('annee_scolaire')
            ->push(InscriptionService::anneeCourante())->unique()->sortDesc()->values();
    }

    #[Computed]
    public function eleves()
    {
        if (! $this->classe) {
            return collect();
        }

        $terme = trim($this->recherche);

        return Inscription::with('eleve')
            ->join('eleves', 'eleves.id', '=', 'inscriptions.eleve_id')
            ->select('inscriptions.*')
            ->where('inscriptions.specialite_id', $this->classe->id)
            ->where('inscriptions.annee_scolaire', $this->annee)
            ->when($terme !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('eleves.nom', 'like', "%{$terme}%")
                ->orWhere('eleves.prenoms', 'like', "%{$terme}%")
                ->orWhere('eleves.matricule', 'like', "%{$terme}%")))
            ->orderBy('eleves.nom')->orderBy('eleves.prenoms')
            ->get();
    }

    #[Computed]
    public function detail()
    {
        return $this->detailId ? Inscription::with('eleve.inscriptions.classe')->find($this->detailId) : null;
    }
};
?>

<x-layouts.app title="Élèves de ma classe">
    @volt
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
        @if (! $this->classe)
            <div class="rounded-2xl bg-white p-10 text-center shadow-sm border border-gray-100 mt-6">
                <h1 class="text-xl font-semibold text-gray-900">Aucune classe ne vous est attribuée</h1>
                <p class="mt-2 text-gray-500">L'administration doit vous désigner comme responsable d'une classe (page Classes, champ « Responsable »).</p>
            </div>
        @else
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Élèves — {{ $this->classe->name }}</h1>
                    <p class="text-gray-500">{{ $this->eleves->count() }} élève(s) inscrit(s) en {{ $annee }}</p>
                </div>
                <div class="flex gap-2">
                    <select wire:model.live="annee" class="rounded-lg border border-gray-300 p-2.5 text-sm">
                        @foreach ($this->annees as $a)<option value="{{ $a }}">{{ $a }}</option>@endforeach
                    </select>
                    <input type="search" wire:model.live.debounce.300ms="recherche" placeholder="Nom ou matricule…" class="rounded-lg border border-gray-300 p-2.5 text-sm">
                </div>
            </div>

            <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-x-auto">
                <table class="w-full text-sm" data-export-titre="Élèves {{ $this->classe->name }} {{ $annee }}">
                    <thead class="bg-slate-50 text-left text-gray-600">
                        <tr>
                            <th class="p-3">N°</th><th class="p-3">Matricule</th><th class="p-3">Nom et prénoms</th><th class="p-3">Sexe</th>
                            <th class="p-3">Né(e) le</th><th class="p-3">Parent</th><th class="p-3">Téléphone</th><th class="p-3">Type</th><th class="p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($this->eleves as $n => $i)
                            @php($e = $i->eleve)
                            <tr class="hover:bg-slate-50" wire:key="el-{{ $i->id }}">
                                <td class="p-3 text-gray-500">{{ $n + 1 }}</td>
                                <td class="p-3 font-mono text-xs">{{ $e->matricule }}</td>
                                <td class="p-3 font-medium">{{ $e->nomComplet() }}</td>
                                <td class="p-3">{{ $e->sexe ?? '—' }}</td>
                                <td class="p-3">{{ $e->date_naissance?->format('d/m/Y') ?? '—' }}</td>
                                <td class="p-3">{{ $e->pere_nom ?: ($e->mere_nom ?: ($e->tuteur_nom ?: ($i->inscrit_par ?: '—'))) }}</td>
                                <td class="p-3">{{ $e->pere_telephone ?: ($e->mere_telephone ?: ($e->tuteur_telephone ?: '—')) }}</td>
                                <td class="p-3">{{ $i->type === 'inscription' ? 'Nouveau' : 'Ancien' }}</td>
                                <td class="p-3"><button type="button" wire:click="$set('detailId', {{ $i->id }})" class="bouton-icone text-sky-600 hover:bg-sky-50" title="Fiche" aria-label="Fiche"><x-icone-action nom="voir" /></button></td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="p-8 text-center text-gray-500">Aucun élève inscrit dans cette classe pour {{ $annee }}.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($this->detail)
                @php($e = $this->detail->eleve)
                <div class="fixed inset-0 z-50 bg-black/40 flex items-start justify-center overflow-y-auto p-4" wire:click.self="$set('detailId', null)">
                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl my-8 p-6 text-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $e->nomComplet() }}</h2>
                                <p class="text-gray-500">{{ $e->matricule }} · {{ $this->classe->name }}</p>
                            </div>
                            <button type="button" wire:click="$set('detailId', null)" class="text-gray-400 hover:text-gray-700 text-xl">✕</button>
                        </div>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2" data-sans-export>
                            @foreach ([
                                'Sexe' => $e->sexe, 'Naissance' => trim(($e->date_naissance?->format('d/m/Y') ?? '').' '.($e->lieu_naissance ? 'à '.$e->lieu_naissance : '')),
                                'Père' => trim($e->pere_nom.' '.$e->pere_telephone), 'Mère' => trim($e->mere_nom.' '.$e->mere_telephone),
                                'Tuteur' => trim($e->tuteur_nom.' '.$e->tuteur_telephone), 'Urgence' => trim($e->urgence_nom.' '.$e->urgence_telephone),
                                'Allergies / maladies' => $e->allergies, 'Besoins particuliers' => $e->besoins_particuliers,
                                'Redoublant' => $this->detail->redoublant ? 'Oui' : 'Non', 'Classe précédente' => $this->detail->classe_precedente,
                            ] as $libelle => $valeur)
                                <div class="border-b border-gray-50 py-1"><dt class="text-gray-500">{{ $libelle }}</dt><dd class="text-gray-900">{{ filled($valeur) ? $valeur : '—' }}</dd></div>
                            @endforeach
                        </dl>
                    </div>
                </div>
            @endif
        @endif
    </div>
    @endvolt
</x-layouts.app>
