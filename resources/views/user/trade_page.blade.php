@extends('user.layout.main')

@section('title','Trade — Teman')

@section('content')
@section('navbar')
  @include('user.multi_page.trade_navbar')
@endsection

@section('hideFooter')
@endsection

<div class="min-h-screen text-white">

<div class="relative overflow-hidden -mx-6" style="width:100vw; left:50%; right:50%; margin-left:-50vw; margin-right:-50vw;">

<!-- TOP BAR -->
<div class="grid grid-cols-[1fr_260px_1fr] gap-4 px-6 py-6">

  <div class="bg-[#12141c] rounded-lg p-6 text-center">
    <h3 class="font-semibold">You offer</h3>
    <p class="text-gray-400 text-sm mt-1">What items do you offer</p>
    <div class="mt-4 text-green-400 text-xl">⌄</div>
  </div>

  <div class="flex flex-col gap-3 justify-center">
    <button class="bg-[#3b3bf6] py-3 rounded font-semibold">Trade</button>
    <button class="bg-[#1a1d2b] py-2 rounded text-sm text-gray-300">
      🎁 Get $5.0 for free
    </button>
  </div>

  <div class="bg-[#12141c] rounded-lg p-6 text-center">
    <h3 class="font-semibold">You receive</h3>
    <p class="text-gray-400 text-sm mt-1">Choose the items you would like to receive</p>
    <div class="mt-4 text-green-400 text-xl">⌄</div>
  </div>

</div>

<!-- MAIN -->
<div class="grid grid-cols-[1fr_260px_1fr] gap-4 px-6 pb-10 items-stretch">

<!-- LEFT -->
<section class="bg-[#12141c] rounded-lg flex flex-col h-full">

  <div class="h-[56px] flex items-center gap-2 px-4 border-b border-white/5">
    <svg width="16" height="16" stroke="currentColor" stroke-width="2"
         fill="none" class="text-gray-400">
      <circle cx="11" cy="11" r="8"/>
      <line x1="21" y1="21" x2="16.65" y2="16.65"/>
    </svg>
    <input class="bg-transparent text-sm w-full outline-none"
           placeholder="Search inventory">
  </div>

  <div class="flex-1 flex flex-col items-center justify-center text-gray-400 text-sm px-6">
    <p class="text-center">
      No items were found matching your search criteria.
    </p>
    <button class="mt-4 bg-[#1a1d2b] px-4 py-2 rounded">
      Reset Filters
    </button>
  </div>

</section>

<!-- FILTER -->
<aside class="bg-[#12141c] rounded-lg flex flex-col h-full">

  <div class="h-[56px] flex items-center px-4 border-b border-white/5 font-semibold">
    Filters
  </div>

  <div class="flex-1 p-4 text-sm space-y-4 overflow-auto">

    <div>
      <div class="font-semibold mb-2">Price ($)</div>
      <div class="flex gap-2">
        <input class="w-1/2 bg-[#0f1117] p-2 rounded outline-none" placeholder="Min">
        <input class="w-1/2 bg-[#0f1117] p-2 rounded outline-none" placeholder="Max">
      </div>
    </div>

    @foreach(['Type','Trade Lock','Sticker','Exterior','Colors','StatTrak','Rarity','Collection','Float'] as $f)
      <div class="flex justify-between items-center py-1 text-gray-300 hover:text-white cursor-pointer">
        <span>{{ $f }}</span>
        <svg width="14" height="14" stroke="currentColor" stroke-width="2" fill="none">
          <path d="M6 9l6 6 6-6"/>
        </svg>
      </div>
    @endforeach

  </div>

  <div class="p-4 border-t border-white/5">
    <button class="w-full bg-[#1a1d2b] py-2 rounded text-gray-300">
      Reset Filters
    </button>
  </div>

</aside>

<!-- RIGHT -->
<section class="bg-[#12141c] rounded-lg flex flex-col h-full">

  <div class="h-[56px] flex items-center gap-2 px-4 border-b border-white/5">
    <svg width="16" height="16" stroke="currentColor" stroke-width="2"
         fill="none" class="text-gray-400">
      <circle cx="11" cy="11" r="8"/>
      <line x1="21" y1="21" x2="16.65" y2="16.65"/>
    </svg>
    <input class="bg-transparent text-sm w-full outline-none"
           placeholder="Search inventory">
  </div>

  <div class="flex-1 grid grid-cols-3 gap-3 p-4 overflow-auto">
    @for($i=0;$i<9;$i++)
      <div class="bg-[#181b26] rounded-lg p-3 hover:ring-2 hover:ring-[#3b3bf6] transition">
        <div class="h-24 flex items-center justify-center mb-2"></div>
        <div class="text-xs text-gray-400">FT</div>
        <div class="text-sm truncate">Item Name</div>
        <div class="text-green-400 text-sm mt-1">$1.35</div>
      </div>
    @endfor
  </div>

</section>

  </div>

  {{-- Trade page specific footer --}}
  @include('user.multi_page.trade_footer')

</div>
@endsection
