<nav
    class="sticky top-0 z-[100] w-full backdrop-blur-md h-20 flex items-center justify-between px-6 lg:px-8 bg-app-bg/80 border-b border-white/5 shadow-lg shadow-black/20">
    <!-- Left: Logo + small links -->
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('user.index') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <i class="fas fa-crosshairs text-[#f97316] animate-pulse text-3xl drop-shadow-md"></i>
                <div class="leading-none">
                    <div class="text-xl font-rajdhani font-bold text-[#f97316] tracking-wider">TEMAN</div>
                    <div class="text-sm text-app-muted">User</div>
                </div>
            </a>
        </div>

        <div class="hidden md:flex items-center gap-6 ml-6">
            <a href="{{ route('user.trade') }}"
                class="text-sm font-bold uppercase tracking-wider transition-colors {{ Route::is('user.trade') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Trade</a>
            <a href="{{ route('user.store') }}"
                class="text-sm font-bold uppercase tracking-wider transition-colors {{ Route::is('user.store') ? 'text-white' : 'text-gray-400 hover:text-white' }}">Store</a>
            <a href="#"
                class="text-sm font-bold uppercase tracking-wider transition-colors text-gray-400 hover:text-white">Sell</a>
        </div>
    </div>

    <!-- Right: Profile / Auth (no notification) -->
    <div class="flex items-center gap-4">
        @auth
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="flex items-center gap-3 focus:outline-none">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-semibold text-white">{{ Auth::user()->nama ?? Auth::user()->name }}</p>
                        <p class="text-xs text-app-muted">User</p>
                    </div>
                    <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('assets/images/default-avatar.png') }}"
                        class="h-9 w-9 rounded-full object-cover border-2 border-app-border transition-colors"
                        alt="User Image">
                    <i class="fas fa-chevron-down text-xs text-app-muted"></i>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 mt-3 w-56 bg-[#151823] border border-white/5 rounded-xl shadow-2xl py-2 z-[100]"
                    style="display:none;">

                    <div class="px-4 py-2 border-b border-white/5 mb-2 md:hidden">
                        <p class="text-white font-bold">{{ Auth::user()->nama ?? Auth::user()->name }}</p>
                        <p class="text-xs text-[#22c55e] font-mono">$ 0.00</p>
                    </div>

                    <a href="{{ route('user.profil') }}"
                        class="flex items-center px-4 py-2.5 text-sm text-gray-400 hover:bg-[#1c202e] hover:text-white transition-colors">
                        <i class="fas fa-user w-6 text-center mr-2"></i> My Profile
                    </a>
                    <a href="{{ route('user.change-password') }}"
                        class="flex items-center px-4 py-2.5 text-sm text-gray-400 hover:bg-[#1c202e] hover:text-white transition-colors">
                        <i class="fas fa-lock w-6 text-center mr-2"></i> Change Password
                    </a>

                    <div class="h-px bg-white/5 my-2 mx-4"></div>

                    <a href="{{ route('logout') }}"
                        onclick="localStorage.removeItem('store_cart'); localStorage.removeItem('trade_my_offer'); localStorage.removeItem('trade_their_offer');"
                        class="flex items-center px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition-colors">
                        <i class="fas fa-sign-out-alt w-6 text-center mr-2"></i> Logout
                    </a>
                </div>
            </div>
        @else
            <button id="openLogin" class="navbar-btn px-5 py-2.5 text-base font-rajdhani font-bold uppercase tracking-wider"
                style="background: linear-gradient(90deg,#f97316,#ea580c);box-shadow:0 6px 20px rgba(249,115,22,0.25);">Sign
                In</button>
            <button id="openRegister"
                class="navbar-btn-outline px-5 py-2.5 text-base font-rajdhani font-bold uppercase tracking-wider"
                style="border-color:#f97316;color:#f97316">Sign Up</button>
        @endauth
    </div>
</nav>
