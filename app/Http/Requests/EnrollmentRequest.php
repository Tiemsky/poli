<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
        'nationality' => 'required|string',
        'gender' => 'required|in:M,F',
        'birth_date' => 'required|date',
        'birth_place' => 'required|string',
        'country_id' => 'required|exists:countries,id',
        'city_id' => 'required|exists:cities,id',
        'commune_id' => 'nullable|required_if:city_is_abidjan,true|exists:communes,id',
        'emergency_phone' => 'required|string',
        'emergency_name' => 'required|string',
        // Docs
        'doc_photo' => 'required|image|max:10240',
        'doc_id_recto' => 'required|file|mimes:jpg,png,pdf|max:10240',
        'doc_id_verso' => 'required|file|mimes:jpg,png,pdf|max:10240',
        'doc_insurance' => 'required|file|mimes:jpg,png,pdf|max:10240',
        'doc_registration' => 'required|file|mimes:jpg,png,pdf|max:10240',
        // Professionnel
        'experience_start' => 'required|date',
        'vehicle_type' => 'required|string',
    ];
    }
}
