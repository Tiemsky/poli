<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EnrollementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    return [

            'id' => $this->id,
            'key' => $this->key,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i'),
            'approved_at' => $this->approved_at?->format('d/m/Y H:i'),
            'rejection_reason' => $this->rejection_reason,
            'city' => $this->whenLoaded('city', fn() => $this->city->name),
            'commune' => $this->whenLoaded('commune', fn() => $this->commune?->name),

            // === INFORMATIONS PERSONNELLES ===
            'nationality' => $this->nationality,
            'gender' => $this->gender,
            'gender_label' => $this->gender === 'M' ? 'Masculin' : 'Féminin',
            'full_name' => $this->full_name,
            'birth_date' => $this->birth_date ? \Carbon\Carbon::parse($this->birth_date)->format('d/m/Y') : null,
            'birth_place' => $this->birth_place,
            'phone' => $this->formatPhone($this->phone),
            'emergency_phone' => $this->formatPhone($this->emergency_phone),
            'emergency_name' => $this->emergency_name,
            'education_level' => $this->education_level,
            'last_diploma' => $this->last_diploma,

            // === INFORMATIONS PROFESSIONNELLES ===
            'experience_start' => $this->experience_start ? \Carbon\Carbon::parse($this->experience_start)->format('d/m/Y') : null,
            'working_for' => $this->working_for,
            'working_for_label' => $this->getWorkingForLabel(),
            'used_platform' => (bool) $this->used_platform,
            'platform_name' => $this->when($this->used_platform, $this->platform_name),
            'vehicle_type' => $this->vehicle_type,
            'vehicle_type_label' => $this->getVehicleTypeLabel(),
            'is_vehicle_owner' => (bool) $this->is_vehicle_owner,
            'is_vehicle_registered' => $this->is_vehicle_registered,
            'vehicle_number' => $this->vehicle_number,
            'vehicle_number_formatted' => $this->formatVehicleNumber($this->vehicle_number),
            'licence_type' => $this->licence_type,
            'licence_type_label' => $this->getLicenceTypeLabel(),

            // === DOCUMENTS (URLs complètes) ===
            'documents' => [
                'photo_id' => $this->getDocumentUrl($this->photo_id),
                'doc_id_recto' => $this->getDocumentUrl($this->doc_id_recto),
                'doc_id_verso' => $this->getDocumentUrl($this->doc_id_verso),
                'driving_licence' => $this->getDocumentUrl($this->driving_licence),
                'insurance' => $this->getDocumentUrl($this->insurance),
                'gray_card' => $this->getDocumentUrl($this->gary_card),
                'cmu' => $this->getDocumentUrl($this->cmu),
            ],
    ];
    }



        /**
     * Génère l'URL publique complète pour un document
     */
    protected function getDocumentUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        // Si le path est déjà une URL complète, on le retourne tel quel
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Utilise Storage::url() qui gère automatiquement le disque 'public'
        try {
            return Storage::disk('public')->url($path);
        } catch (\Exception $e) {
            // Fallback: construction manuelle de l'URL
            return asset('storage/' . ltrim($path, '/'));
        }
    }

    /**
     * Formate un numéro de téléphone ivoirien
     */
    protected function formatPhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        // Supprime tous les caractères non numériques
        $clean = preg_replace('/[^0-9]/', '', $phone);

        // Format: 07 00 00 00 00
        if (strlen($clean) === 10) {
            return implode(' ', str_split($clean, 2));
        }

        return $phone;
    }

    /**
     * Formate le numéro d'immatriculation
     */
    protected function formatVehicleNumber(?string $number): ?string
    {
        if (!$number) {
            return null;
        }

        // Format: AB-1234-CD
        $clean = strtoupper(str_replace('-', '', $number));

        if (preg_match('/^([A-Z]{2})(\d{4})([A-Z]{2})$/', $clean, $matches)) {
            return "{$matches[1]}-{$matches[2]}-{$matches[3]}";
        }

        return $number;
    }

    /**
     * Label lisible pour le statut
     */
    protected function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'En attente',
            'in_review' => 'En vérification',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            default => $this->status,
        };
    }

    /**
     * Label lisible pour working_for
     */
    protected function getWorkingForLabel(): string
    {
        return match ($this->working_for) {
            'independent' => 'À mon compte',
            'company' => 'Une entreprise',
            'platform' => 'Une plateforme de livraison',
            'none' => 'Je ne travaille pas actuellement',
            default => $this->working_for,
        };
    }

    /**
     * Label lisible pour vehicle_type
     */
    protected function getVehicleTypeLabel(): string
    {
        return match ($this->vehicle_type) {
            'moto' => 'Moto',
            'velo' => 'Vélo',
            'velo_electrique' => 'Vélo électrique',
            'scooter' => 'Scooter',
            'voiture' => 'Voiture',
            'camionnette' => 'Camionnette',
            default => $this->vehicle_type,
        };
    }

    /**
     * Label lisible pour licence_type
     */
    protected function getLicenceTypeLabel(): string
    {
        return match ($this->licence_type) {
            'A' => 'Permis A (Moto)',
            'B' => 'Permis B (Voiture)',
            'C' => 'Permis C (Poids lourd)',
            'AB' => 'Permis A+B',
            'none' => 'Aucun permis',
            default => $this->licence_type,
        };
    }
}
