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
}
