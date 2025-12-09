@extends('user.layout.main')

@section('title', 'Catalog')

@section('content')
    <div x-data="{ selectedCategory: 'all' }" x-cloak class="w-full">
        <!-- Hero Section - TradeIt Style - Full Width -->
        <div class="relative overflow-hidden mb-0 -mx-6 px-6 w-screen left-1/2 right-1/2 ml-[-50vw] mr-[-50vw]">
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
                        <a href="{{ Auth::check() ? '#catalogGrid' : 'javascript:openModal(\"registerModalOverlay\")' }}"
                            class="px-8 py-4 font-black text-white rounded-lg transition-all duration-300 whitespace-nowrap"
                            style="background-color: #f97316; box-shadow: 0 0 20px rgba(249, 115, 22, 0.3);"
                            onmouseover="this.style.boxShadow='0 0 30px rgba(249, 115, 22, 0.6)'; this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.boxShadow='0 0 20px rgba(249, 115, 22, 0.3)'; this.style.transform='translateY(0)'"
                            style="display:inline-block; transition:all 0.3s;">
                            {{ Auth::check() ? 'Browse Now' : 'Sign Up' }}
                        </a>
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
            <!-- Category Buttons (no icons) -->
            <div class="mb-8 mt-20 flex justify-center">
                <div class="flex gap-3 min-w-min justify-center">
                    <button
                        class="category-filter px-6 py-2.5 rounded-lg font-medium text-white bg-app-accent/20 border border-app-accent/50 hover:bg-app-accent/30 transition-all whitespace-nowrap active"
                        data-category="all">
                        All
                    </button>
                    <button
                        class="category-filter px-6 py-2.5 rounded-lg font-medium text-app-text-muted border border-app-border hover:border-app-accent/50 hover:text-white transition-all whitespace-nowrap"
                        data-category="Guns">
                        Guns
                    </button>
                    <button
                        class="category-filter px-6 py-2.5 rounded-lg font-medium text-app-text-muted border border-app-border hover:border-app-accent/50 hover:text-white transition-all whitespace-nowrap"
                        data-category="Gloves">
                        Gloves
                    </button>
                    <button
                        class="category-filter px-6 py-2.5 rounded-lg font-medium text-app-text-muted border border-app-border hover:border-app-accent/50 hover:text-white transition-all whitespace-nowrap"
                        data-category="Knifes">
                        Knifes
                    </button>
                    <button
                        class="category-filter px-6 py-2.5 rounded-lg font-medium text-app-text-muted border border-app-border hover:border-app-accent/50 hover:text-white transition-all whitespace-nowrap"
                        data-category="Stickers">
                        Stickers
                    </button>
                </div>
            </div>

            <!-- Removed dynamic category tabs -->

            <!-- Products Grid -->
            <div class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-20" id="catalogGrid">
                    @isset($items)
                        @forelse($items as $item)
                            <div class="p-4">
                                <div class="product-card group app-card border border-app-border rounded-xl overflow-hidden transition-all duration-300 hover:border-orange-500/50 hover:shadow-xl hover:shadow-orange-500/10 cursor-pointer"
                                    data-item-title="{{ $item->name }}"
                                    data-item-desc="{{ Str::limit($item->description ?? '', 500) }}"
                                    data-item-price="{{ $item->price ?? '-' }}"
                                    data-item-image="{{ !empty($item->image) ? asset('storage/' . $item->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                                    data-category="{{ $item->category->name ?? 'Umum' }}" onclick="showItemModal(this)">

                                    <!-- Image Container -->
                                    <div class="relative h-48 overflow-hidden bg-app-bg">
                                        @if (!empty($item->image))
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                                class="w-full h-full object-cover transition-transform duration-300">
                                        @else
                                            <img src="https://via.placeholder.com/300x200?text=No+Image"
                                                alt="{{ $item->name }}"
                                                class="w-full h-full object-cover transition-transform duration-300">
                                        @endif
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5">
                                        <div class="flex items-start justify-between gap-3 mb-3">
                                            <div class="flex-1">
                                                <h3
                                                    class="font-bold text-white text-lg leading-tight group-hover:text-orange-500 transition-colors">
                                                    {{ $item->name }}</h3>
                                            </div>
                                        </div>

                                        @php
                                            $catGradients = [
                                                'Guns' =>
                                                    'bg-gradient-to-r from-app-accent via-app-accent/60 to-app-accent/30 text-white',
                                                'Knives' =>
                                                    'bg-gradient-to-r from-gray-800 via-gray-600 to-gray-400 text-white',
                                                'Gloves' =>
                                                    'bg-gradient-to-r from-yellow-600 via-yellow-400 to-yellow-200 text-black',
                                                'Stickers' =>
                                                    'bg-gradient-to-r from-pink-600 via-pink-400 to-pink-200 text-white',
                                            ];
                                        @endphp

                                        <div class="flex items-center justify-between mb-4">
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium {{ $catGradients[$item->category->name] ?? 'bg-app-bg border border-app-border text-white' }}">
                                                {{ $item->category->name ?? 'General' }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between pt-4 border-t border-app-border">
                                            <div>
                                                <p class="text-xs app-text-muted mb-1">Price</p>
                                                <p class="text-2xl font-bold text-orange-500">
                                                    ${{ number_format($item->price, 2) }}</p>
                                            </div>
                                            <button
                                                class="p-3 rounded-lg bg-app-accent/20 border border-app-accent/30 text-orange-500 hover:bg-app-accent/30 group-hover:border-orange-500 transition-all">
                                                <i class="fas fa-arrow-right text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-16">
                                <i class="fas fa-inbox text-4xl app-text-muted mb-4 block"></i>
                                <p class="text-xl app-text-muted">No items found.</p>
                            </div>
                        @endforelse
                    @else
                        @php
                            $samples = [
                                [
                                    'name' => 'Premium Leather Jacket',
                                    'desc' =>
                                        'High-quality leather jacket perfect for everyday wear. Durable and stylish.',
                                    'price' => 450000,
                                    'category' => 'Clothing',
                                    'img' => 'https://via.placeholder.com/300x200?text=Jacket',
                                ],
                                [
                                    'name' => 'Classic Backpack',
                                    'desc' =>
                                        'Spacious and comfortable backpack for work or travel. Multiple compartments.',
                                    'price' => 280000,
                                    'category' => 'Accessories',
                                    'img' => 'https://via.placeholder.com/300x200?text=Backpack',
                                ],
                                [
                                    'name' => 'Running Shoes',
                                    'desc' =>
                                        'Comfortable running shoes with premium cushioning. Perfect for gym and jogging.',
                                    'price' => 650000,
                                    'category' => 'Footwear',
                                    'img' => 'https://via.placeholder.com/300x200?text=Shoes',
                                ],
                                [
                                    'name' => 'Cotton T-Shirt',
                                    'desc' => 'Premium cotton t-shirt in various colors. Soft and breathable fabric.',
                                    'price' => 120000,
                                    'category' => 'Clothing',
                                    'img' => 'https://via.placeholder.com/300x200?text=TShirt',
                                ],
                                [
                                    'name' => 'Denim Jeans',
                                    'desc' => 'Classic denim jeans with perfect fit. Durable and stylish design.',
                                    'price' => 350000,
                                    'category' => 'Clothing',
                                    'img' => 'https://via.placeholder.com/300x200?text=Jeans',
                                ],
                                [
                                    'name' => 'Sports Watch',
                                    'desc' =>
                                        'Smart watch with fitness tracking. Water-resistant and long battery life.',
                                    'price' => 900000,
                                    'category' => 'Accessories',
                                    'img' => 'https://via.placeholder.com/300x200?text=Watch',
                                ],
                            ];
                        @endphp
                        @foreach ($samples as $s)
                            <div class="p-4">
                                <div class="product-card group app-card border border-app-border rounded-xl overflow-hidden transition-all duration-300 hover:border-orange-500/50 hover:shadow-xl hover:shadow-orange-500/10 cursor-pointer"
                                    data-item-title="{{ $s['name'] }}" data-item-desc="{{ $s['desc'] }}"
                                    data-item-price="{{ $s['price'] }}" data-item-image="{{ $s['img'] }}"
                                    data-category="{{ $s['category'] }}" onclick="showItemModal(this)">

                                    <!-- Image Container -->
                                    <div class="relative h-48 overflow-hidden bg-app-bg">
                                        <img src="{{ $s['img'] }}" alt="{{ $s['name'] }}"
                                            class="w-full h-full object-cover transition-transform duration-300">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5">
                                        <div class="flex items-start justify-between gap-3 mb-3">
                                            <div class="flex-1">
                                                <h3
                                                    class="font-bold text-white text-lg leading-tight group-hover:text-orange-500 transition-colors">
                                                    {{ $s['name'] }}</h3>
                                            </div>
                                        </div>

                                        <p class="text-sm app-text-muted mb-4 line-clamp-2">{{ $s['desc'] }}</p>

                                        <div class="flex items-center justify-between mb-4">
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-app-bg border border-app-border text-xs font-medium text-white">
                                                <i class="fas fa-folder text-orange-500"></i>{{ $s['category'] }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between pt-4 border-t border-app-border">
                                            <div>
                                                <p class="text-xs app-text-muted mb-1">Price</p>
                                                <p class="text-2xl font-bold text-orange-500">
                                                    ${{ number_format($s['price'], 2) }}</p>
                                            </div>
                                            <button
                                                class="p-3 rounded-lg bg-app-accent/20 border border-app-accent/30 text-orange-500 hover:bg-app-accent/30 group-hover:border-orange-500 transition-all">
                                                <i class="fas fa-arrow-right text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endisset
                </div>
            </div>
        </div>

        <script>
            // Category filter
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.addEventListener('click', function() {
                    const category = this.dataset.category;

                    // Update active state
                    document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active',
                        'bg-app-accent/20', 'border-app-accent/50'));
                    document.querySelectorAll('.category-filter').forEach(b => b.classList.add(
                        'text-app-text-muted', 'border-app-border'));
                    this.classList.add('active', 'bg-app-accent/20', 'border-app-accent/50');
                    this.classList.remove('text-app-text-muted', 'border-app-border');
                    this.classList.add('text-white');

                    // Filter products by category only
                    filterProducts(category);
                });
            });

            function filterProducts(category) {
                const cards = document.querySelectorAll('#catalogGrid .product-card');
                let visibleCount = 0;

                cards.forEach(card => {
                    const cardCategory = card.dataset.category.toLowerCase();
                    const categoryMatch = category === 'all' || cardCategory === category.toLowerCase();

                    if (categoryMatch) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show "no results" message if needed
                if (visibleCount === 0) {
                    if (!document.getElementById('noResults')) {
                        const noResults = document.createElement('div');
                        noResults.id = 'noResults';
                        noResults.className = 'col-span-full text-center py-16';
                        noResults.innerHTML =
                            '<i class="fas fa-search text-4xl app-text-muted mb-4 block"></i><p class="text-xl app-text-muted">No items match your category.</p>';
                        document.getElementById('catalogGrid').appendChild(noResults);
                    }
                } else {
                    const noResults = document.getElementById('noResults');
                    if (noResults) noResults.remove();
                }
            }

            // Modal integration
            function showItemModal(card) {
                const title = card.dataset.itemTitle;
                const desc = card.dataset.itemDesc;
                const price = card.dataset.itemPrice;
                const image = card.dataset.itemImage;
                const category = card.dataset.category;

                // Update modal (assuming modal exists in layout)
                if (document.getElementById('itemTitle')) {
                    document.getElementById('itemTitle').textContent = title;
                }
                if (document.getElementById('itemImage')) {
                    document.getElementById('itemImage').src = image || 'https://via.placeholder.com/300x200';
                }
                if (document.getElementById('itemDesc')) {
                    document.getElementById('itemDesc').textContent = desc || 'No description available.';
                }
                if (document.getElementById('itemPrice')) {
                    document.getElementById('itemPrice').textContent = 'Rp ' + (price ? price.toString().replace(
                        /\B(?=(\d{3})+(?!\d))/g, '.') : '-');
                }
                if (document.getElementById('itemCategory')) {
                    document.getElementById('itemCategory').textContent = category;
                }

                // Show modal
                const modal = document.getElementById('itemModal');
                if (modal) {
                    modal.style.display = 'block';
                }
            }
        </script>

    @endsection
