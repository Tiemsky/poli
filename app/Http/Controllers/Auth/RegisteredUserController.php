<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        // On récupère des tableaux simples, pas des objets
        $cities = $this->getCitiesForCountry("Côte d’Ivoire");

        return Inertia::render('Auth/Register', [
            'cities' => $cities,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = $this->createUser($request->validated());

        Auth::login($user);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Compte créé avec succès. Bienvenue !');
    }

    protected function getCitiesForCountry(string $countryName, bool $useCache = true)
    {
        $cacheKey = 'cities_array.'.Str::slug($countryName);

        if ($useCache) {
            return cache()->remember($cacheKey, 3600, function () use ($countryName) {
                return $this->loadCities($countryName);
            });
        }

        return $this->loadCities($countryName);
    }

    /**
     * Charge les villes et les convertit en tableau immédiatement.
     */
    private function loadCities(string $countryName): array
    {
        $country = Country::where('slug', Str::slug($countryName))->firstOrFail();

        return City::query()
            ->where('country_id', $country->id)
            ->orderBy('name', 'ASC')
            ->get(['id', 'name'])
            ->toArray(); // CRITIQUE : On transforme en tableau pour le cache
    }

    protected function createUser(array $validated)
    {
        return User::create([
            'key' => Str::uuid(),
            'last_name' => Str::title(trim($validated['last_name'])),
            'first_name' => Str::title(trim($validated['first_name'])),
            'phone' => $validated['phone'],
            'role' => 'user',
            // 'email' => 'noteUsedForNow@email.com',
            'city_id' => $validated['city_id'],
            'commune_id' => $validated['commune_id'] ?? null,
            'account_type' => $validated['is_company'] ? 'entreprise' : 'particulier',
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);
    }
}
