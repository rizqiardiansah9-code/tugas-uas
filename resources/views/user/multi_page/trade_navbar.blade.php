<nav class="backdrop-blur-md h-20 flex items-center justify-between px-6 lg:px-8 bg-app-bg/80">
    <!-- Left: Logo + small links -->
    <div class="flex items-center gap-4">
        <a href="/" class="flex items-center gap-3">
            <i class="fas fa-crosshairs text-[#f97316] animate-pulse text-3xl drop-shadow-md"></i>
            <div class="leading-none">
                <div class="text-xl font-rajdhani font-bold text-[#f97316] tracking-wider">TEMAN</div>
                <div class="text-sm text-app-muted">User</div>
            </div>
        </a>

        <div class="hidden md:flex items-center gap-6 ml-6">
            <a href="{{ route('user.trade') }}" class="text-sm font-semibold text-white">Trade</a>
            <a href="#" class="text-sm text-app-muted hover:text-white">Store</a>
            <a href="#" class="text-sm text-app-muted hover:text-white">Sell</a>
        </div>
    </div>

    <!-- Right: Profile / Auth (no notification) -->
    <div class="flex items-center gap-4">
        @auth
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 focus:outline-none">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-semibold text-white">{{ Auth::user()->nama ?? Auth::user()->name }}</p>
                        <p class="text-xs text-app-muted">User</p>
                    </div>
                    <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('assets/images/default-avatar.png') }}" class="h-9 w-9 rounded-full object-cover border-2 border-app-border transition-colors" alt="User Image">
                    <i class="fas fa-chevron-down text-xs text-app-muted"></i>
                </button>

                <div x-show="open" x-transition class="absolute right-0 mt-3 w-56 bg-app-card border border-app-border rounded-xl shadow-2xl py-2 z-50" style="display:none;">
                    <a href="#" class="flex items-center px-4 py-2.5 text-sm text-app-muted hover:bg-app-cardHover hover:text-white transition-colors">
                        <i class="fas fa-user w-6 text-center mr-2"></i> My Profile
                    </a>
                    <a href="#" class="flex items-center px-4 py-2.5 text-sm text-app-muted hover:bg-app-cardHover hover:text-white transition-colors">
                        <i class="fas fa-lock w-6 text-center mr-2"></i> Change Password
                    </a>

                    <div class="h-px bg-app-border my-2 mx-4"></div>

                    <a href="{{ route('logout') }}" class="flex items-center px-4 py-2.5 text-sm text-app-danger hover:bg-red-500/10 transition-colors">
                        <i class="fas fa-sign-out-alt w-6 text-center mr-2"></i> Logout
                    </a>
                </div>
            </div>
        @else
            <button id="openLogin" class="navbar-btn px-5 py-2.5 text-base" style="background: linear-gradient(90deg,#f97316,#ea580c);box-shadow:0 6px 20px rgba(249,115,22,0.25);">Sign In</button>
            <button id="openRegister" class="navbar-btn-outline px-5 py-2.5 text-base" style="border-color:#f97316;color:#f97316">Sign Up</button>
        @endauth
    </div>
</nav>
