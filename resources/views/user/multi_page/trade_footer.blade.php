<footer class="w-full bg-[#07080a] border-t border-white/5 text-gray-400 mt-8">
  <div class="max-w-7xl mx-auto px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="text-sm">© {{ date('Y') }} TEMAN — All rights reserved.</div>
    <div class="flex items-center gap-4 text-sm">
      <a href="/" class="hover:text-white">Home</a>
      <a href="{{ route('user.index') }}" class="hover:text-white">Catalog</a>
      <a href="#" class="hover:text-white">Support</a>
    </div>
  </div>
</footer>
