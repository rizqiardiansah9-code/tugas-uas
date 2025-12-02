@extends('admin.layout.main')
@section('title', 'Item Detail')
@section('content')

    <div class="container-fluid pt-4" x-data="{ showDeleteModal: false, confirmDelete: {} }" @delete-item.window="confirmDelete = $event.detail; showDeleteModal = true">
        <a href="{{ route('admin.items.index') }}" class="text-sm text-app-muted hover:underline">← Back to item list</a>

        <div class="mt-4 bg-app-card border border-app-border rounded-xl p-6">
            <div class="flex gap-6 items-start">
                <div class="w-56 h-56 bg-app-bg/30 rounded-xl overflow-hidden flex items-center justify-center border border-app-border">
                    @if(!empty($item->image))
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-box-open text-white text-4xl"></i>
                    @endif
                </div>

                <div class="flex-1">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-white">{{ $item->name }}</h2>
                            {{-- small metadata under title removed per request; price/rarity/category will be shown on the left column below --}}
                            @php
                                $rKey = data_get($item, 'metadata.rarity', 'common');
                                $rText = in_array($rKey, ['legendary']) ? '#000' : '#fff';
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
                            @endphp
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.items.edit', $item->id) }}" class="px-3 py-2 rounded bg-app-accent text-white hover:bg-app-accent/90">Edit</a>

                            <button
                                type="button"
                                @click='confirmDelete = { id: {{ $item->id }}, name: {!! json_encode($item->name) !!} }; showDeleteModal = true'
                                class="px-3 py-2 rounded bg-red-600/80 hover:bg-red-600 text-white">Hapus</button>
                                <!-- Delete confirmation modal (reused from dashboard) -->
                                <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                                    <div class="absolute inset-0 bg-black/60" @click="showDeleteModal = false"></div>
                                    <div class="relative bg-app-card border border-app-border rounded-lg p-6 w-full max-w-md mx-4">
                                                <h3 class="text-lg font-semibold text-white">Konfirmasi Hapus Item</h3>
                                                <p class="text-app-muted mt-2">Anda akan menghapus item berikut:</p>
                                        <p class="font-semibold text-white mt-3" x-text="confirmDelete.name"></p>
                                        <p class="text-sm text-app-muted mt-1">This action cannot be undone. The item image will be deleted as well.</p>

                                        <div class="mt-6 flex justify-end gap-3">
                                            <button type="button" @click="showDeleteModal = false" class="px-4 py-2 rounded bg-app-bg/60 text-app-muted">Batal</button>

                                            <form x-bind:action="'{{ url('admin/items') }}/' + (confirmDelete.id || '')" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white">Hapus Item</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                        {{-- Left: large description card (like screenshot) --}}
                        <div class="md:col-span-2">
                            <h3 class="text-sm text-app-muted mb-3">Skin Description</h3>
                            <div class="bg-app-bg/30 border border-app-border rounded p-6 text-app-muted leading-relaxed">
                                {{-- if description empty show a placeholder --}}
                                @if(!empty($item->description))
                                    {!! nl2br(e($item->description)) !!}
                                @else
                                    <div class="text-sm text-app-muted">Tidak ada deskripsi tersedia untuk item ini.</div>
                                @endif

                                {{-- optional quote/attribution below description (if metadata.quote provided) --}}
                                @if(!empty(data_get($item, 'metadata.quote')))
                                    <blockquote class="mt-4 italic text-sm text-app-muted">{{ data_get($item, 'metadata.quote') }}</blockquote>
                                    <div class="mt-2 text-xs text-app-muted">&mdash; {{ data_get($item, 'metadata.quote_by', '') }}</div>
                                @endif
                            </div>
                        </div>

                        {{-- Right: compact attribute tiles --}}
                        <div class="md:col-span-1 grid grid-cols-1 gap-4">
                            {{-- Price --}}
                            <div class="bg-app-bg/30 border border-app-border rounded p-4 flex flex-col">
                                <div class="text-sm text-app-muted">Harga</div>
                                <div class="font-semibold text-white mt-2">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </div>

                            {{-- Rarity --}}
                            <div class="bg-app-bg/30 border border-app-border rounded p-4 flex flex-col items-start justify-center">
                                <div class="text-sm text-app-muted">Rarity</div>
                                <div class="mt-2">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full font-semibold" style="background: {{ $rarityColor }}; color: {{ $rText }}; box-shadow: 0 8px 24px {{ $rarityColor }}33">{{ ucfirst($rKey) }}</span>
                                </div>
                            </div>

                            {{-- Category --}}
                            <div class="bg-app-bg/30 border border-app-border rounded p-4">
                                <div class="text-sm text-app-muted">Kategori</div>
                                <div class="font-semibold text-white mt-2">{{ $item->category->name ?? '—' }}</div>
                            </div>

                            {{-- Added (created_at) --}}
                            <div class="bg-app-bg/30 border border-app-border rounded p-4">
                                <div class="text-sm text-app-muted">Ditambahkan</div>
                                <div class="font-semibold text-white mt-2">{{ optional($item->created_at)->format('Y-m-d') ?? '—' }}</div>
                            </div>

                            {{-- Pattern/Paint Index removed per request --}}
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection
