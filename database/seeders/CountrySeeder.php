<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = config('data.countries');

        $payload = [];

        foreach ($countries as $country) {

            $name = $country;
            $slug = Str::slug($country);

            $payload[] = [
                'key' => strtoupper(Str::substr($slug, 0, 3)) . '-' . strtoupper($slug),
                'name' => $name,
                'slug' => $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Country::upsert(
            $payload,
            ['slug'],
            ['name', 'key']
        );
    }
}
