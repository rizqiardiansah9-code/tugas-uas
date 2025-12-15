<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

class CatalogController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index(Request $request)
    {
        // Simple: load all items. Controller can be extended with paging/filtering later.
        $items = Item::where('is_official', true)->get();
        return view('user.catalog', compact('items'));
    }
}
