<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

class TradeController extends Controller
{
    public function index()
    {
        // Load user's inventory
        $userInventory = auth()->user()->inventory()->with('category')->get();
        
        // Load market items (items not in user's inventory)
        $marketInventory = Item::with('category')->whereNotIn('id', $userInventory->pluck('id'))->get();

        // Load categories for filter
        $categories = \App\Models\Category::orderBy('name')->get();
        
        // Define rarities based on ItemController validation
        $rarities = ['common', 'uncommon', 'rare', 'mythical', 'legendary', 'ancient', 'exceedingly_rare', 'immortal'];

        return view('user.trade_page', compact('userInventory', 'marketInventory', 'categories', 'rarities'));
    }

    public function refresh()
    {
        $userInventory = auth()->user()->inventory()->with('category')->get();
        $marketInventory = Item::with('category')->whereNotIn('id', $userInventory->pluck('id'))->get();

        return response()->json([
            'userInventory' => $userInventory,
            'marketInventory' => $marketInventory
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'my_items' => 'required|array',
            'my_items.*' => 'exists:items,id',
            'their_items' => 'required|array',
            'their_items.*' => 'exists:items,id',
        ]);

        $user = auth()->user();
        $myItemsIds = $request->my_items;
        $theirItemsIds = $request->their_items;

        // 1. Verify User owns "my_items"
        // count how many of $myItemsIds are actually in user's inventory
        $ownedCount = $user->inventory()->whereIn('items.id', $myItemsIds)->count();
        if ($ownedCount !== count($myItemsIds)) {
            return response()->json(['message' => 'You do not own all selected items.'], 403);
        }

        // 2. Verify values (My Total >= Their Total)
        $myTotal = Item::whereIn('id', $myItemsIds)->sum('price');
        $theirTotal = Item::whereIn('id', $theirItemsIds)->sum('price');

        // Allow a small margin of error or strict? Strict based on JS logic.
        if ($myTotal < $theirTotal) {
            return response()->json(['message' => 'Your offer value is too low.'], 422);
        }

        // 3. Perform Trade
        // DB Transaction recommended but keeping it simple for now
        
        // Remove my items
        $user->inventory()->detach($myItemsIds);
        
        // Add their items
        // Important: In a real multi-user system, "their items" should be removed from someone else.
        // Assuming "Market" is infinite/system based on current 'whereNotIn' logic which implies 
        // "Market" = "Everything I don't have".
        // So we just attach them.
        $user->inventory()->attach($theirItemsIds);

        return response()->json(['message' => 'Trade successful!']);
    }
}
