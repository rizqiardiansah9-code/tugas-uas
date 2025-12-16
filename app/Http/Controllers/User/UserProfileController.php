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
        return view('user.profile', compact('user', 'inventory', 'categories'));
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

        $user = Auth::user();
        /** @var \App\Models\User $user */ // Hit for IDE introspection if available

        // Fill data except image first
        $user->nama = $validatedData['nama'];

        if ($request->file('image')) {
            // Hapus file lama dari disk 'public' jika bukan default
            if ($request->oldImage && $request->oldImage <> 'profil-pic/default.jpg') {
                Storage::disk('public')->delete($request->oldImage);
            }
            // Simpan file ke disk 'public'
            $user->image = $request->file('image')->store('profil-pic', 'public');
        }

        if ($user->isDirty()) {
            $user->save();
            return redirect()->route('user.profil')->with('success', 'Your profile has been updated successfully.');
        }

        // No changes -> No notification
        return redirect()->route('user.profil');
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

    public function editPassword()
    {
        return view('user.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return redirect()->route('user.profil')->with('success', 'Password successfully changed!');
    }
}
