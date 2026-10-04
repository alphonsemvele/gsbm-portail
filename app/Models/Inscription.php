<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscription extends Model
{
    protected $fillable = [
        'eleve_id', 'annee_scolaire', 'type', 'specialite_id', 'redoublant', 'redoublant_classe',
        'etablissement_precedent', 'classe_precedente', 'resultat', 'moyenne_annuelle',
        'raison_changement', 'inscrit_par', 'remise_10', 'tranche_1', 'source', 'saisi_par',
    ];

    protected $casts = [
        'redoublant' => 'boolean',
        'remise_10' => 'boolean',
        'moyenne_annuelle' => 'decimal:2',
        'tranche_1' => 'decimal:2',
    ];

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

    /** La classe est une « specialite » dans le vocabulaire du logiciel. */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Specialite::class, 'specialite_id');
    }

    public function saisiePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'saisi_par');
    }
}
