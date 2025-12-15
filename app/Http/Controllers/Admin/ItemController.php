<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    // Show create form
    public function create()
    {
        // Jika tabel categories belum tersedia (belum migrate), jangan lempar exception.
        try {
            $categories = Category::whereIn('name', ['Guns', 'Knives', 'Gloves', 'Stickers'])->orderBy('name')->get();
        } catch (\Throwable $e) {
            $categories = collect();
        }

        return view('admin.items.create', compact('categories'));
    }

    // Store new item
    public function store(Request $request)
    {
        // Normalize price input so users can paste formatted values like "1.000,50" or "1,000.50"
        $priceRaw = $request->input('price');
        if (is_string($priceRaw)) {
            // keep digits, dot and comma only
            $p = preg_replace('/[^\d\.,]/', '', $priceRaw);
            if ($p !== '') {
                if (strpos($p, ',') !== false && strpos($p, '.') !== false) {
                    // both comma and dot present: assume dot as thousand separator and comma as decimal
                    $p = str_replace('.', '', $p);
                    $p = str_replace(',', '.', $p);
                } else {
                    // only comma present: treat comma as decimal separator
                    if (strpos($p, ',') !== false) {
                        $p = str_replace(',', '.', $p);
                    }
                    // only dots present: allow as decimal separator (or integer thousands if multiple dots handled below)
                }

                // If multiple dots remain, keep the last as decimal separator and remove others (handle 1.234.567,89 scenarios)
                if (substr_count($p, '.') > 1) {
                    $parts = explode('.', $p);
                    $dec = array_pop($parts);
                    $p = implode('', $parts) . '.' . $dec;
                }

                $normalizedPrice = $p;
            } else {
                $normalizedPrice = $priceRaw;
            }

            $request->merge(['price' => $normalizedPrice]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'required|string|max:250',
            'rarity' => 'required|string|in:common,uncommon,rare,mythical,legendary,ancient,exceedingly_rare,immortal',
            'price' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            // allow webp as well
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'name.required' => 'Item name is required.',
            'description.required' => 'Description is required.',
            'rarity.required' => 'Rarity must be selected.',
            'price.required' => 'Price is required and must be greater than 0.',
            'category_id.required' => 'Category must be selected.',
            'image.required' => 'Item image is required.',
        ]);

        // set metadata to include rarity (store as JSON in metadata column)
        $meta = ['rarity' => $validated['rarity']];

        // server-side: description max length enforced above (max:250)

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'metadata' => $meta,
            'price' => $validated['price'],
            'category_id' => $validated['category_id'] ?? null,
        ];

        if ($request->file('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->route('admin.dashboard')->with('success', 'Item added successfully.');
    }

    // Show edit form
    public function edit(Item $item)
    {
        try {
            $categories = Category::whereIn('name', ['Guns', 'Knives', 'Gloves', 'Stickers'])->orderBy('name')->get();
        } catch (\Throwable $e) {
            $categories = collect();
        }

        return view('admin.items.edit', compact('item', 'categories'));
    }

    // Update existing item
    public function update(Request $request, Item $item)
    {
        // Normalize price input (same logic as store)
        $priceRaw = $request->input('price');
        if (is_string($priceRaw)) {
            $p = preg_replace('/[^\d\.,]/', '', $priceRaw);
            if ($p !== '') {
                if (strpos($p, ',') !== false && strpos($p, '.') !== false) {
                    $p = str_replace('.', '', $p);
                    $p = str_replace(',', '.', $p);
                } else {
                    if (strpos($p, ',') !== false) {
                        $p = str_replace(',', '.', $p);
                    }
                }

                if (substr_count($p, '.') > 1) {
                    $parts = explode('.', $p);
                    $dec = array_pop($parts);
                    $p = implode('', $parts) . '.' . $dec;
                }

                $normalizedPrice = $p;
            } else {
                $normalizedPrice = $priceRaw;
            }
            $request->merge(['price' => $normalizedPrice]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'required|string|max:250',
            'rarity' => 'required|string|in:common,uncommon,rare,mythical,legendary,ancient,exceedingly_rare,immortal',
            'price' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'name.required' => 'Item name is required.',
            'description.required' => 'Description is required.',
            'rarity.required' => 'Rarity must be selected.',
            'price.required' => 'Price is required and must be greater than 0.',
            'category_id.required' => 'Category must be selected.',
        ]);

        // update metadata
        $meta = ['rarity' => $validated['rarity']];

        $item->name = $validated['name'];
        $item->description = $validated['description'] ?? null;
        $item->metadata = $meta;
        $item->price = $validated['price'];
        $item->category_id = $validated['category_id'] ?? null;

        if ($request->file('image')) {
            // delete old image if present
            if (!empty($item->image) && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
            $item->image = $request->file('image')->store('items', 'public');
        }

        $item->save();

        return redirect()->route('admin.dashboard')->with('success', 'Item updated successfully.');
    }

    // Delete an item
    public function destroy(Item $item)
    {
        // delete image file
        if (!empty($item->image) && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        // Prefer returning to the items list after deletion to avoid redirecting to a now-deleted detail page.
        $indexUrl = route('admin.items.index');

        if (request()->wantsJson() || request()->ajax()) {
            // include the index URL so clients can redirect or refresh the grid if they wish
            return response()->json(['success' => true, 'message' => 'Item deleted.', 'redirect' => $indexUrl]);
        }

        return redirect()->route('admin.items.index')->with('success', 'Item deleted.');
    }

    // Browse / index list of items with optional category filter
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');

        // categories for filter UI
        try {
            // include a count of items per category so UI can show badges
            $categories = Category::withCount('items')->orderBy('name')->get();
        } catch (\Throwable $e) {
            $categories = collect();
        }

        $itemsQuery = Item::with('category')->where('is_official', true)->orderBy('created_at', 'desc');

        if ($categorySlug) {
            $cat = Category::where('slug', $categorySlug)->first();
            if ($cat) {
                $itemsQuery->where('category_id', $cat->id);
            }
        }

        $items = $itemsQuery->paginate(12)->withQueryString();

        // total items (all categories) - used to show the "Semua" count badge
        try {
            $totalItems = Item::count();
        } catch (\Throwable $e) {
            $totalItems = 0;
        }

        return view('admin.items.browse_items', compact('items', 'categories', 'categorySlug', 'totalItems'));
    }

    // Show item detail
    public function show(Item $item)
    {
        return view('admin.items.show', compact('item'));
    }
}
