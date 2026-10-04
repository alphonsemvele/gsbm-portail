<?php
use function Laravel\Folio\{name, middleware};
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

name('presence.index');
middleware(['auth', 'verified']);

new class extends Component {
    use WithFileUploads;

    public $presences;
    public $presenceRate;
    public $absenceRate;
    public $showJustifyModal = false;
    public $selectedPresence;
    public $motif = '';
    public $justificatif;

    public function mount()
    {
        \Log::info('mount appelé pour presence.index à ' . now()->toDateTimeString());
        $this->loadData();
    }

    private function loadData()
    {
        \Log::info('Chargement des données statiques pour les présences');
        $this->presences = collect([
            (object) [
                'date' => '2025-09-01',
                'status' => 'présent',
                'motif' => null,
                'motif_status' => null,
                'justificatif_path' => null,
            ],
            (object) [
                'date' => '2025-09-02',
                'status' => 'présent',
                'motif' => null,
                'motif_status' => null,
                'justificatif_path' => null,
            ],
            (object) [
                'date' => '2025-09-03',
                'status' => 'absent',
                'motif' => null,
                'motif_status' => 'non_justifié',
                'justificatif_path' => null,
            ],
            (object) [
                'date' => '2025-09-04',
                'status' => 'présent',
                'motif' => null,
                'motif_status' => null,
                'justificatif_path' => null,
            ],
            (object) [
                'date' => '2025-09-05',
                'status' => 'absent',
                'motif' => null,
                'motif_status' => 'non_justifié',
                'justificatif_path' => null,
            ],
            (object) [
                'date' => '2025-09-06',
                'status' => 'présent',
                'motif' => null,
                'motif_status' => null,
                'justificatif_path' => null,
            ],
            (object) [
                'date' => '2025-09-07',
                'status' => 'présent',
                'motif' => null,
                'motif_status' => null,
                'justificatif_path' => null,
            ],
            (object) [
                'date' => '2025-09-08',
                'status' => 'présent',
                'motif' => null,
                'motif_status' => null,
                'justificatif_path' => null,
            ],
            (object) [
                'date' => '2025-09-09',
                'status' => 'absent',
                'motif' => null,
                'motif_status' => 'non_justifié',
                'justificatif_path' => null,
            ],
            (object) [
                'date' => '2025-09-10',
                'status' => 'présent',
                'motif' => null,
                'motif_status' => null,
                'justificatif_path' => null,
            ],
        ]);

        // Calcul du taux de présence et d'absence
        $total = $this->presences->count();
        $presencesCount = $this->presences->where('status', 'présent')->count();
        $absencesCount = $total - $presencesCount;

        $this->presenceRate = $total > 0 ? round(($presencesCount / $total) * 100, 2) : 0;
        $this->absenceRate = $total > 0 ? round(($absencesCount / $total) * 100, 2) : 0;

        \Log::info('Données statiques chargées', [
            'presences_count' => $this->presences->count(),
            'presence_rate' => $this->presenceRate,
            'absence_rate' => $this->absenceRate,
        ]);
    }

    public function openJustifyModal($date)
    {
        $this->selectedPresence = $this->presences->firstWhere('date', $date);
        if ($this->selectedPresence && $this->selectedPresence->status === 'absent' && $this->selectedPresence->motif_status === 'non_justifié') {
            $this->showJustifyModal = true;
            $this->motif = '';
            $this->justificatif = null;
        }
    }

    public function justifyAbsence()
    {
        $this->validate([
            'motif' => 'required|in:maladie,voyage,autre',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $index = $this->presences->search(function ($item) {
            return $item->date === $this->selectedPresence->date;
        });

        if ($index !== false) {
            $this->presences[$index]->motif = $this->motif;
            $this->presences[$index]->motif_status = 'en_attente';

            if ($this->justificatif) {
                $path = $this->justificatif->store('justificatifs', 'public');
                $this->presences[$index]->justificatif_path = $path;
            }
        }

        $this->showJustifyModal = false;
        $this->selectedPresence = null;
        $this->motif = '';
        $this->justificatif = null;
    }
};
?>

<x-layouts.app header="true">
    @volt
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <header class="mb-10">
                <h1 class="text-4xl font-extrabold text-gray-800 tracking-tight">Présences et Absences</h1>
                <p class="mt-2 text-lg text-gray-500">Suivez vos présences et absences scolaires.</p>
            </header>

            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Liste des Présences</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-2">
                        <thead>
                            <tr class="bg-gray-100 rounded-lg">
                                <th class="p-4 text-sm font-medium text-gray-600 rounded-tl-lg">Date</th>
                                <th class="p-4 text-sm font-medium text-gray-600">Statut</th>
                                <th class="p-4 text-sm font-medium text-gray-600">Motif</th>
                                <th class="p-4 text-sm font-medium text-gray-600">Statut du motif</th>
                                <th class="p-4 text-sm font-medium text-gray-600 rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($presences as $presence)
                                <tr class="bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                                    <td class="p-4 rounded-l-lg">{{ \Carbon\Carbon::parse($presence->date)->format('d/m/Y') }}</td>
                                    <td class="p-4">
                                        <span class="{{ $presence->status === 'présent' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ ucfirst($presence->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4">{{ $presence->motif ?? '-' }}</td>
                                    <td class="p-4">
                                        @if ($presence->motif_status === 'justifié')
                                            <span class="text-green-600">Justifié</span>
                                        @elseif ($presence->motif_status === 'non_justifié')
                                            <span class="text-red-600">Non justifié</span>
                                        @elseif ($presence->motif_status === 'en_attente')
                                            <span class="text-yellow-600">En attente</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-4 rounded-r-lg">
                                        @if ($presence->status === 'absent' && $presence->motif_status === 'non_justifié')
                                            <button wire:click="openJustifyModal('{{ $presence->date }}')" class="bg-blue-600 text-white py-1 px-3 rounded-lg hover:bg-blue-700 transition duration-200">
                                                Justifier
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-gray-600">Aucune présence enregistrée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Justifier Absence -->
            @if ($showJustifyModal)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl transform transition-all duration-300 ease-in-out">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Justifier l'Absence</h2>
                        <form wire:submit.prevent="justifyAbsence">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Motif</label>
                                <select wire:model="motif" required class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                                    <option value="">Sélectionnez un motif</option>
                                    <option value="maladie">Maladie</option>
                                    <option value="voyage">Voyage</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Justificatif (optionnel)</label>
                                <input type="file" wire:model="justificatif" class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                            </div>
                            <div class="flex justify-end space-x-4">
                                <button type="submit" class="bg-indigo-600 text-white py-2 px-6 rounded-xl hover:bg-indigo-700 transition duration-300 shadow-md">
                                    Soumettre
                                </button>
                                <button wire:click="$set('showJustifyModal', false)" class="bg-gray-500 text-white py-2 px-6 rounded-xl hover:bg-gray-600 transition duration-300 shadow-md">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Graphes des Taux de Présence et Absence</h2>
                <div class="flex justify-center">
                    <canvas id="presenceChart" width="400" height="400"></canvas>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('presenceChart').getContext('2d');
                    const data = {
                        labels: ['Présence', 'Absence'],
                        datasets: [{
                            data: [@json($presenceRate), @json($absenceRate)],
                            backgroundColor: ['#22c55e', '#ef4444'],
                            hoverOffset: 4
                        }]
                    };
                    const config = {
                        type: 'pie',
                        data: data,
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                                        }
                                    }
                                }
                            }
                        }
                    };
                    new Chart(ctx, config);
                });
            </script>
        </div>
    @endvolt
</x-layouts.app>