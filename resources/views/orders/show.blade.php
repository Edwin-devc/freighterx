<x-layouts.app :title="'Order #' . $order->order_number">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Success Message -->
        @if(session('success'))
            <div class="rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="ml-3 text-sm font-medium text-green-800 dark:text-green-300">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('orders') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-black dark:text-white">Order #{{ $order->order_number }}</h1>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tracking Code: {{ $order->tracking_code }}</p>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $statusColors = [
                        'pending' => 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200',
                        'loaded' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                        'in_transit' => 'bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200',
                        'arrived' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                        'ready' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                        'delivered' => 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200',
                    ];
                    $statusClass = $statusColors[$order->status] ?? $statusColors['pending'];
                @endphp
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $statusClass }}">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Main Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Information -->
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-black dark:text-white">Order Information</h2>
                        @auth
                        <p class="text-sm text-gray-600 dark:text-gray-400">Tap status to update:</p>
                        @endauth
                    </div>

                    @auth
                    <!-- Status Options -->
                    <div class="mb-4 pb-4 border-b border-neutral-200 dark:border-neutral-700">
                        <div class="flex flex-wrap gap-2">
                            <button onclick="confirmStatusChange('pending', 'New')"
                                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition border {{ $order->status == 'pending' ? 'bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600' : 'bg-white dark:bg-neutral-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                New
                            </button>
                            <button onclick="confirmStatusChange('loaded', 'Processing')"
                                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition border {{ $order->status == 'loaded' ? 'bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600' : 'bg-white dark:bg-neutral-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Processing
                            </button>
                            <button onclick="confirmStatusChange('in_transit', 'Shipped')"
                                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition border {{ $order->status == 'in_transit' ? 'bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600' : 'bg-white dark:bg-neutral-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                                Shipped
                            </button>
                            <button onclick="confirmStatusChange('ready', 'Delivered')"
                                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition border {{ $order->status == 'ready' ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border-green-200 dark:border-green-800' : 'bg-white dark:bg-neutral-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Delivered
                            </button>
                            <button onclick="confirmStatusChange('delivered', 'Cancelled')"
                                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition border {{ $order->status == 'delivered' ? 'bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600' : 'bg-white dark:bg-neutral-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancelled
                            </button>
                        </div>
                    </div>
                    @endauth

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Order Number</p>
                            <p class="text-base font-medium text-black dark:text-white mt-1">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Tracking Code</p>
                            <p class="text-base font-medium text-black dark:text-white mt-1">{{ $order->tracking_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Category</p>
                            <p class="text-base font-medium text-black dark:text-white mt-1">{{ $order->category->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Weight</p>
                            <p class="text-base font-medium text-black dark:text-white mt-1">{{ $order->weight ? $order->weight . ' kg' : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Origin Country</p>
                            <p class="text-base font-medium text-black dark:text-white mt-1">{{ $order->origin_country }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Created Date</p>
                            <p class="text-base font-medium text-black dark:text-white mt-1">{{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @if($order->description)
                        <div class="mt-4 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Description</p>
                            <p class="text-base text-black dark:text-white mt-1">{{ $order->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Customer Information -->
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-6">
                    <h2 class="text-lg font-semibold text-black dark:text-white mb-4">Customer Information</h2>
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-600 text-white text-lg font-semibold">
                            {{ $order->customer->initials }}
                        </div>
                        <div class="flex-1">
                            <p class="text-lg font-medium text-black dark:text-white">{{ $order->customer->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->customer->email }}</p>
                            @if($order->customer->phone)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->customer->phone }}</p>
                            @endif
                            @if($order->customer->address)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $order->customer->address }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-6">
                    <h2 class="text-lg font-semibold text-black dark:text-white mb-4">Order Timeline</h2>
                    <div class="space-y-4">
                        <!-- Created -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                                    <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="w-px h-12 bg-gray-200 dark:bg-gray-700"></div>
                            </div>
                            <div class="pb-4">
                                <p class="font-medium text-black dark:text-white">Order Created</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->created_at->format('M d, Y • g:i A') }}</p>
                            </div>
                        </div>

                        @if($order->loaded_at)
                        <!-- Loaded -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @if($order->arrived_at)
                                    <div class="w-px h-12 bg-gray-200 dark:bg-gray-700"></div>
                                @endif
                            </div>
                            <div class="pb-4">
                                <p class="font-medium text-black dark:text-white">Loaded</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->loaded_at->format('M d, Y • g:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->arrived_at)
                        <!-- Arrived -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900">
                                    <svg class="h-4 w-4 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @if($order->delivered_at)
                                    <div class="w-px h-12 bg-gray-200 dark:bg-gray-700"></div>
                                @endif
                            </div>
                            <div class="pb-4">
                                <p class="font-medium text-black dark:text-white">Arrived</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->arrived_at->format('M d, Y • g:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->delivered_at)
                        <!-- Delivered -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                                    <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="font-medium text-black dark:text-white">Delivered</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->delivered_at->format('M d, Y • g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- QR Code Sidebar -->
            <div class="lg:col-span-1">
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-black dark:text-white mb-4">QR Code</h2>
                    @if($order->qr_code_url)
                        <div class="flex flex-col items-center">
                            <div class="w-full aspect-square bg-white p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700">
                                <img src="{{ $order->qr_code_url }}" alt="Order QR Code" class="w-full h-full object-contain">
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center mt-4">Scan this QR code to view order details</p>
                            <a href="{{ $order->qr_code_url }}" download="{{ $order->order_number }}-qrcode.png" class="mt-4 w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-orange-600 hover:bg-orange-700 focus:outline-none transition">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download QR Code
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="h-12 w-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">QR Code not available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeConfirmModal()"></div>

            <!-- Modal panel -->
            <div class="relative inline-block align-middle bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all max-w-lg w-full">
                <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 dark:bg-orange-900 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Update Order Status
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" id="confirmMessage">
                                    Are you sure you want to update the status?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-neutral-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="button" onclick="submitStatusChange()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Confirm
                    </button>
                    <button type="button" onclick="closeConfirmModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-neutral-700 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for status update -->
    <form id="statusUpdateForm" action="{{ route('orders.update', $order->id) }}" method="POST" class="hidden">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="statusInput">
    </form>

    <script>
        let selectedStatus = '';
        let selectedStatusLabel = '';

        function confirmStatusChange(status, label) {
            selectedStatus = status;
            selectedStatusLabel = label;

            const currentStatus = '{{ ucfirst(str_replace("_", " ", $order->status)) }}';
            const message = `Change order status from "${currentStatus}" to "${label}"?`;

            document.getElementById('confirmMessage').textContent = message;
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function submitStatusChange() {
            document.getElementById('statusInput').value = selectedStatus;
            document.getElementById('statusUpdateForm').submit();
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            selectedStatus = '';
            selectedStatusLabel = '';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeConfirmModal();
            }
        });
    </script>
</x-layouts.app>
