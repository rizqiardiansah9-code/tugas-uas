@extends('user.layout.main')

@section('title', 'My Profile')

@section('navbar')
  @include('user.multi_page.multi_navbar')
@endsection

@section('content')
<style>
    :root{
      --bg:#0e1015; --card:#151823; --card-inner:#1c202e;
      --primary:#4c6fff; --gray:#aab0d5;
    }
    
    /* GRID */
    .catalog-grid {
      display:grid;
      grid-template-columns:repeat(auto-fill,minmax(140px,1fr));
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

    /* HOVER */
    .item-details:hover .item-image{
      transform:translateY(-8px) scale(.92);
    }
    
    .item-details:hover .indicators{
      transform:translateY(-36px);
    }
</style>

<div class="max-w-5xl mx-auto pt-6 px-6 pb-20 text-white">

    <!-- Form Wrapper -->
    <form role="form" action="{{ route('user.profil.update') }}" method="post" enctype="multipart/form-data" id="profilForm">
        @csrf
        @method('PUT')

        <div class="flex flex-col lg:flex-row gap-6">

            <!-- Left Column: Avatar & Quick Info -->
            <div class="lg:w-1/3">
                <div class="bg-[#12141c] border border-white/5  shadow-xl p-6 text-center relative overflow-hidden">
                    <!-- Background Decoration -->
                    <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-b from-[#f97316]/20 to-transparent"></div>

                    <div class="relative z-10 mt-4">
                        <!-- Avatar Preview -->
                        <div class="relative inline-block group">
                            <div class="w-32 h-32 rounded-full p-1 bg-gradient-to-tr from-[#f97316] to-purple-600 mx-auto shadow-lg shadow-[#f97316]/20">
                                <img class="img-preview w-full h-full rounded-full object-cover bg-[#0e1015] border-4 border-[#12141c]"
                                    src="{{ asset('storage/' . Auth::user()->image)}}"
                                    alt="User Avatar">
                            </div>

                            <!-- Camera Icon Overlay -->
                            <label for="image" class="absolute bottom-2 right-2 bg-[#12141c] border border-white/10 text-white p-2 rounded-full shadow-md cursor-pointer hover:bg-[#f97316] transition-colors opacity-0 group-hover:opacity-100 input-file-trigger" style="pointer-events: none;">
                                <i class="fas fa-camera text-xs"></i>
                            </label>
                        </div>

                        <h2 class="text-xl font-bold text-white mt-4">{{ Auth::user()->nama }}</h2>
                        <p class="text-sm text-gray-400">Trader</p>
                        
                        <!-- Hidden File Input -->
                        <input type="hidden" name="oldImage" value="{{Auth::user()->image }}">
                        <input type="file" class="hidden @error('image') is-invalid @enderror"
                                id="image" name="image" data-default-src="{{ Auth::user()->image }}"
                                accept="image/x-png,image/jpg,image/jpeg" onchange="previewImage()" disabled>

                        @error('image')
                            <div class="text-red-500 text-xs mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-[#12141c] border border-white/5  p-4 mt-6 shadow-lg">
                    <button type="button" class="w-full py-3  font-bold transition-all duration-300 bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 border border-white/5 shadow-md" id="toggleButton">
                        <i class="fas fa-edit mr-2"></i> Click to Edit Profile
                    </button>

                    <button type="button" class="w-full py-2 mt-3  text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors hidden border border-transparent hover:border-red-500/30" id="batal">
                        <i class="fas fa-times mr-1"></i> Cancel Edit
                    </button>
                </div>
            </div>

            <!-- Right Column: Details Form -->
            <div class="lg:w-2/3 space-y-8">
                <div class="bg-[#12141c] border border-white/5  shadow-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 bg-white/5">
                        <h3 class="text-lg font-rajdhani font-bold text-white uppercase tracking-wider">Account Detail</h3>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Nama -->
                        <div class="group">
                            <label for="nama" class="block text-label mb-2">Name</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-500"></i>
                                </span>
                                <input type="text"
                                    class="w-full bg-[#0e1015] border border-white/10  py-2.5 pl-10 pr-4 text-white placeholder-gray-600 focus:outline-none focus:border-[#f97316] focus:ring-1 focus:ring-[#f97316] transition-all read-only:opacity-60 read-only:cursor-not-allowed read-only:focus:border-white/10 read-only:focus:ring-0 @error('nama') border-red-500 focus:border-red-500 @enderror font-medium"
                                    name="nama" id="nama" placeholder="Full Name"
                                    value="{{ Auth::user()->nama }}" data-initial-value="{{ Auth::user()->nama }}" readonly>
                            </div>
                            @error('nama')
                                <span id="nama-error" class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="group opacity-70" title="Email cannot be changed">
                            <label for="email" class="block text-label mb-2">Email Address</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-500"></i>
                                </span>
                                <input type="email" class="w-full bg-[#0e1015] border border-white/10  py-2.5 pl-10 pr-4 text-gray-500 cursor-not-allowed focus:outline-none"
                                    name="email" id="email" placeholder="Email"
                                    value="{{ Auth::user()->email }}" data-initial-value="{{ Auth::user()->email }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INVENTORY SECTION -->
                <div class="bg-[#12141c] border border-white/5  shadow-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 bg-white/5 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-rajdhani font-bold text-white uppercase tracking-wider">My Inventory</h3>
                            <span class="text-xs bg-white/10 px-2 py-1 rounded text-gray-400 font-bold">{{ $inventory->count() }} Items</span>
                        </div>
                        <button type="button" onclick="openAddItemModal()" class="text-sm bg-green-600/20 text-green-400 hover:bg-green-600/30 hover:text-green-300 border border-green-600/20 px-3 py-1.5  transition-all flex items-center gap-2 font-bold uppercase tracking-wider text-xs">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>

                    <div class="p-6">
                        @if($inventory->count() > 0)
                            <div class="catalog-grid">
                                @foreach($inventory as $item)
                                    <div class="catalog-col">
                                        <div class="item-cell">
                                            <div class="item-details">
                                                <img class="item-image" src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}" alt="{{ $item->name }}">
                                                <div class="indicators">
                                                    <div class="item-name font-bold text-sm text-gray-200 tracking-tight">{{ $item->name }}</div>
                                                    <div class="price font-rajdhani font-bold text-[#f97316]">${{ number_format($item->price, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10 text-gray-400">
                                <i class="fas fa-box-open text-4xl mb-3 block opacity-50"></i>
                                <p>Your inventory is empty.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Add Item Modal -->
    <div id="addItemModal" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
        
        <!-- Modal Content -->
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-[#151823] border border-white/10  shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-300" id="modalContent">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-white">Add New Item</h3>
                        <button type="button" onclick="closeAddItemModal()" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form action="{{ route('user.profil.inventory.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <!-- Item Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1.5">Item Name</label>
                                <input type="text" name="name" required
                                    class="w-full bg-[#0e1015] border border-white/10  py-2.5 px-4 text-white focus:outline-none focus:border-[#f97316] focus:ring-1 focus:ring-[#f97316] transition-all"
                                    placeholder="e.g. Dragon Lore">
                            </div>

                            <!-- Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1.5">Price ($)</label>
                                <input type="number" step="0.01" name="price" required
                                    class="w-full bg-[#0e1015] border border-white/10  py-2.5 px-4 text-white focus:outline-none focus:border-[#f97316] focus:ring-1 focus:ring-[#f97316] transition-all"
                                    placeholder="0.00">
                            </div>

                            <!-- Category & Rarity -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Category</label>
                                    <select name="category_id" required
                                        class="w-full bg-[#0e1015] border border-white/10  py-2.5 px-4 text-white focus:outline-none focus:border-[#f97316] focus:ring-1 focus:ring-[#f97316] transition-all appearance-none cursor-pointer">
                                        <option value="" disabled selected>Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Rarity</label>
                                    <select name="rarity" required
                                        class="w-full bg-[#0e1015] border border-white/10  py-2.5 px-4 text-white focus:outline-none focus:border-[#f97316] focus:ring-1 focus:ring-[#f97316] transition-all appearance-none cursor-pointer">
                                        <option value="" disabled selected>Select Rarity</option>
                                        <option value="common" class="text-gray-400">Common</option>
                                        <option value="uncommon" class="text-blue-400">Uncommon</option>
                                        <option value="rare" class="text-blue-600">Rare</option>
                                        <option value="mythical" class="text-purple-500">Mythical</option>
                                        <option value="legendary" class="text-pink-500">Legendary</option>
                                        <option value="ancient" class="text-red-500">Ancient</option>
                                        <option value="exceedingly_rare" class="text-yellow-500">Exceedingly Rare</option>
                                        <option value="immortal" class="text-yellow-600">Immortal</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1.5">Item Image</label>
                                <input type="file" name="image" accept="image/*"
                                    class="w-full bg-[#0e1015] border border-white/10  text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-white/5 file:text-white hover:file:bg-white/10 transition-all">
                            </div>
                            
                            <!-- Description (Optional) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1.5">Description (Optional)</label>
                                <textarea name="description" rows="3"
                                    class="w-full bg-[#0e1015] border border-white/10  py-2.5 px-4 text-white focus:outline-none focus:border-[#f97316] focus:ring-1 focus:ring-[#f97316] transition-all"
                                    placeholder="Item description..."></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button type="button" onclick="closeAddItemModal()" class="flex-1 py-2.5  font-medium text-gray-400 hover:bg-white/5 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 py-2.5  font-bold bg-[#f97316] text-white hover:bg-[#ea580c] shadow-lg shadow-[#f97316]/20 transition-all">
                                Add Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const btnDefaultClass = 'bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 border-white/5';
        const btnActiveClass = 'bg-[#f97316] text-white hover:bg-[#ea580c] border-transparent shadow-lg shadow-[#f97316]/20';

        function toggleEditMode(isEditMode, isCancel) {
            const btn = $('#toggleButton');

                if(isEditMode) {
                // Enter Edit Mode
                btn.removeClass(btnDefaultClass).addClass(btnActiveClass);
                btn.html('<i class="fas fa-save mr-2"></i> Save Changes');
                btn.attr('type', 'submit');

                $('#batal').removeClass('hidden');
                $('.input-file-trigger').css('pointer-events', 'auto').addClass('opacity-100 cursor-pointer');
            } else {
                // Keluar Mode Edit
                btn.removeClass(btnActiveClass).addClass(btnDefaultClass);
                btn.html('<i class="fas fa-edit mr-2"></i> Click to Edit Profile');
                btn.attr('type', 'button');

                $('#batal').addClass('hidden');
                $('.input-file-trigger').css('pointer-events', 'none').removeClass('opacity-100 cursor-pointer');
            }

            // Toggle Readonly pada input
            $('#nama').prop('readonly', !isEditMode);

            // Focus ke nama jika mode edit
            if(isEditMode) $('#nama').focus();

            // Toggle Disabled pada file input
            if (isEditMode || isCancel) {
                $('#image').prop('disabled', !isEditMode);
            }
        }

        $('#toggleButton').click(function(e) {
            e.preventDefault();
            if ($(this).attr('type') === 'button') {
                toggleEditMode(true, false);
            } else {
                $('#profilForm').submit();
            }
        });

        $('#batal').click(function() {
            toggleEditMode(false, true);
            $('#nama').val($('#nama').data('initial-value'));
            $('#image').val('');
            const defaultSrc = "{{ asset('storage/' . Auth::user()->image) }}";
            $('.img-preview').attr('src', defaultSrc);
        });
    });

    function previewImage() {
        const image = $('#image');
        const imgPreview = $('.img-preview');
        if (image[0].files && image[0].files[0]) {
            const file = image[0].files[0];
            const reader = new FileReader();
            reader.onload = function(event) {
                imgPreview.attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    }

    // Modal Functions
    function openAddItemModal() {
        const modal = document.getElementById('addItemModal');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('modalContent');
        
        modal.classList.remove('hidden');
        // Small delay to allow display:block to apply before opacity transition
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            content.classList.remove('opacity-0', 'scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function closeAddItemModal() {
        const modal = document.getElementById('addItemModal');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('modalContent');
        
        backdrop.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('opacity-0', 'scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300); // Match transition duration
    }

    // Close modal on backdrop click
    document.getElementById('modalBackdrop').addEventListener('click', closeAddItemModal);
</script>
@endpush
@endsection
