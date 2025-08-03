<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $categories = Category::withCount('books')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ], [
            'name.required' => 'Nama kategori harus diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'color.required' => 'Warna kategori harus dipilih.',
            'color.regex' => 'Format warna tidak valid.',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori "' . $request->name . '" berhasil ditambahkan.');
    }

    public function show(Category $category)
    {
        $category->load(['books' => function($query) {
            $query->latest()->take(10);
        }]);

        $totalBooks = $category->books()->count();
        $availableBooks = $category->books()->where('available_stock', '>', 0)->count();

        return view('admin.categories.show', compact('category', 'totalBooks', 'availableBooks'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ], [
            'name.required' => 'Nama kategori harus diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'color.required' => 'Warna kategori harus dipilih.',
            'color.regex' => 'Format warna tidak valid.',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori "' . $category->name . '" berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $booksCount = $category->books()->count();

        if ($booksCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Tidak dapat menghapus kategori "' . $category->name . '" karena masih memiliki ' . $booksCount . ' buku.');
        }

        // Petugas tidak bisa hapus kategori
        if (auth()->user()->isPetugas()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Petugas tidak dapat menghapus kategori. Hubungi administrator.');
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori "' . $categoryName . '" berhasil dihapus.');
    }
}
