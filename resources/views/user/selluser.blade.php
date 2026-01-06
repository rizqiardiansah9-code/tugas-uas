@extends('user.layout.main')

@section('title', 'Sell Items — Teman')

@section('navbar')
    @include('user.multi_page.multi_navbar')
@endsection

@section('hideFooter')
@endsection

@section('content')
    @if (session('success'))
        <div
            class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div
            class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
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

    <div class="min-h-screen text-white" x-data="sellApp()">
        <script>
            function sellApp() {
                return {
                    userInventory: @json($userInventory),
                    selectedItems: [],
                    userBalance: @json(auth()->user()->balance),

                    init() {
                        // No localStorage needed for selling
                    },

                    activeItem: null,
                    popoverPos: {
                        x: 0,
                        y: 0
                    },
                    searchMarket: '',
                    cartOpen: false,

                    // Filters
                    categories: @json($categories),
                    rarities: @json($rarities),
                    selectedCategory: null,
                    selectedRarity: null,
                    priceMin: '',
                    priceMax: '',

                    filterCategory(id) {
                        this.selectedCategory = this.selectedCategory === id ? null : id;
                    },

                    filterRarity(r) {
                        this.selectedRarity = this.selectedRarity === r ? null : r;
                    },

                    showDetail(item, event) {
                        const btn = event.currentTarget;
                        const rect = btn.getBoundingClientRect();

                        this.activeItem = item;
                        // Position popover (simplified)
                        this.popoverPos = {
                            x: rect.right + 10,
                            y: rect.top - 100
                        };

                        if (window.innerWidth - this.popoverPos.x < 300) {
                            this.popoverPos.x = rect.left - 310;
                        }
                    },

                    closeDetail() {
                        this.activeItem = null;
                    },

                    formatPrice(price) {
                        return '$' + parseFloat(price).toFixed(2);
                    },

                    toggleSelected(item) {
                        if (this.isSelected(item)) {
                            this.selectedItems = this.selectedItems.filter(i => i.id !== item.id);
                        } else {
                            this.selectedItems.push(item);
                        }
                    },

                    isSelected(item) {
                        return this.selectedItems.some(i => i.id === item.id);
                    },

                    clearSelected() {
                        this.selectedItems = [];
                    },

                    get selectedTotal() {
                        return this.selectedItems.reduce((acc, i) => acc + parseFloat(i.price), 0);
                    },

                    submitSell() {
                        if (this.selectedItems.length === 0) return;
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('user.sell') }}';
                        // Add CSRF token
                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';
                        form.appendChild(csrf);
                        // Add selected items as JSON
                        const items = document.createElement('input');
                        items.type = 'hidden';
                        items.name = 'selected_items';
                        items.value = JSON.stringify(this.selectedItems);
                        form.appendChild(items);
                        document.body.appendChild(form);
                        form.submit();
                    },

                    get filteredUserInventory() {
                        let items = this.userInventory;

                        // Search
                        if (this.searchMarket !== '') {
                            items = items.filter(item => item.name.toLowerCase().includes(this.searchMarket.toLowerCase()));
                        }

                        // Filter Category
                        if (this.selectedCategory) {
                            items = items.filter(item => item.category_id === this.selectedCategory);
                        }

                        // Filter Rarity (Check metadata.rarity)
                        if (this.selectedRarity) {
                            items = items.filter(item => item.metadata && item.metadata.rarity === this.selectedRarity);
                        }

                        // Filter Price
                        if (this.priceMin !== '') {
                            items = items.filter(item => parseFloat(item.price) >= parseFloat(this.priceMin));
                        }
                        if (this.priceMax !== '') {
                            items = items.filter(item => parseFloat(item.price) <= parseFloat(this.priceMax));
                        }

                        return items;
                    },

                    getRarityColor(rarity) {
                        const colors = {
                            'common': '#6b7280',
                            'uncommon': '#16a34a',
                            'rare': '#3b82f6',
                            'mythical': '#7c3aed',
                            'legendary': '#f59e0b',
                            'ancient': '#3730a3',
                            'exceedingly_rare': '#db2777',
                            'immortal': '#dc2626'
                        };
                        return colors[rarity] || colors['common'];
                    },

                    getRarityStyle(item) {
                        const rarity = item.metadata && item.metadata.rarity ? item.metadata.rarity : 'common';
                        const color = this.getRarityColor(rarity);
                        return `box-shadow: 0 8px 24px ${color}33; border-color: ${color}22;`;
                    },

                    getRarityBadgeStyle(item) {
                        const rarity = item.metadata && item.metadata.rarity ? item.metadata.rarity : 'common';
                        const color = this.getRarityColor(rarity);
                        const textColor = ['legendary'].includes(rarity) ? '#000' : '#fff';
                        return `background: ${color}; color: ${textColor}; box-shadow: 0 6px 16px ${color}33;`;
                    },

                    getRarityText(item) {
                        const rarity = item.metadata && item.metadata.rarity ? item.metadata.rarity : 'common';
                        return rarity.charAt(0).toUpperCase() + rarity.slice(1).replace('_', ' ');
                    },

                    isLoading: false,


                }
            }
        </script>

        <!-- POPOVER DETAIL -->
        <div x-show="activeItem" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed z-[150] w-[300px] bg-[#1a1d2b] border border-[#2d3246]  shadow-2xl overflow-hidden p-4"
            :style="`top: ${popoverPos.y}px; left: ${popoverPos.x}px`" @click.outside="closeDetail()"
            @scroll.window="closeDetail()" style="display:none;">

            <div class="flex justify-between items-start mb-2">
                <div>
                    <div class="text-[10px] text-gray-400 uppercase tracking-wider">Item Detail</div>
                    <h4 class="text-white font-bold text-lg leading-tight" x-text="activeItem?.name"></h4>
                </div>
                <button @click="closeDetail()" class="text-gray-500 hover:text-white"><i class="fas fa-times"></i></button>
            </div>

            <div class="relative w-full h-40 bg-[#151823]  mb-3 flex items-center justify-center border border-white/5">
                <img :src="activeItem?.image ? '/storage/' + activeItem.image : 'https://via.placeholder.com/200'"
                    class="max-h-32 object-contain drop-shadow-lg">
            </div>

            <div class="flex justify-between items-center bg-[#4c6fff] text-white p-3 ">
                <span class="font-semibold text-sm">Price</span>
                <span class="font-bold text-lg" x-text="activeItem ? formatPrice(activeItem.price) : ''"></span>
            </div>
        </div>

        <style>
            :root {
                --bg: #0e1015;
                --card: #151823;
                --card-inner: #1c202e;
                --primary: #4c6fff;
                --gray: #aab0d5;
            }

            /* GRID */
            .catalog-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                /* Adjusted min-width for smaller sidebar */
                gap: 10px;
            }

            /* CARD SIZE */
            .catalog-col {
                height: 200px;
            }

            /* CARD */
            .item-cell {
                width: 100%;
                height: 100%;
                background: var(--card);
                border-radius: 0;
                overflow: hidden;
            }

            .item-details {
                position: relative;
                height: 100%;
                padding: 10px;
                background: var(--card-inner);
                overflow: hidden;
                cursor: pointer;
            }

            /* COUNT */
            .count {
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 13px;
                color: #7fa2ff;
                z-index: 3;
            }

            /* IMAGE */
            .item-image {
                width: 100%;
                height: 100%;
                object-fit: contain;
                padding: 20px;
                transition: .35s cubic-bezier(.2, .8, .2, 1);
            }

            /* TEXT */
            .indicators {
                position: absolute;
                left: 10px;
                right: 10px;
                bottom: 10px;
                transition: .35s cubic-bezier(.2, .8, .2, 1);
            }

            .item-name {
                font-size: 12px;
                font-weight: 600;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                color: #7d859e;
            }

            .price {
                font-size: 13px;
                font-weight: 700;
                margin-top: 2px;
                color: #22c55e;
            }

            /* BUTTONS */
            .buttons {
                position: absolute;
                left: 10px;
                right: 10px;
                bottom: 10px;
                opacity: 0;
                transform: translateY(100%);
                transition: .35s cubic-bezier(.2, .8, .2, 1);
            }

            .btn-wrap {
                display: flex;
                height: 28px;
                border-radius: 8px;
                overflow: hidden;
            }

            .btn-unstack {
                flex: 1;
                background: var(--primary);
                border: 0;
                color: #fff;
                font-size: 11px;
                font-weight: 600;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 4px;
                transition: all 0.2s ease;
            }

            .btn-unstack:hover {
                filter: brightness(1.1);
                box-shadow: 0 4px 12px rgba(76, 111, 255, 0.4);
            }

            .btn-unstack:active {
                box-shadow: none;
            }

            .btn-more {
                width: 28px;
                background: #5b6dff;
                border: 0;
                color: #fff;
                font-size: 14px;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .btn-more:hover {
                filter: brightness(1.1);
                box-shadow: 0 4px 12px rgba(91, 109, 255, 0.4);
            }

            .btn-more:active {
                box-shadow: none;
            }

            /* HOVER */
            .item-details:hover .item-image {
                transform: translateY(-8px) scale(.92);
            }

            .item-details:hover .indicators {
                transform: translateY(-36px);
            }

            .item-details:hover .buttons {
                opacity: 1;
                transform: translateY(0);
            }

            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>


        <div class="relative overflow-hidden -mx-6"
            style="width:100vw; left:50%; right:50%; margin-left:-50vw; margin-right:-50vw;">

            <!-- MAIN LAYOUT -->
            <div class="grid grid-cols-[200px_1fr] gap-4 px-4 items-stretch">

                <!-- LEFT SIDEBAR (FILTERS) -->
                <aside class="bg-[#12141c]  flex flex-col relative h-full">
                    <div class="sticky top-0 flex flex-col h-[calc(100vh-20px)]">

                        <div class="h-[56px] flex-shrink-0 flex items-center px-4 border-b border-white/5 font-semibold">
                            Filters
                        </div>

                        <div class="flex-1 min-h-0 p-4 text-sm space-y-4 overflow-y-auto no-scrollbar overscroll-contain">

                            <!-- Price -->
                            <div>
                                <div class="font-semibold mb-2 text-gray-400">Price ($)</div>
                                <div class="flex gap-2 items-center">
                                    <input
                                        class="w-1/2 bg-[#0f1117] p-2 rounded outline-none border border-white/5 focus:border-[#4c6fff] transition"
                                        placeholder="Min" x-model="priceMin" inputmode="decimal" pattern="[0-9]*">
                                    <span class="text-gray-500">-</span>
                                    <input
                                        class="w-1/2 bg-[#0f1117] p-2 rounded outline-none border border-white/5 focus:border-[#4c6fff] transition"
                                        placeholder="Max" x-model="priceMax" inputmode="decimal" pattern="[0-9]*">
                                </div>
                            </div>

                            <!-- Category Filter -->
                            <div x-data="{ open: true }">
                                <div class="flex justify-between items-center py-2 cursor-pointer group"
                                    @click="open = !open">
                                    <span
                                        class="font-semibold text-gray-300 group-hover:text-white transition">Category</span>
                                    <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform duration-200"
                                        :class="{ 'rotate-180': !open }"></i>
                                </div>
                                <div x-show="open" x-transition
                                    class="space-y-1 pl-1 max-h-[180px] overflow-y-auto no-scrollbar">
                                    <template x-for="cat in categories" :key="cat.id">
                                        <div class="flex items-center gap-2 py-1 cursor-pointer hover:text-white text-gray-400 group/item"
                                            @click="filterCategory(cat.id)" :title="cat.name">
                                            <div class="w-4 h-4 shrink-0 rounded-full border border-gray-600 flex items-center justify-center transition"
                                                :class="{ 'border-[#4c6fff] bg-[#4c6fff]': selectedCategory === cat.id }">
                                                <div class="w-1.5 h-1.5 bg-white rounded-full"
                                                    x-show="selectedCategory === cat.id"></div>
                                            </div>
                                            <span class="truncate flex-1" x-text="cat.name"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Rarity Filter -->
                            <div x-data="{ open: true }">
                                <div class="flex justify-between items-center py-2 cursor-pointer group"
                                    @click="open = !open">
                                    <span
                                        class="font-semibold text-gray-300 group-hover:text-white transition">Rarity</span>
                                    <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform duration-200"
                                        :class="{ 'rotate-180': !open }"></i>
                                </div>
                                <div x-show="open" x-transition
                                    class="space-y-1 pl-1 max-h-[180px] overflow-y-auto no-scrollbar">
                                    <template x-for="r in rarities" :key="r">
                                        <div class="flex items-center gap-2 py-1 cursor-pointer hover:text-white text-gray-400 group/item"
                                            @click="filterRarity(r)"
                                            :title="r.charAt(0).toUpperCase() + r.slice(1).replace('_', ' ')">
                                            <div class="w-4 h-4 shrink-0 rounded-full border border-gray-600 flex items-center justify-center transition"
                                                :class="{ 'border-[#4c6fff] bg-[#4c6fff]': selectedRarity === r }">
                                                <div class="w-1.5 h-1.5 bg-white rounded-full"
                                                    x-show="selectedRarity === r"></div>
                                            </div>
                                            <span class="truncate flex-1"
                                                x-text="r.charAt(0).toUpperCase() + r.slice(1).replace('_', ' ')"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 border-t border-white/5 mt-auto bg-[#12141c] flex-shrink-0">
                            <button class="w-full bg-[#1a1d2b] hover:bg-[#252a3d] py-2 rounded text-gray-300 transition"
                                @click="selectedCategory = null; selectedRarity = null; priceMin = ''; priceMax = ''">
                                Reset Filters
                            </button>
                        </div>
                    </div> <!-- End Sticky -->
                </aside>

                <!-- RIGHT CONTENT (GRID) -->
                <div class="flex flex-col">

                    <!-- STORE HEADER & SEARCH -->
                    <div
                        class="bg-[#12141c]  border-b border-white/5 h-[64px] flex items-center justify-between px-4 mb-4 relative z-[100]">
                        <!-- LEFT: Search Bar -->
                        <div class="flex items-center gap-3 w-full max-w-md">
                            <div class="relative w-full">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-500"></i>
                                </span>
                                <input type="text" x-model="searchMarket"
                                    class="w-full bg-[#0f1117] border border-white/5 rounded pl-10 pr-4 py-2 text-sm text-gray-300 placeholder-gray-600 focus:outline-none focus:border-[#4c6fff] transition-colors"
                                    placeholder="Search inventory">
                            </div>
                        </div>

                        <!-- RIGHT: Actions -->
                        <div class="flex items-center gap-4">

                            <!-- Refresh Button -->
                            <div class="relative hidden sm:block">
                                <button
                                    class="w-8 h-8 rounded bg-[#1c202e] hover:bg-[#252a3d] flex items-center justify-center text-gray-400 transition-colors"
                                    title="Refresh" @click="window.location.reload()">
                                    <i class="fas fa-redo-alt text-xs"></i>
                                </button>
                            </div>

                            <div class="h-6 w-px bg-white/10 hidden sm:block"></div>

                            <!-- CHECKOUT BUTTON & CART POPOVER CONTAINER -->
                            <div class="relative" @click.away="cartOpen = false">

                                <!-- Sell Button -->
                                <button
                                    class="h-[40px] px-4 rounded flex items-center gap-3 transition-all duration-200 border border-transparent"
                                    :class="selectedItems.length > 0 ?
                                        'bg-[#22c55e] hover:bg-[#16a34a] text-white shadow-lg shadow-[#22c55e]/20' :
                                        'bg-[#1a1d2b] text-gray-500 cursor-not-allowed'"
                                    :disabled="selectedItems.length === 0" @click="cartOpen = !cartOpen">

                                    <div class="flex items-center gap-2 text-sm font-bold">
                                        <span x-text="'(' + selectedItems.length + ' Items)'"></span>
                                        <span x-text="formatPrice(selectedTotal)"></span>
                                    </div>
                                    <i class="fas fa-dollar-sign"></i>
                                </button>

                                <!-- CART POPOVER / MODAL -->
                                <div x-show="cartOpen" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-2"
                                    class="absolute right-0 top-full mt-2 w-[380px] bg-[#151823] border border-white/10  shadow-2xl z-50 overflow-hidden"
                                    style="display: none;">

                                    <!-- Header -->
                                    <div
                                        class="flex items-center justify-between px-4 py-3 border-b border-white/5 bg-[#1a1d2b]">
                                        <h3 class="font-bold text-white">Selected Items (<span
                                                x-text="selectedItems.length"></span>)</h3>
                                        <div class="flex items-center gap-2">
                                            <button @click="clearSelected(); cartOpen = false"
                                                class="text-xs text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 px-2 py-1 rounded transition">
                                                Clear Selected
                                            </button>
                                            <button @click="cartOpen = false"
                                                class="w-6 h-6 rounded bg-white/5 hover:bg-white/10 flex items-center justify-center text-gray-400 hover:text-white transition">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Item List -->
                                    <div class="max-h-[300px] overflow-y-auto no-scrollbar p-2 space-y-1">
                                        <template x-for="(item, index) in selectedItems" :key="item.id">
                                            <div
                                                class="flex items-center gap-3 p-2 rounded hover:bg-white/5 transition group">
                                                <!-- Image -->
                                                <div
                                                    class="w-12 h-12 bg-[#0e1015] rounded border border-white/5 p-1 flex-shrink-0">
                                                    <img :src="item.image ? '/storage/' + item.image :
                                                        'https://via.placeholder.com/50'"
                                                        class="w-full h-full object-contain">
                                                </div>

                                                <!-- Info -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-white truncate"
                                                        x-text="item.name"></div>
                                                    <div class="text-xs text-gray-500"
                                                        x-text="item.rarity ? item.rarity : 'Item'"></div>
                                                </div>

                                                <!-- Price & Remove -->
                                                <div class="text-right">
                                                    <div class="font-bold text-green-500 text-sm font-rajdhani"
                                                        x-text="formatPrice(item.price)"></div>
                                                    <button @click.stop="toggleSelected(item)"
                                                        class="text-xs text-red-500/50 group-hover:text-red-500 transition mt-1">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Footer Summary -->
                                    <div class="p-4 bg-[#12141c] border-t border-white/5 space-y-2">


                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-400">Selling Price</span>
                                            <span class="text-gray-200 font-bold font-rajdhani"
                                                x-text="formatPrice(selectedTotal)"></span>
                                        </div>

                                        <div class="h-px bg-white/5 my-2"></div>

                                        <div class="flex justify-between items-center mb-4">
                                            <span class="font-bold text-white">Total earnings</span>
                                            <span class="font-bold text-xl text-green-500 font-rajdhani"
                                                x-text="formatPrice(selectedTotal)"></span>
                                        </div>

                                        <button @click="submitSell()"
                                            class="relative group overflow-hidden w-full py-2 font-bold text-sm text-white transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed block text-center"
                                            style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); box-shadow: 0 8px 20px -6px rgba(34, 197, 94, 0.5);">

                                            <!-- Shine effect -->
                                            <div
                                                class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]">
                                            </div>

                                            <span class="flex items-center justify-center gap-2 relative z-10">
                                                Sell Selected Items <i class="fas fa-dollar-sign"></i>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <style>
                        @keyframes shimmer {
                            100% {
                                transform: translateX(100%);
                            }
                        }
                    </style>

                    <main class="min-h-[600px] relative z-0">

                        <!-- GRID -->
                        <div class="catalog-grid">
                            <template x-for="item in filteredUserInventory" :key="item.id">
                                <div class="catalog-col">
                                    <div class="item-cell">
                                        <div class="item-details relative" @click="toggleSelected(item)"
                                            :style="getRarityStyle(item)">
                                            <!-- Rarity badge overlay -->
                                            <div class="absolute left-2 -top-1 z-30">
                                                <span class="text-xs font-semibold px-2 py-0.5 rounded"
                                                    :style="getRarityBadgeStyle(item)">
                                                    <span x-text="getRarityText(item)"></span>
                                                </span>
                                            </div>

                                            <!-- Selected overlay with check icon -->
                                            <div x-show="isSelected(item)"
                                                class="absolute inset-0 bg-black/50 z-10 flex items-center justify-center">
                                                <div class="w-12 h-12 bg-black/80  flex items-center justify-center">
                                                    <i class="fas fa-check text-white text-lg"></i>
                                                </div>
                                            </div>

                                            <img :src="item.image ? '/storage/' + item.image :
                                                'https://via.placeholder.com/300x200?text=No+Image'"
                                                class="item-image">
                                            <div class="indicators">
                                                <div class="item-name" x-text="item.name"></div>
                                                <div class="price" x-text="formatPrice(item.price)"></div>
                                            </div>
                                            <div class="buttons z-20">
                                                <div class="btn-wrap">
                                                    <button class="btn-unstack"
                                                        :style="isSelected(item) ? 'background: #ef4444' :
                                                            'background: var(--primary)'"
                                                        @click.stop="toggleSelected(item)">
                                                        <i class="fas"
                                                            :class="isSelected(item) ? 'fa-times' : 'fa-check'"></i>
                                                        <span x-text="isSelected(item) ? 'Deselect' : 'Select'"></span>
                                                    </button>
                                                    <button class="btn-more"
                                                        :style="isSelected(item) ? 'background: #dc2626' : ''"
                                                        @click.stop="showDetail(item, $event)">⋮</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div class="col-span-full py-20 flex flex-col items-center justify-center text-gray-500"
                                x-show="filteredUserInventory.length === 0">
                                <i class="fas fa-box-open text-4xl mb-4 opacity-50"></i>
                                <p class="font-medium">No items found matching your filters.</p>
                            </div>
                        </div>
                    </main>
                </div> <!-- close flex-col wrapper -->

            </div> <!-- close grid -->
        </div> <!-- close full-width wrapper -->
    </div> <!-- close Alpine wrapper -->


    {{-- TradeIt Style Bottom SEO Content --}}

    {{-- TradeIt Style Bottom SEO Content --}}
    <div class="relative overflow-hidden -mx-6"
        style="width:100vw; left:50%; right:50%; margin-left:-50vw; margin-right:-50vw;">
        <section class="w-full py-12 bg-[#12141c] mt-0 border-t border-white/5">
            <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12 text-gray-400">
                <div>
                    <h3 class="text-xl font-rajdhani font-bold text-white mb-4 uppercase tracking-wider">Why Sell on TEMAN
                        Store?</h3>
                    <p class="leading-relaxed mb-4 text-sm">
                        TEMAN Store provides the safest and fastest way to sell your premium items.
                        Our marketplace is verified and trusted by thousands of gamers worldwide.
                    </p>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2"><i class="fas fa-check text-[#f97316]"></i> Instant Payment
                        </li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-[#f97316]"></i> Best Selling
                            Prices
                        </li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-[#f97316]"></i> Secure
                            Transactions</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-rajdhani font-bold text-white mb-4 uppercase tracking-wider">How it works</h3>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div
                                class="w-8 h-8 rounded-full bg-[#1c202e] flex items-center justify-center font-bold text-[#f97316] shrink-0">
                                1</div>
                            <div>
                                <h4 class="text-white font-bold text-sm">Browse Inventory</h4>
                                <p class="text-xs mt-1">Use our advanced filters to find items you want to sell.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="w-8 h-8 rounded-full bg-[#1c202e] flex items-center justify-center font-bold text-[#f97316] shrink-0">
                                2</div>
                            <div>
                                <h4 class="text-white font-bold text-sm">Select Items</h4>
                                <p class="text-xs mt-1">Choose items from your inventory. You can select multiple items.
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="w-8 h-8 rounded-full bg-[#1c202e] flex items-center justify-center font-bold text-[#f97316] shrink-0">
                                3</div>
                            <div>
                                <h4 class="text-white font-bold text-sm">Sell Items</h4>
                                <p class="text-xs mt-1">Confirm the sale and receive payment instantly to your balance.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="relative overflow-hidden -mx-6"
        style="width:100vw; left:50%; right:50%; margin-left:-50vw; margin-right:-50vw;">
        @include('user.multi_page.multi_footer')
    </div>

@endsection
