<?php
use function Laravel\Folio\{name, middleware};
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\Cycle;
use App\Models\Section;
use Illuminate\Support\Str;

name('admin.personnel');
middleware(['auth', 'verified']);

new class extends Component {
    use WithFileUploads;

    public $users = [];
    public bool $showAddModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;
    public bool $showActivateModal = false;
    public bool $showDeactivateModal = false;
    public bool $showDetailsModal = false;
    public bool $showImageModal = false;
    public bool $showNotification = false;
    public string $notificationMessage = '';
    public string $notificationType = '';
    public array $formErrors = [];
    public string $sectionError = '';
    public $userElement = null;

    // Propriétés du formulaire
    public string $name = '';
    public string $role = '';
    public $cycle_id = '';
    public $section_id = '';
    public string $email = '';
    public string $contact = '';
    public string $status = '';
    public $image = null;
    public $sections = [];
    public $cycles = [];

    public function mount()
    {
        $this->loadData();
        $this->loadSections();
        $this->cycles = collect();
    }

    public function updated($property, $value)
    {
        if ($property === 'section_id' && $value) {
            $this->loadCycles($value);
            $this->cycle_id = ''; // Réinitialiser le cycle sélectionné
        }
        if ($property === 'role') {
            if ($this->role !== 'enseignant') {
                $this->section_id = '';
                $this->cycle_id = '';
                $this->cycles = collect();
            } else {
                // Charger les sections si le rôle est enseignant
                $this->loadSections();
            }
        }
    }

    public function loadData()
    {
        try {
            $this->users = User::with(['cycle', 'section'])
                ->where('role', '!=', 'student')
                ->where('status', '!=', 'failed')
                ->get();
            logger('Données personnel chargées', [
                'users_count' => $this->users->count(),
            ]);
        } catch (\Exception $e) {
            logger('Erreur loadData: ' . $e->getMessage());
            $this->users = collect();
            $this->showErrorNotification('Erreur lors du chargement du personnel.');
        }
    }

    public function loadSections()
    {
        try {
            $this->sections = Section::where('status', 'Success')->get();
            if ($this->sections->isEmpty()) {
                $this->sectionError = 'Aucune section active disponible. Veuillez créer une section avec le statut "Actif".';
            }
            logger('Sections chargées', [
                'sections_count' => $this->sections->count(),
            ]);
        } catch (\Exception $e) {
            logger('Erreur loadSections: ' . $e->getMessage());
            $this->sections = collect();
            $this->sectionError = 'Erreur lors du chargement des sections.';
            $this->showErrorNotification('Erreur lors du chargement des sections.');
        }
    }

    public function loadCycles($sectionId)
    {
        try {
            if ($sectionId) {
                $this->cycles = Cycle::where('section_id', $sectionId)
                    ->where('status', 'Success')
                    ->get();
                logger('Cycles chargés', [
                    'section_id' => $sectionId,
                    'cycles_count' => $this->cycles->count(),
                ]);
            } else {
                $this->cycles = collect();
                logger('Aucun section_id fourni pour loadCycles');
            }
        } catch (\Exception $e) {
            logger('Erreur loadCycles: ' . $e->getMessage());
            $this->cycles = collect();
            $this->showErrorNotification('Erreur lors du chargement des cycles.');
        }
    }

    public function openAddModal()
    {
        if ($this->sections->isEmpty()) {
            $this->showErrorNotification('Impossible d\'ajouter un personnel : aucune section active disponible.');
            return;
        }
        $this->resetForm();
        $this->showAddModal = true;
        $this->formErrors = [];
        logger('Modal ajouter ouvert');
    }

    public function openEditModal($id)
    {
        try {
            $this->userElement = User::with(['cycle', 'section'])->findOrFail($id);
            $this->name = $this->userElement->name ?? '';
            $this->role = $this->userElement->role ?? '';
            $this->cycle_id = $this->userElement->cycle_id ?? '';
            $this->section_id = $this->userElement->section_id ?? '';
            $this->email = $this->userElement->email ?? '';
            $this->contact = $this->userElement->contact ?? '';
            $this->status = $this->userElement->status ?? '';
            if ($this->section_id && $this->role === 'enseignant') {
                $this->loadCycles($this->section_id);
            } else {
                $this->cycles = collect();
            }
            $this->showEditModal = true;
            $this->formErrors = [];
            logger('Modal édition ouvert pour ID: ' . $id);
        } catch (\Exception $e) {
            logger('Erreur openEditModal: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de l\'ouverture du modal d\'édition.');
        }
    }

    public function openDetailsModal($id)
    {
        try {
            $this->userElement = User::with(['cycle', 'section'])->findOrFail($id);
            $this->showDetailsModal = true;
            logger('Modal détails ouvert pour ID: ' . $id);
        } catch (\Exception $e) {
            logger('Erreur openDetailsModal: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de l\'ouverture du modal des détails.');
        }
    }

    public function openImageModal($id)
    {
        try {
            $this->userElement = User::findOrFail($id);
            $this->showImageModal = true;
            logger('Modal image ouvert pour ID: ' . $id);
        } catch (\Exception $e) {
            logger('Erreur openImageModal: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de l\'ouverture du modal de l\'image.');
        }
    }

    public function openActivateModal($id)
    {
        try {
            $this->userElement = User::findOrFail($id);
            $this->showActivateModal = true;
            logger('Modal activation ouvert pour ID: ' . $id);
        } catch (\Exception $e) {
            logger('Erreur openActivateModal: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de l\'ouverture du modal.');
        }
    }

    public function openDeactivateModal($id)
    {
        try {
            $this->userElement = User::findOrFail($id);
            $this->showDeactivateModal = true;
            logger('Modal désactivation ouvert pour ID: ' . $id);
        } catch (\Exception $e) {
            logger('Erreur openDeactivateModal: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de l\'ouverture du modal.');
        }
    }

    public function openDeleteModal($id)
    {
        try {
            $this->userElement = User::findOrFail($id);
            $this->showDeleteModal = true;
            logger('Modal suppression ouvert pour ID: ' . $id);
        } catch (\Exception $e) {
            logger('Erreur openDeleteModal: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de l\'ouverture du modal.');
        }
    }

    public function save()
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'role' => 'required|in:enseignant,directeur,secretaire,econome,concierge,bibliothecaire,admin',
                'cycle_id' => 'nullable|exists:cycles,id',
                'section_id' => 'required_if:role,enseignant|exists:sections,id',
                'email' => 'required|email|unique:users,email',
                'contact' => 'required|string|max:20',
                'status' => 'nullable|in:Success,pending',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];

            $validated = $this->validate($rules);

            $matricule = null;
            if ($this->role === 'enseignant' && $this->section_id) {
                $section = Section::findOrFail($this->section_id);
                $matricule = $section->code . '-' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            }

            $data = [
                'name' => $this->name,
                'role' => $this->role,
                'cycle_id' => $this->role === 'enseignant' ? $this->cycle_id : null,
                'section_id' => $this->role === 'enseignant' ? $this->section_id : null,
                'email' => $this->email,
                'contact' => $this->contact,
                'status' => $this->status ?: 'pending',
                'password' => bcrypt('password'),
                'matricule' => $matricule,
            ];

            if ($this->image) {
                $data['image'] = $this->image->store('personnel', 'public');
            }

            User::create($data);

            $this->resetForm();
            $this->showAddModal = false;
            $this->loadData();
            $this->showSuccessNotification('Personnel ajouté avec succès !');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->formErrors = $e->errors();
        } catch (\Exception $e) {
            logger('Erreur save: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de la sauvegarde.');
        }
    }

    public function update()
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'role' => 'required|in:enseignant,directeur,secretaire,econome,concierge,bibliothecaire,admin',
                'cycle_id' => 'nullable|exists:cycles,id',
                'section_id' => 'required_if:role,enseignant|exists:sections,id',
                'email' => 'required|email|unique:users,email,' . $this->userElement->id,
                'contact' => 'required|string|max:20',
                'status' => 'nullable|in:Success,pending',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];

            $validated = $this->validate($rules);

            $matricule = $this->userElement->matricule;
            if ($this->role === 'enseignant' && $this->section_id && $this->userElement->section_id != $this->section_id) {
                $section = Section::findOrFail($this->section_id);
                $matricule = $section->code . '-' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            } elseif ($this->role !== 'enseignant') {
                $matricule = null;
            }

            $data = [
                'name' => $this->name,
                'role' => $this->role,
                'cycle_id' => $this->role === 'enseignant' ? $this->cycle_id : null,
                'section_id' => $this->role === 'enseignant' ? $this->section_id : null,
                'email' => $this->email,
                'contact' => $this->contact,
                'status' => $this->status ?: 'pending',
                'matricule' => $matricule,
            ];

            if ($this->image) {
                if ($this->userElement->image && \Storage::disk('public')->exists($this->userElement->image)) {
                    \Storage::disk('public')->delete($this->userElement->image);
                }
                $data['image'] = $this->image->store('personnel', 'public');
            }

            $this->userElement->update($data);

            $this->resetForm();
            $this->showEditModal = false;
            $this->userElement = null;
            $this->loadData();
            $this->showSuccessNotification('Personnel mis à jour avec succès !');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->formErrors = $e->errors();
        } catch (\Exception $e) {
            logger('Erreur update: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de la mise à jour.');
        }
    }

    public function activate($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update(['status' => 'Success']);
            $this->closeModal();
            $this->loadData();
            $this->showSuccessNotification('Personnel activé avec succès !');
        } catch (\Exception $e) {
            logger('Erreur activate: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de l\'activation.');
        }
    }

    public function deactivate($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update(['status' => 'pending']);
            $this->closeModal();
            $this->loadData();
            $this->showSuccessNotification('Personnel désactivé avec succès !');
        } catch (\Exception $e) {
            logger('Erreur deactivate: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de la désactivation.');
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update(['status' => 'failed']);
            $this->closeModal();
            $this->loadData();
            $this->showSuccessNotification('Personnel supprimé avec succès !');
        } catch (\Exception $e) {
            logger('Erreur delete: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de la suppression.');
        }
    }

    public function closeModal()
    {
        $this->showAddModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showActivateModal = false;
        $this->showDeactivateModal = false;
        $this->showDetailsModal = false;
        $this->showImageModal = false;
        $this->userElement = null;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->role = '';
        $this->cycle_id = '';
        $this->section_id = '';
        $this->email = '';
        $this->contact = '';
        $this->status = '';
        $this->image = null;
        $this->formErrors = [];
        $this->cycles = collect();
    }

    private function showSuccessNotification($message)
    {
        $this->notificationMessage = $message;
        $this->notificationType = 'success';
        $this->showNotification = true;
    }

    private function showErrorNotification($message)
    {
        $this->notificationMessage = $message;
        $this->notificationType = 'error';
        $this->showNotification = true;
    }
};
?>

<x-layouts.app header="true">
    @volt
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header -->
            <header class="mb-12 text-center">
                <h1
                    class="text-4xl font-bold text-gray-900 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent tracking-tight">
                    Gestion du Personnel
                </h1>
                <p class="mt-3 text-base text-gray-600">Créez, modifiez, activez, désactivez ou supprimez des membres du personnel</p>
            </header>

            <!-- Bouton Ajouter -->
            @if ($sectionError)
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg border-l-4 border-red-500">
                    {{ $sectionError }}
                </div>
            @else
                <div class="mb-8 text-center">
                    <button wire:click="openAddModal" type="button"
                        class="bg-indigo-600 text-white py-3 px-8 rounded-xl hover:bg-indigo-700 transition duration-300 shadow-lg">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter un personnel
                    </button>
                </div>
            @endif

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-800">Total Personnel</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ count($users) }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-800">Personnel Actif</h3>
                    <p class="text-3xl font-bold text-green-600">
                        {{ collect($users)->where('status', 'Success')->count() }}
                    </p>
                </div>
            </div>

            <!-- Liste du Personnel -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Liste du Personnel</h2>

                @if (count($users) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-2">
                            <thead>
                                <tr class="bg-gray-100 rounded-lg">
                                    <th class="p-4 text-sm font-medium text-gray-600">Nom</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Matricule</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Rôle</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Section</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Cycle</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Statut</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Image</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                                        <td class="p-4">{{ $user->name }}</td>
                                        <td class="p-4">{{ $user->matricule ?? 'Non défini' }}</td>
                                        <td class="p-4">
                                            @switch($user->role)
                                                @case('enseignant') Enseignant @break
                                                @case('directeur') Directeur @break
                                                @case('secretaire') Secrétaire @break
                                                @case('econome') Économe @break
                                                @case('concierge') Concierge @break
                                                @case('bibliothecaire') Bibliothécaire @break
                                                @case('admin') Administrateur @break
                                                @default N/A @break
                                            @endswitch
                                        </td>
                                        <td class="p-4">{{ $user->section ? $user->section->name : '-' }}</td>
                                        <td class="p-4">{{ $user->cycle ? $user->cycle->name : '-' }}</td>
                                        <td class="p-4">
                                            <span
                                                class="inline-block px-3 py-1 text-xs font-medium rounded-full
                                                {{ $user->status === 'Success' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $user->status === 'Success' ? 'Actif' : 'En attente' }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            @if ($user->image)
                                                <img src="{{ asset('storage/' . $user->image) }}" alt="Image du personnel"
                                                    class="w-12 h-12 object-cover rounded-full cursor-pointer"
                                                    wire:click="openImageModal({{ $user->id }})">
                                            @else
                                                <span class="text-gray-500">Aucune image</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-wrap gap-1">
                                                <button wire:click="openDetailsModal({{ $user->id }})" type="button"
                                                    class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                                    Détails
                                                </button>
                                                @if ($user->status === 'pending')
                                                    <button wire:click="openActivateModal({{ $user->id }})" type="button"
                                                        class="px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                                        Activer
                                                    </button>
                                                @else
                                                    <button wire:click="openDeactivateModal({{ $user->id }})" type="button"
                                                        class="px-2 py-1 bg-yellow-600 text-white text-xs rounded hover:bg-yellow-700">
                                                        Désactiver
                                                    </button>
                                                @endif
                                                <button wire:click="openEditModal({{ $user->id }})" type="button"
                                                    class="px-2 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700">
                                                    Modifier
                                                </button>
                                                <button wire:click="openDeleteModal({{ $user->id }})" type="button"
                                                    class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                                    Supprimer
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500">Aucun personnel trouvé.</p>
                @endif
            </div>

            <!-- Modal Ajouter -->
            @if ($showAddModal)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-8 w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Ajouter un Personnel</h2>

                        @if (!empty($formErrors))
                            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl border-l-4 border-red-500">
                                <strong>Erreurs :</strong>
                                <ul class="list-disc ml-5 mt-2">
                                    @foreach ($formErrors as $field => $errors)
                                        @foreach ((array) $errors as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form wire:submit="save" enctype="multipart/form-data">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nom Complet</label>
                                    <input type="text" wire:model="name" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Rôle</label>
                                    <select wire:model.live="role" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sélectionner un rôle</option>
                                        <option value="directeur">Directeur</option>
                                        <option value="secretaire">Secrétaire</option>
                                        <option value="econome">Économe</option>
                                        <option value="enseignant">Enseignant</option>
                                        <option value="concierge">Concierge</option>
                                        <option value="bibliothecaire">Bibliothécaire</option>
                                        <option value="admin">Administrateur</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Section</label>
                                    <select wire:model.live="section_id" {{ $role !== 'enseignant' ? 'disabled' : '' }}
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sélectionner une section</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }} ({{ $section->abbreviation }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cycle</label>
                                    <select wire:model="cycle_id" {{ $role !== 'enseignant' ? 'disabled' : '' }}
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sélectionner un cycle</option>
                                        @foreach ($cycles as $cycle)
                                            <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" wire:model="email" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                    <input type="text" wire:model="contact" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Statut</label>
                                    <select wire:model="status"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sélectionner un statut</option>
                                        <option value="Success">Actif</option>
                                        <option value="pending">En attente</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Image de profil</label>
                                    <input type="file" wire:model="image" accept="image/*"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                    @if ($image)
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600">Aperçu :</p>
                                            <img src="{{ $image->temporaryUrl() }}" alt="Aperçu" class="w-20 h-20 object-cover rounded-full mt-1">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end space-x-4">
                                <button type="submit"
                                    class="bg-indigo-600 text-white py-3 px-6 rounded-xl hover:bg-indigo-700">
                                    Ajouter
                                </button>
                                <button type="button" wire:click="closeModal"
                                    class="bg-gray-500 text-white py-3 px-6 rounded-xl hover:bg-gray-600">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Modal Modifier -->
            @if ($showEditModal && $userElement)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-8 w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Modifier un Personnel</h2>

                        @if (!empty($formErrors))
                            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl border-l-4 border-red-500">
                                <strong>Erreurs :</strong>
                                <ul class="list-disc ml-5 mt-2">
                                    @foreach ($formErrors as $field => $errors)
                                        @foreach ((array) $errors as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form wire:submit="update" enctype="multipart/form-data">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nom Complet</label>
                                    <input type="text" wire:model="name" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Rôle</label>
                                    <select wire:model.live="role" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sélectionner un rôle</option>
                                        <option value="directeur">Directeur</option>
                                        <option value="secretaire">Secrétaire</option>
                                        <option value="econome">Économe</option>
                                        <option value="enseignant">Enseignant</option>
                                        <option value="concierge">Concierge</option>
                                        <option value="bibliothecaire">Bibliothécaire</option>
                                        <option value="admin">Administrateur</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Section</label>
                                    <select wire:model.live="section_id" {{ $role !== 'enseignant' ? 'disabled' : '' }}
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sélectionner une section</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }} ({{ $section->abbreviation }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cycle</label>
                                    <select wire:model="cycle_id" {{ $role !== 'enseignant' ? 'disabled' : '' }}
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sélectionner un cycle</option>
                                        @foreach ($cycles as $cycle)
                                            <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" wire:model="email" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                    <input type="text" wire:model="contact" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Statut</label>
                                    <select wire:model="status"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sélectionner un statut</option>
                                        <option value="Success">Actif</option>
                                        <option value="pending">En attente</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Image de profil</label>
                                    <input type="file" wire:model="image" accept="image/*"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                    @if ($image)
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600">Nouvelle image :</p>
                                            <img src="{{ $image->temporaryUrl() }}" alt="Nouvelle image" class="w-20 h-20 object-cover rounded-full mt-1">
                                        </div>
                                    @endif
                                    @if ($userElement->image && !$image)
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600">Image actuelle :</p>
                                            <img src="{{ asset('storage/' . $userElement->image) }}" alt="Image actuelle"
                                                class="w-20 h-20 object-cover rounded-full mt-1">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end space-x-4">
                                <button type="submit"
                                    class="bg-indigo-600 text-white py-3 px-6 rounded-xl hover:bg-indigo-700">
                                    Mettre à jour
                                </button>
                                <button type="button" wire:click="closeModal"
                                    class="bg-gray-500 text-white py-3 px-6 rounded-xl hover:bg-gray-600">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Modal Détails -->
            @if ($showDetailsModal && $userElement)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-8 w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Détails du Personnel</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Colonne Image -->
                            <div class="flex flex-col items-center">
                                @if ($userElement->image)
                                    <img src="{{ asset('storage/' . $userElement->image) }}" alt="Photo du personnel"
                                        class="w-40 h-40 object-cover rounded-full mb-4 shadow-lg">
                                @else
                                    <div class="w-40 h-40 bg-gray-200 rounded-full flex items-center justify-center mb-4 shadow-lg">
                                        <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                @endif
                                <h3 class="text-xl font-bold text-center">{{ $userElement->name }}</h3>
                                <p class="text-gray-600 text-center">{{ $userElement->matricule ?? 'Non défini' }}</p>
                                <p class="text-gray-600 text-center">
                                    @switch($userElement->role)
                                        @case('enseignant') Enseignant @break
                                        @case('directeur') Directeur @break
                                        @case('secretaire') Secrétaire @break
                                        @case('econome') Économe @break
                                        @case('concierge') Concierge @break
                                        @case('bibliothecaire') Bibliothécaire @break
                                        @case('admin') Administrateur @break
                                        @default N/A @break
                                    @endswitch
                                </p>
                            </div>

                            <!-- Colonne Informations -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Informations</h4>
                                <div class="space-y-3">
                                    <p><strong>Email :</strong> {{ $userElement->email ?? 'Non défini' }}</p>
                                    <p><strong>Téléphone :</strong> {{ $userElement->contact ?? 'Non défini' }}</p>
                                    <p><strong>Section :</strong> {{ $userElement->section ? $userElement->section->name : 'Non défini' }}</p>
                                    <p><strong>Cycle :</strong> {{ $userElement->cycle ? $userElement->cycle->name : 'Non défini' }}</p>
                                    <p><strong>Statut :</strong>
                                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full {{ $userElement->status === 'Success' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $userElement->status === 'Success' ? 'Actif' : 'En attente' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="button" wire:click="closeModal"
                                class="bg-gray-500 text-white py-3 px-6 rounded-xl hover:bg-gray-600">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Modal Image -->
            @if ($showImageModal && $userElement && $userElement->image)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-80 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-6 w-full max-w-2xl shadow-2xl">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">
                            Photo de {{ $userElement->name }}
                        </h3>
                        <div class="flex justify-center">
                            <img src="{{ asset('storage/' . $userElement->image) }}" alt="Image du personnel"
                                class="max-w-full max-h-96 object-contain rounded-lg shadow-lg">
                        </div>
                        <div class="mt-6 flex justify-center">
                            <button type="button" wire:click="closeModal"
                                class="bg-gray-500 text-white py-2 px-6 rounded-xl hover:bg-gray-600">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Modal Activer -->
            @if ($showActivateModal && $userElement)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Activer le Personnel</h3>
                        <p class="mb-6 text-gray-600">Voulez-vous activer {{ $userElement->name }} ?</p>
                        <div class="flex justify-end space-x-4">
                            <button type="button" wire:click="activate({{ $userElement->id }})"
                                class="bg-green-600 text-white py-2 px-4 rounded-xl hover:bg-green-700">
                                Oui
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="bg-gray-500 text-white py-2 px-4 rounded-xl hover:bg-gray-600">
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Modal Désactiver -->
            @if ($showDeactivateModal && $userElement)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Désactiver le Personnel</h3>
                        <p class="mb-6 text-gray-600">Voulez-vous désactiver {{ $userElement->name }} ?</p>
                        <div class="flex justify-end space-x-4">
                            <button type="button" wire:click="deactivate({{ $userElement->id }})"
                                class="bg-yellow-600 text-white py-2 px-4 rounded-xl hover:bg-yellow-700">
                                Oui
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="bg-gray-500 text-white py-2 px-4 rounded-xl hover:bg-gray-600">
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Modal Supprimer -->
            @if ($showDeleteModal && $userElement)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Supprimer le Personnel</h3>
                        <p class="mb-6 text-gray-600">Voulez-vous supprimer {{ $userElement->name }} ? Cette action est irréversible.</p>
                        <div class="flex justify-end space-x-4">
                            <button type="button" wire:click="delete({{ $userElement->id }})"
                                class="bg-red-600 text-white py-2 px-4 rounded-xl hover:bg-red-700">
                                Oui
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="bg-gray-500 text-white py-2 px-4 rounded-xl hover:bg-gray-600">
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Notification -->
            @if ($showNotification)
                <div class="fixed top-6 right-6 z-50 max-w-sm w-full" x-data="{ show: true }" x-init="setTimeout(() => { show = false;
                    $wire.set('showNotification', false); }, 3000)"
                    x-show="show">
                    <div
                        class="{{ $notificationType === 'success' ? 'bg-green-100 text-green-800 border-green-500' : 'bg-red-100 text-red-800 border-red-500' }} p-4 rounded-xl shadow-md border-l-4">
                        {{ $notificationMessage }}
                    </div>
                </div>
            @endif
        </div>
    @endvolt
</x-layouts.app>
