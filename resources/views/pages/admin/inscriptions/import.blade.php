<?php
use function Laravel\Folio\{name, middleware};
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Services\InscriptionService;

name('admin.inscriptions.import');
middleware(['auth', 'verified', 'role']);

new class extends Component {
    use WithFileUploads;

    public $fichier = null;
    public string $nomFichier = '';
    public string $chemin = '';
    public string $annee = '';
    public ?array $rapport = null;
    public bool $applique = false;
    public string $erreur = '';

    public function mount(): void
    {
        $this->annee = (string) request('annee', InscriptionService::anneeCourante());
    }

    public function updatedFichier(): void
    {
        $this->erreur = '';
        $this->rapport = null;
        $this->applique = false;
        $this->validate(['fichier' => 'required|file|mimes:xlsx,xls|max:10240'], [
            'fichier.mimes' => 'Le fichier doit être un classeur Excel (.xlsx ou .xls).',
        ]);

        $this->nomFichier = $this->fichier->getClientOriginalName();

        // L'annee figure souvent dans le nom : « INSCRIPTIONS ANNEE SCOLAIRE 2026-2027 ».
        if (preg_match('/(20\d{2})\s*[-\/]\s*(20\d{2})/', $this->nomFichier, $m)) {
            $this->annee = $m[1].'-'.$m[2];
        }

        $this->chemin = $this->fichier->store('imports-inscriptions', 'local');
        $this->analyser(app(InscriptionService::class));
    }

    public function analyser(InscriptionService $service): void
    {
        $this->erreur = '';
        $this->applique = false;

        if (! preg_match('/^\d{4}-\d{4}$/', $this->annee)) {
            $this->erreur = "L'année scolaire doit être au format 2026-2027.";
            return;
        }

        try {
            $this->rapport = $service->importer(Storage::disk('local')->path($this->chemin), $this->annee);
        } catch (\Throwable $e) {
            $this->rapport = null;
            $this->erreur = 'Lecture du fichier impossible : '.$e->getMessage();
        }
    }

    public function importer(InscriptionService $service): void
    {
        if (! $this->chemin || ! $this->rapport) {
            return;
        }

        $this->rapport = $service->importer(Storage::disk('local')->path($this->chemin), $this->annee, true, auth()->id());
        $this->applique = true;
        Storage::disk('local')->delete($this->chemin);
        $this->chemin = '';
    }
};
?>

<x-layouts.app header="true">
    @volt
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="/admin/inscriptions?annee={{ $annee }}" class="text-sm text-indigo-600 hover:underline">← Liste des inscriptions</a>
        <h1 class="text-3xl font-bold text-gray-800 mt-2">Importer les inscriptions (Excel)</h1>
        <p class="text-gray-500 mb-6">Format du registre de l'école : colonnes « NOMS ET PRENOMS », « CLASSE », « MATRICULES », « NOMS DE LA PERSONNE AYANT INSCRIT », parents, contacts… Toutes les feuilles sont lues ; un élève présent sur plusieurs feuilles n'est enregistré qu'une fois, et personne n'est inscrit deux fois la même année.</p>

        <div class="bg-white rounded-xl shadow p-6 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="text-sm text-gray-600">Fichier Excel</label>
                <input type="file" wire:model="fichier" accept=".xlsx,.xls" class="mt-1 block w-full text-sm border border-gray-300 rounded-lg p-2">
                <div wire:loading wire:target="fichier" class="text-xs text-gray-500 mt-1">Lecture du fichier…</div>
                @error('fichier') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm text-gray-600">Année scolaire</label>
                <div class="flex gap-2 mt-1">
                    <input type="text" wire:model="annee" class="w-full border border-gray-300 rounded-lg p-2 text-sm" placeholder="2026-2027">
                    @if ($chemin)
                        <button type="button" wire:click="analyser" class="px-3 rounded-lg border border-gray-300 text-sm">Analyser</button>
                    @endif
                </div>
            </div>
        </div>

        @if ($erreur)
            <div class="mt-4 rounded-lg px-4 py-3 text-sm bg-red-50 border border-red-200 text-red-800">{{ $erreur }}</div>
        @endif

        @if ($rapport)
            @php($r = $rapport)
            <div class="mt-6 rounded-xl p-5 {{ $applique ? 'bg-green-50 border border-green-200' : 'bg-indigo-50 border border-indigo-200' }}">
                <h2 class="font-semibold text-gray-800">{{ $applique ? 'Import terminé' : 'Aperçu — rien n’est encore enregistré' }} · {{ $nomFichier }} · {{ $r['annee'] }}</h2>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mt-4 text-center">
                    <div><p class="text-2xl font-bold">{{ $r['lignes'] }}</p><p class="text-xs text-gray-600">lignes lues</p></div>
                    <div><p class="text-2xl font-bold">{{ $r['eleves'] }}</p><p class="text-xs text-gray-600">élèves distincts</p></div>
                    <div><p class="text-2xl font-bold text-amber-600">{{ count($r['doublons_fichier']) }}</p><p class="text-xs text-gray-600">en double dans le fichier</p></div>
                    <div><p class="text-2xl font-bold text-gray-500">{{ count($r['deja_inscrits']) }}</p><p class="text-xs text-gray-600">déjà inscrits</p></div>
                    <div><p class="text-2xl font-bold text-red-600">{{ count($r['classes_inconnues']) + count($r['conflits_classe']) + count($r['matricules_en_conflit']) }}</p><p class="text-xs text-gray-600">à corriger</p></div>
                    <div><p class="text-2xl font-bold {{ $applique ? 'text-green-700' : 'text-indigo-700' }}">{{ $applique ? $r['importes'] : count($r['a_importer']) }}</p><p class="text-xs text-gray-600">{{ $applique ? 'importés' : 'à importer' }}</p></div>
                </div>

                @if (! $applique && count($r['a_importer']))
                    <div class="mt-5 flex justify-end">
                        <button type="button" wire:click="importer" wire:loading.attr="disabled" class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                            <span wire:loading.remove wire:target="importer">Importer {{ count($r['a_importer']) }} élève(s)</span>
                            <span wire:loading wire:target="importer">Import en cours…</span>
                        </button>
                    </div>
                @elseif ($applique)
                    <div class="mt-5 flex justify-end">
                        <a href="/admin/inscriptions?annee={{ $r['annee'] }}" class="px-5 py-2.5 rounded-lg bg-green-700 text-white text-sm font-medium">Voir la liste des inscrits</a>
                    </div>
                @endif
            </div>

            @php($blocs = [
                ['Erreurs', $r['erreurs'], fn ($x) => $x['nom'].' — '.$x['erreur'], 'red'],
                ['Classe inconnue (non importés)', $r['classes_inconnues'], fn ($x) => $x['nom'].' — classe « '.$x['classe'].' »', 'red'],
                ['Classes contradictoires dans le fichier (non importés)', $r['conflits_classe'], fn ($x) => $x['nom'].' — '.implode(' / ', $x['classes']), 'red'],
                ['Matricule déjà attribué à un autre élève (non importés)', $r['matricules_en_conflit'], fn ($x) => $x['nom'].' — '.$x['matricule'].' appartient à '.$x['deja_attribue_a'], 'red'],
                ['Déjà inscrits pour cette année (ignorés)', $r['deja_inscrits'], fn ($x) => $x['nom'].($x['matricule'] ? ' — '.$x['matricule'] : ''), 'gray'],
                ['Présents plusieurs fois dans le fichier (fusionnés en un seul élève)', $r['doublons_fichier'], fn ($x) => $x['nom'].' — '.$x['occurrences'].' lignes ('.implode(', ', $x['feuilles']).')', 'amber'],
            ])
            @foreach ($blocs as [$titre, $elements, $format, $couleur])
                @if (count($elements))
                    <details class="mt-4 bg-white rounded-xl shadow" @if($couleur === 'red') open @endif>
                        <summary class="cursor-pointer p-4 font-medium text-{{ $couleur }}-700">{{ $titre }} ({{ count($elements) }})</summary>
                        <ul class="px-6 pb-4 text-sm text-gray-700 list-disc space-y-1">
                            @foreach ($elements as $x)<li>{{ $format($x) }}</li>@endforeach
                        </ul>
                    </details>
                @endif
            @endforeach

            @if (count($r['a_importer']))
                <details class="mt-4 bg-white rounded-xl shadow" {{ $applique ? '' : 'open' }}>
                    <summary class="cursor-pointer p-4 font-medium text-indigo-700">{{ $applique ? 'Élèves importés' : 'Élèves qui seront importés' }} ({{ count($r['a_importer']) }})</summary>
                    <div class="overflow-x-auto px-4 pb-4">
                        <table class="w-full text-sm">
                            <thead class="text-left text-gray-500"><tr><th class="p-2">Élève</th><th class="p-2">Classe</th><th class="p-2">Matricule</th><th class="p-2">Type</th></tr></thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($r['a_importer'] as $x)
                                    <tr><td class="p-2">{{ $x['nom'] }}</td><td class="p-2">{{ $x['classe'] }}</td><td class="p-2 font-mono text-xs">{{ $x['matricule'] ?? 'attribué à l’import' }}</td><td class="p-2">{{ $x['type'] === 'inscription' ? 'Inscription' : 'Réinscription' }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
            @endif
        @endif
    </div>
    @endvolt
</x-layouts.app>
