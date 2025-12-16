
@extends('admin.layout.main')

@section('title', 'My Profile')
@section('content')
    <div class="max-w-5xl mx-auto pt-6">

        <!-- Form Wrapper -->
        <form role="form" action="{{ route('admin.profile') }}" method="post" enctype="multipart/form-data" id="profilForm">
            @csrf
            @method('PUT')

            <div class="flex flex-col lg:flex-row gap-6">

                <!-- Left Column: Avatar & Quick Info -->
                <div class="lg:w-1/3">
                    <div class="bg-app-card border border-app-border rounded-xl shadow-xl p-6 text-center relative overflow-hidden">
                        <!-- Background Decoration -->
                        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-b from-app-accent/20 to-transparent"></div>

                        <div class="relative z-10 mt-4">
                            <!-- Avatar Preview -->
                            <div class="relative inline-block group">
                                <div class="w-32 h-32 rounded-full p-1 bg-gradient-to-tr from-app-accent to-purple-600 mx-auto shadow-lg shadow-app-accent/20">
                                    <img class="img-preview w-full h-full rounded-full object-cover bg-app-bg border-4 border-app-card"
                                        src="{{ asset('storage/' . Auth::user()->image)}}"
                                        alt="User Avatar">
                                </div>

                                <!-- Camera Icon Overlay (Only appears when enabled via JS) -->
                                <label for="image" class="absolute bottom-2 right-2 bg-app-card border border-app-border text-white p-2 rounded-full shadow-md cursor-pointer hover:bg-app-accent transition-colors opacity-0 group-hover:opacity-100 input-file-trigger" style="pointer-events: none;">
                                    <i class="fas fa-camera text-xs"></i>
                                </label>
                            </div>

                            <h2 class="text-xl font-bold text-white mt-4">{{ Auth::user()->nama }}</h2>
                            <p class="text-sm text-app-muted">Admin Trader</p>
                            <div class="mt-4 flex justify-center gap-2">
                                <span class="bg-app-bg border border-app-border px-3 py-1 rounded-full text-xs text-app-success font-mono">
                                    Active
                                </span>
                            </div>

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

                    <!-- Action Buttons moved here for Mobile/Desktop consistency -->
                    <div class="bg-app-card border border-app-border rounded-xl p-4 mt-6 shadow-lg">
                        <button type="button" class="w-full py-3 rounded-lg font-bold transition-all duration-300 bg-app-cardHover text-app-muted hover:text-white hover:bg-app-border border border-app-border shadow-md" id="toggleButton">
                            <i class="fas fa-edit mr-2"></i> Click to Edit Profile
                        </button>

                        <button type="button" class="w-full py-2 mt-3 rounded-lg text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors hidden border border-transparent hover:border-red-500/30" id="batal">
                            <i class="fas fa-times mr-1"></i> Cancel Edit
                        </button>
                    </div>
                </div>

                <!-- Right Column: Details Form -->
                <div class="lg:w-2/3">
                    <div class="bg-app-card border border-app-border rounded-xl shadow-xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-app-border bg-app-bg/50">
                            <h3 class="text-lg font-semibold text-white">Account Detail</h3>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Nama -->
                            <div class="group">
                                <label for="nama" class="block text-sm font-medium text-app-muted mb-2">Name</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-app-muted"></i>
                                    </span>
                                    <input type="text"
                                        class="w-full bg-app-bg/50 border border-app-border rounded-lg py-2.5 pl-10 pr-4 text-white placeholder-gray-600 focus:outline-none focus:border-app-accent focus:ring-1 focus:ring-app-accent transition-all read-only:opacity-60 read-only:cursor-not-allowed read-only:focus:border-app-border read-only:focus:ring-0 @error('nama') border-red-500 focus:border-red-500 @enderror"
                                        name="nama" id="nama" placeholder="Full Name"
                                        value="{{ Auth::user()->nama }}" data-initial-value="{{ Auth::user()->nama }}" readonly>
                                </div>
                                @error('nama')
                                    <span id="nama-error" class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email (Always Readonly) -->
                            <div class="group opacity-70" title="Email cannot be changed">
                                <label for="email" class="block text-sm font-medium text-app-muted mb-2">Email Address</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-app-muted"></i>
                                    </span>
                                    <input type="email" class="w-full bg-app-bg/30 border border-app-border rounded-lg py-2.5 pl-10 pr-4 text-gray-400 cursor-not-allowed focus:outline-none"
                                        name="email" id="email" placeholder="Email"
                                        value="{{ Auth::user()->email }}" data-initial-value="{{ Auth::user()->email }}"
                                        readonly>
                                </div>
                                <p class="text-[10px] text-gray-600 mt-1">*Contact Super Admin to change email.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Definisi Class Tailwind untuk Toggle
            const btnDefaultClass = 'bg-app-cardHover text-app-muted hover:text-white hover:bg-app-border border-app-border';
            const btnActiveClass = 'bg-app-accent text-white hover:bg-app-accent border-transparent shadow-lg shadow-app-accent/20';

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

                // Toggle Readonly pada input (hanya nama yang tersisa untuk diedit)
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
                // Cek apakah tombol sedang dalam mode "Simpan" (type submit) atau "Edit" (type button)
                // Kita cek atribut type saja karena class stringnya panjang
                if ($(this).attr('type') === 'button') {
                    toggleEditMode(true, false);
                } else {
                    // Submit form
                    $('#profilForm').submit();
                }
            });

            $('#batal').click(function() {
                toggleEditMode(false, true);

                // Kembalikan nilai input ke nilai awal dari database
                $('#nama').val($('#nama').data('initial-value'));

                // Reset Input File
                $('#image').val('');

                // Kosongkan pratinjau gambar ke gambar asli
                // Ambil src dari hidden input oldImage atau data-default-src
                const defaultSrc = "{{ asset('storage/' . Auth::user()->image) }}";
                $('.img-preview').attr('src', defaultSrc);
            });
        });

        function previewImage() {
            const image = $('#image');
            const imgPreview = $('.img-preview');

            // Cek apakah ada file
            if (image[0].files && image[0].files[0]) {
                const file = image[0].files[0];
                const reader = new FileReader();

                reader.onload = function(event) {
                    imgPreview.attr('src', event.target.result);
                }

                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush
