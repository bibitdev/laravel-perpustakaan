<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('super_admin')->except(['index', 'show']);
        $this->middleware('admin')->only(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'petugas', 'member'])],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ], [
            'name.required' => 'Nama lengkap harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role harus dipilih.',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['is_active'] = true;

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna "' . $request->name . '" berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load(['borrowings' => function($query) {
            $query->with('book')->latest()->take(10);
        }]);

        $activeBorrowings = $user->borrowings()->where('status', 'borrowed')->with('book')->get();
        $totalBorrowings = $user->borrowings()->count();
        $overdueBorrowings = $user->borrowings()
            ->where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->count();

        return view('admin.users.show', compact('user', 'activeBorrowings', 'totalBorrowings', 'overdueBorrowings'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'petugas', 'member'])],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->except(['password', 'password_confirmation']);
        $data['is_active'] = $request->has('is_active');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna "' . $user->name . '" berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Tidak bisa hapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Cek apakah user memiliki peminjaman aktif
        $activeBorrowings = $user->borrowings()->where('status', 'borrowed')->count();

        if ($activeBorrowings > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus pengguna "' . $user->name . '" karena masih memiliki ' . $activeBorrowings . ' peminjaman aktif.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna "' . $userName . '" berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna "' . $user->name . '" berhasil ' . $status . '.');
    }
}
