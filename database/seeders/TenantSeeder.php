<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = [
            [
                'name' => 'Demo Logistics Ltd',
                'slug' => 'demo-logistics',
                'email' => 'info@demologistics.com',
                'phone' => '+256 700 000 000',
                'address' => 'Kampala, Uganda',
                'is_active' => true,
            ],
            [
                'name' => 'Swift Cargo Services',
                'slug' => 'swift-cargo',
                'email' => 'contact@swiftcargo.com',
                'phone' => '+256 700 111 111',
                'address' => 'Entebbe, Uganda',
                'is_active' => true,
            ],
        ];

        foreach ($tenants as $tenantData) {
            Tenant::create($tenantData);
        }
    }
}
