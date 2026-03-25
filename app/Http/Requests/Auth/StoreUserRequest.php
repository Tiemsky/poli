<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normalisation du téléphone (supprime les espaces, tirets, etc.)
        $this->merge([
            'phone' => preg_replace('/[^0-9]/', '', $this->phone ?? ''),
            'is_company' => filter_var($this->is_company, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // === TYPE DE COMPTE ===
            'is_company' => ['required', 'boolean'],
            'account_type' => ['sometimes', 'nullable', 'in:particulier,entreprise'],

            // === INFORMATIONS PERSONNELLES ===
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[\p{L}\s\-\']+$/u'],
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[\p{L}\s\-\']+$/u'],

            // === CONTACT ===
            'phone' => [
                'required',
                'string',
                'regex:/^[0-9]{10}$/',
                'unique:'.User::class.',phone'
            ],
            // Optionnel : email si vous l'utilisez plus tard
            // 'email' => ['nullable', 'email', 'max:255', 'unique:'.User::class],

            // === LOCALISATION ===
            'city_id' => ['required', 'exists:cities,id'],
            'commune_id' => ['nullable', 'exists:communes,id'],

            // === SÉCURITÉ ===
            'password' => [
                'required',
                'confirmed',
                'string',
                'size:4', // PIN à 4 chiffres
                'regex:/^[0-9]{4}$/',
            ],
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            // Phone
            'phone.required' => 'Le numéro de téléphone est requis',
            'phone.regex' => 'Le numéro doit contenir exactement 10 chiffres (ex: 0708091011)',
            'phone.unique' => 'Ce numéro est déjà associé à un compte',

            // Names
            'last_name.required' => 'Le nom est requis',
            'last_name.regex' => 'Le nom ne peut contenir que des lettres, espaces et tirets',
            'first_name.required' => 'Les prénoms sont requis',
            'first_name.regex' => 'Les prénoms ne peuvent contenir que des lettres, espaces et tirets',

            // Location
            'city_id.required' => 'La ville est requise',
            'city_id.exists' => 'La ville sélectionnée est invalide',
            'commune_id.exists' => 'La commune sélectionnée est invalide',

            // Password (PIN)
            'password.required' => 'Le code PIN est requis',
            'password.confirmed' => 'Les codes PIN ne correspondent pas',
            'password.size' => 'Le code PIN doit contenir exactement 4 chiffres',
            'password.regex' => 'Le code PIN ne peut contenir que des chiffres',

            // Account
            'is_company.required' => 'Veuillez indiquer le type de compte',
            'is_company.boolean' => 'Le type de compte doit être vrai ou faux',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'is_company' => 'type de compte',
            'last_name' => 'nom',
            'first_name' => 'prénoms',
            'phone' => 'numéro de téléphone',
            'city_id' => 'ville',
            'commune_id' => 'commune',
            'password' => 'code PIN',
        ];
    }
}
