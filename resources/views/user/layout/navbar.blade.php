<nav class="backdrop-blur-md h-20 flex items-center justify-between px-6 lg:px-8">
    <!-- Logo -->
    <a href="/" class="flex items-center gap-3">
        <i class="fas fa-crosshairs text-[#f97316] animate-pulse text-3xl drop-shadow-md"></i>
        <div class="leading-none">
            <div class="text-xl font-rajdhani font-bold text-white tracking-wider">TEMAN</div>
            <div class="text-sm text-app-muted">Marketplace</div>
        </div>
    </a>

    <!-- Auth Buttons -->
    <div class="flex items-center gap-4">
        @auth
            <div class="flex items-center gap-3">
                <span class="text-base app-text-muted hidden sm:inline">{{ Auth::user()->name ?? Auth::user()->nama }}</span>
                <a href="{{ route('logout') }}" class="btn ghost px-4 py-2 text-base">Logout</a>
            </div>
        @else
            <button id="openLogin" class="navbar-btn px-5 py-2.5 text-base">Sign In</button>
            <button id="openRegister" class="navbar-btn-outline px-5 py-2.5 text-base">Sign Up</button>
        @endauth
    </div>
</nav>
