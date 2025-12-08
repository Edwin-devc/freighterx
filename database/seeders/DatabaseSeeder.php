<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create tenants first
        $this->call([
            TenantSeeder::class,
        ]);

        // Get the first tenant
        $tenant = Tenant::first();

        // Create test user with tenant as admin
        User::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        $this->call([
            CategorySeeder::class,
            OriginCountrySeeder::class,
        ]);
    }
}
