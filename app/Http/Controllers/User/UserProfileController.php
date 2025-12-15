<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        // Get user's inventory
        $inventory = $user->inventory()->with('category')->get();
        // Get all categories for the add item form
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('user.profil', compact('user', 'inventory', 'categories'));
    }

    public function update(Request $request)
    {
        $messages = [
            'nama.required' => 'Full name is required.',
            'image.image' => 'Image must be an image file.',
            'image.max' => 'Maximum file size is 1 MB.',
        ];
        
        $validatedData = $request->validate([
            'nama' => 'required|string|max:128',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|file|max:1024',
        ], $messages);

        if ($request->file('image')) {
            // Hapus file lama dari disk 'public' jika bukan default
            if ($request->oldImage && $request->oldImage <> 'profil-pic/default.jpg') {
                Storage::disk('public')->delete($request->oldImage);
            }
            // Simpan file ke disk 'public'
            $validatedData['image'] = $request->file('image')->store('profil-pic', 'public');
        }

        User::where('id', Auth::user()->id)->update($validatedData);
        return redirect()->route('user.profil')->with('success', 'Your profile has been updated successfully.');
    }

    public function storeInventory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'category_id' => 'required|exists:categories,id',
            'rarity' => 'required|string|in:common,uncommon,rare,mythical,legendary,ancient,exceedingly_rare,immortal',
        ]);

        $itemData = [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description, // Optional
            'category_id' => $request->category_id,
            'metadata' => ['rarity' => $request->rarity],
            'is_official' => false,
        ];

        if ($request->hasFile('image')) {
            $itemData['image'] = $request->file('image')->store('items', 'public');
        }

        // Create the item
        $item = \App\Models\Item::create($itemData);

        // Attach to user's inventory
        Auth::user()->inventory()->attach($item->id);

        return redirect()->back()->with('success', 'Item added to inventory successfully!');
    }
}
