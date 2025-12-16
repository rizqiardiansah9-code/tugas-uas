<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Item;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        // Get users that are marked active (is_active = 1)
        $activeUsers = User::select('id', 'nama', 'email', 'image', 'is_active', 'created_at')
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->take(24)
            ->get();

        $totalUsers = User::count();

        // Ambil data items dari DB jika model tersedia
        try {
            $totalItems = Item::where('is_official', true)->count();
            // configurable window (minutes) — defaults to 8 minutes if not set in config/env
            $window = (int) config('admin.new_items_window_minutes', 8);
            // count items created within the configured window using Item model helper
            $newItems = Item::countNewWithinMinutes($window);
            $items = Item::where('is_official', true)->orderBy('created_at', 'desc')->take(12)->get();
        } catch (\Throwable $e) {
            // Jika belum ada table/items, gunakan fallback
            $totalItems = 0;
            $newItems = 0;
            $items = [];
        }

        return view('admin.dashboard', compact('activeUsers', 'items', 'totalUsers', 'totalItems', 'newItems'));
    }

    // PROFIL

    public function tampilProfil()
    {
    return view('admin.profile');
    }

    public function updateProfil(Request $request)
    {
        $messages = [
        'nama.required' => 'Full name is required.',
        'image.image' => 'Image must be an image file.',
        'image.max' => 'Maximum file size is 1 MB.',
    ];
    $validatedData = $request->validate([
        'nama' => 'required|string|max:128',
        'image' => 'image|mimes:jpeg,jpg,png|file|max:1024',
    ], $messages);

    if ($request->file('image')) {
    // Hapus file lama dari disk 'public' jika bukan default
    if ($request->oldImage && $request->oldImage <> 'profil-pic/default.jpg') {
        Storage::disk('public')->delete($request->oldImage);
    }
    // Simpan file ke disk 'public' sehingga dapat diakses lewat asset('storage/...')
    $validatedData['image'] = $request->file('image')->store('profil-pic', 'public');
    }
    User::where('id', Auth::user()->id)->update($validatedData);
    return redirect()->route('admin.profile')->with('success', 'Your profile has been updated successfully.');
    }

    // GANTI PASSWORD
     public function tampilGantiPassword()
    {
    return view('admin.change_password');
    }

    public function updateGantiPassword(Request $request)
    {
        $messages = [
        'password_saat_ini.required' => 'Current password is required.',
        'password_saat_ini.min' => 'Minimum 8 characters.',
        'password_baru.required' => 'New password is required.',
        'password_baru.min' => 'Minimum 8 characters.',
        'konfirmasi_password.required' => 'Password confirmation is required.',
        'konfirmasi_password.min' => 'Minimum 8 characters.',
        'konfirmasi_password.same' => 'Password and confirmation do not match.',
        ];

        $validatedData = $request->validate([
        'password_saat_ini' => 'required|string|min:8',
        'password_baru' => 'required|string|min:8',
        'konfirmasi_password' => 'required|string|min:8|same:password_baru',
        ], $messages);

        $cekPassword = Hash::check($request->password_saat_ini, Auth::user()->password);

        if (!$cekPassword) {
        return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        User::where('id', Auth::user()->id)->update([
        'password' => Hash::make($request->password_baru),
        ]);
        return redirect()->route('admin.ganti-password')->with('success', 'Password updated successfully.');
    }
}
