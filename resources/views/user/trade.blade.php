@extends('user.layout.main')

@section('title','Trade — Teman')

@section('content')
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
@section('navbar')
  @include('user.multi_page.multi_navbar')
@endsection

@section('hideFooter')
@endsection

<div class="min-h-screen text-white" x-data="tradeApp()">
    <script>
        function tradeApp() {
            return {
                myInventory: @json($userInventory),
                marketInventory: @json($marketInventory),
                myOffer: JSON.parse(localStorage.getItem('trade_my_offer') || '[]'),
                theirOffer: JSON.parse(localStorage.getItem('trade_their_offer') || '[]'),

                init() {
                    this.$watch('myOffer', (val) => localStorage.setItem('trade_my_offer', JSON.stringify(val)));
                    this.$watch('theirOffer', (val) => localStorage.setItem('trade_their_offer', JSON.stringify(val)));
                },

                activeItem: null,
                popoverPos: { x: 0, y: 0 },
                offerOpen: true,
                receiveOpen: true,
                offerExpanded: false,
                receiveExpanded: false,
                inventoryOpen: true,
                marketOpen: true,
                searchMy: '',
                searchMarket: '',
                
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

                addToOffer(item) {
                    // Toggle: if already in myOffer, remove it; otherwise add it
                    if (this.myOffer.some(i => i.id === item.id)) {
                        this.myOffer = this.myOffer.filter(i => i.id !== item.id);
                    } else {
                        this.myOffer.push(item);
                    }
                },
                removeFromOffer(item) {
                     // Just remove from offer, item stays in inventory
                     this.myOffer = this.myOffer.filter(i => i.id !== item.id);
                },
                
                isInOffer(item) {
                    return this.myOffer.some(i => i.id === item.id);
                },
                
                addToReceive(item) {
                    // Toggle: if already in theirOffer, remove it; otherwise add it
                    if (this.theirOffer.some(i => i.id === item.id)) {
                        this.theirOffer = this.theirOffer.filter(i => i.id !== item.id);
                    } else {
                        this.theirOffer.push(item);
                    }
                },
                removeFromReceive(item) {
                    this.theirOffer = this.theirOffer.filter(i => i.id !== item.id);
                },
                
                isInReceive(item) {
                    return this.theirOffer.some(i => i.id === item.id);
                },

                clearOffer() {
                    this.myOffer = [];
                },
                clearReceive() {
                    this.theirOffer = [];
                },
                
                get myTotal() { return this.myOffer.reduce((acc, i) => acc + parseFloat(i.price), 0); },
                get theirTotal() { return this.theirOffer.reduce((acc, i) => acc + parseFloat(i.price), 0); },
                
                get canTrade() {
                    return this.myOffer.length > 0 && this.theirOffer.length > 0 && this.myTotal >= this.theirTotal;
                },
                
                get filteredMyInventory() {
                    let items = this.myInventory;
                    if (this.searchMy !== '') {
                        items = items.filter(item => item.name.toLowerCase().includes(this.searchMy.toLowerCase()));
                    }
                    return items;
                },
                
                get filteredMarketInventory() {
                    let items = this.marketInventory;
                    
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
                
                isLoading: false,
                
                async refreshPage() {
                    this.isLoading = true;
                    try {
                        const res = await fetch('{{ route('user.trade.refresh') }}');
                        const data = await res.json();
                        
                        // Update inventories. Keep items in list even if selected.
                        this.myInventory = data.userInventory;
                        this.marketInventory = data.marketInventory;
                        
                        // Clean up offers if items no longer exist/owned
                        this.myOffer = this.myOffer.filter(o => this.myInventory.some(i => i.id === o.id));
                        this.theirOffer = this.theirOffer.filter(o => this.marketInventory.some(i => i.id === o.id));
                        
                    } catch (error) {
                        console.error('Refresh failed:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                async submitTrade() {
                    if (!this.canTrade) return;
                    this.isLoading = true;

                    try {
                        const res = await fetch('{{ route('user.trade.process') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                my_items: this.myOffer.map(i => i.id),
                                their_items: this.theirOffer.map(i => i.id)
                            })
                        });

                        const data = await res.json();

                        if (res.ok) {
                            alert('Trade Successful!');
                            // Clear offers
                            this.myOffer = [];
                            this.theirOffer = [];
                            // Refresh inventories
                            this.refreshPage();
                        } else {
                            alert('Trade Failed: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Trade error:', error);
                        alert('An error occurred during trade.');
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>

    <!-- POPOVER DETAIL -->
    <div x-show="activeItem" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed z-[150] w-[300px] bg-[#1a1d2b] border border-[#2d3246]  shadow-2xl overflow-hidden p-4"
         :style="`top: ${popoverPos.y}px; left: ${popoverPos.x}px`"
         @click.outside="closeDetail()"
         @scroll.window="closeDetail()"
         style="display:none;">
         
         <div class="flex justify-between items-start mb-2">
            <div>
                <div class="text-[10px] text-gray-400 uppercase tracking-wider">Item Detail</div>
                <h4 class="text-white font-bold text-lg leading-tight" x-text="activeItem?.name"></h4>
            </div>
            <button @click="closeDetail()" class="text-gray-500 hover:text-white"><i class="fas fa-times"></i></button>
         </div>
         
         <div class="relative w-full h-40 bg-[#151823]  mb-3 flex items-center justify-center border border-white/5">
             <img :src="activeItem?.image ? '/storage/' + activeItem.image : 'https://via.placeholder.com/200'" class="max-h-32 object-contain drop-shadow-lg">
         </div>
         
         <div class="flex justify-between items-center bg-[#4c6fff] text-white p-3 ">
            <span class="font-semibold text-sm">Price</span>
            <span class="font-bold text-lg" x-text="activeItem ? formatPrice(activeItem.price) : ''"></span>
         </div>
    </div>
<style>
:root{
  --bg:#0e1015; --card:#151823; --card-inner:#1c202e;
  --primary:#4c6fff; --gray:#aab0d5;
}

/* GRID */
.catalog-grid {
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); /* Adjusted min-width for smaller sidebar */
  gap:10px;
}

/* CARD SIZE */
.catalog-col{
  height:200px;
}

/* CARD */
.item-cell{
  width:100%;
  height:100%;
  background:var(--card);
  border-radius:0;
  overflow:hidden;
}

.item-details{
  position:relative;
  height:100%;
  padding:10px;
  background:var(--card-inner);
  overflow:hidden;
  cursor:pointer;
}

/* COUNT */
.count{
  position:absolute;
  top:10px;
  right:10px;
  font-size:13px;
  color:#7fa2ff;
  z-index:3;
}

/* IMAGE */
.item-image{
  width:100%;
  height:100%;
  object-fit:contain;
  padding: 20px;
  transition:.35s cubic-bezier(.2,.8,.2,1);
}

/* TEXT */
.indicators{
  position:absolute;
  left:10px;
  right:10px;
  bottom:10px;
  transition:.35s cubic-bezier(.2,.8,.2,1);
}

.item-name{
  font-size:12px;
  font-weight:600;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
  color: #7d859e;
}

.price{
  font-size:13px;
  font-weight:700;
  margin-top:2px;
  color: #22c55e;
}

/* BUTTONS */
.buttons{
  position:absolute;
  left:10px;
  right:10px;
  bottom:10px;
  opacity:0;
  transform:translateY(100%);
  transition:.35s cubic-bezier(.2,.8,.2,1);
}

.btn-wrap{
  display:flex;
  height:28px;
  border-radius:8px;
  overflow:hidden;
}

.btn-unstack{
  flex:1;
  background:var(--primary);
  border:0;
  color:#fff;
  font-size:11px;
  font-weight:600;
  cursor:pointer;
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

.btn-more{
  width:28px;
  background:#5b6dff;
  border:0;
  color:#fff;
  font-size:14px;
  cursor:pointer;
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
.item-details:hover .item-image{
  transform:translateY(-8px) scale(.92);
}

.item-details:hover .indicators{
  transform:translateY(-36px);
}

.item-details:hover .buttons{
  opacity:1;
  transform:translateY(0);
}

.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
@keyframes shimmer {
  100% {
    transform: translateX(100%);
  }
}
</style>

<div class="relative overflow-hidden -mx-6" style="width:100vw; left:50%; right:50%; margin-left:-50vw; margin-right:-50vw;">

<!-- TOP BAR -->
<div class="grid grid-cols-[1fr_200px_1fr] gap-4 px-6 py-6 items-start">

    <div class="bg-[#12141c]  overflow-hidden flex flex-col" >
        <!-- Header -->
        <div class="flex items-center justify-between p-4 bg-[#151823] border-b border-white/5 cursor-pointer select-none" @click="offerOpen = !offerOpen">
            <div class="flex items-center gap-3">
                <i class="fas fa-shopping-cart text-white"></i>
                <span class="text-white font-bold text-lg" x-text="formatPrice(myTotal)"></span>
                <span class="bg-[#4c6fff] text-white text-xs font-bold px-2 py-0.5 rounded" x-text="myOffer.length" x-show="myOffer.length > 0"></span>
            </div>
            <div class="flex items-center gap-4">
                <button class="text-xs text-[#aab0d5] hover:text-white px-2 py-1 rounded bg-[#1c202e] hover:bg-[#252a3d] transition-colors" 
                        @click.stop="clearOffer()" x-show="myOffer.length > 0">
                    Clear
                </button>
                <button class="w-6 h-6 rounded bg-[#1c202e] hover:bg-[#252a3d] flex items-center justify-center text-gray-400 hover:text-white transition-colors" 
                        @click.stop="offerExpanded = !offerExpanded" title="Toggle expand">
                    <i class="fas text-xs" :class="offerExpanded ? 'fa-compress' : 'fa-expand'"></i>
                </button>
                <div class="flex items-center gap-2">
                    <span class="text-gray-400 font-semibold text-sm">You offer</span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="{'rotate-180': !offerOpen}"></i>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div x-show="offerOpen" x-transition class="p-4 bg-[#12141c] overflow-y-auto no-scrollbar transition-all duration-300"
             :class="offerExpanded ? 'min-h-[300px] max-h-[500px]' : 'min-h-[150px] max-h-[200px]'">
             <p class="text-gray-400 text-sm text-center py-8" x-show="myOffer.length === 0">What items do you offer</p>
             
             <!-- SELECTED ITEMS GRID -->
             <div class="grid grid-cols-4 gap-2" x-show="myOffer.length > 0">
                  <template x-for="item in myOffer" :key="item.id">
                     <div class="relative bg-[#1a1d2b] p-2 rounded cursor-pointer group" @click="removeFromOffer(item)">
                         <img :src="item.image ? '/storage/' + item.image : 'https://via.placeholder.com/100'" class="w-full h-16 object-contain">
                         <div class="text-[10px] truncate text-gray-400 mt-1" x-text="item.name"></div>
                         <div class="text-[10px] text-green-400 font-bold" x-text="formatPrice(item.price)"></div>
                         <div class="absolute inset-0 bg-red-500/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded">
                             <span class="text-white font-bold text-xs">x</span>
                         </div>
                     </div>
                  </template>
             </div>
        </div>
    </div>

  <div class="flex flex-col gap-3 justify-center">
    <button class="relative group overflow-hidden w-full py-4  font-bold text-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]" 
            style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); box-shadow: 0 10px 30px -10px rgba(249, 115, 22, 0.6);"
            :disabled="!canTrade || isLoading" 
            @click="submitTrade()">
        
        <!-- Shine effect -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>

        <span x-show="!isLoading" class="flex items-center gap-3 relative z-10">
            TRADENOW <i class="fas fa-exchange-alt"></i>
        </span>
        <i x-show="isLoading" class="fas fa-spinner fa-spin relative z-10"></i>
    </button>
    
    <!-- Balance Indicator -->
    <div class="bg-[#151823] p-4  border border-white/5 text-center shadow-lg w-full">
        <div class="text-gray-400 text-[10px] uppercase tracking-wider mb-1 flex items-center justify-center gap-1 font-semibold">
            Needed for trade <i class="fas fa-info-circle text-gray-500"></i>
        </div>
        <div class="text-2xl font-bold font-rajdhani" 
             :class="myTotal < theirTotal ? 'text-red-500' : 'text-gray-200'"
             x-text="formatPrice(Math.max(0, theirTotal - myTotal))">
        </div>
    </div>
  </div>

    <div class="bg-[#12141c]  overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 bg-[#151823] border-b border-white/5 cursor-pointer select-none transition-colors hover:bg-[#1c202e]" 
             @click="receiveOpen = !receiveOpen">
            <div class="flex items-center gap-3">
                <i class="fas fa-shopping-cart text-white"></i>
                <span class="text-white font-bold text-lg" x-text="formatPrice(theirTotal)"></span>
                <span class="bg-[#4c6fff] text-white text-xs font-bold px-2 py-0.5 rounded" x-text="theirOffer.length" x-show="theirOffer.length > 0"></span>
            </div>
            <div class="flex items-center gap-4">
                <button class="text-xs text-[#aab0d5] hover:text-white px-2 py-1 rounded bg-[#1c202e] hover:bg-[#252a3d] transition-colors" 
                        @click.stop="clearReceive()" x-show="theirOffer.length > 0">
                    Clear
                </button>
                <button class="w-6 h-6 rounded bg-[#1c202e] hover:bg-[#252a3d] flex items-center justify-center text-gray-400 hover:text-white transition-colors" 
                        @click.stop="receiveExpanded = !receiveExpanded" title="Toggle expand">
                    <i class="fas text-xs" :class="receiveExpanded ? 'fa-compress' : 'fa-expand'"></i>
                </button>
                <div class="flex items-center gap-2">
                    <span class="text-gray-400 font-semibold text-sm">You receive</span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" 
                       :class="{'rotate-180': !receiveOpen}"></i>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div x-show="receiveOpen" x-transition class="p-4 bg-[#12141c] overflow-y-auto no-scrollbar transition-all duration-300"
             :class="receiveExpanded ? 'min-h-[300px] max-h-[500px]' : 'min-h-[150px] max-h-[200px]'">
             <p class="text-gray-400 text-sm text-center py-8" x-show="theirOffer.length === 0">Choose the items you would like to receive</p>
             
             <!-- SELECTED ITEMS GRID -->
             <div class="grid grid-cols-4 gap-2" x-show="theirOffer.length > 0">
                  <template x-for="item in theirOffer" :key="item.id">
                     <div class="relative bg-[#1a1d2b] p-2 rounded cursor-pointer group" @click="removeFromReceive(item)">
                         <img :src="item.image ? '/storage/' + item.image : 'https://via.placeholder.com/100'" class="w-full h-16 object-contain">
                         <div class="text-[10px] truncate text-gray-400 mt-1" x-text="item.name"></div>
                         <div class="text-[10px] text-green-400 font-bold" x-text="formatPrice(item.price)"></div>
                         <div class="absolute inset-0 bg-red-500/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded">
                             <span class="text-white font-bold text-xs">x</span>
                         </div>
                     </div>
                  </template>
             </div>
        </div>
    </div>

</div>

<!-- MAIN -->
<div class="grid grid-cols-[1fr_200px_1fr] gap-4 px-6 pb-10 items-stretch h-[calc(100vh-280px)] min-h-[500px]">

<!-- LEFT -->
<section class="bg-[#12141c]  flex flex-col h-full overflow-hidden min-h-0">

  <div class="h-[56px] flex items-center justify-between px-4 border-b border-white/5 gap-4">
    <!-- Search Box -->
    <div class="w-56 flex items-center gap-2 bg-[#0b0e14] px-3 py-2 rounded border border-white/5" @click.stop>
        <i class="fas fa-search text-gray-500 text-xs"></i>
        <input class="bg-transparent text-sm w-full outline-none text-gray-300 placeholder-gray-600 pointer-events-auto"
               placeholder="Search inventory" x-model="searchMy">
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-2" @click.stop>
         <button class="w-8 h-8 rounded bg-[#1c202e] hover:bg-[#252a3d] flex items-center justify-center text-gray-400 transition-colors" 
                 title="Refresh" @click="refreshPage()">
             <i class="fas fa-redo-alt text-xs" :class="{'fa-spin': isLoading}"></i>
         </button>
    </div>
  </div>

  <div class="flex-1 min-h-0 overflow-y-auto no-scrollbar bg-[#12141c] p-4" @scroll="closeDetail()">
      <!-- USER INVENTORY GRID -->
      <div class="catalog-grid">
        <template x-for="item in filteredMyInventory" :key="item.id">
          <div class="catalog-col">
              <div class="item-cell">
                  <div class="item-details relative" @click="addToOffer(item)">
                      <!-- Selected overlay with cart icon -->
                      <div x-show="isInOffer(item)" 
                           class="absolute inset-0 bg-black/50 z-10 flex items-center justify-center">
                          <div class="w-12 h-12 bg-black/80  flex items-center justify-center">
                              <i class="fas fa-check text-white text-lg"></i>
                          </div>
                      </div>

                      <img :src="item.image ? '/storage/' + item.image : 'https://via.placeholder.com/300x200?text=No+Image'" class="item-image">
                      <div class="indicators">
                          <div class="item-name font-bold text-sm text-gray-200 tracking-tight" x-text="item.name"></div>
                          <div class="price font-rajdhani font-bold text-[#f97316]" x-text="formatPrice(item.price)"></div>
                      </div>
                      <div class="buttons z-20">
                          <div class="btn-wrap">
                              <button class="btn-unstack" 
                                      :style="isInOffer(item) ? 'background: #ef4444' : 'background: var(--primary)'"
                                      @click.stop="addToOffer(item)">
                                  <i class="fas" :class="isInOffer(item) ? 'fa-times' : 'fa-plus'"></i>
                                  <span x-text="isInOffer(item) ? 'Cancel' : 'Offer'"></span>
                              </button>
                              <button class="btn-more" 
                                      :style="isInOffer(item) ? 'background: #dc2626' : ''"
                                      @click.stop="showDetail(item, $event)">⋮</button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </template>
        
        <div class="col-span-full text-center py-8 text-gray-400" x-show="filteredMyInventory.length === 0">
            No items in your inventory.
        </div>
      </div>
  </div>

</section>

<!-- FILTER -->
<aside class="bg-[#12141c]  flex flex-col h-full overflow-hidden min-h-0">

  <div class="h-[56px] flex items-center px-4 border-b border-white/5 font-semibold">
    Filters
  </div>

  <div class="flex-1 min-h-0 p-4 text-sm space-y-4 overflow-y-auto no-scrollbar overscroll-contain">

    <!-- Price -->
    <div>
      <div class="font-semibold mb-2 text-gray-400">Price ($)</div>
      <div class="flex gap-2 items-center">
        <input class="w-1/2 bg-[#0f1117] p-2 rounded outline-none border border-white/5 focus:border-[#4c6fff] transition" 
               placeholder="Min" x-model="priceMin" inputmode="decimal" pattern="[0-9]*">
        <span class="text-gray-500">-</span>
        <input class="w-1/2 bg-[#0f1117] p-2 rounded outline-none border border-white/5 focus:border-[#4c6fff] transition" 
               placeholder="Max" x-model="priceMax" inputmode="decimal" pattern="[0-9]*">
      </div>
    </div>

    <!-- Category Filter -->
    <div x-data="{ open: true }">
       <div class="flex justify-between items-center py-2 cursor-pointer group" @click="open = !open">
           <span class="font-semibold text-gray-300 group-hover:text-white transition">Category</span>
           <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform duration-200" :class="{'rotate-180': !open}"></i>
       </div>
       <div x-show="open" x-transition class="space-y-1 pl-1 max-h-[180px] overflow-y-auto no-scrollbar">
           <template x-for="cat in categories" :key="cat.id">
                   <div class="flex items-center gap-2 py-1 cursor-pointer hover:text-white text-gray-400 group/item" @click="filterCategory(cat.id)" :title="cat.name">
                   <div class="w-4 h-4 shrink-0 rounded-full border border-gray-600 flex items-center justify-center transition"
                        :class="{'border-[#4c6fff] bg-[#4c6fff]': selectedCategory === cat.id}">
                       <div class="w-1.5 h-1.5 bg-white rounded-full" x-show="selectedCategory === cat.id"></div>
                   </div>
                   <span class="truncate flex-1" x-text="cat.name"></span>
               </div>
           </template>
       </div>
    </div>

    <!-- Rarity Filter -->
    <div x-data="{ open: true }">
       <div class="flex justify-between items-center py-2 cursor-pointer group" @click="open = !open">
           <span class="font-semibold text-gray-300 group-hover:text-white transition">Rarity</span>
           <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform duration-200" :class="{'rotate-180': !open}"></i>
       </div>
       <div x-show="open" x-transition class="space-y-1 pl-1 max-h-[180px] overflow-y-auto no-scrollbar">
           <template x-for="r in rarities" :key="r">
               <div class="flex items-center gap-2 py-1 cursor-pointer hover:text-white text-gray-400 group/item" @click="filterRarity(r)" :title="r.charAt(0).toUpperCase() + r.slice(1).replace('_', ' ')">
                   <div class="w-4 h-4 shrink-0 rounded-full border border-gray-600 flex items-center justify-center transition"
                        :class="{'border-[#4c6fff] bg-[#4c6fff]': selectedRarity === r}">
                       <div class="w-1.5 h-1.5 bg-white rounded-full" x-show="selectedRarity === r"></div>
                   </div>
                   <span class="truncate flex-1" x-text="r.charAt(0).toUpperCase() + r.slice(1).replace('_', ' ')"></span>
               </div>
           </template>
       </div>
    </div>

  </div>

  <div class="p-4 border-t border-white/5">
    <button class="w-full bg-[#1a1d2b] hover:bg-[#252a3d] py-2 rounded text-gray-300 transition"
            @click="selectedCategory = null; selectedRarity = null; priceMin = ''; priceMax = ''">
      Reset Filters
    </button>
  </div>

</aside>

<!-- RIGHT -->
<section class="bg-[#12141c]  flex flex-col h-full overflow-hidden min-h-0">

  <div class="h-[56px] flex items-center justify-between px-4 border-b border-white/5 gap-4">
    <!-- Search Box -->
    <div class="w-56 flex items-center gap-2 bg-[#0b0e14] px-3 py-2 rounded border border-white/5" @click.stop>
        <i class="fas fa-search text-gray-500 text-xs"></i>
        <input class="bg-transparent text-sm w-full outline-none text-gray-300 placeholder-gray-600 pointer-events-auto"
               placeholder="Search inventory" x-model="searchMarket">
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-2" @click.stop>
         <button class="w-8 h-8 rounded bg-[#1c202e] hover:bg-[#252a3d] flex items-center justify-center text-gray-400 transition-colors" 
                 title="Refresh" @click="refreshPage()">
             <i class="fas fa-redo-alt text-xs" :class="{'fa-spin': isLoading}"></i>
         </button>
    </div>
  </div>

    <div class="flex-1 min-h-0 overflow-y-auto no-scrollbar bg-[#12141c] p-4" @scroll="closeDetail()">
        <!-- MARKET INVENTORY GRID -->
        <div class="catalog-grid">
            <template x-for="item in filteredMarketInventory" :key="item.id">
            <div class="catalog-col">
                <div class="item-cell">
                    <div class="item-details relative" @click="addToReceive(item)">
                        <!-- Selected overlay with cart icon -->
                        <div x-show="isInReceive(item)" 
                             class="absolute inset-0 bg-black/50 z-10 flex items-center justify-center">
                            <div class="w-12 h-12 bg-black/80  flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-white text-lg"></i>
                            </div>
                        </div>
                        
                        <img :src="item.image ? '/storage/' + item.image : 'https://via.placeholder.com/300x200?text=No+Image'" class="item-image">
                        <div class="indicators">
                            <div class="item-name font-bold text-sm text-gray-200 tracking-tight" x-text="item.name"></div>
                            <div class="price font-rajdhani font-bold text-[#f97316]" x-text="formatPrice(item.price)"></div>
                        </div>
                        <div class="buttons z-20">
                            <div class="btn-wrap">
                                <button class="btn-unstack" 
                                        :style="isInReceive(item) ? 'background: #ef4444' : 'background: var(--primary)'"
                                        @click.stop="addToReceive(item)">
                                    <i class="fas" :class="isInReceive(item) ? 'fa-times' : 'fa-plus'"></i>
                                    <span x-text="isInReceive(item) ? 'Remove' : 'Request'"></span>
                                </button>
                                <button class="btn-more" 
                                        :style="isInReceive(item) ? 'background: #dc2626' : ''"
                                        @click.stop="showDetail(item, $event)">⋮</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </template>
            
            <div class="col-span-full text-center py-8 text-gray-400" x-show="filteredMarketInventory.length === 0">
                No items available in market.
            </div>
        </div>
    </div>

</section>
</div>

  {{-- Why Trade With Us Section --}}
  <section class="w-full py-8 bg-[#12141c] mt-0 border-t border-white/5">
    <div class="max-w-[1920px] mx-auto px-6 space-y-6 text-gray-300 leading-relaxed">
      <h3 class="text-xl font-semibold text-white mt-8 mb-4">Trade Items - Secure Item Trading Platform</h3>
      <p>Welcome to TEMAN, the premier platform for secure item trading. With thousands of successful trades and a growing community of traders, we are dedicated to providing you with the fastest, most secure, and reliable trading experience for your valuable items.</p>

      <h3 class="text-xl font-semibold text-white mt-8 mb-4">Why TEMAN Stands Out as the Best Trading Platform:</h3>
      <p>Item trading is a serious business—every day, countless transactions happen worldwide. Trading items is an essential part of the gaming and collecting experience. While there are many platforms available, you need one you can truly trust.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Lightning-Fast Transactions:</h4>
      <p>Our system is built for speed. Execute trades quickly and efficiently, with access to a vast inventory of premium items instantly.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Trusted by Thousands:</h4>
      <p>Our commitment to user satisfaction and reliability is unmatched, backed by positive reviews and a growing user base.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Expert Item Knowledge:</h4>
      <p>Our detailed item guides and informative resources provide you with all the essential information to make smart trading decisions.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">24/7 Support:</h4>
      <p>Our dedicated support team is always available to help with any questions or issues, ensuring a smooth and enjoyable trading experience.</p>

      <p>Our platform is user-friendly and has everything you need to trade items effectively. We're also mobile-optimized, so you can trade from anywhere, anytime.</p>

      <h3 class="text-xl font-semibold text-white mt-8 mb-4">Fair Pricing, No Hidden Fees</h3>
      <p>We're becoming the go-to platform because we prioritize fair dealing. When you trade with us, you get competitive pricing without the high commission fees that other platforms charge.</p>

      <p>We use market analysis to ensure fair pricing for all items, so both buyers and sellers get the best possible deal. This means you get more value for your money and can build your collection affordably.</p>

      <h3 class="text-xl font-semibold text-white mt-8 mb-4">User-Focused Trading Experience:</h3>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Intuitive Design:</h4>
      <p>Whether you're a seasoned trader or just getting started, our platform is designed with simplicity in mind.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Secure Transactions:</h4>
      <p>Your security is our top priority. Trade with confidence, knowing your transactions are fully protected.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Stay Informed:</h4>
      <p>We regularly update our resources with guides, item evaluations, and the latest market trends. Stay ahead of the curve with our expert insights.</p>

      <h3 class="text-xl font-semibold text-white mt-8 mb-4">Building a Trading Community:</h3>
      <p>Join our vibrant community of traders. Share experiences, get advice, and connect with fellow enthusiasts.</p>
      <p class="text-orange-500 font-semibold">Join our community today!</p>

      <h3 class="text-xl font-semibold text-white mt-8 mb-4">The Premier Trading Platform</h3>
      <p>TEMAN is proud to be your trusted trading partner, offering an extensive selection of items available for purchase, sale, and trade. Our intuitive platform combined with our commitment to excellent service makes it easy to find what you're looking for. Our inventory updates regularly, so check back often to discover new opportunities!</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Trade with TEMAN:</h4>
      <p>At TEMAN, we're more than just a trading platform. We're a community of enthusiasts who understand your needs and are committed to delivering exceptional trading services. Fast, reliable, and knowledgeable—this is our promise to you.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Start Trading Now:</h4>
      <p>Dive into the exciting world of item trading with TEMAN. Experience our speed, reliability, and expertise firsthand!</p>

      <h3 class="text-xl font-semibold text-white mt-8 mb-4">Frequently Asked Questions</h3>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">What are the best practices for safe item trading?</h4>
      <p>For safe trading, always verify trading partners, use secure platforms like TEMAN, and follow platform guidelines.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">How does automated trading work?</h4>
      <p>Automated trading uses secure systems to facilitate fast, reliable transactions between users, acting as a trusted intermediary.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">How can I ensure a smooth trading experience?</h4>
      <p>To ensure smooth trading, read item descriptions carefully, communicate clearly with trading partners, and use our secure platform features.</p>
    </div>
  </section>

  {{-- Trade page specific footer --}}
  @include('user.multi_page.multi_footer')

</div>
@endsection
