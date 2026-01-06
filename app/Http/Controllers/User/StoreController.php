<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

class StoreController extends Controller
{
    public function index()
    {
        // Load ALL items available in the market (official/market items)
        // Store shows items that users can BUY.
        // Assuming 'is_official' = true means it's available in store? 
        // Or simply items not in current user's inventory?
        // TradeController logic uses "not in user inventory". Let's stick to that for now
        // or refine to "is_official" if that's the intention. 
        // Given current logic: Market = All Items - My Items.

        $userInventoryIds = auth()->user()->inventory()->pluck('items.id');
        $marketInventory = Item::with('category')
            ->whereNotIn('id', $userInventoryIds)
            ->get();

        $categories = \App\Models\Category::orderBy('name')->get();
        $rarities = ['common', 'uncommon', 'rare', 'mythical', 'legendary', 'ancient', 'exceedingly_rare', 'immortal'];

        return view('user.store', compact('marketInventory', 'categories', 'rarities'));
    }

    public function checkout()
    {
        return view('user.checkout');
    }

    public function buy(Request $request)
    {
        // Simple buy logic mock (since we don't have currency system fully spec'd out yet,
        // or just move item to user inventory).
        // For now, let's assume it works like "Trade" but without giving items back?
        // Tradeit Store usually implies buying with Balance.
        // The TradeController uses pure item swap check.

        // Requirements: "Buy" items.
        // Logic: Attach item to user.
        // Todo: deduct balance (future).

        $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:items,id'
        ]);

        $user = auth()->user();
        $itemsIds = $request->items;

        // Verify items are not already owned (redundant but safe)
        // Actually, if someone else bought it, we should check availability.

        // Attach to user
        $user->inventory()->attach($itemsIds);

        // Update ownership flag
        Item::whereIn('id', $itemsIds)->update(['is_official' => false]);

        return response()->json(['message' => 'Purchase successful!']);
    }

    public function selluser()
    {
        // Show user's inventory for selling
        $userInventory = auth()->user()->inventory()->with('category')->get();

        $categories = \App\Models\Category::orderBy('name')->get();
        $rarities = ['common', 'uncommon', 'rare', 'mythical', 'legendary', 'ancient', 'exceedingly_rare', 'immortal'];

        return view('user.selluser', compact('userInventory', 'categories', 'rarities'));
    }

    public function sell(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|string' // JSON string
        ]);

        $selectedItems = json_decode($request->selected_items, true);
        if (!is_array($selectedItems)) {
            return redirect()->back()->with('error', 'Invalid items selected.');
        }
        $itemsIds = array_column($selectedItems, 'id');

        $user = auth()->user();

        // Verify items are owned by user
        $ownedItems = $user->inventory()->whereIn('items.id', $itemsIds)->get();
        if ($ownedItems->count() !== count($itemsIds)) {
            return redirect()->back()->with('error', 'Some items are not in your inventory.');
        }

        // Calculate total value
        $totalValue = $ownedItems->sum('price');

        // Remove items from user inventory
        $user->inventory()->detach($itemsIds);

        // Update items to be available in market
        Item::whereIn('id', $itemsIds)->update(['is_official' => true]);

        // Add to user balance
        $user->increment('balance', $totalValue);

        return redirect()->back()->with('success', 'Items sold successfully! You earned $' . number_format($totalValue, 2) . '. New balance: $' . number_format($user->fresh()->balance, 2));
    }
}
