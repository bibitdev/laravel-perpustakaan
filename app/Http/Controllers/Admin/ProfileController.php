<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->load(['borrowings' => function($query) {
            $query->with('book')->latest()->take(5);
        }]);

        $stats = [
            'active_borrowings' => $user->borrowings()->where('status', 'borrowed')->count(),
            'total_borrowings' => $user->borrowings()->count(),
            'total_fines' => $user->borrowings()->sum('fine_amount'),
        ];

        return view('profile.index', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar && Storage::exists('public/avatars/' . $user->avatar)) {
                Storage::delete('public/avatars/' . $user->avatar);
            }

            $avatar = $request->file('avatar');
            $filename = time() . '_' . $avatar->getClientOriginalName();

            $img = Image::make($avatar)->fit(200, 200);
            Storage::put('public/avatars/' . $filename, $img->stream());

            $data['avatar'] = $filename;
        }

        // Handle password change
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }

            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
