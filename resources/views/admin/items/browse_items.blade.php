@extends('admin.layout.main')
@section('title', 'Browse Items')
@section('content')

    <div class="container-fluid pt-2" x-data="{ showDeleteModal: false, confirmDelete: {} }" @delete-item.window="confirmDelete = $event.detail; showDeleteModal = true">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-app-muted hover:underline">← Dashboard</a>
                <h1 class="text-2xl font-bold text-white mt-2">Browse Items</h1>
                <p class="text-app-muted text-sm">List all items / skins — use the category filter or click on an item to view details.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.items.create') }}" class="px-3 py-2 rounded-lg bg-app-accent text-white hover:bg-app-accent/90 transition-colors">Add Item</a>
            </div>
        </div>

        <!-- Category filters -->
        <div class="mb-6 flex items-center gap-3 flex-wrap">
            <a href="{{ route('admin.items.index') }}" class="px-3 py-1 rounded-full text-sm {{ empty($categorySlug) ? 'bg-app-accent text-white shadow-lg shadow-app-accent/20' : 'bg-app-bg border border-app-border text-app-muted hover:text-white hover:bg-app-cardHover' }}">Semua <span class="ml-1 inline-flex items-center justify-center w-5 h-5 text-xs rounded-full bg-app-bg/70 border border-app-border text-app-muted">{{ $totalItems ?? ($items->total() ?? 0) }}</span></a>
            @php
                // consistent category gradient styles reused across admin
                $catGraduents = [
                    'Guns' => 'bg-gradient-to-r from-app-accent via-app-accent/60 to-app-accent/30 text-white',
                    'Knives' => 'bg-gradient-to-r from-gray-800 via-gray-600 to-gray-400 text-white',
                    'Gloves' => 'bg-gradient-to-r from-yellow-600 via-yellow-400 to-yellow-200 text-black',
                    'Stickers' => 'bg-gradient-to-r from-pink-600 via-pink-400 to-pink-200 text-white',
                ];
            @endphp

            @foreach($categories as $cat)
                @php
                    $grad = $catGraduents[$cat->name] ?? 'bg-app-bg border border-app-border text-app-muted hover:text-white hover:bg-app-cardHover';
                    $isActive = ($categorySlug ?? null) === $cat->slug;
                @endphp
                <a href="{{ route('admin.items.index', ['category' => $cat->slug]) }}" class="px-3 py-1 rounded-full text-sm inline-flex items-center gap-2 {{ $isActive ? ($grad . ' shadow-lg shadow-app-accent/20') : 'text-app-muted hover:text-white hover:bg-app-cardHover' }}">
                    <span class="{{ $isActive ? '' : 'text-app-muted' }}">{{ $cat->name }}</span>
                    <span class="ml-1 inline-flex items-center justify-center w-5 h-5 text-xs rounded-full border border-app-border {{ $isActive ? str_replace(' text-white','',$grad) : 'bg-app-bg/70 text-app-muted' }}">{{ $cat->items_count ?? 0 }}</span>
                </a>
            @endforeach

        </div>

        <!-- Grid of items -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($items as $item)
                @php
                    $rKey = data_get($item, 'metadata.rarity', 'common');
                    $rarityColor = match($rKey) {
                        'uncommon' => '#16a34a',
                        'rare' => '#3b82f6',
                        'mythical' => '#7c3aed',
                        'legendary' => '#f59e0b',
                        'ancient' => '#3730a3',
                        'exceedingly_rare' => '#db2777',
                        'immortal' => '#dc2626',
                        default => '#6b7280'
                    };
                    $rText = in_array($rKey, ['legendary']) ? '#000' : '#fff';
                @endphp

                <div id="item-card-{{ $item->id }}" role="button" tabindex="0" onclick="if(event.target.closest('form') || event.target.closest('.admin-action')) return; window.location='{{ route('admin.items.show', ['item' => $item->id]) }}';" class="relative block bg-app-card border border-app-border rounded-lg p-4 flex items-start gap-4 transition-shadow hover:shadow-2xl cursor-pointer" style="box-shadow: 0 8px 24px {{ $rarityColor }}33; border-color: {{ $rarityColor }}22;">

                    <div class="w-20 h-20 flex-shrink-0 rounded overflow-hidden bg-app-bg/40 border border-app-border flex items-center justify-center relative">
                        @if(!empty($item->image))
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover rounded">
                        @else
                            <i class="fas fa-box-open text-white text-xl"></i>
                        @endif
                    </div>

                    <div class="flex-1">
                        <p class="font-semibold text-white">{{ $item->name }}</p>

                        {{-- category below name --}}
                        <div class="mt-3 flex items-center gap-2">
                            @if($item->category)
                                <span class="text-xs px-2 py-1 rounded-full bg-app-bg border border-app-border text-app-muted">{{ $item->category->name }}</span>
                            @endif
                        </div>

                        {{-- price below category (as requested) --}}
                        <div class="mt-3">
                            <div class="text-sm text-app-muted">Rp {{ number_format($item->price, 2, ',', '.') }}</div>
                        </div>

                    </div>

                    <!-- admin actions -->
                    <div class="flex flex-col items-end gap-2 admin-action absolute right-4 top-4">
                        <a href="{{ route('admin.items.edit', $item->id) }}" class="text-xs px-2 py-1 rounded bg-app-accent/80 hover:bg-app-accent text-white" onclick="event.stopPropagation();">Edit</a>

                        <button
                            type="button"
                            onclick="event.stopPropagation();"
                            @click='confirmDelete = { id: {{ $item->id }}, name: {!! json_encode($item->name) !!} }; showDeleteModal = true'
                            class="text-xs px-2 py-1 rounded bg-red-600/80 hover:bg-red-600 text-white">Hapus</button>
                    </div>

                    {{-- rarity badge: above image (overlay) --}}
                    <div class="absolute left-2 -top-3">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded" style="background: {{ $rarityColor }}; color: {{ $rText }}; box-shadow: 0 6px 16px {{ $rarityColor }}33">{{ ucfirst($rKey) }}</span>
                    </div>

                </div>

            @empty
                <div class="col-span-full bg-app-card border border-app-border rounded-lg p-6 text-app-muted">
                    Tidak ada items ditemukan.
                </div>
            @endforelse

        </div>

        <div class="mt-6">
            {{-- Use default paginator view (fallback) to avoid missing vendor template errors --}}
            {{ $items->links() }}
        </div>

        <!-- Delete confirmation modal (same as dashboard) - placed INSIDE the container so Alpine x-data works -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/60" @click="showDeleteModal = false"></div>
            <div class="relative bg-app-card border border-app-border rounded-lg p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold text-white">Confirm To Delete Item</h3>
                <p class="text-app-muted mt-2">You will delete the following items:</p>
                <p class="font-semibold text-white mt-3" x-text="confirmDelete.name"></p>
                <p class="text-sm text-app-muted mt-1">This action cannot be undone. The item image will be deleted as well.</p>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="showDeleteModal = false" class="px-4 py-2 rounded bg-app-bg/60 text-app-muted">Cancle</button>

                    <form x-bind:action="'{{ url('admin/items') }}/' + (confirmDelete.id || '')" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white">Delete Item</button>
                    </form>
                </div>
            </div>
        </div>
        </div>

    </div>
@endsection
