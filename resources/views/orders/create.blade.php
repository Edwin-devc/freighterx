<x-layouts.app :title="__('New Order')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-black dark:text-white">Create New Order</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Add a new cargo order to the system</p>
            </div>
            <a href="{{ route('orders') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-neutral-700">
                ← Back to Orders
            </a>
        </div>

        <!-- Form -->
        <div class="max-w-3xl">
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-8">
                <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Customer Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Customer *
                        </label>
                        <div class="flex gap-2">
                            <select id="customerSelect" name="customer_id" required class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-neutral-800 text-black dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="">Select a customer</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="document.getElementById('newCustomerModal').classList.remove('hidden')" class="px-4 py-3 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition flex items-center gap-1" title="Add new customer">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                        @if($customers->isEmpty())
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            No customers found. Click the + button to add your first customer.
                        </p>
                        @endif
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Category *
                            </label>
                            <select name="category_id" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-neutral-800 text-black dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Origin Country -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Origin Country *
                            </label>
                            <select name="origin_country" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-neutral-800 text-black dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="">Select country</option>
                                <option value="China">China</option>
                                <option value="USA">USA</option>
                                <option value="UK">UK</option>
                                <option value="Dubai">Dubai</option>
                                <option value="India">India</option>
                                <option value="Germany">Germany</option>
                                <option value="Japan">Japan</option>
                                <option value="South Korea">South Korea</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Italy">Italy</option>
                                <option value="France">France</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Weight -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Weight (kg)
                        </label>
                        <input type="number" name="weight" step="0.01" min="0" placeholder="e.g., 5.5" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-neutral-800 text-black dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description
                        </label>
                        <textarea name="description" rows="4" placeholder="Enter package description, contents, or special handling instructions..." class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-neutral-800 text-black dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent"></textarea>
                    </div>

                    <!-- Info Box -->
                    <div class="rounded-lg bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 p-4">
                        <div class="flex gap-3">
                            <svg class="h-5 w-5 text-orange-600 dark:text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm text-orange-800 dark:text-orange-200">
                                <p class="font-medium mb-1">Order Number & Tracking Code</p>
                                <p>These will be automatically generated when you create the order. You'll receive a unique order number and tracking code for this shipment.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                        <a href="{{ route('orders') }}" class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-neutral-700">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- New Customer Modal -->
    <div id="newCustomerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-neutral-900 rounded-xl shadow-xl max-w-md w-full mx-4">
            <div class="flex items-center justify-between p-6 border-b border-neutral-200 dark:border-neutral-700">
                <h2 class="text-xl font-bold text-black dark:text-white">New Customer</h2>
                <button onclick="closeCustomerModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="newCustomerForm" class="p-6 space-y-4">
                @csrf
                <div id="customerFormErrors" class="hidden rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-3 text-sm text-red-800 dark:text-red-200"></div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer Name *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-neutral-800 text-black dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address *</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-neutral-800 text-black dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                    <input type="text" name="phone" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-neutral-800 text-black dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                    <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-neutral-800 text-black dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <button type="button" onclick="closeCustomerModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-neutral-700">
                        Cancel
                    </button>
                    <button type="submit" id="createCustomerBtn" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700">
                        Create Customer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function closeCustomerModal() {
            document.getElementById('newCustomerModal').classList.add('hidden');
            document.getElementById('newCustomerForm').reset();
            document.getElementById('customerFormErrors').classList.add('hidden');
        }

        document.getElementById('newCustomerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('createCustomerBtn');
            const errorDiv = document.getElementById('customerFormErrors');
            const originalBtnText = submitBtn.textContent;

            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating...';
            errorDiv.classList.add('hidden');

            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route("customers.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Add new customer to dropdown
                    const select = document.getElementById('customerSelect');
                    const option = new Option(
                        `${data.customer.name} (${data.customer.email})`,
                        data.customer.id,
                        true,
                        true
                    );
                    select.add(option);

                    // Close modal and reset form
                    closeCustomerModal();

                    // Show success message (optional)
                    console.log('Customer created successfully');
                } else {
                    // Show errors
                    let errorMessage = 'Failed to create customer. ';
                    if (data.errors) {
                        errorMessage += Object.values(data.errors).flat().join(' ');
                    } else if (data.message) {
                        errorMessage += data.message;
                    }
                    errorDiv.textContent = errorMessage;
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }
        });
    </script>
</x-layouts.app>
