<?php
use function Laravel\Folio\{name,middleware};
use Livewire\Volt\Component;
use App\Models\Annonce;
name('barbillard.index');
middleware(['auth','verified']);

new class extends Component
{
    public $annonces;

    public function mount()
    {
        $this->annonces = Annonce::orderBy('id', 'DESC')->where('status','Success')->get();
    }
}
?>

<x-layouts.app header="true">
    @volt
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <header class="mb-10">
                <h1 class="text-4xl font-extrabold text-gray-800 tracking-tight">Barbillard Numérique</h1>
                <p class="mt-2 text-lg text-gray-500">Restez informé des dernières annonces et communications officielles.</p>
            </header>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Annonces Récentes</h2>
                <div class="space-y-6">
                    @foreach ($annonces as $data)
                        <div class="bg-white border-4 border-l-blue-600 shadow-sm rounded-md p-4">
                            <h3 class="text-lg text-blue-600 font-medium">{{$data->title}}</h3>
                            <p class="text-gray-600">{{$data->content}}</p>
                            <span class="text-sm text-gray-500">Publié le {{$data->date}}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 flex justify-center">
                    <button class="bg-indigo-600 text-white py-2 px-6 rounded-lg hover:bg-indigo-700 transition duration-200">
                        Charger plus d'annonces
                    </button>
                </div>
            </div>
        </div>
    @endvolt
</x-layouts.app>
