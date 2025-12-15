@extends('user.layout.main')

@section('title', 'Catalog')

@section('content')
    <div x-data="{ selectedCategory: 'all' }" x-cloak class="w-full">
<style>
:root{
  --bg:#0e1015; --card:#151823; --card-inner:#1c202e;
  --primary:#4c6fff; --gray:#aab0d5;
}

/* GRID */
.catalog-grid {
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(175px,1fr));
  gap:12px;
  /* padding:16px; removed padding to fit layout */
}

/* CARD SIZE */
.catalog-col{
  /* width:175px; removed fixed width to allow fluid grid */
  height:206px;
}

/* CARD */
.item-cell{
  width:100%;
  height:100%;
  background:var(--card);
  border-radius:10px;
  overflow:hidden;
}

.item-details{
  position:relative;
  height:100%;
  padding:12px;
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
  left:12px;
  right:12px;
  bottom:12px;
  transition:.35s cubic-bezier(.2,.8,.2,1);
}

.item-name{
  font-size:13px;
  font-weight:600;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
  color: #7d859e;
}

.price{
  font-size:15px;
  font-weight:700;
  margin-top:2px;
  color: #22c55e;
}

/* BUTTONS */
.buttons{
  position:absolute;
  left:12px;
  right:12px;
  bottom:12px;
  opacity:0;
  transform:translateY(100%);
  transition:.35s cubic-bezier(.2,.8,.2,1);
}

.btn-wrap{
  display:flex;
  height:30px;
  border-radius:8px;
  overflow:hidden;
}

.btn-unstack{
  flex:1;
  background:var(--primary);
  border:0;
  color:#fff;
  font-size:12px;
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
  width:30px;
  background:#5b6dff;
  border:0;
  color:#fff;
  font-size:16px;
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
/* HIDE SCROLLBAR */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
/* MARQUEE ANIMATION */
@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.animate-marquee {
    width: max-content; /* Force width to fit content for scrolling */
    animation: marquee 40s linear infinite;
}
.animate-marquee:hover {
    animation-play-state: paused;
}
</style>
        <!-- Hero Section - TradeIt Style - Full Width -->
        <div class="relative overflow-hidden mb-0 -mx-6 px-6 w-screen left-1/2 right-1/2 ml-[-50vw] mr-[-50vw]">
            <!-- ... (hero content skipped) ... -->

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center min-h-[600px] py-12 lg:py-20">
                <!-- Left Content -->
                <div class="relative z-10 pt-8 lg:pt-0 px-6 lg:px-12">
                    <!-- Rating Badge -->
                    <div class="flex items-center gap-2 mb-6">
                        <span class="text-green-500 text-2xl">★</span>
                        <span class="text-white font-semibold">Teman Market</span>
                        <span class="text-app-text-muted text-sm">4.8/5 rating based on 1K+ reviews</span>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="text-5xl lg:text-6xl font-black text-white mb-6 leading-tight">
                        Trade, Buy & Sell<br><span class="app-accent">Instantly</span>
                    </h1>

                    <!-- Description -->
                    <p class="text-lg app-text-muted mb-4">
                        The Ultimate Marketplace Platform.
                    </p>
                    <p class="text-base app-text-muted mb-8">
                        Fast, secure, and hassle-free.
                    </p>

                    <!-- CTA Button -->
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                        <button id="openLogin" class="navbar-btn px-5 py-2.5 text-base">Sign In</button>
                        <span class="text-white text-sm app-text-muted">
                            <i class="fas fa-gift mr-2 text-orange-500"></i>Start now for a bonus
                        </span>
                    </div>
                </div>

                <!-- Right Side - Featured Items Display -->
                <div class="relative hidden lg:block h-[600px] px-6 lg:px-12">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="relative w-96 h-96">
                            <!-- Specialist Gloves - Field Agent -->
                            <!-- Specialist Gloves - Field Agent: smaller, thicker border, subtle glow -->
                            <div class="absolute top-4 right-8 rounded-lg overflow-visible"
                                style="width:14rem;height:9rem;">
                                <div class="absolute -inset-6 rounded-2xl pointer-events-none"
                                    style="background: radial-gradient(closest-side, rgba(249,115,22,0.18), transparent 70%); filter: blur(22px);">
                                </div>
                                <div
                                    class="relative w-full h-full rounded-lg overflow-hidden border-2 border-orange-500/40 shadow-lg bg-gradient-to-br from-white/0 to-white/0">
                                    <img src="{{ asset('assets/images/Specialist-Gloves-Field-Agent.webp') }}"
                                        alt="Specialist Gloves Field Agent" class="w-full h-full object-cover">
                                </div>
                            </div <!-- M4A1-S Hot Rod: reduced size, lifted and rotated -->
                            <div class="absolute top-52 left-6 rounded-lg overflow-visible transform -rotate-6"
                                style="width:12rem;height:7.5rem;">
                                <div class="absolute -inset-5 rounded-2xl pointer-events-none"
                                    style="background: radial-gradient(closest-side, rgba(249,115,22,0.15), transparent 70%); filter: blur(18px);">
                                </div>
                                <div
                                    class="relative w-full h-full rounded-lg overflow-hidden border-2 border-orange-500/30 shadow-lg">
                                    <img src="{{ asset('assets/images/M4A1-S-Hot-Rod.webp') }}" alt="M4A1-S Hot Rod"
                                        class="w-full h-full object-cover">
                                </div>
                            </div>

                            <!-- Butterfly Knife Slaughter: smaller, angled with gentle glow -->
                            <div class="absolute bottom-0 left-8 rounded-lg overflow-visible transform rotate-0"
                                style="width:11.5rem;height:9.5rem;">
                                <div class="absolute -inset-5 rounded-2xl pointer-events-none"
                                    style="background: radial-gradient(closest-side, rgba(249,115,22,0.13), transparent 70%); filter: blur(16px);">
                                </div>
                                <div
                                    class="relative w-full h-full rounded-lg overflow-hidden border-2 border-orange-500/30 shadow-lg">
                                    <img src="{{ asset('assets/images/Butterfly-Knife-Slaughter.webp') }}"
                                        alt="Butterfly Knife Slaughter" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative Background -->
            <div class="absolute -right-40 -top-40 w-96 h-96 bg-orange-500/5 rounded-full blur-3xl pointer-events-none">
            </div>
        </div>

        <!-- Features Row (below signup, above product list) -->
        <div class="max-w-7xl mx-auto py-16 px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div>
                    <div
                        class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-app-card/20 text-orange-500 mb-4">
                        <i class="fas fa-headphones-alt text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-white mb-2">24/7 Support</h4>
                    <p class="text-sm app-text-muted">Our dedicated support team is available around the clock</p>
                </div>

                <div>
                    <div
                        class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-app-card/20 text-orange-500 mb-4">
                        <i class="fas fa-user text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-white mb-2">User-Friendly</h4>
                    <p class="text-sm app-text-muted">Intuitive and easy to use platform for new and experienced traders</p>
                </div>

                <div>
                    <div
                        class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-app-card/20 text-orange-500 mb-4">
                        <i class="fas fa-dollar-sign text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-white mb-2">Lowest Fees</h4>
                    <p class="text-sm app-text-muted">Lowest fees on the market. No hidden fees</p>
                </div>

                <div>
                    <div
                        class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-app-card/20 text-orange-500 mb-4">
                        <i class="fas fa-gift text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-white mb-2">Free Giveaways</h4>
                    <p class="text-sm app-text-muted">Trade CS2 skins to gain access to free giveaways</p>
                </div>
            </div>
        </div>

        <!-- Content Container - Back to normal width -->
        <div class="px-6">
            <!-- Category Buttons (Modern Style) -->
            <div class="mb-8 mt-12 flex justify-center">
                <div class="inline-flex flex-wrap justify-center gap-3 p-1.5 bg-black/20 backdrop-blur-sm rounded-2xl border border-white/5">
                    <button class="category-filter relative px-6 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 overflow-hidden group active"
                        data-category="all">
                        <span class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-500 opacity-0 group-[.active]:opacity-100 transition-opacity duration-300"></span>
                        <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative z-10 group-[.active]:text-white text-gray-400 group-hover:text-white">All Items</span>
                    </button>

                    @foreach(['Guns', 'Gloves', 'Knifes', 'Stickers'] as $cat)
                    <button class="category-filter relative px-6 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 overflow-hidden group"
                        data-category="{{ $cat }}">
                        <span class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-500 opacity-0 group-[.active]:opacity-100 transition-opacity duration-300"></span>
                        <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative z-10 group-[.active]:text-white text-gray-400 group-hover:text-white">{{ $cat }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Removed dynamic category tabs -->

            <!-- Products Grid (Infinite Marquee) -->
            <div class="relative group overflow-hidden">
                <!-- Wrapper for marquee to strictly hide overflow -->
                <div class="w-full overflow-hidden relative">
                     <!-- Gradient Overlays for smooth entry/exit -->
                    <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-[#0e1015] to-transparent z-20 pointer-events-none"></div>
                    <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-[#0e1015] to-transparent z-20 pointer-events-none"></div>
                    
                    <!-- Track -->
                    <div id="marqueeTrack" class="flex gap-6 animate-marquee py-8 px-4">
                        <!-- Content will be duplicated by JS for seamless loop -->
                        @isset($items)
                            @forelse($items as $item)
                                <div class="catalog-col flex-shrink-0" style="flex: 0 0 220px; width: 220px; height: 320px;" data-category="{{ $item->category->name ?? 'Umum' }}">
                                    <div class="item-cell">
                                        <div class="item-details"
                                            data-item-title="{{ $item->name }}"
                                            data-item-desc="{{ Str::limit($item->description ?? '', 500) }}"
                                            data-item-price="{{ $item->price ?? '-' }}"
                                            data-item-image="{{ !empty($item->image) ? asset('storage/' . $item->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                                            data-category="{{ $item->category->name ?? 'Umum' }}" onclick="showItemModal(this)">

                                            @if (!empty($item->image))
                                                <img class="item-image" src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                                            @else
                                                <img class="item-image" src="https://via.placeholder.com/300x200?text=No+Image" alt="{{ $item->name }}">
                                            @endif
                                            
                                            <div class="indicators">
                                                <div class="item-name">{{ $item->name }}</div>
                                                <div class="price">${{ number_format($item->price, 2) }}</div>
                                            </div>
                                            
                                            <div class="buttons">
                                                <div class="btn-wrap">
                                                <button class="btn-unstack" onclick="@auth window.location.href='{{ route('user.trade') }}' @else openModal('loginModalOverlay') @endauth"><i class="fas fa-shopping-cart"></i> Cart</button>
                                                    <button class="btn-more">⋮</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="w-full text-center py-16 flex-shrink-0" style="width: 100vw;">
                                    <i class="fas fa-inbox text-4xl app-text-muted mb-4 block"></i>
                                    <p class="text-xl app-text-muted">No items found.</p>
                                </div>
                            @endforelse
                        @else
                            <!-- Sample Loop kept for fallback/dev -->
                            @php
                            $samples = [
                                ['name' => 'Premium Leather Jacket', 'price' => 450000, 'category' => 'Clothing', 'img' => 'https://via.placeholder.com/300x200?text=Jacket'],
                                ['name' => 'Classic Backpack', 'price' => 280000, 'category' => 'Accessories', 'img' => 'https://via.placeholder.com/300x200?text=Backpack'],
                                ['name' => 'Running Shoes', 'price' => 650000, 'category' => 'Footwear', 'img' => 'https://via.placeholder.com/300x200?text=Shoes'],
                                ['name' => 'Cotton T-Shirt', 'price' => 120000, 'category' => 'Clothing', 'img' => 'https://via.placeholder.com/300x200?text=TShirt'],
                                ['name' => 'Denim Jeans', 'price' => 350000, 'category' => 'Clothing', 'img' => 'https://via.placeholder.com/300x200?text=Jeans'],
                                ['name' => 'Sports Watch', 'price' => 900000, 'category' => 'Accessories', 'img' => 'https://via.placeholder.com/300x200?text=Watch'],
                            ];
                            @endphp
                            @foreach ($samples as $s)
                                <div class="catalog-col flex-shrink-0" style="flex: 0 0 220px; width: 220px; height: 320px;" data-category="{{ $s['category'] }}">
                                    <div class="item-cell">
                                        <div class="item-details" data-item-title="{{ $s['name'] }}" data-item-price="{{ $s['price'] }}" data-item-image="{{ $s['img'] }}" data-category="{{ $s['category'] }}" onclick="showItemModal(this)">
                                            <div class="count">x{{ rand(1, 50) }}</div>
                                            <img class="item-image" src="{{ $s['img'] }}" alt="{{ $s['name'] }}">
                                            <div class="indicators">
                                                <div class="item-name">{{ $s['name'] }}</div>
                                                <div class="price">${{ number_format($s['price'], 2) }}</div>
                                            </div>
                                            <div class="buttons">
                                                <div class="btn-wrap">
                                                    <button class="btn-unstack" onclick="@auth window.location.href='{{ route('user.trade') }}' @else openModal('loginModalOverlay') @endauth"><i class="fas fa-shopping-cart"></i> Cart</button>
                                                    <button class="btn-more">⋮</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endisset
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const track = document.getElementById('marqueeTrack');
                
                // Initial Clone Logic
                if(track) {
                    const children = Array.from(track.children); // Get original items
                    if(children.length > 0) {
                        // Clone once to ensure we have enough content for the loop setup
                        // We mark clones so we can control them or identify them if needed
                        children.forEach(child => {
                            const clone = child.cloneNode(true);
                            clone.classList.add('is-clone');
                            track.appendChild(clone);
                        });
                    }
                    
                    // Initial check
                    checkAnimationState('all');
                }
            });

            // Category filter
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.addEventListener('click', function() {
                    const category = this.dataset.category;
                    
                    // Update Active State
                    document.querySelectorAll('.category-filter').forEach(b => {
                        b.classList.remove('active');
                    });
                    this.classList.add('active');

                    // Filter and Update Animation
                    checkAnimationState(category);
                });
            });

            function checkAnimationState(category) {
                const track = document.getElementById('marqueeTrack');
                if(!track) return;

                const cards = track.querySelectorAll('.catalog-col');
                let visibleCount = 0;
                let visibleOriginals = 0;

                // 1. First Pass: Show/Hide items based on category
                cards.forEach(card => {
                    const cardCategory = card.dataset.category; 
                    if(!cardCategory) return;
                    
                    const match = (category === 'all' || cardCategory.toLowerCase() === category.toLowerCase());
                    
                    if(match) {
                        card.style.display = 'block';
                        visibleCount++;
                        if(!card.classList.contains('is-clone')) {
                             visibleOriginals++;
                        }
                    } else {
                        card.style.display = 'none';
                    }
                });

                // 2. Handle Empty State
                let noResultsMsg = document.getElementById('noResultsMsg');
                if (visibleOriginals === 0) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.id = 'noResultsMsg';
                        // Changed from absolute to relative to maintain container height
                        noResultsMsg.className = 'relative w-full h-96 flex flex-col items-center justify-center text-center';
                        // Modified inner HTML to remove background
                        noResultsMsg.innerHTML = `
                            <div class="p-8">
                                <i class="fas fa-search-location text-5xl text-orange-500/50 mb-4 block"></i>
                                <p class="text-xl font-medium text-white">Couldn't find items here</p>
                                <p class="text-sm app-text-muted mt-2">Try selecting another category</p>
                            </div>
                        `;
                        track.parentNode.appendChild(noResultsMsg);
                    }
                    noResultsMsg.style.display = 'flex';
                    track.style.display = 'none'; // Hide track
                } else {
                    if (noResultsMsg) noResultsMsg.style.display = 'none';
                    track.style.display = 'flex';
                }

                // 3. Handle Animation Logic
                // If few items (e.g. <= 4), disable animation to prevent awkward loops with gaps
                // or if they fit within screen width.
                // NOTE: 'visibleCount' includes clones. 'visibleOriginals' is the real count.
                
                // Logic: If we have enough originals to justify scrolling?
                // Let's say if we have fewer than 5 items, we just center them and kill animation.
                
                if (visibleOriginals > 0 && visibleOriginals < 5) {
                    track.classList.remove('animate-marquee');
                    track.classList.add('justify-center'); // Center items if static
                    // Also need to hide clones to avoid duplicate display in static mode
                     cards.forEach(card => {
                        if(card.classList.contains('is-clone')) {
                            card.style.display = 'none';
                        }
                    });
                } else {
                    // Re-enable animation
                    // Ensure clones are visible (if they match category)
                    cards.forEach(card => {
                        const cardCategory = card.dataset.category;
                        const match = (category === 'all' || cardCategory.toLowerCase() === category.toLowerCase());
                        if(match) {
                            card.style.display = 'block'; // Show matched clones too
                        }
                    });

                    track.classList.remove('justify-center');
                    track.classList.add('animate-marquee');
                }
            }

            // Modal integration (unchanged)
            function showItemModal(card) {
                // ... same modal logic ...
                const title = card.dataset.itemTitle;
                const desc = card.dataset.itemDesc;
                const price = card.dataset.itemPrice;
                const image = card.dataset.itemImage;
                const category = card.dataset.category;

                if(document.getElementById('itemTitle')) document.getElementById('itemTitle').textContent = title;
                if(document.getElementById('itemImage')) document.getElementById('itemImage').src = image || 'https://via.placeholder.com/300x200';
                if(document.getElementById('itemDesc')) document.getElementById('itemDesc').textContent = desc || 'No description available.';
                if(document.getElementById('itemPrice')) document.getElementById('itemPrice').textContent = 'Rp ' + (price ? price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '-');
                if(document.getElementById('itemCategory')) document.getElementById('itemCategory').textContent = category;

                const modal = document.getElementById('itemModal');
                if (modal) modal.style.display = 'block';
            }
        </script>

    @endsection
