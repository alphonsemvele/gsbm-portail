<?php
use function Laravel\Folio\{name, middleware};

name('personnel.index');
middleware(['auth', 'verified', 'role']);
?>

<x-layouts.app title="Mon espace">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Bonjour {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-gray-500">{{ config('etablissement.nom') }}</p>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @if (auth()->user()->hasSpecialiteModule())
                <a href="/specialite" class="group rounded-2xl bg-white p-6 shadow-sm border border-gray-100 hover:border-blue-300 hover:shadow">
                    <h2 class="text-lg font-semibold text-gray-900 group-hover:text-blue-800">Ma classe →</h2>
                    <p class="mt-1 text-sm text-gray-500">Élèves, matières, évaluations et notes.</p>
                </a>
            @endif
            <a href="/personnel/profil" class="group rounded-2xl bg-white p-6 shadow-sm border border-gray-100 hover:border-blue-300 hover:shadow">
                <h2 class="text-lg font-semibold text-gray-900 group-hover:text-blue-800">Mon profil →</h2>
                <p class="mt-1 text-sm text-gray-500">Mes informations personnelles.</p>
            </a>
        </div>
    </div>
</x-layouts.app>
