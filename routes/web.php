<?php

use App\Http\Controllers\ExportInscriptionsController;
use Illuminate\Support\Facades\Route;
use Spatie\RouteDiscovery\Discovery\Discover;

// Export complet de la liste des inscriptions (Excel ou CSV), avec les filtres de l'écran
Route::get('/admin/inscriptions/export', ExportInscriptionsController::class)
    ->middleware(['auth', 'verified', 'role'])
    ->name('admin.inscriptions.export');

Discover::controllers()->in(app_path('Http/Controllers'));

/*
 * Cette application est accessible depuis le portail La Majestueuse.
 * Un visiteur est renvoyé au portail, un employé déjà connecté rejoint son espace.
 */
Route::match(['get', 'post'], '/', function () {
    if (auth()->check()) {
        return redirect(auth()->user()->homePath());
    }

    return redirect(config('portail.url'));
})->name('ism');

Route::get('/articles/{id}', ['App\Http\Controllers\vitrineController', 'show'])->name('articles.show');

Route::post('/logout', ['App\Http\Controllers\AuthController', 'logout'])->name('logout');

Route::get('/specialite/examens/notes/{examen}/{cours}', function ($examen, $cours) {
    return view('pages.specialite.examens.notes', [
        'examenId' => $examen,
        'coursId'  => $cours,
    ]);
})->name('specialite.examens.notes')->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
