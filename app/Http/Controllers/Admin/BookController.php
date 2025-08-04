<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Gate;

class BookController extends Controller
{
    public function __construct()
    {
        // $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where('available_stock', '>', 0);
            } elseif ($request->status === 'unavailable') {
                $query->where('available_stock', '<=', 0);
            }
        }

        $books = $query->latest()->paginate(10);
        $categories = Category::where('is_active', true)->get();

        return view('admin.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1900|max:' . date('Y'),
            'pages' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured' => 'nullable|boolean',

        ], [
            'title.required' => 'Judul buku harus diisi.',
            'isbn.unique' => 'ISBN sudah digunakan untuk buku lain.',
            'author.required' => 'Nama penulis harus diisi.',
            'publisher.required' => 'Penerbit harus diisi.',
            'publication_year.required' => 'Tahun terbit harus diisi.',
            'publication_year.min' => 'Tahun terbit tidak valid.',
            'publication_year.max' => 'Tahun terbit tidak boleh melebihi tahun sekarang.',
            'stock.required' => 'Jumlah stok harus diisi.',
            'stock.min' => 'Stok minimal 1.',
            'category_id.required' => 'Kategori harus dipilih.',
            'cover_image.image' => 'File harus berupa gambar.',
            'cover_image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = $request->all();
        $data['available_stock'] = $data['stock'];
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $filename = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());

            try {
                $img = Image::make($image)->resize(400, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                Storage::put('public/books/' . $filename, $img->stream());
                $data['cover_image'] = $filename;
            } catch (\Exception $e) {
                return back()->withErrors(['cover_image' => 'Gagal mengupload gambar.'])->withInput();
            }
        }

        Book::create($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku "' . $data['title'] . '" berhasil ditambahkan ke perpustakaan.');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'borrowings.user', 'borrowings' => function($query) {
            $query->latest();
        }]);

        $activeBorrowings = $book->borrowings()->where('status', 'borrowed')->with('user')->get();
        $borrowingHistory = $book->borrowings()->where('status', '!=', 'borrowed')->with('user')->latest()->take(10)->get();

        return view('admin.books.show', compact('book', 'activeBorrowings', 'borrowingHistory'));
    }

    public function edit(Book $book)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1900|max:' . date('Y'),
            'pages' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul buku harus diisi.',
            'isbn.unique' => 'ISBN sudah digunakan untuk buku lain.',
            'stock.min' => 'Stok tidak boleh kurang dari jumlah buku yang sedang dipinjam.',
        ]);

        // Validasi khusus untuk stok
        $activeBorrowings = $book->borrowings()->where('status', 'borrowed')->count();
        if ($request->stock < $activeBorrowings) {
            return back()->withErrors([
                'stock' => "Stok tidak boleh kurang dari {$activeBorrowings} (jumlah buku yang sedang dipinjam)."
            ])->withInput();
        }

        $data = $request->all();
        $data['is_featured'] = $request->has('is_featured');

        // Update available stock berdasarkan perubahan total stock
        $stockDifference = $data['stock'] - $book->stock;
        $data['available_stock'] = max(0, $book->available_stock + $stockDifference);

        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($book->cover_image && Storage::exists('public/books/' . $book->cover_image)) {
                Storage::delete('public/books/' . $book->cover_image);
            }

            $image = $request->file('cover_image');
            $filename = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());

            try {
                $img = Image::make($image)->resize(400, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                Storage::put('public/books/' . $filename, $img->stream());
                $data['cover_image'] = $filename;
            } catch (\Exception $e) {
                return back()->withErrors(['cover_image' => 'Gagal mengupload gambar.'])->withInput();
            }
        }

        $book->update($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku "' . $book->title . '" berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        // Cek apakah ada peminjaman aktif
        $activeBorrowings = $book->borrowings()->where('status', 'borrowed')->count();

        if ($activeBorrowings > 0) {
            return redirect()->route('admin.books.index')
                ->with('error', 'Tidak dapat menghapus buku "' . $book->title . '" karena sedang dipinjam oleh ' . $activeBorrowings . ' orang.');
        }

        // Cek permission untuk petugas
        if (auth()->user()->isPetugas()) {
            $totalBorrowings = $book->borrowings()->count();
            if ($totalBorrowings > 0) {
                return redirect()->route('admin.books.index')
                    ->with('error', 'Petugas tidak dapat menghapus buku yang pernah dipinjam. Hubungi administrator.');
            }
        }

        $bookTitle = $book->title;

        // Delete cover image if exists
        if ($book->cover_image && Storage::exists('public/books/' . $book->cover_image)) {
            Storage::delete('public/books/' . $book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku "' . $bookTitle . '" berhasil dihapus dari perpustakaan.');
    }

    // Method untuk bulk actions
    public function bulkDelete(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya administrator yang dapat melakukan bulk delete.');
        }

        $request->validate([
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:books,id',
        ]);

        $books = Book::whereIn('id', $request->book_ids)->get();
        $deleted = 0;
        $errors = [];

        foreach ($books as $book) {
            $activeBorrowings = $book->borrowings()->where('status', 'borrowed')->count();

            if ($activeBorrowings > 0) {
                $errors[] = $book->title . ' (sedang dipinjam)';
                continue;
            }

            // Delete cover image
            if ($book->cover_image && Storage::exists('public/books/' . $book->cover_image)) {
                Storage::delete('public/books/' . $book->cover_image);
            }

            $book->delete();
            $deleted++;
        }

        $message = $deleted . ' buku berhasil dihapus.';
        if (!empty($errors)) {
            $message .= ' Gagal menghapus: ' . implode(', ', $errors);
        }

        return redirect()->route('admin.books.index')->with('success', $message);
    }
}
