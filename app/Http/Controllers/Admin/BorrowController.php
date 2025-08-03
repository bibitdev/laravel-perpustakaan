<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        $activeBorrowings = $user->borrowings()
            ->where('status', 'borrowed')
            ->with('book')
            ->get();

        $borrowingHistory = $user->borrowings()
            ->where('status', '!=', 'borrowed')
            ->with('book')
            ->latest()
            ->paginate(10);

        $stats = [
            'active' => $activeBorrowings->count(),
            'total' => $user->borrowings()->count(),
            'overdue' => $activeBorrowings->where('due_date', '<', Carbon::today())->count(),
            'fine' => $user->borrowings()->sum('fine_amount'),
        ];

        return view('borrowings.index', compact('activeBorrowings', 'borrowingHistory', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::find($request->book_id);
        $user = auth()->user();

        // Check if book is available
        if (!$book->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak tersedia untuk dipinjam.'
            ], 400);
        }

        // Check if user already borrowed this book
        $existingBorrowing = $user->borrowings()
            ->where('book_id', $book->id)
            ->where('status', 'borrowed')
            ->exists();

        if ($existingBorrowing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah meminjam buku ini.'
            ], 400);
        }

        // Check borrowing limit (max 3 books)
        $activeBorrowings = $user->borrowings()->where('status', 'borrowed')->count();
        if ($activeBorrowings >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mencapai batas maksimal peminjaman (3 buku).'
            ], 400);
        }

        // Create borrowing request
        $borrowing = Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays(7),
            'status' => 'borrowed',
            'approved_by' => null, // Will be set when admin approves
        ]);

        // Update book stock
        $book->decrement('available_stock');

        return response()->json([
            'success' => true,
            'message' => 'Permintaan peminjaman berhasil dikirim! Kode peminjaman: ' . $borrowing->code
        ]);
    }

    public function extend(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke peminjaman ini.');
        }

        if ($borrowing->status !== 'borrowed') {
            return back()->with('error', 'Peminjaman ini tidak dapat diperpanjang.');
        }

        // Check if already extended
        if (str_contains($borrowing->notes ?? '', 'Diperpanjang')) {
            return back()->with('error', 'Peminjaman ini sudah pernah diperpanjang.');
        }

        // Check if overdue
        if (Carbon::parse($borrowing->due_date)->lt(Carbon::today())) {
            return back()->with('error', 'Tidak dapat memperpanjang peminjaman yang sudah terlambat.');
        }

        $newDueDate = Carbon::parse($borrowing->due_date)->addDays(7);

        $borrowing->update([
            'due_date' => $newDueDate,
            'notes' => ($borrowing->notes ?? '') . "\nDiperpanjang 7 hari oleh peminjam pada " . Carbon::now()->format('d/m/Y H:i'),
        ]);

        return back()->with('success', 'Peminjaman berhasil diperpanjang hingga ' . $newDueDate->format('d/m/Y'));
    }
}
