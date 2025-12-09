<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teman ADMIN | Login CS2 Style</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Rajdhani:wght@500;600;700&display=swap"
        rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* BACKGROUND & UTILITIES */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            background-color: #0b0e11;
            background-image: radial-gradient(circle at 50% 0%, #1f1209 0%, #0b0e11 80%);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .font-rajdhani {
            font-family: 'Rajdhani', sans-serif;
        }

        /* Pattern Grid */
        .bg-grid-pattern {
            background-image: linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .weapon-bg {
            position: absolute;
            z-index: 0;
            opacity: 0.15;
            pointer-events: none;
            transition: all 0.5s ease;
            filter: grayscale(40%) contrast(120%);
        }

        body:hover .weapon-bg {
            opacity: 0.2;
            transform: scale(1.02);
        }

        .login-card {
            background: rgba(21, 25, 33, 0.85);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        body,
        body * {
            user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }

        input,
        input *,
        textarea,
        textarea * {
            user-select: text !important;
            -webkit-user-select: text !important;
            -ms-user-select: text !important;
        }
    </style>
</head>

<body
    class="h-screen w-full flex items-center justify-center relative text-white selection:bg-orange-500 selection:text-white">

    <!-- Background Grid -->
    <div class="absolute inset-0 bg-grid-pattern pointer-events-none"></div>

    <!-- Weapons -->
    <!-- Karambit (kanan atas) -->
    <img src="{{ asset('assets/images/Karambit-Marble-Fade.webp') }}" alt="Karambit"
        class="weapon-bg w-[120px] md:w-[180px] top-10 -right-1 rotate-[130deg] hidden md:block">

    <!-- AK-47 (kiri bawah) -->
    <img src="{{ asset('assets/images/AK-47-Jet-Set.webp') }}" alt="AK-47"
        class="weapon-bg w-[280px] md:w-[400px] -bottom-17 -left-16 rotate-[-15deg]">

    <!-- AWP (kanan bawah) -->
    <img src="{{ asset('assets/images/AWP-CMYK.webp') }}" alt="AWP"
        class="weapon-bg w-[200px] md:w-[300px] bottom-5 -right-0 rotate-[10deg] hidden lg:block">

    <!-- Login Form -->
    <div class="relative z-10 w-full max-w-[400px] p-4">

        <div class="login-card rounded-xl overflow-hidden relative">
            <div class="h-1 w-full bg-gradient-to-r from-orange-900 via-orange-500 to-orange-900"></div>

            <div class="p-8 pt-10">

                <!-- Logo -->
                <div class="text-center mb-10">
                    <h1
                        class="font-rajdhani text-4xl font-bold tracking-widest text-white flex items-center justify-center gap-3 drop-shadow-md">
                        <i class="fa-solid fa-crosshairs text-orange-500 animate-pulse"></i>
                        TEMAN
                    </h1>
                    <div class="mt-2 inline-block border-b border-orange-500/30 pb-1">
                        <span class="font-rajdhani text-lg font-bold tracking-[0.4em] text-orange-500 uppercase">
                            ADMIN
                        </span>
                    </div>
                </div>

                <!-- 🔥 ERROR MESSAGE AREA (NEW) -->
                @if (session('error'))
                    <div
                        class="mb-5 p-3 rounded border border-red-500 bg-red-600/10 text-red-400 font-rajdhani text-sm">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="mb-5 p-3 rounded border border-red-500 bg-red-600/10 text-red-400 font-rajdhani text-sm">
                        <i class="fa-solid fa-circle-xmark mr-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif
                <!-- END ERROR MESSAGE -->

                <!-- Form Input -->
                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Operator
                            E-Mail</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-envelope text-slate-500"></i>
                            </div>
                            <input type="email" name="email" required
                                class="block w-full pl-10 pr-3 py-3.5 bg-[#0b0e11]/90 border border-slate-700 rounded-lg text-sm text-gray-200 placeholder-slate-600 focus:border-orange-500 focus:ring-orange-500 font-rajdhani shadow-inner"
                                placeholder="Enter access email">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1">
                        <label
                            class="text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Passcode</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-key text-slate-500"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="block w-full pl-10 pr-10 py-3.5 bg-[#0b0e11]/90 border border-slate-700 rounded-lg text-sm text-gray-200 placeholder-slate-600 focus:border-orange-500 focus:ring-orange-500 font-rajdhani shadow-inner"
                                placeholder="••••••••">

                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 text-slate-500 hover:text-orange-400">
                                <i id="eyeIcon" class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Button -->
                    <button type="submit"
                        class="w-full group relative overflow-hidden rounded-lg bg-gradient-to-r from-orange-600 to-orange-500 p-0.5 hover:scale-[1.01] shadow-[0_0_20px_rgba(249,115,22,0.3)]">
                        <div
                            class="bg-[#0f1215] group-hover:bg-opacity-0 px-6 py-3.5 rounded-md transition-all flex items-center justify-center gap-2">
                            <span
                                class="font-rajdhani font-bold text-lg uppercase tracking-widest text-orange-500 group-hover:text-white">
                                Connect
                            </span>
                            <i
                                class="fa-solid fa-arrow-right text-orange-500 group-hover:text-white group-hover:translate-x-1 text-sm"></i>
                        </div>
                    </button>
                </form>
            </div>

            <!-- Footer Status -->
            <div
                class="bg-[#0b0e11] px-6 py-3 border-t border-white/5 flex justify-between items-center text-[10px] font-mono uppercase tracking-wider text-slate-600">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>System Online</span>
                </div>
                <span>Login for Admin</span>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>

</html>
