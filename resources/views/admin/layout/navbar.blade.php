<nav class="bg-app-bg/80 backdrop-blur-md border-b border-app-border h-16 flex items-center justify-between px-4 lg:px-6 shadow-lg shadow-black/20">

    <!-- Left: Sidebar Toggle (Mobile) -->
    <div class="flex items-center">
        <button @click="sidebarOpen = !sidebarOpen" class="text-app-muted hover:text-white p-2 mr-4 lg:hidden focus:outline-none">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Logo Text (Visible on Mobile) -->
        <span class="text-lg font-bold text-white lg:hidden tracking-wider">
            TEMA <span class="text-app-brand">MARKET</span>
        </span>

        <!-- Desktop logo + small badge (matching login header) -->
        <div class="hidden lg:flex items-center gap-3 ml-3">
                    <i class="fas fa-crosshairs text-app-brand animate-pulse text-2xl drop-shadow-md"></i>
                    <div class="leading-none">
                        <div class="text-lg font-rajdhani font-bold text-white tracking-wider drop-shadow-md">TEMAN <span class="text-app-brand">MARKET</span></div>
                        <div class="mt-0 inline-block pb-0.5">
                            <span class="font-rajdhani text-xs font-bold tracking-[0.35em] text-app-brand uppercase">Admin</span>
                        </div>
                    </div>
        </div>

    </div>

    <!-- Right: Menu -->
    <ul class="flex items-center gap-4 lg:gap-6">

        {{-- <!-- Balance Display (Aesthetic Trading) -->
        <li class="hidden sm:flex items-center gap-2 bg-app-card border border-app-border px-3 py-1.5 rounded-full">
            <i class="fas fa-wallet text-app-success"></i>
            <span class="text-sm font-bold text-white">$ 14,250.00</span>
        </li> --}}

        <!-- Notification -->
        <li class="relative">
            <a href="#" class="text-app-muted hover:text-white transition-colors">
                <i class="fas fa-bell text-lg"></i>
                <span class="absolute -top-1 -right-1 w-2 h-2 bg-app-accent rounded-full animate-pulse"></span>
            </a>
        </li>

        <!-- Profile Dropdown (Menggunakan Alpine JS) -->
        <li class="relative" x-data="{ open: false }">
            <button
                @click="open = !open"
                @click.outside="open = false"
                class="flex items-center gap-3 focus:outline-none group">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold text-white group-hover:text-app-accent transition-colors">{{ Auth::user()->nama }}</p>
                    <p class="text-xs text-app-muted">Admin</p>
                </div>
                <img src="{{ asset('storage/' . Auth::user()->image) }}" class="h-9 w-9 rounded-full object-cover border-2 border-app-border group-hover:border-app-accent transition-colors" alt="User Image">
                <i class="fas fa-chevron-down text-xs text-app-muted transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <!-- Dropdown Content -->
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 mt-3 w-56 bg-app-card border border-app-border rounded-xl shadow-2xl py-2 z-50"
                style="display: none;">

                <div class="px-4 py-2 border-b border-app-border mb-2 md:hidden">
                    <p class="text-white font-bold">{{ Auth::user()->nama }}</p>
                    <p class="text-xs text-app-success font-mono">$ 14,250.00</p>
                </div>

                <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-2.5 text-sm text-app-muted hover:bg-app-cardHover hover:text-white transition-colors">
                    <i class="fas fa-user w-6 text-center mr-2"></i> My Profile
                </a>
                <a href="{{ route('admin.change-password') }}" class="flex items-center px-4 py-2.5 text-sm text-app-muted hover:bg-app-cardHover hover:text-white transition-colors">
                    <i class="fas fa-lock w-6 text-center mr-2"></i> Change Password
                </a>

                <div class="h-px bg-app-border my-2 mx-4"></div>

                <a href="{{ route('logout') }}" class="flex items-center px-4 py-2.5 text-sm text-app-danger hover:bg-red-500/10 transition-colors">
                    <i class="fas fa-sign-out-alt w-6 text-center mr-2"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
