<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollements', function (Blueprint $table) {
            $table->id();

            // === IDENTIFIANTS ===
            $table->string('key')->unique(); // Clé unique de suivi (ex: ENR-2024-001234)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Le coursier
            $table->foreignId('created_by_id')->constrained('users'); // Qui a créé l'enrôlement

            // === INFORMATIONS PERSONNELLES ===
            $table->string('nationality'); // Code pays: CI, SN, ML, etc.
            $table->enum('gender', ['M', 'F']);
            $table->string('full_name'); // Nom complet
            $table->date('birth_date');
            $table->string('birth_place');
            $table->foreignId('city_id')->constrained('cities');
            $table->foreignId('commune_id')->nullable()->constrained('communes');
            $table->string('phone'); // Téléphone principal
            $table->string('emergency_phone');
            $table->string('emergency_name');
            $table->string('education_level')->nullable();
            $table->string('last_diploma')->nullable();

            // === INFORMATIONS PROFESSIONNELLES ===
            $table->date('experience_start')->nullable();
            $table->string('working_for'); // independent, company, platform, none
            $table->boolean('used_platform')->default(false);
            $table->string('platform_name')->nullable();
            $table->string('vehicle_type'); // moto, velo, velo_electrique, scooter, voiture, camionnette
            $table->boolean('is_vehicle_owner');
            $table->boolean('is_vehicle_registered')->nullable();
            $table->string('vehicle_number')->nullable(); // Immatriculation
            $table->string('licence_type')->nullable(); // A, B, C, AB, none

            // === DOCUMENTS (Chemins de stockage) ===
            $table->string('photo_id'); // Photo d'identité
            $table->string('doc_id_recto'); // CNI Recto
            $table->string('doc_id_verso'); // CNI Verso
            $table->string('driving_licence')->nullable(); // Permis
            $table->string('insurance')->nullable(); // Assurance véhicule
            $table->string('gray_card')->nullable(); // Carte grise
            $table->string('cmu')->nullable(); // CMU/Assurance maladie

            // === STATUT ET MÉTADONNÉES ===
            $table->enum('status', ['pending', 'approved', 'rejected', 'in_review'])->default('pending');
            $table->text('rejection_reason')->nullable(); // Motif de rejet
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by_id')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes(); // Pour archivage sans suppression définitive

                 // Index pour optimiser les recherches
            $table->index(['user_id', 'status']);
            $table->index(['key']);
            $table->index(['created_at']);
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('enrollements');
    }
};
