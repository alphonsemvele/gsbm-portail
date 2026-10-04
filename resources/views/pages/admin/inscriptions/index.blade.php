<?php
use function Laravel\Folio\{name, middleware};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Inscription;
use App\Services\InscriptionService;

name('admin.inscriptions.index');
middleware(['auth', 'verified', 'role']);

new class extends Component {
    use WithPagination;

    public string $annee = '';
    public string $section = '';
    public string $classe = '';
    public string $type = '';
    public string $recherche = '';
    public ?int $detailId = null;
    public ?int $annulerId = null;
    public string $message = '';

    public function mount(): void
    {
        $this->annee = (string) request('annee', InscriptionService::anneeCourante());
        $this->message = (string) session('inscription_message', '');
    }

    public function updating($propriete): void
    {
        if (in_array($propriete, ['annee', 'section', 'classe', 'type', 'recherche'], true)) {
            $this->resetPage();
        }
    }

    #[Computed]
    public function annees()
    {
        return Inscription::query()->distinct()->pluck('annee_scolaire')
            ->push(InscriptionService::anneeCourante())
            ->unique()->sortDesc()->values();
    }

    #[Computed]
    public function classes()
    {
        return app(InscriptionService::class)->classes();
    }

    #[Computed]
    public function sections()
    {
        return $this->classes->map(fn ($c) => $c->filiere?->departement)->filter()->unique('id')->values();
    }

    #[Computed]
    public function inscriptions()
    {
        return Inscription::with(['eleve', 'classe.filiere.departement'])
            ->join('eleves', 'eleves.id', '=', 'inscriptions.eleve_id')
            ->select('inscriptions.*')
            ->where('inscriptions.annee_scolaire', $this->annee)
            ->when($this->type, fn ($q) => $q->where('inscriptions.type', $this->type))
            ->when($this->classe, fn ($q) => $q->where('inscriptions.specialite_id', $this->classe))
            ->when($this->section, fn ($q) => $q->whereIn('inscriptions.specialite_id',
                $this->classes->filter(fn ($c) => (string) $c->filiere?->departement_id === $this->section)->pluck('id')))
            ->when(trim($this->recherche) !== '', function ($q) {
                $terme = '%'.trim($this->recherche).'%';
                $q->where(fn ($q) => $q->where('eleves.nom', 'like', $terme)
                    ->orWhere('eleves.prenoms', 'like', $terme)
                    ->orWhere('eleves.matricule', 'like', $terme));
            })
            ->orderBy('eleves.nom')->orderBy('eleves.prenoms')
            ->paginate(25);
    }

    #[Computed]
    public function stats(): array
    {
        $parClasse = Inscription::where('annee_scolaire', $this->annee)
            ->selectRaw('specialite_id, type, count(*) as n')
            ->groupBy('specialite_id', 'type')->get();

        $parSection = [];
        foreach ($this->classes as $c) {
            $sectionId = $c->filiere?->departement_id;
            $parSection[$sectionId] = ($parSection[$sectionId] ?? 0) + $parClasse->where('specialite_id', $c->id)->sum('n');
        }

        return [
            'total' => $parClasse->sum('n'),
            'inscriptions' => $parClasse->where('type', 'inscription')->sum('n'),
            'reinscriptions' => $parClasse->where('type', 'reinscription')->sum('n'),
            'par_section' => $parSection,
            'par_classe' => $parClasse->groupBy('specialite_id')->map->sum('n'),
        ];
    }

    #[Computed]
    public function detail()
    {
        return $this->detailId
            ? Inscription::with(['eleve.inscriptions.classe', 'classe.filiere.departement', 'saisiePar'])->find($this->detailId)
            : null;
    }

    public function annuler(): void
    {
        $inscription = Inscription::with('eleve')->find($this->annulerId);

        if ($inscription) {
            $nom = $inscription->eleve?->nomComplet();
            $inscription->delete();
            $this->message = "L'inscription de {$nom} pour {$this->annee} a été annulée.";
        }

        $this->annulerId = null;
    }
};
?>

<x-layouts.app header="true">
    @volt
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-8">
            <div>
                <a href="/admin" class="text-sm text-indigo-600 hover:underline">← Tableau de bord</a>
                <h1 class="text-3xl font-bold text-gray-800 mt-2">Inscriptions</h1>
                <p class="text-gray-500">Nouveaux élèves (inscription) et anciens élèves (réinscription), par année scolaire.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/admin/inscriptions/nouvelle?type=inscription&annee={{ $annee }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">+ Nouvelle inscription</a>
                <a href="/admin/inscriptions/nouvelle?type=reinscription&annee={{ $annee }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">↻ Réinscription</a>
                <a href="/admin/inscriptions/import?annee={{ $annee }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">⇪ Importer un fichier Excel</a>
            </div>
        </div>

        @if ($message)
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 flex justify-between items-start">
                <span>{{ $message }}</span>
                <button type="button" wire:click="$set('message', '')" class="text-green-700 ml-4">✕</button>
            </div>
        @endif

        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Total {{ $annee }}</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $this->stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Inscriptions (nouveaux)</p>
                <p class="text-3xl font-bold text-gray-800">{{ $this->stats['inscriptions'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Réinscriptions (anciens)</p>
                <p class="text-3xl font-bold text-emerald-600">{{ $this->stats['reinscriptions'] }}</p>
            </div>
            @foreach ($this->sections as $s)
                <div class="bg-white rounded-xl shadow p-4">
                    <p class="text-xs uppercase text-gray-500">{{ $s->nom }}</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $this->stats['par_section'][$s->id] ?? 0 }}</p>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-xl shadow p-4 mb-6 grid grid-cols-1 md:grid-cols-5 gap-3">
            <select wire:model.live="annee" class="border border-gray-300 rounded-lg p-2.5 text-sm">
                @foreach ($this->annees as $a)
                    <option value="{{ $a }}">Année {{ $a }}</option>
                @endforeach
            </select>
            <select wire:model.live="section" class="border border-gray-300 rounded-lg p-2.5 text-sm">
                <option value="">Toutes les sections</option>
                @foreach ($this->sections as $s)
                    <option value="{{ $s->id }}">{{ $s->nom }}</option>
                @endforeach
            </select>
            <select wire:model.live="classe" class="border border-gray-300 rounded-lg p-2.5 text-sm">
                <option value="">Toutes les classes</option>
                @foreach ($this->classes as $c)
                    @continue($section !== '' && (string) $c->filiere?->departement_id !== $section)
                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $this->stats['par_classe'][$c->id] ?? 0 }})</option>
                @endforeach
            </select>
            <select wire:model.live="type" class="border border-gray-300 rounded-lg p-2.5 text-sm">
                <option value="">Inscriptions et réinscriptions</option>
                <option value="inscription">Inscriptions (nouveaux)</option>
                <option value="reinscription">Réinscriptions (anciens)</option>
            </select>
            <input type="search" wire:model.live.debounce.300ms="recherche" placeholder="Nom, prénom ou matricule…" class="border border-gray-300 rounded-lg p-2.5 text-sm">
        </div>

        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="w-full text-sm" data-export-titre="Inscriptions {{ $annee }}" data-export-url="/admin/inscriptions/export?{{ http_build_query(array_filter(['annee' => $annee, 'section' => $section, 'classe' => $classe, 'type' => $type, 'recherche' => $recherche])) }}">
                <thead class="bg-gray-50 text-left text-gray-600">
                    <tr>
                        <th class="p-3">Matricule</th>
                        <th class="p-3">Élève</th>
                        <th class="p-3">Classe</th>
                        <th class="p-3">Section</th>
                        <th class="p-3">Type</th>
                        <th class="p-3">Inscrit par</th>
                        <th class="p-3">Source</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($this->inscriptions as $i)
                        <tr class="hover:bg-gray-50" wire:key="insc-{{ $i->id }}">
                            <td class="p-3 font-mono text-xs">{{ $i->eleve?->matricule ?? '—' }}</td>
                            <td class="p-3 font-medium text-gray-800">{{ $i->eleve?->nomComplet() }}</td>
                            <td class="p-3">{{ $i->classe?->name ?? '—' }}</td>
                            <td class="p-3 text-gray-500">{{ $i->classe?->filiere?->departement?->nom ?? '—' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $i->type === 'inscription' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $i->type === 'inscription' ? 'Inscription' : 'Réinscription' }}
                                </span>
                            </td>
                            <td class="p-3 text-gray-500">{{ $i->inscrit_par ?? '—' }}</td>
                            <td class="p-3 text-gray-500">{{ $i->source === 'import' ? 'Import Excel' : 'En ligne' }}</td>
                            <td class="p-3 text-right whitespace-nowrap">
                                <button type="button" wire:click="$set('detailId', {{ $i->id }})" class="bouton-icone text-sky-600 hover:bg-sky-50" title="Fiche" aria-label="Fiche"><x-icone-action nom="voir" /></button>
                                <a href="/admin/inscriptions/nouvelle?id={{ $i->id }}" class="bouton-icone text-indigo-600 hover:bg-indigo-50" title="Modifier" aria-label="Modifier"><x-icone-action nom="modifier" /></a>
                                <button type="button" wire:click="$set('annulerId', {{ $i->id }})" class="bouton-icone text-red-600 hover:bg-red-50" title="Annuler" aria-label="Annuler"><x-icone-action nom="annuler" /></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-8 text-center text-gray-500">Aucune inscription pour ces critères.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $this->inscriptions->links() }}</div>

        @if ($this->detail)
            @php($e = $this->detail->eleve)
            <div class="fixed inset-0 z-50 bg-black/40 flex items-start justify-center overflow-y-auto p-4" wire:click.self="$set('detailId', null)">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl my-8">
                    <div class="flex justify-between items-start p-6 border-b">
                        <div>
                            <p class="text-xs uppercase text-gray-500">Fiche de {{ $this->detail->type === 'inscription' ? 'inscription' : 'réinscription' }} — {{ $this->detail->annee_scolaire }}</p>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $e->nomComplet() }}</h2>
                            <p class="text-gray-500">{{ $e->matricule }} · {{ $this->detail->classe?->name }} · {{ $this->detail->classe?->filiere?->departement?->nom }}</p>
                        </div>
                        <button type="button" wire:click="$set('detailId', null)" class="text-gray-400 hover:text-gray-700 text-xl">✕</button>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        @php($ligne = fn ($libelle, $valeur) => '<div class="flex justify-between gap-4 py-1 border-b border-gray-50"><span class="text-gray-500">'.e($libelle).'</span><span class="text-gray-800 text-right">'.e(filled($valeur) ? $valeur : '—').'</span></div>')
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">I. Élève</h3>
                            {!! $ligne('Sexe', $e->sexe) !!}
                            {!! $ligne('Né(e) le', $e->date_naissance?->format('d/m/Y').($e->lieu_naissance ? ' à '.$e->lieu_naissance : '')) !!}
                            {!! $ligne('Nationalité', $e->nationalite) !!}
                            {!! $ligne('Langue maternelle', $e->langue_maternelle) !!}
                            {!! $ligne('Lieu de résidence', $e->lieu_residence) !!}
                            {!! $ligne('Redoublant', $this->detail->redoublant ? 'Oui'.($this->detail->redoublant_classe ? ' ('.$this->detail->redoublant_classe.')' : '') : 'Non') !!}
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">II. Scolarité précédente</h3>
                            {!! $ligne('Établissement', $this->detail->etablissement_precedent) !!}
                            {!! $ligne('Classe', $this->detail->classe_precedente) !!}
                            {!! $ligne('Résultat', $this->detail->resultat ? ucfirst($this->detail->resultat) : null) !!}
                            {!! $ligne('Moyenne', $this->detail->moyenne_annuelle ? $this->detail->moyenne_annuelle.' / 20' : null) !!}
                            {!! $ligne('Raison du changement', $this->detail->raison_changement) !!}
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">III. Parents / tuteur</h3>
                            {!! $ligne('Père', trim($e->pere_nom.' '.($e->pere_telephone ? '· '.$e->pere_telephone : ''))) !!}
                            {!! $ligne('Profession du père', $e->pere_profession) !!}
                            {!! $ligne('Mère', trim($e->mere_nom.' '.($e->mere_telephone ? '· '.$e->mere_telephone : ''))) !!}
                            {!! $ligne('Profession de la mère', $e->mere_profession) !!}
                            {!! $ligne('Tuteur', trim($e->tuteur_nom.' '.($e->tuteur_lien ? '('.$e->tuteur_lien.')' : '').' '.($e->tuteur_telephone ?? ''))) !!}
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">IV. Santé et urgence</h3>
                            {!! $ligne('Allergies / maladies', $e->allergies) !!}
                            {!! $ligne('Besoins particuliers', $e->besoins_particuliers) !!}
                            {!! $ligne('Urgence', trim($e->urgence_nom.' '.($e->urgence_telephone ? '· '.$e->urgence_telephone : ''))) !!}
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="font-semibold text-gray-800 mb-2">Historique</h3>
                            @foreach ($e->inscriptions as $h)
                                {!! $ligne($h->annee_scolaire, ($h->classe?->name ?? '—').' — '.($h->type === 'inscription' ? 'inscription' : 'réinscription')) !!}
                            @endforeach
                            {!! $ligne('Inscrit par', $this->detail->inscrit_par) !!}
                            {!! $ligne('Saisi', ($this->detail->source === 'import' ? 'Import Excel' : 'En ligne').($this->detail->saisiePar ? ' par '.trim($this->detail->saisiePar->name.' '.$this->detail->saisiePar->lastname) : '').' le '.$this->detail->created_at?->format('d/m/Y')) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($annulerId)
            <div class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                    <h2 class="text-lg font-bold text-gray-800">Annuler cette inscription ?</h2>
                    <p class="text-gray-600 mt-2 text-sm">L'inscription de l'année {{ $annee }} sera supprimée. La fiche de l'élève et ses autres années sont conservées.</p>
                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" wire:click="$set('annulerId', null)" class="px-4 py-2 rounded-lg border border-gray-300 text-sm">Retour</button>
                        <button type="button" wire:click="annuler" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm">Annuler l'inscription</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @endvolt
</x-layouts.app>
