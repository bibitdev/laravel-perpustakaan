@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="dashboard-header">
    <h1>Dashboard Perpustakaan</h1>
    <p>Selamat datang, {{ auth()->user()->name }}!</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="color: #3b82f6;">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ number_format($stats['total_books']) }}</div>
            <div class="stat-label">Total Buku</div>
            <div class="stat-sublabel">{{ $stats['available_books'] }} tersedia</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="color: #10b981;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ number_format($stats['total_members']) }}</div>
            <div class="stat-label">Anggota Aktif</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="color: #f59e0b;">
            <i class="fas fa-book-reader"></i>
        </div>
                <div class="stat-content">
            <div class="stat-number">{{ number_format($stats['active_borrowings']) }}</div>
            <div class="stat-label">Sedang Dipinjam</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="color: #ef4444;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ number_format($stats['overdue_borrowings']) }}</div>
            <div class="stat-label">Terlambat</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="color: #8b5cf6;">
            <i class="fas fa-tags"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ number_format($stats['total_categories']) }}</div>
            <div class="stat-label">Kategori</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="dashboard-section">
    <div class="section-header">
        <h2>Aksi Cepat</h2>
    </div>
    <div class="quick-actions">
        <a href="{{ route('admin.books.create') }}" class="action-card">
            <i class="fas fa-plus"></i>
            <span>Tambah Buku</span>
        </a>
        <a href="{{ route('admin.borrowings.create') }}" class="action-card">
            <i class="fas fa-hand-holding-heart"></i>
            <span>Pinjam Buku</span>
        </a>
        <a href="{{ route('admin.categories.create') }}" class="action-card">
            <i class="fas fa-folder-plus"></i>
            <span>Tambah Kategori</span>
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.users.create') }}" class="action-card">
            <i class="fas fa-user-plus"></i>
            <span>Tambah User</span>
        </a>
        @endif
    </div>
</div>

<!-- Recent Borrowings -->
@if($recentBorrowings->count() > 0)
<div class="dashboard-section">
    <div class="section-header">
        <h2>Peminjaman Terbaru</h2>
        <a href="{{ route('admin.borrowings.index') }}" class="btn btn-outline">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentBorrowings as $borrowing)
                <tr>
                    <td>{{ $borrowing->code }}</td>
                    <td>{{ $borrowing->user->name }}</td>
                    <td>{{ Str::limit($borrowing->book->title, 30) }}</td>
                    <td>{{ $borrowing->borrowed_date->format('d/m/Y') }}</td>
                    <td>
                        <span class="status-badge status-{{ $borrowing->status }}">
                            @if($borrowing->status === 'borrowed')
                                📖 Dipinjam
                            @elseif($borrowing->status === 'returned')
                                ✅ Dikembalikan
                            @else
                                ⏳ Menunggu
                            @endif
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="btn btn-sm btn-outline">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Popular Books -->
@if($popularBooks->count() > 0)
<div class="dashboard-section">
    <div class="section-header">
        <h2>Buku Populer Bulan Ini</h2>
        <a href="{{ route('admin.books.index') }}" class="btn btn-outline">Kelola Buku</a>
    </div>
    <div class="popular-books">
        @foreach($popularBooks as $book)
        <div class="popular-book-card">
            <div class="book-cover" style="background-image: url('{{ $book->cover_image_url }}')"></div>
            <div class="book-info">
                <h4>{{ Str::limit($book->title, 25) }}</h4>
                <p>{{ $book->author }}</p>
                <div class="book-stats">
                    <span><i class="fas fa-chart-line"></i> {{ $book->borrowings_count }} peminjaman</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<style>
.dashboard-header {
    margin-bottom: 2rem;
}

.dashboard-header h1 {
    font-size: 2rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.dashboard-header p {
    color: #6b7280;
    font-size: 1.125rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    font-size: 2.5rem;
    flex-shrink: 0;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-label {
    color: #6b7280;
    font-weight: 500;
}

.stat-sublabel {
    color: #9ca3af;
    font-size: 0.875rem;
}

.dashboard-section {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-header h2 {
    font-size: 1.5rem;
    color: #1f2937;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    text-decoration: none;
    color: #374151;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.action-card:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.action-card i {
    font-size: 2rem;
}

.action-card span {
    font-weight: 600;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th,
.admin-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.admin-table th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-borrowed {
    background: #dbeafe;
    color: #1e40af;
}

.status-returned {
    background: #d1fae5;
    color: #065f46;
}

.popular-books {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.popular-book-card {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 0.75rem;
}

.book-cover {
    width: 60px;
    height: 80px;
    background-size: cover;
    background-position: center;
    border-radius: 0.375rem;
    flex-shrink: 0;
}

.book-info h4 {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #1f2937;
}

.book-info p {
    font-size: 0.75rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.book-stats {
    font-size: 0.75rem;
    color: #667eea;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .quick-actions {
        grid-template-columns: repeat(2, 1fr);
    }

    .popular-books {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
