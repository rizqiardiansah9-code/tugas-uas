<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Teman')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root{
            --app-bg:#0b0e11;
            --app-card:#0f1720;
            --app-border:#21252b;
            --app-muted:#9aa3b2;
            --app-accent:#f97316;
            --app-accent-600:#ea580c;
            --app-accent-900:#7c2d00;
            --app-brand:#ff7a2d;
        }
        /* Basic typography & background */
        body{font-family:'Poppins',sans-serif;background:var(--app-bg);color:#fff;min-height:100vh}
        .font-rajdhani{font-family:'Rajdhani',sans-serif}

        /* Background subtle grid pattern (used in login theme) */
        .bg-grid-pattern{
            background-image: linear-gradient(rgba(255,255,255,0.01) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,0.01) 1px, transparent 1px);
            background-size: 46px 46px;
        }

        /* App tokens */
        .app-card{background:var(--app-card);border-color:var(--app-border)}
        .app-text-muted{color:var(--app-muted)}
        .app-accent{color:var(--app-accent)}

        /* Buttons */
        .btn{display:inline-flex;align-items:center;gap:.5rem;padding:.55rem .9rem;border-radius:.6rem;background:var(--app-accent);color:#fff;border:none;font-weight:600}
        .btn:hover{background:var(--app-accent-600);transform:translateY(-2px)}
        .btn.ghost{background:transparent;border:1px solid rgba(255,255,255,0.04);color:#fff}

        /* Navbar Buttons with Glow Effect */
        .navbar-btn {
            display:inline-flex;align-items:center;gap:.5rem;padding:.55rem .9rem;border-radius:.6rem;background:var(--app-accent);color:#fff;border:none;font-weight:600;
            box-shadow: 0 0 15px rgba(249, 115, 22, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .navbar-btn:hover {
            background:var(--app-accent-600);
            transform:translateY(-2px);
            box-shadow: 0 0 25px rgba(249, 115, 22, 0.7), 0 0 40px rgba(249, 115, 22, 0.4);
        }
        .navbar-btn:active {
            transform:translateY(0);
        }

        .navbar-btn-outline {
            display:inline-flex;align-items:center;gap:.5rem;padding:.55rem .9rem;border-radius:.6rem;background:transparent;color:#fff;border:2px solid var(--app-accent);font-weight:600;
            box-shadow: 0 0 12px rgba(249, 115, 22, 0.3), inset 0 0 12px rgba(249, 115, 22, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .navbar-btn-outline:hover {
            background:transparent;
            border-color:var(--app-accent);
            transform:translateY(-2px);
            box-shadow: 0 0 20px rgba(249, 115, 22, 0.6), inset 0 0 15px rgba(249, 115, 22, 0.2);
        }
        .navbar-btn-outline:active {
            transform:translateY(0);
        }

        /* Cards */
        .card{background:linear-gradient(180deg, rgba(255,255,255,0.02), transparent);border:1px solid rgba(255,255,255,0.03);border-radius:.75rem;padding:1rem;display:flex;gap:1rem;align-items:center;transition:transform .22s,box-shadow .22s}
        .card:hover{transform:translateY(-6px);box-shadow:0 10px 30px rgba(0,0,0,.6)}

        /* Modal */
        .modal-overlay{position:fixed;inset:0;background:linear-gradient(rgba(0,0,0,0.6),rgba(0,0,0,0.7));display:none;align-items:center;justify-content:center;z-index:60;opacity:0;transition:opacity .3s ease}
        .modal-overlay.open{display:flex;opacity:1}
        .modal{background:var(--app-card);border-radius:.75rem;max-width:920px;width:94%;max-height:86vh;overflow:auto;border:1px solid var(--app-border);box-shadow:0 30px 80px rgba(2,6,23,.7);transform:scale(0.85) translateY(20px);opacity:0;transition:transform .4s cubic-bezier(0.34, 1.56, 0.64, 1), opacity .3s ease}
        .modal-overlay.open .modal{transform:scale(1) translateY(0);opacity:1}

        /* Modal Login/Register Specific */
        #loginModalOverlay .modal, #registerModalOverlay .modal {
            max-width: 450px;
            transform: translateY(-100%);
            opacity: 0;
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
        }

        #loginModalOverlay.open .modal, #registerModalOverlay.open .modal {
            transform: translateY(0);
            opacity: 1;
        }

        @media(max-width: 640px) {
            #loginModalOverlay .modal, #registerModalOverlay .modal {
                max-width: 95vw;
                width: 95vw;
            }
        }

        /* Small helpers */
        .app-muted-sm{color:var(--app-muted);font-size:.95rem}
        .badge{display:inline-block;padding:.25rem .5rem;border-radius:.5rem;font-weight:700;background:rgba(255,255,255,0.03);color:#fff}
    </style>
</head>
<body>
    <div class="absolute inset-0 bg-grid-pattern pointer-events-none" aria-hidden="true"></div>

    {{-- Allow pages to override navbar. If not provided, include default navbar --}}
    @hasSection('navbar')
        @yield('navbar')
    @else
        @include('user.layout.navbar')
    @endif

    <main class="relative z-10 container-fluid pt-6 px-6" style="max-width:1200px;margin:0 auto">
        @yield('content')
    </main>

    @unless(View::hasSection('hideFooter'))
        @include('user.layout.footer')
    @endunless

    {{-- Modals (login/register/item) --}}
    @includeIf('user.layout.partials.modals')

    <script>
        // simple modal helpers reused in partials
        function openModal(id){document.getElementById(id).classList.add('open');document.getElementById(id).setAttribute('aria-hidden','false')}
        function closeModal(id){document.getElementById(id).classList.remove('open');document.getElementById(id).setAttribute('aria-hidden','true')}

        // Check if user is authenticated
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

        document.addEventListener('click', function(e){
            var t = e.target;

            // Login/Register button handlers
            if(t.id === 'openLogin') openModal('loginModalOverlay');
            if(t.id === 'openRegister') openModal('registerModalOverlay');

            // Close modal handler
            var closeAttr = t.dataset && t.dataset.close;
            if(closeAttr) closeModal(closeAttr);

            // Item card click handler - check login first
            if(t.closest && t.closest('.card')){
                var card = t.closest('.card');
                if(card.dataset.itemTitle){
                    // Check if user is logged in
                    if(!isLoggedIn){
                        alert('You must log in first to view item details.');
                        openModal('loginModalOverlay');
                        return;
                    }

                    // If logged in, show item detail
                    document.getElementById('itemTitle').textContent = card.dataset.itemTitle;
                    document.getElementById('itemTitle2').textContent = card.dataset.itemTitle;
                    document.getElementById('itemDesc').textContent = card.dataset.itemDesc || '';
                    document.getElementById('itemPrice').textContent = card.dataset.itemPrice || '-';
                    var img = document.getElementById('itemImage');
                    if(card.dataset.itemImage) img.src = card.dataset.itemImage; else img.src = '';
                    openModal('itemModalOverlay');
                }
            }
        });
        document.querySelectorAll('.modal-overlay').forEach(function(ov){ov.addEventListener('click', function(e){ if(e.target===ov) closeModal(ov.id); });});
    </script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    @stack('scripts')
</body>
</html>
