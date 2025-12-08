<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Stats Overview -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Total Orders -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Orders</p>
                            <p class="text-3xl font-bold text-black dark:text-white mt-2">{{ $totalOrders }}</p>
                            <p class="text-xs text-green-600 dark:text-green-500 mt-1">+{{ $ordersChange }}% from last month</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900">
                            <svg class="h-6 w-6 text-orange-600 dark:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Customers</p>
                            <p class="text-3xl font-bold text-black dark:text-white mt-2">{{ $totalCustomers }}</p>
                            <p class="text-xs text-green-600 dark:text-green-500 mt-1">+{{ $customersChange }}% from last month</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900">
                            <svg class="h-6 w-6 text-orange-600 dark:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- In Transit -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">In Transit</p>
                            <p class="text-3xl font-bold text-black dark:text-white mt-2">{{ $inTransitOrders }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Active shipments</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900">
                            <svg class="h-6 w-6 text-orange-600 dark:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders & Customers -->
        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Recent Orders -->
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
                <div class="border-b border-neutral-200 dark:border-neutral-700 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-black dark:text-white">Recent Orders</h2>
                        <a href="{{ route('orders') }}" class="text-sm font-medium text-orange-600 hover:text-orange-700 dark:text-orange-500 dark:hover:text-orange-400">View All</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentOrders->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentOrders as $index => $order)
                                <div class="flex items-center justify-between {{ $index < $recentOrders->count() - 1 ? 'pb-4 border-b border-neutral-100 dark:border-neutral-800' : '' }}">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900">
                                            <svg class="h-5 w-5 text-orange-600 dark:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-black dark:text-white">Package #{{ $order->order_number }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->customer->name }} • {{ $order->category->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center rounded-full bg-{{ $order->status_badge_color }}-100 dark:bg-{{ $order->status_badge_color }}-900 px-2.5 py-0.5 text-xs font-medium text-{{ $order->status_badge_color }}-800 dark:text-{{ $order->status_badge_color }}-200">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $order->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No orders yet</p>
                    @endif
                </div>
            </div>

            <!-- Recent Customers -->
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
                <div class="border-b border-neutral-200 dark:border-neutral-700 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-black dark:text-white">Recent Customers</h2>
                        <a href="{{ route('customers') }}" class="text-sm font-medium text-orange-600 hover:text-orange-700 dark:text-orange-500 dark:hover:text-orange-400">View All</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentCustomers->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentCustomers as $index => $customer)
                                <div class="flex items-center justify-between {{ $index < $recentCustomers->count() - 1 ? 'pb-4 border-b border-neutral-100 dark:border-neutral-800' : '' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-600 text-white font-semibold">
                                            {{ $customer->initials }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-black dark:text-white">{{ $customer->name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $customer->email }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-black dark:text-white">{{ $customer->orders_count }} {{ Str::plural('order', $customer->orders_count) }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($customer->status) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No customers yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
