<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::where('available_stock', '>', 0)->count(),
            'total_members' => User::where('role', 'member')->count(),
            'active_borrowings' => Borrowing::where('status', 'borrowed')->count(),
            'overdue_borrowings' => Borrowing::where('status', 'borrowed')
                ->where('due_date', '<', Carbon::today())
                ->count(),
            'total_categories' => Category::count(),
        ];

        $recentBorrowings = Borrowing::with(['user', 'book'])
            ->latest()
            ->take(5)
            ->get();

        $popularBooks = Book::withCount(['borrowings' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subMonth());
            }])
            ->orderBy('borrowings_count', 'desc')
            ->take(5)
            ->get();

        $monthlyBorrowings = Borrowing::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        return view('admin.dashboard', compact('stats', 'recentBorrowings', 'popularBooks', 'monthlyBorrowings'));
    }
}
