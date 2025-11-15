@props(['product'])

<div x-data="{
    open: false,
    selectedSize: 'small',
    quantity: 1,
    comments: '',
    sizes: [
        { id: 'small', name: 'Small', price: 44.00 },
        { id: 'medium', name: 'Medium', price: 33.00 }
    ],
    get selectedPrice() {
        return this.sizes.find(s => s.id === this.selectedSize)?.price || 44.00;
    },
    get totalPrice() {
        return (this.selectedPrice * this.quantity).toFixed(2);
    },
    increment() {
        this.quantity++;
    },
    decrement() {
        if (this.quantity > 1) this.quantity--;
    }
}">


    <!-- Modal Overlay -->
    <div
            x-show="modalOpen"
            x-transition.opacity
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
            @click.self="open = false"
    >
        <!-- Modal Container -->
        <div
                x-show="modalOpen"
                x-transition
                @click.away="modalOpen = false"
                class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col md:flex-row"
        >

            <!-- Left Side - Image -->
            <div class="md:w-1/2 bg-gradient-to-br from-yellow-200 via-orange-200 to-pink-200 p-8 flex items-center justify-center">
                <div class="relative w-full aspect-square max-w-md">
                    <img
                            src="{{ $product['image'] ?? 'https://images.unsplash.com/photo-1562376552-0d160a2f238d?w=800&q=80' }}"
                            alt="{{ $product['name'] ?? 'Product' }}"
                            class="w-full h-full object-cover rounded-xl shadow-lg"
                    />
                </div>
            </div>

            <!-- Right Side - Content -->
            <div class="md:w-1/2 flex flex-col">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $product['name'] ?? 'Waffle' }}</h2>
                        <p class="text-xl font-semibold text-gray-700 mt-1">
                            {{ $setting['currency']->value ?? "$" }} <span x-text="selectedPrice.toFixed(2)"></span>
                        </p>
                    </div>
                    <button
                            @click="modalOpen = false"
                            class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center transition"
                    >
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- Size Selection -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-500 text-sm">Select minimum 1 option</span>
                            <span class="bg-gray-100 text-gray-700 text-xs font-medium px-3 py-1 rounded-full">
                                Required
                            </span>
                        </div>

                        <div class="space-y-3">
                            <template x-for="size in sizes" :key="size.id">
                                <div
                                        @click="selectedSize = size.id"
                                        class="flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition hover:border-blue-400"
                                        :class="selectedSize === size.id ? 'border-blue-600' : 'border-gray-200'"
                                >
                                    <div>
                                        <p class="font-semibold text-gray-900" x-text="size.name"></p>
                                        <p class="text-gray-700 font-medium">{{ $setting['currency']->value ?? "$" }} <span x-text="size.price.toFixed(2)"></span></p>
                                    </div>
                                    <div
                                            class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition"
                                            :class="selectedSize === size.id ? 'border-blue-600' : 'border-gray-300'"
                                    >
                                        <div
                                                x-show="selectedSize === size.id"
                                                class="w-3 h-3 rounded-full bg-blue-600"
                                        ></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div class="mb-6">
                        <h3 class="font-bold text-gray-900 mb-3">Comments</h3>
                        <textarea
                                x-model="comments"
                                placeholder="(Optional)"
                                rows="3"
                                class="w-full border border-gray-300 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                        ></textarea>
                    </div>
                </div>

                <!-- Footer - Quantity and Add Button -->
                <div class="p-6 border-t bg-gray-50">
                    <form id="variantModalForm" action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product['id'] ?? '' }}">
                        <input type="hidden" name="size" x-model="selectedSize">
                        <input type="hidden" name="quantity" x-model="quantity">
                        <input type="hidden" name="comments" x-model="comments">
                        <input type="hidden" name="price" x-model="selectedPrice.toFixed(2)">

                        <div class="flex items-center gap-4">
                            <!-- Quantity Controls -->
                            <div class="flex items-center bg-white border border-gray-300 rounded-lg overflow-hidden">
                                <button
                                        type="button"
                                        @click="decrement()"
                                        class="p-3 hover:bg-gray-100 transition"
                                        :disabled="quantity <= 1"
                                >
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </button>
                                <span class="px-6 font-semibold text-lg" x-text="quantity"></span>
                                <button
                                        type="button"
                                        @click="increment()"
                                        class="p-3 hover:bg-gray-100 transition"
                                >
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Add to Cart Button -->
                            <button
                                    type="button"

                                    class="cart-add flex-1 bg-blue-600 text-white font-semibold py-4 rounded-xl hover:bg-blue-700 transition shadow-lg"
                            >
                                Add  {{ $setting['currency']->value ?? "$" }} <span x-text="totalPrice"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
