<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
    $books = Book::where('is_active', true)
        ->with('category')
        ->paginate(12); // Bisa disesuaikan

    $featuredBooks = Book::where('is_featured', true)
        ->where('is_active', true)
        ->with('category')
        ->take(6)
        ->get();

    $newBooks = Book::where('is_active', true)
        ->with('category')
        ->latest()
        ->take(8)
        ->get();

    $categories = Category::where('is_active', true)
        ->withCount('activeBooks')
        ->take(6)
        ->get();

    $stats = [
        'total_books' => Book::where('is_active', true)->count(),
        'total_members' => User::where('role', 'member')->where('is_active', true)->count(),
        'borrowed_books' => Borrowing::where('status', 'borrowed')->count(),
        'categories' => Category::where('is_active', true)->count(),
    ];

    return view('home', compact('books', 'featuredBooks', 'newBooks', 'categories', 'stats'));
    }


    public function books(Request $request)
    {
        $query = Book::where('is_active', true)->with(['category']);

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

        $books = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('admin.books.index', compact('books', 'categories'));
    }

    public function bookDetail($slug)
    {
        $book = Book::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category'])
            ->firstOrFail();

        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('books.detail', compact('book', 'relatedBooks'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
