<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\User;
use App\Services\QRCodeService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $qrCodeService = new QRCodeService();

        // Get the first tenant and user
        $tenant = Tenant::first();
        $user = User::where('tenant_id', $tenant->id)->first();

        if (!$user) {
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => 'Demo User',
                'email' => 'demo@freighterx.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Create sample customers
        $customers = [
            ['name' => 'John Kamau', 'email' => 'john.kamau@email.com', 'phone' => '+256 700 123 456'],
            ['name' => 'Sarah Nakato', 'email' => 'sarah.nakato@email.com', 'phone' => '+256 700 234 567'],
            ['name' => 'David Okello', 'email' => 'david.okello@email.com', 'phone' => '+256 700 345 678'],
            ['name' => 'Grace Achieng', 'email' => 'grace.achieng@email.com', 'phone' => '+256 700 456 789'],
        ];

        $createdCustomers = [];
        foreach ($customers as $customerData) {
            $createdCustomers[] = Customer::create([
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'phone' => $customerData['phone'],
                'address' => 'Kampala, Uganda',
                'status' => 'active',
            ]);
        }

        // Create sample orders
        $categories = Category::where('is_active', true)->get();
        $countries = ['China', 'USA', 'UK', 'Dubai', 'India', 'Germany'];
        $statuses = ['pending', 'loaded', 'in_transit', 'arrived', 'ready', 'delivered'];

        for ($i = 1; $i <= 20; $i++) {
            $customer = $createdCustomers[array_rand($createdCustomers)];
            $category = $categories->random();
            $status = $statuses[array_rand($statuses)];

            $orderNumber = 'ORD-2024-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $trackingCode = 'TRK-' . strtoupper(Str::random(8));

            // Generate QR code for each order
            $qrCodePath = $qrCodeService->generateOrderQRCode($trackingCode, $orderNumber);

            Order::create([
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'category_id' => $category->id,
                'order_number' => $orderNumber,
                'tracking_code' => $trackingCode,
                'qr_code' => $qrCodePath,
                'weight' => rand(1, 50) + (rand(0, 99) / 100),
                'origin_country' => $countries[array_rand($countries)],
                'status' => $status,
                'description' => 'Sample order description',
                'loaded_at' => in_array($status, ['loaded', 'in_transit', 'arrived', 'ready', 'delivered']) ? now()->subDays(rand(1, 10)) : null,
                'arrived_at' => in_array($status, ['arrived', 'ready', 'delivered']) ? now()->subDays(rand(1, 5)) : null,
                'delivered_at' => $status === 'delivered' ? now()->subDays(rand(1, 3)) : null,
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}
