<!-- Item Detail Modal -->
<div id="itemModalOverlay" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true">
    <div class="px-6 py-4 border-b border-app-border flex items-center justify-between">
      <strong id="itemTitle" class="text-xl font-bold app-accent">Item Details</strong>
      <button class="close-x text-2xl" data-close="itemModalOverlay">×</button>
    </div>

    <div class="modal-body px-6 py-6">
      <div class="flex gap-6 flex-wrap">
        <img id="itemImage" src="" alt="" class="w-56 h-40 object-cover rounded-lg border border-app-border">
        <div class="flex-1 min-w-[220px]">
          <h3 id="itemTitle2" class="text-2xl font-semibold mb-2"></h3>
          <p id="itemDesc" class="app-text-muted mb-4 leading-relaxed"></p>
          <p class="text-lg font-bold text-app-accent">Price: <span id="itemPrice" class="text-white">-</span></p>
        </div>
      </div>
    </div>

    <div class="modal-footer px-6 py-4 border-t border-app-border text-right">
      <button class="btn ghost" data-close="itemModalOverlay">Close</button>
    </div>
  </div>
</div>

<!-- Login Modal -->
<div id="loginModalOverlay" class="modal-overlay" aria-hidden="true">
  <div class="modal relative z-20"
       role="dialog"
       aria-modal="true"
       style="
          background: rgba(15, 23, 32, 0.7);
          backdrop-filter: blur(12px);
          border: 2px solid #f97316;
          border-radius: 12px;
          width: 100%;
          max-width: 360px;
          box-shadow: 0 0 35px rgba(249, 115, 22, 0.35);
       ">

    <!-- FORM -->
    <form id="loginForm" method="POST" action="{{ route('login') }}"
        class="px-6 py-6 space-y-4">
      <h2 class="text-2xl font-bold text-white text-center mb-5">Sign Up</h2>
      @csrf
      <!-- Intent: specify this login is for regular users (prevents admin login here) -->
      <input type="hidden" name="intent" value="user">

      <!-- Email -->
      <div class="group">
        <label class="text-xs text-gray-400 block mb-3" style="letter-spacing:1px;">
          Email
        </label>

        <div class="relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600">
            <i class="fas fa-envelope"></i>
          </span>

          <input id="login_email" name="email" type="email" required
                 placeholder="email@gmail.com"
                 class="w-full pl-12 pr-4 py-3.5 rounded-lg bg-white text-gray-800
                 placeholder-gray-500 focus:outline-none focus:ring-2
                 focus:ring-orange-400 focus:ring-offset-2 focus:ring-offset-gray-900
                 transition-all duration-300 hover:bg-gray-50">
        </div>

        @error('email')
          <p class="text-red-400 text-xs mt-2 animate-pulse">{{ $message }}</p>
        @enderror
      </div>

      <!-- Password -->
      <div class="group">
        <label class="text-xs text-gray-400 block mb-3" style="letter-spacing:1px;">
          Passcode
        </label>

        <div class="relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600">
            <i class="fas fa-lock"></i>
          </span>

          <input id="login_password" type="password" name="password"
                 required placeholder="••••••••"
                 class="w-full pl-12 pr-12 py-3.5 rounded-lg bg-white text-gray-800
                 placeholder-gray-500 focus:outline-none focus:ring-2
                 focus:ring-orange-400 focus:ring-offset-2 focus:ring-offset-gray-900
                 transition-all duration-300 hover:bg-gray-50">

          <!-- Eye Icon -->
          <button type="button"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-orange-400 transition-colors toggle-eye"
                  data-target="login_password" aria-label="Toggle password visibility">
            <i class="fas fa-eye"></i>
          </button>
        </div>

        @error('password')
          <p class="text-red-400 text-xs mt-2 animate-pulse">{{ $message }}</p>
        @enderror
      </div>

      <!-- Status -->
      <div class="flex items-center justify-between pt-6 pb-4" style="border-top:1px solid #333;">
        <span class="flex items-center text-xs text-gray-400">
          <span class="inline-block w-2.5 h-2.5 bg-green-400 rounded-full mr-2 animate-pulse"></span>
          SYSTEM ONLINE
        </span>

        <span class="text-xs text-gray-400" style="letter-spacing:1px;">
          LOGIN FOR USER
        </span>
      </div>

      <!-- CONNECT BUTTON -->
      <button type="submit"
              class="w-full py-3.5 font-black text-sm uppercase tracking-widest transition-all duration-300"
              style="
                  border:2px solid #f97316;
                  color:#f97316;
                  border-radius:6px;
                  background:transparent;
              "
              onmouseover="this.style.backgroundColor='#f97316'; this.style.color='white'; this.style.boxShadow='0 0 20px rgba(249,115,22,0.6)'; this.style.transform='translateY(-2px)'"
              onmouseout="this.style.backgroundColor='transparent'; this.style.color='#f97316'; this.style.boxShadow='none'; this.style.transform='translateY(0)'">
        CONNECT →
      </button>

      <!-- Register Link -->
      <div class="text-center">
        <span class="text-xs text-gray-400">
          Don't have an account?
          <button type="button"
                  onclick="closeModal('loginModalOverlay'); setTimeout(()=>openModal('registerModalOverlay'),220);"
                  class="font-bold transition-colors"
                  style="color:#f97316;">
            REGISTER HERE
          </button>
        </span>
      </div>
    </form>

    <!-- CLOSE BUTTON -->
    <button data-close="loginModalOverlay"
            class="absolute top-5 right-5 text-gray-400 hover:text-orange-400 text-2xl">
      ×
    </button>

  </div>
</div>

<!-- Register Modal -->
<div id="registerModalOverlay" class="modal-overlay" aria-hidden="true">
  <div class="modal relative z-20"
       role="dialog"
       aria-modal="true"
       style="
          background: rgba(15, 23, 32, 0.7);
          backdrop-filter: blur(12px);
          border: 2px solid #f97316;
          border-radius: 12px;
          width: 100%;
          max-width: 380px;
          max-height: 95vh;
          display: flex;
          flex-direction: column;
          box-shadow: 0 0 35px rgba(249, 115, 22, 0.35);
       ">

    <!-- Header Section -->
    <div class="px-6 py-5 flex-shrink-0">
      <h2 class="text-2xl font-bold text-white text-center">Register Member</h2>
    </div>

    <!-- FORM - Scrollable Content -->
    <form id="registerForm" method="POST" action="{{ route('register') }}"
          class="px-5 py-5 overflow-y-auto flex-1">
      @csrf
      <div class="space-y-3">

        <!-- Nama -->
        <div class="group">
          <label class="text-xs font-bold text-gray-400 uppercase block mb-2" style="letter-spacing:1px;">
            Full Name <span class="text-red-500">*</span>
          </label>
          <input id="reg_name" type="text" name="name" value="{{ old('name') }}" required placeholder="Enter full name"
                 class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm transition-all duration-300 hover:bg-gray-50">
          @error('name')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Alamat -->
        <div class="group">
          <label class="text-xs font-bold text-gray-400 uppercase block mb-2" style="letter-spacing:1px;">
            Address <span class="text-red-500">*</span>
          </label>
          <input id="reg_alamat" type="text" name="alamat" value="{{ old('alamat') }}" required placeholder="Enter address"
                 class="w-full px-4 py-2.5 rounded-lg bg-white text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm transition-all duration-300 hover:bg-gray-50">
          @error('alamat')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Email -->
        <div class="group">
          <label class="text-xs font-bold text-gray-400 uppercase block mb-2" style="letter-spacing:1px;">
            Email <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <span class="absolute left-4 top-2.5 text-gray-600"><i class="fas fa-envelope text-sm"></i></span>
            <input id="reg_email" type="email" name="email" value="{{ old('email') }}" required placeholder="email@gmail.com"
                   class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-white text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm transition-all duration-300 hover:bg-gray-50">
          </div>
          @error('email')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div class="group">
          <label class="text-xs font-bold text-gray-400 uppercase block mb-2" style="letter-spacing:1px;">
            Passcode <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <span class="absolute left-4 top-2.5 text-gray-600"><i class="fas fa-lock text-sm"></i></span>
                 <input id="reg_password" type="password" name="password" required placeholder="Min 8 characters"
                   class="w-full pl-10 pr-10 py-2.5 rounded-lg bg-white text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm transition-all duration-300 hover:bg-gray-50">
            <button type="button" class="absolute right-4 top-2.5 text-gray-600 hover:text-orange-400 text-sm transition-colors toggle-eye" data-target="reg_password">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          @error('password')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div class="group">
          <label class="text-xs font-bold text-gray-400 uppercase block mb-2" style="letter-spacing:1px;">
            Confirm <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <span class="absolute left-4 top-2.5 text-gray-600"><i class="fas fa-lock text-sm"></i></span>
                 <input id="reg_password_confirmation" type="password" name="password_confirmation" required placeholder="Repeat passcode"
                   class="w-full pl-10 pr-10 py-2.5 rounded-lg bg-white text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm transition-all duration-300 hover:bg-gray-50">
            <button type="button" class="absolute right-4 top-2.5 text-gray-600 hover:text-orange-400 text-sm transition-colors toggle-eye" data-target="reg_password_confirmation">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <!-- Status -->
        <div class="flex items-center justify-between pt-3 pb-2" style="border-top:1px solid #333;">
          <span class="flex items-center text-xs text-gray-400">
            <span class="inline-block w-2.5 h-2.5 bg-green-400 rounded-full mr-2 animate-pulse"></span>
            SYSTEM ONLINE
          </span>
          <span class="text-xs text-gray-400" style="letter-spacing:0.5px;">REGISTER</span>
        </div>

        <!-- REGISTER BUTTON -->
        <button type="submit"
                class="w-full py-3 font-black text-sm uppercase tracking-widest transition-all duration-300"
                style="
                    border:2px solid #f97316;
                    color:#f97316;
                    border-radius:6px;
                    background:transparent;
                "
                onmouseover="this.style.backgroundColor='#f97316'; this.style.color='white'; this.style.boxShadow='0 0 20px rgba(249,115,22,0.6)'; this.style.transform='translateY(-2px)'"
                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#f97316'; this.style.boxShadow='none'; this.style.transform='translateY(0)'">
          REGISTER →
        </button>

        <!-- Register Link -->
        <div class="text-center pb-2">
          <span class="text-xs text-gray-400">
            Already have an account?
            <button type="button"
                    onclick="closeModal('registerModalOverlay'); setTimeout(()=>openModal('loginModalOverlay'),220);"
                    class="font-bold transition-colors"
                    style="color:#f97316;">
              LOGIN HERE
            </button>
          </span>
        </div>
      </div>
    </form>

    <!-- CLOSE BUTTON -->
    <button data-close="registerModalOverlay"
            class="absolute top-5 right-5 text-gray-400 hover:text-orange-400 text-2xl">
      ×
    </button>

  </div>
</div>

<!-- Animations CSS & Modal JS -->
<style>
/* overlay + modal animation */
.modal-overlay {
  position: fixed;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0,0,0,0.62);
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.33s ease, visibility 0.33s ease;
  z-index: 9999;
  padding: 16px;
}

.modal-overlay.open {
  opacity: 1;
  visibility: visible;
}

/* base modal transition (works with .open on overlay) */
.modal {
  transform: translateY(20px) scale(.96);
  opacity: 0;
  transition: transform 0.34s cubic-bezier(.2,.9,.3,1), opacity 0.26s ease;
  will-change: transform, opacity;
}

.modal-overlay.open .modal {
  transform: translateY(0) scale(1);
  opacity: 1;
}

/* small subtle pop animation on open (stagger-ish feel) */
.modal.open-pop {
  animation: pop-in 320ms ease forwards;
}
@keyframes pop-in {
  0% { transform: translateY(25px) scale(.94); opacity:0; }
  60% { transform: translateY(-6px) scale(1.02); opacity:1; }
  100% { transform: translateY(0) scale(1); opacity:1; }
}

/* close animation */
.modal.close-pop {
  animation: pop-out 220ms ease forwards;
}
@keyframes pop-out {
  0% { transform: translateY(0) scale(1); opacity:1; }
  100% { transform: translateY(12px) scale(.96); opacity:0; }
}

/* small helper for eye button */
.toggle-eye { background: transparent; border: none; cursor: pointer; }
</style>

<script>
/**
 * Modal helpers (openModal / closeModal) with animation hooks
 * - Adds 'open' class to overlay to show overlay + modal
 * - Adds 'open-pop' class to modal for pop-in animation
 * - On close, plays pop-out animation before hiding overlay
 */
function openModal(id) {
  const overlay = document.getElementById(id);
  if(!overlay) return;
  overlay.classList.add('open');
  // ensure modal gets pop animation
  const modal = overlay.querySelector('.modal');
  if(modal) {
    modal.classList.remove('close-pop');
    // force reflow so animation restarts
    void modal.offsetWidth;
    modal.classList.add('open-pop');
  }
  overlay.setAttribute('aria-hidden', 'false');
}

function closeModal(id) {
  const overlay = document.getElementById(id);
  if(!overlay) return;
  const modal = overlay.querySelector('.modal');
  if(modal) {
    modal.classList.remove('open-pop');
    modal.classList.add('close-pop');
    // wait for animation to finish before removing overlay visibility
    modal.addEventListener('animationend', function handler(){
      overlay.classList.remove('open');
      overlay.setAttribute('aria-hidden','true');
      modal.classList.remove('close-pop');
      modal.removeEventListener('animationend', handler);
    });
  } else {
    overlay.classList.remove('open');
    overlay.setAttribute('aria-hidden','true');
  }
}

/* Close when clicking elements with data-close attribute */
document.addEventListener('click', function(e){
  const t = e.target;
  // elements with data-close
  if(t && t.dataset && t.dataset.close) {
    closeModal(t.dataset.close);
    return;
  }
  // close-x class
  if(t.closest && t.closest('.close-x')) {
    const overlay = t.closest('.modal-overlay');
    if(overlay && overlay.id) closeModal(overlay.id);
    return;
  }
  // toggle-eye buttons (for password toggle)
  const eye = t.closest && t.closest('.toggle-eye');
  if(eye) {
    const targetId = eye.dataset.target || eye.getAttribute('data-target');
    if(targetId) togglePassword(targetId, eye);
    return;
  }
});

/* Close modal by clicking overlay (but not when clicking modal content) */
document.querySelectorAll('.modal-overlay').forEach(function(overlay){
  overlay.addEventListener('click', function(e){
    if(e.target === overlay) {
      // close overlay
      closeModal(overlay.id);
    }
  });
});

/* Toggle password helper used by eye buttons */
function togglePassword(fieldId, btnElement) {
  const field = document.getElementById(fieldId);
  if(!field) return;
  if(field.type === 'password') {
    field.type = 'text';
    // replace icon if possible
    if(btnElement){
      const i = btnElement.querySelector('i');
      if(i) i.classList.replace('fa-eye','fa-eye-slash');
    }
  } else {
    field.type = 'password';
    if(btnElement){
      const i = btnElement.querySelector('i');
      if(i) i.classList.replace('fa-eye-slash','fa-eye');
    }
  }
}

/* Also support existing single-eye button usage where onclick="togglePassword('id')" was used */
function togglePasswordLegacy(fieldId) {
  const el = document.getElementById(fieldId);
  if(!el) return;
  el.type = (el.type === 'password') ? 'text' : 'password';
}

/* Keyboard: close modal on ESC */
document.addEventListener('keydown', function(e){
  if(e.key === 'Escape' || e.key === 'Esc') {
    document.querySelectorAll('.modal-overlay.open').forEach(function(overlay){
      closeModal(overlay.id);
    });
  }
});

/* Item card click handler assumed in main layout; keep a defensive snippet:
   If your cards have data-* attributes (data-item-title, etc.), this will open item modal.
*/
document.addEventListener('click', function(e){
  const card = e.target.closest && e.target.closest('.card');
  if(!card) return;
  if(card.dataset && card.dataset.itemTitle) {
    // If you use Auth check on server side to inject isLoggedIn variable, prefer that.
    // Here we assume server has set `isLoggedIn` JS variable elsewhere. If not, simply open detail.
    const isLoggedIn = (typeof isLoggedIn !== 'undefined') ? isLoggedIn : true;
    if(!isLoggedIn) {
      // open login modal
      openModal('loginModalOverlay');
      return;
    }
    // populate item modal
    document.getElementById('itemTitle').textContent = card.dataset.itemTitle || 'Detail Item';
    document.getElementById('itemTitle2').textContent = card.dataset.itemTitle || '';
    document.getElementById('itemDesc').textContent = card.dataset.itemDesc || '';
    document.getElementById('itemPrice').textContent = card.dataset.itemPrice || '-';
    const img = document.getElementById('itemImage');
    if(img) img.src = card.dataset.itemImage || '';
    openModal('itemModalOverlay');
  }
});

/* Initialize toggle-eye buttons already present on page (for elements created server-side) */
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('[data-toggle]').forEach(function(b){
    // backward compatibility if any: map to toggle-eye behavior
    if(!b.classList.contains('toggle-eye')) b.classList.add('toggle-eye');
    if(!b.dataset.target && b.getAttribute('onclick')) {
      // do nothing — onclick already wired
    }
  });

  // Re-open modals automatically if server returned errors (keeps your Swal logic intact).
});

/* Preserve older calls that used togglePassword(fieldId) inline */
window.togglePassword = function(fieldId){
  // try to find a button with data-target to swap icon
  const btn = document.querySelector('.toggle-eye[data-target="'+fieldId+'"]') || document.querySelector('[data-toggle="'+fieldId+'"]');
  togglePassword(fieldId, btn);
};
</script>

<!-- Consolidated error & success handling (kept as before) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Handle login errors
  @if ($errors->has('email') || $errors->has('password') || session()->has('error'))
    const loginError = '{{ session("error") ?? ($errors->first("email") ?? $errors->first("password")) }}';
    if(loginError) {
      Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: loginError,
        confirmButtonColor: '#f97316',
        confirmButtonText: 'Coba Lagi'
      }).then(() => {
        openModal('loginModalOverlay');
      });
    }
  @endif

  // Handle login success
  @if (session()->has('success') && !$errors->any())
    Swal.fire({
      icon: 'success',
      title: 'Login Successful',
      text: '{{ session("success") }}',
      confirmButtonColor: '#f97316',
      confirmButtonText: 'Lanjut',
      timer: 2000
    });
  @endif

  // Handle register validation errors
  @if ($errors->any() && Route::current()->getName() === 'register')
    const errors = @json($errors->all());
    if(errors.length > 0) {
      Swal.fire({
        icon: 'error',
        title: 'Validation Failed',
        html: '<ul style="text-align: left;">' + errors.map(e => '<li>' + e + '</li>').join('') + '</ul>',
        confirmButtonColor: '#f97316',
        confirmButtonText: 'Coba Lagi'
      }).then(() => {
        openModal('registerModalOverlay');
      });
    }
  @endif

  // Handle register success
  @if (session()->has('register_success'))
    Swal.fire({
      icon: 'success',
      title: 'Registration Successful!',
      text: '{{ session("register_success") }}',
      confirmButtonColor: '#f97316',
      confirmButtonText: 'Login Sekarang',
      timer: 3000
    }).then(() => {
      closeModal('registerModalOverlay');
      openModal('loginModalOverlay');
    });
  @endif

  // Toggle password visibility
  document.querySelectorAll('.toggle-eye').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const targetId = this.getAttribute('data-target');
      const input = document.getElementById(targetId);
      const icon = this.querySelector('i');

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });
});
</script>
