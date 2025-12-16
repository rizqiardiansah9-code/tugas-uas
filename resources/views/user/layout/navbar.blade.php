<nav class="sticky top-0 z-50 w-full bg-app-bg/80 backdrop-blur-md h-20 flex items-center justify-between px-6 lg:px-8 shadow-lg shadow-black/20 border-b border-white/5">
    <!-- Logo -->
    <div class="flex items-center gap-3">
        <i class="fas fa-crosshairs text-[#f97316] animate-pulse text-3xl drop-shadow-md"></i>
        <div class="leading-none">
            <div class="text-xl font-rajdhani font-bold text-white tracking-wider">TEMAN</div>
            <div class="text-sm text-app-muted">Marketplace</div>
        </div>
    </div>

    <!-- Auth Buttons -->
    <div class="flex items-center gap-4">
        @auth
            <div class="flex items-center gap-3">
                <span class="text-base app-text-muted hidden sm:inline">{{ Auth::user()->name ?? Auth::user()->nama }}</span>
                <a href="{{ route('logout') }}" 
                   onclick="localStorage.removeItem('store_cart'); localStorage.removeItem('trade_my_offer'); localStorage.removeItem('trade_their_offer');"
                   class="btn ghost px-4 py-2 text-base">Logout</a>
            </div>
        @else
            <button id="openLogin" class="navbar-btn px-5 py-2.5 text-base font-rajdhani font-bold uppercase tracking-wider">Sign In</button>
            <button id="openRegister" class="navbar-btn-outline px-5 py-2.5 text-base font-rajdhani font-bold uppercase tracking-wider">Sign Up</button>
        @endauth
    </div>
</nav>
