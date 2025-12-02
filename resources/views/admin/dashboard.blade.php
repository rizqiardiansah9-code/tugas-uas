@extends('admin.layout.main')
@section('title', 'Dashboard')
@section('content')

    <div class="container-fluid pt-2" x-data="{ openPanel: null, showDeleteModal: false, confirmDelete: {} }">

        <!-- Welcome Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-app-card to-[#1e2532] border border-app-border p-8 mb-8 shadow-xl">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-white mb-2">
                    Welcome back, <span class="text-app-accent">{{ Auth::user()->nama }}</span>
                </h1>
                <p class="text-app-muted">
                    <span class="text-white font-semibold">TEMAN Market</span> dashboard. System status is optimal for trading.
                </p>
            </div>
            <!-- Dekorasi Background -->
            <div class="absolute -right-10 -top-20 w-64 h-64 bg-app-accent/10 rounded-full blur-3xl pointer-events-none"></div>
        </div>

        <!-- Stats Grid (Contoh Statistik Ala Market) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <!-- Card 1: Was Buku -> Now Skins -->
              <div class="bg-app-card border border-app-border p-5 rounded-xl hover:border-app-accent/30 transition-all hover:-translate-y-1 duration-300 group cursor-pointer"
                  @click="openPanel === 'items' ? openPanel = null : openPanel = 'items'"
                  :class="openPanel === 'items' ? 'ring-2 ring-app-accent/30' : ''">
                <div class="flex items-start justify-between mb-2">
                    <p class="text-app-muted text-xs font-bold uppercase tracking-wider">Total Items</p>
                    <div class="p-2 bg-app-bg rounded-lg text-app-accent group-hover:text-white transition-colors">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">{{ isset($totalItems) ? number_format($totalItems) : '24,500' }}</h3>
                <span class="text-xs font-medium text-app-success">{{ isset($newItems) ? ('+' . $newItems . ' New Items') : '+120 New Items' }}</span>
            </div>

            <!-- Card 2: Was User -> Now Traders -->
              <div class="bg-app-card border border-app-border p-5 rounded-xl hover:border-app-accent/30 transition-all hover:-translate-y-1 duration-300 group cursor-pointer"
                  @click="openPanel === 'users' ? openPanel = null : openPanel = 'users'"
                  :class="openPanel === 'users' ? 'ring-2 ring-app-accent/30' : ''">
                <div class="flex items-start justify-between mb-2">
                    <p class="text-app-muted text-xs font-bold uppercase tracking-wider">Active Users</p>
                    <div class="p-2 bg-app-bg rounded-lg text-app-accent group-hover:text-white transition-colors">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">{{ isset($activeUsers) ? $activeUsers->count() : 0 }}</h3>
                <span class="text-xs font-medium text-app-success">of {{ isset($totalUsers) ? $totalUsers : '0' }} total</span>
            </div>

            <!-- Card 3: Was Peminjaman -> Now Trades -->
            <div class="bg-app-card border border-app-border p-5 rounded-xl hover:border-app-accent/30 transition-all hover:-translate-y-1 duration-300 group">
                <div class="flex items-start justify-between mb-2">
                    <p class="text-app-muted text-xs font-bold uppercase tracking-wider">Trade Offers</p>
                    <div class="p-2 bg-app-bg rounded-lg text-purple-500 group-hover:text-white transition-colors">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">42</h3>
                <span class="text-xs font-medium text-app-warning">Pending Action</span>
            </div>

            <!-- Card 4 -->
            <div class="bg-app-card border border-app-border p-5 rounded-xl hover:border-app-accent/30 transition-all hover:-translate-y-1 duration-300 group">
                <div class="flex items-start justify-between mb-2">
                    <p class="text-app-muted text-xs font-bold uppercase tracking-wider">Market Volume</p>
                    <div class="p-2 bg-app-bg rounded-lg text-green-500 group-hover:text-white transition-colors">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">$ 12.4k</h3>
                <span class="text-xs font-medium text-app-success">+5% Today</span>
            </div>

        </div>

        <!-- Detail Panel (Muncul saat kartu diklik) -->
        <div class="mt-6" x-cloak x-show="openPanel" x-transition>
            <div class="bg-app-card border border-app-border rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-bold text-white" x-text="openPanel === 'items' ? 'Detail: Items & Skins' : (openPanel === 'users' ? 'Detail: Active Users' : '')"></h4>
                    <button @click="openPanel = null" class="text-app-muted hover:text-white text-sm">Close</button>
                </div>

                <!-- scrollable content wrapper: keeps header fixed, content scrolls when long -->
                <div class="overflow-auto" style="max-height:60vh; padding-right:8px;">

                    <!-- Items Detail -->
                    <div x-show="openPanel === 'items'" x-transition>
                    @if(isset($items) && count($items))
                        @php
                            $rarityMap = [
                                'common' => ['label' => 'Common', 'color' => '#6b7280'],
                                'uncommon' => ['label' => 'Uncommon', 'color' => '#16a34a'],
                                'rare' => ['label' => 'Rare', 'color' => '#3b82f6'],
                                'mythical' => ['label' => 'Mythical', 'color' => '#7c3aed'],
                                'legendary' => ['label' => 'Legendary', 'color' => '#f59e0b'],
                                'ancient' => ['label' => 'Ancient', 'color' => '#3730a3'],
                                'exceedingly_rare' => ['label' => 'Exceedingly Rare', 'color' => '#db2777'],
                                'immortal' => ['label' => 'Immortal', 'color' => '#dc2626'],
                            ];
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($items as $item)
                                @php
                                    $rKey = data_get($item, 'metadata.rarity', 'common');
                                    $rMeta = $rarityMap[$rKey] ?? ['label' => ucfirst($rKey), 'color' => '#6b7280'];
                                    $rLabel = $rMeta['label'];
                                    $rColor = $rMeta['color'];
                                    $rText = in_array($rKey, ['legendary']) ? '#000' : '#fff';
                                @endphp

                                <div class="bg-app-bg/50 p-4 pt-8 rounded-lg border flex items-start gap-4 relative overflow-hidden" style="border-color: {{ $rColor }}; box-shadow: 0 8px 28px {{ $rColor }}22;">
                                    <div class="absolute left-3 top-3 flex items-center gap-2">
                                        <a href="{{ route('admin.items.edit', $item->id) }}" class="text-xs px-2 py-1 rounded bg-app-accent/80 hover:bg-app-accent text-white">Edit</a>
                                        <button type="button" @click='confirmDelete = { id: {{ $item->id }}, name: {!! json_encode($item->name) !!} }; showDeleteModal = true' class="text-xs px-2 py-1 rounded bg-red-600/80 hover:bg-red-600 text-white">Hapus</button>
                                    </div>
                                    {{-- rarity badge (moved to top-right so it doesn't cover long names) --}}
                                    <div class="absolute right-4 top-4">
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded" style="background: {{ $rColor }}; color: {{ $rText }}; box-shadow: 0 6px 16px {{ $rColor }}33">{{ $rLabel }}</span>
                                    </div>

                                    <div class="w-20 h-20 flex-shrink-0">
                                        @if(!empty($item->image))
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-20 h-20 rounded object-cover" style="border:2px solid {{ $rColor }}22;">
                                        @else
                                            <div class="w-20 h-20 rounded bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center text-white">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-white">{{ $item->name ?? 'Nama Item' }}</p>
                                        <p class="text-app-muted text-sm mt-1">{{ $item->description ?? 'Deskripsi singkat item tidak tersedia.' }}</p>
                                        <div class="mt-2">
                                            <p class="text-white font-semibold">{{ isset($item->price) ? ('Rp ' . number_format($item->price, 2, ',', '.')) : '-' }}</p>
                                        </div>
                                    </div>
                                    {{-- added date at bottom-right --}}
                                    <div class="absolute right-4 bottom-3 text-app-muted text-xs">Added: {{ optional($item->created_at)->format('Y-m-d H:i') ?? '-' }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-app-muted">Tidak ada data `items` yang diberikan. Contoh preview:</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="bg-app-bg/50 p-4 rounded-lg border border-app-border">
                                <p class="font-semibold text-white">AK-47 | Redline</p>
                                <p class="text-app-muted text-sm">Skin battle-tested dengan hiasan garis merah.</p>
                            </div>
                            <div class="bg-app-bg/50 p-4 rounded-lg border border-app-border">
                                <p class="font-semibold text-white">Karambit | Fade</p>
                                <p class="text-app-muted text-sm">Pisau eksotis dengan gradasi warna.</p>
                            </div>
                            <div class="bg-app-bg/50 p-4 rounded-lg border border-app-border">
                                <p class="font-semibold text-white">Sticker | Champion</p>
                                <p class="text-app-muted text-sm">Stiker edisi khusus turnamen.</p>
                            </div>
                        </div>
                    @endif
                </div>

                </div>

                <!-- Users Detail -->
                <div x-show="openPanel === 'users'" x-transition>
                    @if(isset($activeUsers) && count($activeUsers))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($activeUsers as $user)
                                <div class="flex items-center justify-between gap-3 bg-app-bg/50 p-3 rounded-lg border border-app-border">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('storage/' . ($user->image ?? 'default.png')) }}" class="h-10 w-10 rounded-full object-cover border border-app-border" alt="user">
                                        <div>
                                            <p class="font-semibold text-white">{{ $user->nama ?? 'Nama Pengguna' }}</p>
                                            <p class="text-app-muted text-xs">{{ $user->email ?? 'email@contoh.test' }}</p>
                                            <p class="text-app-muted text-xs">Joined: {{ optional($user->created_at)->format('Y-m-d') ?? '-' }}</p>
                                            <p class="text-xs mt-1">
                                                @if(isset($user->is_active) && $user->is_active)
                                                    <span class="px-2 py-0.5 rounded bg-green-600 text-white text-xs">Active</span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded bg-gray-600 text-white text-xs">Inactive</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini? Tindakan tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm px-3 py-1 rounded-md bg-red-600/80 hover:bg-red-600 text-white">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-app-muted">Tidak ada data `activeUsers` yang diberikan. Contoh preview:</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="flex items-center gap-3 bg-app-bg/50 p-3 rounded-lg border border-app-border">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-pink-500 to-yellow-400"></div>
                                <div>
                                    <p class="font-semibold text-white">PlayerOne</p>
                                    <p class="text-app-muted text-xs">online</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 bg-app-bg/50 p-3 rounded-lg border border-app-border">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-app-accent to-app-accent/50"></div>
                                <div>
                                    <p class="font-semibold text-white">TraderGal</p>
                                    <p class="text-app-muted text-xs">online</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 bg-app-bg/50 p-3 rounded-lg border border-app-border">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-400"></div>
                                <div>
                                    <p class="font-semibold text-white">GamerX</p>
                                    <p class="text-app-muted text-xs">online</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Delete confirmation modal -->
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
@endsection
