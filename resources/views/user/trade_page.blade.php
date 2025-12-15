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
    <button class="bg-[#f97316] py-3 rounded font-semibold">Trade</button>
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
        <i>_</i>
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
    @forelse($items as $item)
      <div class="bg-[#181b26] rounded-lg p-3 hover:ring-2 hover:ring-[#f97316] transition">
        <div class="h-24 flex items-center justify-center mb-2">
          @if(!empty($item->image))
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover rounded">
          @else
            <div class="text-gray-500 text-xs">No Image</div>
          @endif
        </div>
        <div class="text-xs text-gray-400">{{ $item->category->name ?? 'Umum' }}</div>
        <div class="text-sm truncate">{{ $item->name }}</div>
        <div class="text-green-400 text-sm mt-1">${{ $item->price ?? 'N/A' }}</div>
      </div>
    @empty
      <div class="col-span-3 text-center text-gray-400 py-8">
        No items available for trading
      </div>
    @endforelse
  </div>

</section>
</div>

  {{-- Why Trade With Us Section --}}
  <section class="w-full py-16 px-6">
    <h2 class="text-3xl font-bold text-white text-center mb-8">Trade Items - Secure Item Trading Platform</h2>

    <div class="bg-[#12141c] rounded-lg p-8 space-y-6 text-gray-300 leading-relaxed">
      <p>Welcome to TEMAN, the premier platform for secure item trading. With thousands of successful trades and a growing community of traders, we are dedicated to providing you with the fastest, most secure, and reliable trading experience for your valuable items.</p>

      <h3 class="text-xl font-semibold text-white mt-8 mb-4">Why TEMAN Stands Out as the Best Trading Platform:</h3>
      <p>Item trading is a serious business—every day, countless transactions happen worldwide. Trading items is an essential part of the gaming and collecting experience. While there are many platforms available, you need one you can truly trust.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Lightning-Fast Transactions:</h4>
      <p>Our system is built for speed. Execute trades quickly and efficiently, with access to a vast inventory of premium items instantly.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Trusted by Thousands:</h4>
      <p>Our commitment to user satisfaction and reliability is unmatched, backed by positive reviews and a growing user base.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Expert Item Knowledge:</h4>
      <p>Our detailed item guides and informative resources provide you with all the essential information to make smart trading decisions.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">24/7 Support:</h4>
      <p>Our dedicated support team is always available to help with any questions or issues, ensuring a smooth and enjoyable trading experience.</p>

      <p>Our platform is user-friendly and has everything you need to trade items effectively. We're also mobile-optimized, so you can trade from anywhere, anytime.</p>

      <h3 class="text-xl font-semibold text-white mt-8 mb-4">Fair Pricing, No Hidden Fees</h3>
      <p>We're becoming the go-to platform because we prioritize fair dealing. When you trade with us, you get competitive pricing without the high commission fees that other platforms charge.</p>

      <p>We use market analysis to ensure fair pricing for all items, so both buyers and sellers get the best possible deal. This means you get more value for your money and can build your collection affordably.</p>

      <h3 class="text-xl font-semibold text-white mt-8 mb-4">User-Focused Trading Experience:</h3>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Intuitive Design:</h4>
      <p>Whether you're a seasoned trader or just getting started, our platform is designed with simplicity in mind.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Secure Transactions:</h4>
      <p>Your security is our top priority. Trade with confidence, knowing your transactions are fully protected.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Stay Informed:</h4>
      <p>We regularly update our resources with guides, item evaluations, and the latest market trends. Stay ahead of the curve with our expert insights.</p>

      <h3 class="text-xl font-semibold text-white mt-8 mb-4">Building a Trading Community:</h3>
      <p>Join our vibrant community of traders. Share experiences, get advice, and connect with fellow enthusiasts.</p>
      <p class="text-orange-500 font-semibold">Join our community today!</p>

      <h3 class="text-xl font-semibold text-white mt-8 mb-4">The Premier Trading Platform</h3>
      <p>TEMAN is proud to be your trusted trading partner, offering an extensive selection of items available for purchase, sale, and trade. Our intuitive platform combined with our commitment to excellent service makes it easy to find what you're looking for. Our inventory updates regularly, so check back often to discover new opportunities!</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Trade with TEMAN:</h4>
      <p>At TEMAN, we're more than just a trading platform. We're a community of enthusiasts who understand your needs and are committed to delivering exceptional trading services. Fast, reliable, and knowledgeable—this is our promise to you.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">Start Trading Now:</h4>
      <p>Dive into the exciting world of item trading with TEMAN. Experience our speed, reliability, and expertise firsthand!</p>

      <h3 class="text-xl font-semibold text-white mt-8 mb-4">Frequently Asked Questions</h3>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">What are the best practices for safe item trading?</h4>
      <p>For safe trading, always verify trading partners, use secure platforms like TEMAN, and follow platform guidelines.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">How does automated trading work?</h4>
      <p>Automated trading uses secure systems to facilitate fast, reliable transactions between users, acting as a trusted intermediary.</p>

      <h4 class="text-lg font-semibold text-orange-500 mt-6 mb-2">How can I ensure a smooth trading experience?</h4>
      <p>To ensure smooth trading, read item descriptions carefully, communicate clearly with trading partners, and use our secure platform features.</p>
    </div>
  </section>

  {{-- Trade page specific footer --}}
  @include('user.multi_page.trade_footer')

</div>
@endsection
