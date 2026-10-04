<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Inscriptions scolaires du Groupe Scolaire Bilingue.
 *
 * Un eleve est enregistre une seule fois (identite, parents, sante) ; chaque
 * annee scolaire lui ajoute une inscription (nouveaux) ou une reinscription
 * (anciens). La contrainte unique (eleve, annee) interdit deux inscriptions
 * du meme eleve la meme annee.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->nullable()->unique();
            $table->string('nom');
            $table->string('prenoms')->nullable();
            // Nom complet sans accents ni ordre : sert a reperer les doublons.
            $table->string('cle_doublon')->index();
            $table->enum('sexe', ['M', 'F'])->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('nationalite')->nullable();
            $table->string('langue_maternelle')->nullable();
            $table->string('lieu_residence')->nullable();

            foreach (['pere', 'mere'] as $parent) {
                $table->string("{$parent}_nom")->nullable();
                $table->string("{$parent}_profession")->nullable();
                $table->string("{$parent}_telephone")->nullable();
                $table->string("{$parent}_adresse")->nullable();
            }

            $table->string('tuteur_nom')->nullable();
            $table->string('tuteur_lien')->nullable();
            $table->string('tuteur_telephone')->nullable();
            $table->text('allergies')->nullable();
            $table->text('besoins_particuliers')->nullable();
            $table->string('urgence_nom')->nullable();
            $table->string('urgence_telephone')->nullable();
            $table->timestamps();
        });

        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->string('annee_scolaire', 9);
            $table->enum('type', ['inscription', 'reinscription']);
            $table->unsignedBigInteger('specialite_id')->nullable()->index();
            $table->boolean('redoublant')->default(false);
            $table->string('redoublant_classe')->nullable();
            $table->string('etablissement_precedent')->nullable();
            $table->string('classe_precedente')->nullable();
            $table->enum('resultat', ['admis', 'redoublant', 'transfere'])->nullable();
            $table->decimal('moyenne_annuelle', 4, 2)->nullable();
            $table->text('raison_changement')->nullable();
            $table->string('inscrit_par')->nullable();
            $table->boolean('remise_10')->default(false);
            $table->decimal('tranche_1', 12, 2)->nullable();
            $table->enum('source', ['en_ligne', 'import'])->default('en_ligne');
            $table->unsignedBigInteger('saisi_par')->nullable();
            $table->timestamps();

            $table->unique(['eleve_id', 'annee_scolaire'], 'une_inscription_par_an');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
        Schema::dropIfExists('eleves');
    }
};
