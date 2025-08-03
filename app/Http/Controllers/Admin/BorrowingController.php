<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'book', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('book', function($bookQuery) use ($search) {
                      $bookQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('overdue') && $request->overdue === '1') {
            $query->where('status', 'borrowed')
                  ->where('due_date', '<', Carbon::today());
        }

        $borrowings = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total' => Borrowing::count(),
            'borrowed' => Borrowing::where('status', 'borrowed')->count(),
            'returned' => Borrowing::where('status', 'returned')->count(),
            'overdue' => Borrowing::where('status', 'borrowed')
                ->where('due_date', '<', Carbon::today())
                ->count(),
        ];

        return view('admin.borrowings.index', compact('borrowings', 'stats'));
    }

    public function create()
    {
        $users = User::where('role', 'member')->where('is_active', true)->get();
        $books = Book::where('is_active', true)->where('available_stock', '>', 0)->get();

        return view('admin.borrowings.create', compact('users', 'books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrowed_date' => 'required|date',
            'due_date' => 'required|date|after:borrowed_date',
            'notes' => 'nullable|string',
        ]);

        $book = Book::find($request->book_id);

        if (!$book->isAvailable()) {
            return back()->withErrors(['book_id' => 'Buku tidak tersedia untuk dipinjam.'])->withInput();
        }

        // Create borrowing record
        $borrowing = Borrowing::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'borrowed_date' => $request->borrowed_date,
            'due_date' => $request->due_date,
            'status' => 'borrowed',
            'notes' => $request->notes,
            'approved_by' => auth()->id(),
        ]);

        // Update book stock
        $book->decrement('available_stock');

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Peminjaman buku berhasil dicatat dengan kode: ' . $borrowing->code);
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'book', 'approvedBy']);
        return view('admin.borrowings.show', compact('borrowing'));
    }

    public function approve(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'requested') {
            return back()->with('error', 'Peminjaman ini tidak dapat disetujui.');
        }

        $borrowing->update([
            'status' => 'borrowed',
            'approved_by' => auth()->id(),
            'borrowed_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays(7), // Default 7 hari
        ]);

        // Update book stock
        $borrowing->book->decrement('available_stock');

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function return(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status !== 'borrowed') {
            return back()->with('error', 'Buku ini tidak sedang dipinjam.');
        }

        $request->validate([
            'returned_date' => 'required|date',
            'fine_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Calculate fine if overdue
        $returnDate = Carbon::parse($request->returned_date);
        $dueDate = Carbon::parse($borrowing->due_date);
        $fineAmount = $request->fine_amount ?? 0;

        if ($returnDate->gt($dueDate)) {
            $overdueDays = $returnDate->diffInDays($dueDate);
            $fineAmount = $overdueDays * 1000; // Rp 1.000 per hari
        }

        $borrowing->update([
            'status' => 'returned',
            'returned_date' => $request->returned_date,
            'fine_amount' => $fineAmount,
            'notes' => $request->notes,
        ]);

        // Update book stock
        $borrowing->book->increment('available_stock');

        $message = 'Buku berhasil dikembalikan.';
        if ($fineAmount > 0) {
            $message .= ' Denda keterlambatan: Rp ' . number_format($fineAmount, 0, ',', '.');
        }

        return redirect()->route('admin.borrowings.index')
            ->with('success', $message);
    }

    public function extend(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status !== 'borrowed') {
            return back()->with('error', 'Peminjaman ini tidak dapat diperpanjang.');
        }

        $request->validate([
            'new_due_date' => 'required|date|after:due_date',
        ]);

        $borrowing->update([
            'due_date' => $request->new_due_date,
            'notes' => ($borrowing->notes ?? '') . "\nDiperpanjang hingga " . $request->new_due_date . " oleh " . auth()->user()->name,
        ]);

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Peminjaman berhasil diperpanjang hingga ' . Carbon::parse($request->new_due_date)->format('d/m/Y'));
    }
}
