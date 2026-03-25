<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Resources\EnrollementResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Enrollement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EnrollementController extends Controller
{
    public function selection(){
    return Inertia::render('Enrollement/Select');
    }

    public function showForm(string $selectedType){
      return Inertia::render('Enrollement/Form',[
          'countries' => Country::query()->orderBy('name', 'ASC')->get(),
          'cities' => City::query()->with('communes')->orderBy('name', 'ASC')->get(),
      ]);
    }

     public function store(StoreEnrollmentRequest $request)
    {


        // Génération de la clé unique
        $key = 'ENR-' . date('Y') . '-' . str_pad(Enrollement::count() + 1, 6, '0', STR_PAD_LEFT);

        // Préparation des données
        $data = $request->validated();

        // Upload et stockage des documents
        $documents = [];
        $docFields = [
            'photo_id'      => 'photo_id',
            'doc_id_recto'  => 'doc_id_recto',
            'doc_id_verso' => 'doc_id_verso',
            'driving_licence' => 'driving_licence',
            'insurance' => 'insurance',
            'gray_card' => 'gray_card',
            'cmu' => 'cmu',
        ];

        foreach ($docFields as $inputField => $dbField) {
            if ($request->hasFile($inputField)) {
                $path = $request->file($inputField)->store(
                    "enrollments/{$key}",
                    ['disk' => 'public']
                );
                $documents[$dbField] = $path;
            }
        }

        // Création de l'enrôlement
        $enrollment = Enrollement::create([
            'key' => $key,
            'user_id' => Auth::user()->id,
            'created_by_id' =>  Auth::user()->id,
            ...$data,
            ...$documents,
            'phone' => Auth::user()->id, // Téléphone du profil
            'full_name' => Auth::user()->last_name. ' '. Auth::user()->first_name
        ]);

    }

    public function show(Enrollement $enrollement)
    {
        // Authorization: seul le propriétaire ou un admin peut voir
        // $this->authorize('view', $enrollment);

        $enrollement = $enrollement->load(['city', 'commune','nationality']);
        return Inertia::render('Enrollement/Show', [
            'enrollement' => new EnrollementResource($enrollement)
        ]);
    }
}
