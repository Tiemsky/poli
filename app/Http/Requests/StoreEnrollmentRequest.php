<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
    return true;
    }

    public function rules(): array
    {
        return [
            // === PERSONAL INFORMATION ===
            'nationality' => ['required', 'integer'],
            'gender' => ['required', 'in:M,F'],
            'full_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today', 'after:1950-01-01'],
            'birth_place' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'exists:cities,id'],
            'commune_id' => ['nullable', 'exists:communes,id'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'emergency_phone' => ['required', 'string', 'regex:/^[0-9]{10}$/', 'different:phone'],
            'emergency_name' => ['required', 'string', 'max:255'],
            'education_level' => ['nullable', 'string', 'max:50'],
            'last_diploma' => ['nullable', 'string', 'max:50'],

            // === PROFESSIONAL INFORMATION ===
            'experience_start' => ['nullable', 'date', 'before_or_equal:today'],
            'working_for' => ['required', 'in:independent,company,platform,none'],
            'used_platform' => ['nullable', 'boolean'],
            'platform_name' => ['nullable', 'string', 'max:255', 'required_if:working_for,platform'],
            'vehicle_type' => ['required', 'in:moto,velo,velo_electrique,scooter,voiture,camionnette'],
            'is_vehicle_owner' => ['required', 'boolean'],
            'is_vehicle_registered' => ['nullable', 'boolean'],
            'vehicle_number' => [
                'nullable',
                'string',
                'max:50',
                'required_if:is_vehicle_registered,true',
            ],
            'licence_type' => ['nullable', 'in:A,B,C,AB,none'],

            // === DOCUMENTS (Fichiers) ===
            'photo_id' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'doc_id_recto' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'doc_id_verso' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'driving_licence' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'insurance' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'gray_card' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'cmu' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            // Personal
            'nationality.required' => 'La nationalité est requise',
            'nationality.size' => 'Le code nationalité doit comporter 2 caractères',
            'gender.required' => 'Le genre est requis',
            'birth_date.before' => 'La date de naissance doit être dans le passé',
            'birth_date.after' => 'Date de naissance invalide',
            'phone.regex' => 'Le numéro de téléphone doit contenir 10 chiffres',
            'emergency_phone.different' => 'Le contact d\'urgence doit être différent du téléphone principal',

            // Professional
            'working_for.required' => 'Veuillez indiquer pour qui vous travaillez',
            'platform_name.required_if' => 'Le nom de la plateforme est requis',
            'vehicle_type.required' => 'Le type de véhicule est requis',
            'vehicle_number.required_if' => 'Le numéro d\'immatriculation est requis',
            'vehicle_number.regex' => 'Format invalide (ex: AB-1234-CD)',

            // Documents
            'photo_id.required' => 'La photo d\'identité est requise',
            'photo_id.image' => 'Le fichier doit être une image',
            'photo_id.max' => 'La taille maximale est de 5MB',
            'doc_id_recto.required' => 'La recto de la CNI est requis',
            'doc_id_verso.required' => 'La verso de la CNI est requis',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalisation des données
        $this->merge([
            'nationality' => strtoupper($this->nationality),
            'gender' => strtoupper($this->gender),
            'vehicle_number' => strtoupper(str_replace(' ', '', $this->vehicle_number)),
        ]);
    }
}
