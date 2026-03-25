<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        // Chargement optimisé avec cache si nécessaire
        $cities = $this->getCitiesForCountry("Côte d’Ivoire");

        return Inertia::render('Auth/Register', [
            'cities' => $cities,
        ]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        // Création de l'utilisateur avec les données validées
        $user = $this->createUser($request->validated());

        // Optionnel : dispatch event si vous avez des listeners
        // event(new Registered($user));

        // Connexion automatique
        Auth::login($user);

        // Redirection avec message de succès
        return redirect()
            ->route('dashboard')
            ->with('success', 'Compte créé avec succès. Bienvenue !');
    }

    /**
     * Récupère les villes d'un pays avec cache optionnel.
     */
    protected function getCitiesForCountry(string $countryName, bool $useCache = true): \Illuminate\Support\Collection
    {
        $cacheKey = 'cities.'.Str::slug($countryName);

        if ($useCache) {
            return cache()->remember($cacheKey, 3600, function () use ($countryName) {
                return $this->loadCities($countryName);
            });
        }

        return $this->loadCities($countryName);
    }

    /**
     * Charge les villes depuis la base (méthode interne).
     */
    private function loadCities(string $countryName): \Illuminate\Support\Collection
    {
        $country = Country::where('slug', Str::slug($countryName))->firstOrFail();

        return City::query()
            ->where('country_id', $country->id)
            ->orderBy('name', 'ASC')
            ->get(['id', 'name']);
    }

    /**
     * Crée un nouvel utilisateur avec les données validées.
     */
    protected function createUser(array $validated): User
    {
        return User::create([
            'key' => Str::uuid(), // clé unique
            'last_name' => Str::title(trim($validated['last_name'])),
            'first_name' => Str::title(trim($validated['first_name'])),
            'phone' => $validated['phone'],
            'role' => 'user',
             'email' => 'noteUsedForNow@email.com',
            // 'email' => $validated['email'] ?? null,
            'city_id' => $validated['city_id'],
            'commune_id' => $validated['commune_id'] ?? null,
            'account_type' => $validated['is_company'] ? 'entreprise' : 'particulier',
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(), // Auto-vérifié si pas d'email requis
        ]);
    }
}
