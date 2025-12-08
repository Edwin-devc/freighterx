<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and components'],
            ['name' => 'Fashion', 'description' => 'Clothing, accessories, and apparel'],
            ['name' => 'Business Supplies', 'description' => 'Office and business equipment'],
            ['name' => 'Home Goods', 'description' => 'Household items and furniture'],
            ['name' => 'Automotive Parts', 'description' => 'Vehicle parts and accessories'],
            ['name' => 'Books', 'description' => 'Books and printed materials'],
            ['name' => 'Toys', 'description' => 'Toys and games'],
            ['name' => 'Food & Beverages', 'description' => 'Food products and drinks'],
            ['name' => 'Health & Beauty', 'description' => 'Health and beauty products'],
            ['name' => 'Sports & Outdoors', 'description' => 'Sports equipment and outdoor gear'],
            ['name' => 'Other', 'description' => 'Miscellaneous items'],
        ];

        // Create default categories for each tenant
        Tenant::all()->each(function ($tenant) use ($categories) {
            foreach ($categories as $category) {
                Category::create([
                    'tenant_id' => $tenant->id,
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => true,
                ]);
            }
        });
    }
}
