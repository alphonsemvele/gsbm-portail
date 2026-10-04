<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Eleve extends Model
{
    protected $fillable = [
        'matricule', 'nom', 'prenoms', 'cle_doublon', 'sexe', 'date_naissance', 'lieu_naissance',
        'nationalite', 'langue_maternelle', 'lieu_residence',
        'pere_nom', 'pere_profession', 'pere_telephone', 'pere_adresse',
        'mere_nom', 'mere_profession', 'mere_telephone', 'mere_adresse',
        'tuteur_nom', 'tuteur_lien', 'tuteur_telephone',
        'allergies', 'besoins_particuliers', 'urgence_nom', 'urgence_telephone',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class)->orderByDesc('annee_scolaire');
    }

    public function nomComplet(): string
    {
        return trim($this->nom.' '.$this->prenoms);
    }

    public function estInscrit(string $annee, ?int $sauf = null): bool
    {
        return $this->inscriptions()
            ->where('annee_scolaire', $annee)
            ->when($sauf, fn ($q) => $q->whereKeyNot($sauf))
            ->exists();
    }
}
