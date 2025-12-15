<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

class TradeController extends Controller
{
    /**
     * Display the trade page with items.
     */
    public function index()
    {
        // Load all items for trading
        $items = Item::with('category')->get();
        return view('user.trade_page', compact('items'));
    }
}
