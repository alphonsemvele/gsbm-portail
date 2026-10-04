<?php
use function Laravel\Folio\{name, middleware};
use Livewire\Volt\Component;
use Livewire\WithFileUploads; // Import du trait nécessaire
use App\Models\User;
use App\Models\Section;
use App\Models\Cycle;
use Illuminate\Support\Str;

name('admin.students');
middleware(['auth', 'verified']);

new class extends Component {
    use WithFileUploads; // Ajout du trait pour l'upload de fichiers

    public $students = [];
    public bool $showAddModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;
    public bool $showActivateModal = false;
    public bool $showDeactivateModal = false;
    public bool $showDetailsModal = false;
    public bool $showImageModal = false;
    public $studentElement = null;
    public bool $showNotification = false;
    public string $notificationMessage = '';
    public string $notificationType = '';
    public array $formErrors = [];
    public string $sectionError = '';

    // Propriétés du formulaire
    public string $name = '';
    public string $lastname = '';
    public string $contact = '';
    public string $whatsapp = '';
    public string $email = '';
    public $section_id = '';
    public $cycle_id = '';
    public string $father_name = '';
    public string $father_contact = '';
    public string $mother_name = '';
    public string $mother_contact = '';
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
            $this->cycle_id = '';
        }
    }

    public function loadData()
    {
        try {
            $this->students = User::with(['section', 'cycle'])
                ->where('role', 'student')
                ->where('status', '!=', 'failed')
                ->get();
            logger('Données élèves chargées', [
                'students_count' => $this->students->count(),
            ]);
        } catch (\Exception $e) {
            logger('Erreur loadData: ' . $e->getMessage());
            $this->students = collect();
            $this->showErrorNotification('Erreur lors du chargement des élèves.');
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
                $this->cycles = Cycle::where('section_id', $sectionId)->where('status', 'Success')->get();
            } else {
                $this->cycles = collect();
            }
            logger('Cycles chargés', [
                'section_id' => $sectionId,
                'cycles_count' => $this->cycles->count(),
            ]);
        } catch (\Exception $e) {
            logger('Erreur loadCycles: ' . $e->getMessage());
            $this->cycles = collect();
            $this->showErrorNotification('Erreur lors du chargement des cycles.');
        }
    }

    public function openAddModal()
    {
        if ($this->sections->isEmpty()) {
            $this->showErrorNotification('Impossible d\'ajouter un élève : aucune section active disponible.');
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
            $this->studentElement = User::with(['section', 'cycle'])->findOrFail($id);
            $this->name = $this->studentElement->name ?? '';
            $this->lastname = $this->studentElement->lastname ?? '';
            $this->contact = $this->studentElement->contact ?? '';
            $this->whatsapp = $this->studentElement->whatsapp ?? '';
            $this->email = $this->studentElement->email ?? '';
            $this->section_id = $this->studentElement->section_id ?? '';
            $this->cycle_id = $this->studentElement->cycle_id ?? '';
            $this->father_name = $this->studentElement->father_name ?? '';
            $this->father_contact = $this->studentElement->father_contact ?? '';
            $this->mother_name = $this->studentElement->mother_name ?? '';
            $this->mother_contact = $this->studentElement->mother_contact ?? '';
            $this->status = $this->studentElement->status ?? '';
            // Note: On ne charge pas l'image existante dans $this->image pour éviter les conflits

            if ($this->section_id) {
                $this->loadCycles($this->section_id);
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
            $this->studentElement = User::with(['section', 'cycle'])->findOrFail($id);
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
            $this->studentElement = User::findOrFail($id);
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
            $this->studentElement = User::findOrFail($id);
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
            $this->studentElement = User::findOrFail($id);
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
            $this->studentElement = User::findOrFail($id);
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
                'lastname' => 'nullable|string|max:255',
                'contact' => 'nullable|string|max:20',
                'whatsapp' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255|unique:users,email',
                'section_id' => 'required|exists:sections,id',
                'cycle_id' => 'required|exists:cycles,id',
                'father_name' => 'nullable|string|max:255',
                'father_contact' => 'nullable|string|max:20',
                'mother_name' => 'nullable|string|max:255',
                'mother_contact' => 'nullable|string|max:20',
                'status' => 'nullable|in:Success,pending',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];

            $validated = $this->validate($rules);

            $section = Section::findOrFail($this->section_id);
            $matricule = $section->code . '-' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

            $data = [
                'name' => $this->name,
                'lastname' => $this->lastname,
                'contact' => $this->contact,
                'whatsapp' => $this->whatsapp,
                'email' => $this->email,
                'role' => 'student',
                'matricule' => $matricule,
                'section_id' => $this->section_id,
                'cycle_id' => $this->cycle_id,
                'father_name' => $this->father_name,
                'father_contact' => $this->father_contact,
                'mother_name' => $this->mother_name,
                'mother_contact' => $this->mother_contact,
                'status' => $this->status ?: 'Success',
                'password' => 'password',
            ];

            // Log avant l'upload
        logger('Image reçue pour upload : ', [
            'image_exists' => !empty($this->image),
            'image_type' => $this->image ? get_class($this->image) : 'null',
        ]);

        // Gestion de l'upload d'image
        if ($this->image) {
            $data['image'] = $this->image->store('students', 'public');
            logger('Image stockée : ', ['path' => $data['image']]);
        }

            User::create($data);

            $this->resetForm();
            $this->showAddModal = false;
            $this->loadData();
            $this->showSuccessNotification('Élève ajouté avec succès !');
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
                'lastname' => 'nullable|string|max:255',
                'contact' => 'nullable|string|max:20',
                'whatsapp' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255|unique:users,email,' . $this->studentElement->id,
                'section_id' => 'required|exists:sections,id',
                'cycle_id' => 'required|exists:cycles,id',
                'father_name' => 'nullable|string|max:255',
                'father_contact' => 'nullable|string|max:20',
                'mother_name' => 'nullable|string|max:255',
                'mother_contact' => 'nullable|string|max:20',
                'status' => 'nullable|in:Success,pending',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];

            $validated = $this->validate($rules);

            $matricule = $this->studentElement->matricule;
            if ($this->studentElement->section_id != $this->section_id) {
                $section = Section::findOrFail($this->section_id);
                $matricule = $section->code . '-' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            }

            $data = [
                'name' => $this->name,
                'lastname' => $this->lastname,
                'contact' => $this->contact,
                'whatsapp' => $this->whatsapp,
                'email' => $this->email,
                'matricule' => $matricule,
                'section_id' => $this->section_id,
                'cycle_id' => $this->cycle_id,
                'father_name' => $this->father_name,
                'father_contact' => $this->father_contact,
                'mother_name' => $this->mother_name,
                'mother_contact' => $this->mother_contact,
                'status' => $this->status ?: 'Success',
            ];

            // Gestion de l'upload d'image pour la modification
            if ($this->image) {
                // Supprimer l'ancienne image si elle existe
                if ($this->studentElement->image && \Storage::disk('public')->exists($this->studentElement->image)) {
                    \Storage::disk('public')->delete($this->studentElement->image);
                }
                $data['image'] = $this->image->store('students', 'public');
            }

            $this->studentElement->update($data);

            $this->resetForm();
            $this->showEditModal = false;
            $this->studentElement = null;
            $this->loadData();
            $this->showSuccessNotification('Élève mis à jour avec succès !');
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
            $student = User::findOrFail($id);
            $student->update(['status' => 'Success']);
            $this->closeModal();
            $this->loadData();
            $this->showSuccessNotification('Élève activé avec succès !');
        } catch (\Exception $e) {
            logger('Erreur activate: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de l\'activation.');
        }
    }

    public function deactivate($id)
    {
        try {
            $student = User::findOrFail($id);
            $student->update(['status' => 'pending']);
            $this->closeModal();
            $this->loadData();
            $this->showSuccessNotification('Élève désactivé avec succès !');
        } catch (\Exception $e) {
            logger('Erreur deactivate: ' . $e->getMessage());
            $this->showErrorNotification('Erreur lors de la désactivation.');
        }
    }

    public function delete($id)
    {
        try {
            $student = User::findOrFail($id);
            $student->update(['status' => 'failed']);
            $this->closeModal();
            $this->loadData();
            $this->showSuccessNotification('Élève supprimé avec succès !');
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
        $this->studentElement = null;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->lastname = '';
        $this->contact = '';
        $this->whatsapp = '';
        $this->email = '';
        $this->section_id = '';
        $this->cycle_id = '';
        $this->father_name = '';
        $this->father_contact = '';
        $this->mother_name = '';
        $this->mother_contact = '';
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
                    Gestion des Élèves
                </h1>
                <p class="mt-3 text-base text-gray-600">Créez, modifiez, activez, désactivez ou supprimez des élèves</p>
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
                        Ajouter un élève
                    </button>
                </div>
            @endif

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-800">Total Élèves</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ count($students) }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-800">Élèves Actifs</h3>
                    <p class="text-3xl font-bold text-green-600">
                        {{ collect($students)->where('status', 'Success')->count() }}
                    </p>
                </div>
            </div>

            <!-- Liste des Élèves -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Liste des Élèves</h2>

                @if (count($students) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-2">
                            <thead>
                                <tr class="bg-gray-100 rounded-lg">
                                    <th class="p-4 text-sm font-medium text-gray-600">Nom</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Matricule</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Section</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Cycle</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Statut</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Image</th>
                                    <th class="p-4 text-sm font-medium text-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr class="bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                                        <td class="p-4">{{ $student->name }} {{ $student->lastname }}</td>
                                        <td class="p-4">{{ $student->matricule ?? 'Non défini' }}</td>
                                        <td class="p-4">{{ $student->section ? $student->section->name : 'Non défini' }}</td>
                                        <td class="p-4">{{ $student->cycle ? $student->cycle->name : 'Non défini' }}</td>
                                        <td class="p-4">
                                            <span
                                                class="inline-block px-3 py-1 text-xs font-medium rounded-full
                                                {{ $student->status === 'Success' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $student->status === 'Success' ? 'Actif' : 'En attente' }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            @if ($student->image)
                                                <img src="{{ asset('storage/' . $student->image) }}" alt="Image de l'élève"
                                                    class="w-12 h-12 object-cover rounded-full cursor-pointer"
                                                    wire:click="openImageModal({{ $student->id }})">
                                            @else
                                                <span class="text-gray-500">Aucune image</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-wrap gap-1">
                                                <button wire:click="openDetailsModal({{ $student->id }})" type="button"
                                                    class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                                    Détails
                                                </button>
                                                @if ($student->status === 'pending')
                                                    <button wire:click="openActivateModal({{ $student->id }})" type="button"
                                                        class="px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                                        Activer
                                                    </button>
                                                @else
                                                    <button wire:click="openDeactivateModal({{ $student->id }})" type="button"
                                                        class="px-2 py-1 bg-yellow-600 text-white text-xs rounded hover:bg-yellow-700">
                                                        Désactiver
                                                    </button>
                                                @endif
                                                <button wire:click="openEditModal({{ $student->id }})" type="button"
                                                    class="px-2 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700">
                                                    Modifier
                                                </button>
                                                <button wire:click="openDeleteModal({{ $student->id }})" type="button"
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
                    <p class="text-center text-gray-500">Aucun élève trouvé.</p>
                @endif
            </div>

            <!-- Modal Ajouter -->
            @if ($showAddModal)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-8 w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Ajouter un Élève</h2>

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
                                    <label class="block text-sm font-medium text-gray-700">Nom</label>
                                    <input type="text" wire:model="name" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Prénom</label>
                                    <input type="text" wire:model="lastname"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact</label>
                                    <input type="text" wire:model="contact"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                                    <input type="text" wire:model="whatsapp"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" wire:model="email"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Section</label>
                                    <select wire:model.live="section_id" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sélectionner une section</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}
                                                ({{ $section->abbreviation }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cycle</label>
                                    <select wire:model="cycle_id" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sélectionner un cycle</option>
                                        @foreach ($cycles as $cycle)
                                            <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nom du père</label>
                                    <input type="text" wire:model="father_name"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact du père</label>
                                    <input type="text" wire:model="father_contact"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nom de la mère</label>
                                    <input type="text" wire:model="mother_name"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact de la mère</label>
                                    <input type="text" wire:model="mother_contact"
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
            @if ($showEditModal && $studentElement)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-8 w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Modifier un Élève</h2>

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
                                    <label class="block text-sm font-medium text-gray-700">Nom</label>
                                    <input type="text" wire:model="name" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Prénom</label>
                                    <input type="text" wire:model="lastname" 
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact</label>
                                    <input type="text" wire:model="contact"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                                    <input type="text" wire:model="whatsapp"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" wire:model="email"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Section</label>
                                    <select wire:model.live="section_id" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sélectionner une section</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}
                                                ({{ $section->abbreviation }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cycle</label>
                                    <select wire:model="cycle_id" required
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sélectionner un cycle</option>
                                        @foreach ($cycles as $cycle)
                                            <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nom du père</label>
                                    <input type="text" wire:model="father_name"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact du père</label>
                                    <input type="text" wire:model="father_contact"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nom de la mère</label>
                                    <input type="text" wire:model="mother_name"
                                        class="mt-2 w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact de la mère</label>
                                    <input type="text" wire:model="mother_contact"
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

                                    @if ($studentElement->image && !$image)
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600">Image actuelle :</p>
                                            <img src="{{ asset('storage/' . $studentElement->image) }}" alt="Image actuelle"
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
            @if ($showDetailsModal && $studentElement)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-8 w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Détails de l'Élève</h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Colonne Image -->
                            <div class="flex flex-col items-center">
                                @if ($studentElement->image)
                                    <img src="{{ asset('storage/' . $studentElement->image) }}" alt="Photo de l'élève"
                                        class="w-40 h-40 object-cover rounded-full mb-4 shadow-lg">
                                @else
                                    <div class="w-40 h-40 bg-gray-200 rounded-full flex items-center justify-center mb-4 shadow-lg">
                                        <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                @endif
                                <h3 class="text-xl font-bold text-center">{{ $studentElement->name }} {{ $studentElement->lastname }}</h3>
                                <p class="text-gray-600 text-center">{{ $studentElement->matricule ?? 'Matricule non défini' }}</p>
                            </div>

                            <!-- Colonne Informations personnelles -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Informations personnelles</h4>
                                <div class="space-y-3">
                                    <p><strong>Contact :</strong> {{ $studentElement->contact ?? 'Non défini' }}</p>
                                    <p><strong>WhatsApp :</strong> {{ $studentElement->whatsapp ?? 'Non défini' }}</p>
                                    <p><strong>Email :</strong> {{ $studentElement->email ?? 'Non défini' }}</p>
                                    <p><strong>Section :</strong> {{ $studentElement->section ? $studentElement->section->name : 'Non défini' }}</p>
                                    <p><strong>Cycle :</strong> {{ $studentElement->cycle ? $studentElement->cycle->name : 'Non défini' }}</p>
                                    <p><strong>Statut :</strong>
                                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full {{ $studentElement->status === 'Success' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $studentElement->status === 'Success' ? 'Actif' : 'En attente' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <!-- Colonne Informations famille -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Informations famille</h4>
                                <div class="space-y-3">
                                    <div>
                                        <p><strong>Nom du père :</strong> {{ $studentElement->father_name ?? 'Non défini' }}</p>
                                        <p><strong>Contact du père :</strong> {{ $studentElement->father_contact ?? 'Non défini' }}</p>
                                    </div>
                                    <div class="pt-2">
                                        <p><strong>Nom de la mère :</strong> {{ $studentElement->mother_name ?? 'Non défini' }}</p>
                                        <p><strong>Contact de la mère :</strong> {{ $studentElement->mother_contact ?? 'Non défini' }}</p>
                                    </div>
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
            @if ($showImageModal && $studentElement && $studentElement->image)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-80 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-6 w-full max-w-2xl shadow-2xl">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">
                            Photo de {{ $studentElement->name }} {{ $studentElement->lastname }}
                        </h3>
                        <div class="flex justify-center">
                            <img src="{{ asset('storage/' . $studentElement->image) }}" alt="Image de l'élève"
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
            @if ($showActivateModal && $studentElement)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Activer l'élève</h3>
                        <p class="mb-6 text-gray-600">Voulez-vous activer l'élève "{{ $studentElement->name }}
                            {{ $studentElement->lastname }}" ?</p>
                        <div class="flex justify-end space-x-4">
                            <button type="button" wire:click="activate({{ $studentElement->id }})"
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
            @if ($showDeactivateModal && $studentElement)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Désactiver l'élève</h3>
                        <p class="mb-6 text-gray-600">Voulez-vous désactiver l'élève "{{ $studentElement->name }}
                            {{ $studentElement->lastname }}" ?</p>
                        <div class="flex justify-end space-x-4">
                            <button type="button" wire:click="deactivate({{ $studentElement->id }})"
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
            @if ($showDeleteModal && $studentElement)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Supprimer l'élève</h3>
                        <p class="mb-6 text-gray-600">Voulez-vous supprimer l'élève "{{ $studentElement->name }}
                            {{ $studentElement->lastname }}" ? Cette action est irréversible.</p>
                        <div class="flex justify-end space-x-4">
                            <button type="button" wire:click="delete({{ $studentElement->id }})"
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
