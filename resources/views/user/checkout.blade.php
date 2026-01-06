@extends('user.layout.main')

@section('title', 'Checkout — Teman')

@section('navbar')
    @include('user.multi_page.multi_navbar')
@endsection

@section('hideFooter')
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .no-scrollbar::-webkit-scrollbar {
            width: 0;
            height: 0;
        }

        .no-scrollbar {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
    </style>

    <div class="min-h-screen text-white" x-data="checkoutApp()">
        <script>
            function checkoutApp() {
                return {
                    cart: JSON.parse(localStorage.getItem('store_cart') || '[]'),
                    isLoading: false,

                    init() {
                        if (this.cart.length === 0) {
                            window.location.href = '{{ route('user.store') }}';
                        }
                    },

                    formatPrice(price) {
                        return '$' + parseFloat(price).toFixed(2);
                    },

                    get cartTotal() {
                        return this.cart.reduce((acc, i) => acc + parseFloat(i.price), 0);
                    },

                    async confirmPurchase() {
                        if (this.cart.length === 0) return;
                        this.isLoading = true;

                        try {
                            const response = await fetch('{{ route('user.store.buy') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify({
                                    items: this.cart.map(item => item.id)
                                })
                            });

                            const result = await response.json();

                            if (response.ok) {
                                // Clear cart
                                localStorage.removeItem('store_cart');
                                // Redirect to store or profile
                                window.location.href = '{{ route('user.store') }}';
                            } else {
                                alert('Purchase failed: ' + (result.message || 'Unknown error'));
                            }
                        } catch (error) {
                            alert('Purchase failed: Network error');
                        } finally {
                            this.isLoading = false;
                        }
                    },

                    removeItem(itemId) {
                        this.cart = this.cart.filter(item => item.id !== itemId);
                        localStorage.setItem('store_cart', JSON.stringify(this.cart));
                        if (this.cart.length === 0) {
                            window.location.href = '{{ route('user.store') }}';
                        }
                    }
                }
            }
        </script>

        <div class="relative overflow-hidden -mx-6"
            style="width:100vw; left:50%; right:50%; margin-left:-50vw; margin-right:-50vw;">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 px-4 py-8 items-start">

                <!-- LEFT: Cart Items -->
                <div class="bg-[#12141c] border border-white/5 rounded-lg p-6">
                    <h2 class="text-2xl font-bold text-white mb-6">Checkout Items</h2>

                    <div class="space-y-4 max-h-[600px] overflow-y-auto no-scrollbar">
                        <template x-for="(item, index) in cart" :key="item.id">
                            <div class="flex items-center gap-4 p-4 bg-[#1a1d2b] rounded-lg border border-white/5">
                                <!-- Image -->
                                <div class="w-16 h-16 bg-[#0e1015] rounded border border-white/5 p-1 flex-shrink-0">
                                    <img :src="item.image ? '/storage/' + item.image : 'https://via.placeholder.com/64'"
                                        class="w-full h-full object-contain">
                                </div>

                                <!-- Details -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-white font-semibold truncate" x-text="item.name"></h3>
                                    <p class="text-gray-400 text-sm" x-text="item.category ? item.category.name : 'Item'">
                                    </p>
                                    <p class="text-gray-500 text-xs"
                                        x-text="item.metadata && item.metadata.rarity ? item.metadata.rarity.replace('_', ' ').toUpperCase() : 'COMMON'">
                                    </p>
                                </div>

                                <!-- Price & Remove -->
                                <div class="text-right">
                                    <div class="text-green-500 font-bold text-lg" x-text="formatPrice(item.price)"></div>
                                    <button @click="removeItem(item.id)"
                                        class="text-red-500 hover:text-red-400 text-sm mt-1">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- RIGHT: Summary & Payment -->
                <div class="bg-[#12141c] border border-white/5 rounded-lg p-6">
                    <h2 class="text-2xl font-bold text-white mb-6">Order Summary</h2>

                    <div class="space-y-4">
                        <div class="flex justify-between text-gray-300">
                            <span>Items (<span x-text="cart.length"></span>)</span>
                            <span x-text="formatPrice(cartTotal)"></span>
                        </div>

                        <div class="h-px bg-white/10"></div>

                        <div class="flex justify-between text-xl font-bold text-white">
                            <span>Total</span>
                            <span class="text-green-500" x-text="formatPrice(cartTotal)"></span>
                        </div>

                        <div class="mt-8">
                            <button @click="confirmPurchase()"
                                class="w-full py-3 px-6 bg-[#f97316] hover:bg-[#ea580c] text-white font-bold rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="isLoading">
                                <span x-show="!isLoading">Confirm Purchase</span>
                                <span x-show="isLoading" class="flex items-center justify-center gap-2">
                                    <i class="fas fa-spinner fa-spin"></i> Processing...
                                </span>
                            </button>
                        </div>

                        <div class="text-center text-gray-400 text-sm mt-4">
                            By confirming, you agree to our terms and conditions.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative overflow-hidden -mx-6"
        style="width:100vw; left:50%; right:50%; margin-left:-50vw; margin-right:-50vw;">
        @include('user.multi_page.multi_footer')
    </div>

@endsection
