@extends('user.layout.main')

@section('title', 'Change Password')

@section('navbar')
  @include('user.multi_page.multi_navbar')
@endsection

@section('content')
<style>
    :root{
      --bg:#0e1015; --card:#151823; --card-inner:#1c202e;
      --primary:#4c6fff; --gray:#aab0d5;
    }
</style>

<div class="max-w-4xl mx-auto pt-6 px-6 pb-20 text-white">
    <div class="flex flex-col md:flex-row gap-6">

        <!-- Info Sidebar -->
        <div class="md:w-1/3">
            <div class="bg-[#12141c] border border-white/5  p-6 shadow-xl">
                <div class="w-12 h-12 bg-[#f97316]/10  flex items-center justify-center mb-4">
                    <i class="fas fa-shield-alt text-[#f97316] text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2 font-rajdhani uppercase tracking-wider">Security Settings</h3>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Ensure your account stays secure. We recommend using a strong password combining letters, numbers, and symbols.
                </p>
                
                <div class="mt-6 pt-6 border-t border-white/5">
                    <a href="{{ route('user.profil') }}" class="flex items-center text-sm text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Area -->
        <div class="md:w-2/3">
            <div class="bg-[#12141c] border border-white/5  shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 bg-white/5 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-white font-rajdhani uppercase tracking-wide">Change Password</h3>
                    <i class="fas fa-lock text-gray-500"></i>
                </div>

                <form action="{{ route('user.change-password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-6">

                        <!-- Current Password -->
                        <div class="group" x-data="{ show: false }">
                            <label for="current_password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Current Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-slate-500"></i>
                                </span>
                                <input :type="show ? 'text' : 'password'"
                                    class="w-full bg-[#0b0e11]/90 border border-slate-700  py-3 pl-10 pr-10 text-gray-200 placeholder-slate-600 focus:outline-none focus:border-[#f97316] focus:ring-1 focus:ring-[#f97316] transition-all font-rajdhani shadow-inner @error('current_password') border-red-500 focus:border-red-500 @enderror"
                                    name="current_password" id="current_password" placeholder="Enter current password">
                                    
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-slate-500 hover:text-[#f97316] transition-colors focus:outline-none">
                                    <i class="fas" :class="show ? 'fa-eye' : 'fa-eye-slash'"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <span class="text-[10px] text-red-500 mt-1 block tracking-wide">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="group" x-data="{ show: false }">
                            <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">New Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-slate-500"></i>
                                </span>
                                <input :type="show ? 'text' : 'password'"
                                    class="w-full bg-[#0b0e11]/90 border border-slate-700  py-3 pl-10 pr-10 text-gray-200 placeholder-slate-600 focus:outline-none focus:border-[#f97316] focus:ring-1 focus:ring-[#f97316] transition-all font-rajdhani shadow-inner @error('password') border-red-500 focus:border-red-500 @enderror"
                                    name="password" id="password" placeholder="Enter new password">
                                    
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-slate-500 hover:text-[#f97316] transition-colors focus:outline-none">
                                    <i class="fas" :class="show ? 'fa-eye' : 'fa-eye-slash'"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-[10px] text-red-500 mt-1 block tracking-wide">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="group" x-data="{ show: false }">
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Confirm Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-check-circle text-slate-500"></i>
                                </span>
                                <input :type="show ? 'text' : 'password'"
                                    class="w-full bg-[#0b0e11]/90 border border-slate-700  py-3 pl-10 pr-10 text-gray-200 placeholder-slate-600 focus:outline-none focus:border-[#f97316] focus:ring-1 focus:ring-[#f97316] transition-all font-rajdhani shadow-inner"
                                    name="password_confirmation" id="password_confirmation" placeholder="Repeat new password">
                                    
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-slate-500 hover:text-[#f97316] transition-colors focus:outline-none">
                                    <i class="fas" :class="show ? 'fa-eye' : 'fa-eye-slash'"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="px-6 py-4 bg-white/5 border-t border-white/5 flex justify-end">
                        <button type="submit" class="bg-[#f97316] hover:bg-[#ea580c] text-white font-bold py-2.5 px-6  shadow-lg shadow-[#f97316]/20 transition-all transform hover:scale-[1.02] active:scale-[0.98] font-rajdhani tracking-wide flex items-center gap-2">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
