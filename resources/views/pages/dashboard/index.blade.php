<?php
use function Laravel\Folio\{name, middleware};

middleware(['auth', 'verified', 'role']);
?>
<x-layouts.app title="Accueil">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Bienvenue, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-gray-500">{{ config('etablissement.nom') }}</p>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @foreach ([
                ['/dashboard/notes', 'Mes notes', 'Résultats de chaque évaluation.'],
                ['/dashboard/barbillard', 'Barbillard', 'Notes publiées et annonces officielles.'],
                ['/dashboard/examen', 'Mes évaluations', 'Calendrier des évaluations de ma classe.'],
                ['/dashboard/cours', 'Mes matières', 'Matières et enseignants de ma classe.'],
                ['/dashboard/menu', 'Cantine', 'Menus et commandes de repas.'],
                ['/dashboard/bibliotheque', 'Bibliothèque', 'Livres disponibles et emprunts.'],
                ['/dashboard/support', 'Support', "Poser une question à l'administration."],
                ['/dashboard/compte', 'Mon compte', 'Mes informations personnelles.'],
            ] as [$lien, $titre, $texte])
                <a href="{{ $lien }}" class="group rounded-2xl bg-white p-6 shadow-sm border border-gray-100 hover:border-blue-300 hover:shadow">
                    <h2 class="text-lg font-semibold text-gray-900 group-hover:text-blue-800">{{ $titre }} →</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $texte }}</p>
                </a>
            @endforeach
        </div>
    </div>
</x-layouts.app>
