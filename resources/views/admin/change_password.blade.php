
@extends('admin.layout.main')

@section('title', 'Change Password')
@section('content')
    <div class="max-w-4xl mx-auto pt-6">
        <div class="flex flex-col md:flex-row gap-6">

            <!-- Info Sidebar (Optional Decoration) -->
            <div class="md:w-1/3">
                <div class="bg-app-card border border-app-border rounded-xl p-6">
                    <div class="w-12 h-12 bg-app-accent/10 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-app-accent text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Security Settings</h3>
                    <p class="text-sm text-app-muted leading-relaxed">
                        Make sure your Account is secure. Use a strong password with combination of letters, numbers, and symbols to protect your Account.
                    </p>
                </div>
            </div>

            <!-- Form Area -->
            <div class="md:w-2/3">
                <div class="bg-app-card border border-app-border rounded-xl shadow-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-app-border flex justify-between items-center bg-app-bg/50">
                        <h3 class="text-lg font-semibold text-white">Change Password Form</h3>
                        <i class="fas fa-lock text-app-muted"></i>
                    </div>

                    <!-- form start -->
                    <form role="form" action="{{ route('admin.change-password') }}" method="post" id="gantiPasswordForm">
                        @csrf
                        <div class="p-6 space-y-5">

                            <!-- Password Saat Ini -->
                            <div class="group">
                                <label for="password-saat-ini" class="block text-sm font-medium text-app-muted mb-2">Current Password</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-app-muted group-focus-within:text-app-accent transition-colors"></i>
                                    </span>
                                    <input type="password"
                                        class="w-full bg-app-bg border border-app-border rounded-lg py-2.5 pl-10 pr-4 text-white placeholder-gray-600 focus:outline-none focus:border-app-accent focus:ring-1 focus:ring-app-accent transition-all @error('password_saat_ini') border-red-500 focus:border-red-500 @enderror"
                                        name="password_saat_ini" id="password-saat-ini" placeholder="Enter current password">
                                </div>
                                @error('password_saat_ini')
                                    <span id="password-saat-ini-error" class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password Baru -->
                            <div class="group" x-data="{ show: false }">
                                <label for="password-baru" class="block text-sm font-medium text-app-muted mb-2">New Password</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-app-muted group-focus-within:text-app-accent transition-colors"></i>
                                    </span>
                                    <input
                                        :type="show ? 'text' : 'password'"
                                        class="w-full bg-app-bg border border-app-border rounded-lg py-2.5 pl-10 pr-10 text-white placeholder-gray-600 focus:outline-none focus:border-app-accent focus:ring-1 focus:ring-app-accent transition-all @error('password_baru') border-red-500 focus:border-red-500 @enderror"
                                        name="password_baru" id="password-baru" placeholder="Enter new password">

                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-app-muted hover:text-app-accent transition-colors focus:outline-none">
                                        <i class="fas" :class="show ? 'fa-eye' : 'fa-eye-slash'"></i>
                                    </button>
                                </div>
                                @error('password_baru')
                                    <span id="password-baru-error" class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="group" x-data="{ show: false }">
                                <label for="konfirmasi-password" class="block text-sm font-medium text-app-muted mb-2">Password Confirmation</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-check-circle text-app-muted group-focus-within:text-app-accent transition-colors"></i>
                                    </span>
                                    <input
                                        :type="show ? 'text' : 'password'"
                                        class="w-full bg-app-bg border border-app-border rounded-lg py-2.5 pl-10 pr-10 text-white placeholder-gray-600 focus:outline-none focus:border-app-accent focus:ring-1 focus:ring-app-accent transition-all @error('konfirmasi_password') border-red-500 focus:border-red-500 @enderror"
                                        name="konfirmasi_password" id="konfirmasi-password" placeholder="Repeat new password">

                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-app-muted hover:text-app-accent transition-colors focus:outline-none">
                                        <i class="fas" :class="show ? 'fa-eye' : 'fa-eye-slash'"></i>
                                    </button>
                                </div>
                                @error('konfirmasi_password')
                                    <span id="konfirmasi-password-error" class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>
                        <!-- /.card-body -->

                        <div class="px-6 py-4 bg-app-bg/30 border-t border-app-border flex justify-end">
                            <button type="submit" class="bg-app-accent hover:bg-app-accent text-white font-medium py-2 px-6 rounded-lg shadow-lg shadow-app-accent/20 transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-app-accent ring-offset-app-bg">
                                <i class="fas fa-save mr-2"></i> Change
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#password-saat-ini').focus();
        });
    </script>
@endpush
