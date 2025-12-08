<?php

namespace Database\Seeders;

use App\Models\OriginCountry;
use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OriginCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'United States', 'code' => 'USA'],
            ['name' => 'United Kingdom', 'code' => 'GBR'],
            ['name' => 'Canada', 'code' => 'CAN'],
            ['name' => 'Australia', 'code' => 'AUS'],
            ['name' => 'Germany', 'code' => 'DEU'],
            ['name' => 'France', 'code' => 'FRA'],
            ['name' => 'China', 'code' => 'CHN'],
            ['name' => 'Japan', 'code' => 'JPN'],
            ['name' => 'India', 'code' => 'IND'],
            ['name' => 'Brazil', 'code' => 'BRA'],
        ];

        // Create default countries for each tenant
        Tenant::all()->each(function ($tenant) use ($countries) {
            foreach ($countries as $country) {
                OriginCountry::create([
                    'tenant_id' => $tenant->id,
                    'name' => $country['name'],
                    'code' => $country['code'],
                    'is_active' => true,
                ]);
            }
        });
    }
}
