@extends('layouts.app')

@section('title', 'Beranda - Perpustakaan Desa Prigi')

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Selamat Datang di Perpustakaan Desa Prigi</h1>
        <p>Jelajahi koleksi buku terlengkap untuk menambah wawasan dan pengetahuan Anda</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('books') }}" class="btn btn-primary">
                <i class="fas fa-book"></i> Jelajahi Koleksi
            </a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Daftar Anggota
                </a>
            @endguest
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="color: #3b82f6;">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['total_books']) }}</div>
                <div class="stat-label">Total Buku</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="color: #10b981;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['total_members']) }}</div>
                <div class="stat-label">Anggota Aktif</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="color: #f59e0b;">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['borrowed_books']) }}</div>
                <div class="stat-label">Sedang Dipinjam</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="color: #8b5cf6;">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['categories']) }}</div>
                <div class="stat-label">Kategori</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Books -->
@if($featuredBooks->count() > 0)
<section class="section">
    <div class="container">
        <h2 class="section-title">Buku Pilihan</h2>
        <p class="section-subtitle">Koleksi buku terbaik yang direkomendasikan untuk Anda</p>

        <div class="card-grid card-grid-3">
            @foreach($featuredBooks as $book)
                <div class="book-card">
                    <div class="book-cover" style="background-image: url('{{ $book->cover_image_url }}')">
                        <div class="book-badge">Pilihan</div>
                    </div>
                    <div class="book-content">
                        <h3 class="book-title">{{ $book->title }}</h3>
                        <p class="book-author">oleh {{ $book->author }}</p>
                        <span class="book-category" style="background-color: {{ $book->category->color }}20; color: {{ $book->category->color }};">
                            {{ $book->category->name }}
                        </span>
                        <div class="book-footer">
                            <span class="book-stock {{ $book->isAvailable() ? '' : 'unavailable' }}">
                                @if($book->isAvailable())
                                    <i class="fas fa-check-circle"></i> Tersedia ({{ $book->available_stock }})
                                @else
                                    <i class="fas fa-times-circle"></i> Tidak Tersedia
                                @endif
                            </span>
                            <a href="{{ route('book.detail', $book->slug) }}" class="btn btn-primary" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Categories -->
@if($categories->count() > 0)
<section class="section" style="background: #f9fafb;">
    <div class="container">
        <h2 class="section-title">Kategori Buku</h2>
        <p class="section-subtitle">Temukan buku berdasarkan kategori yang Anda minati</p>

        <div class="card-grid card-grid-3">
            @foreach($categories as $category)
                <a href="{{ route('books', ['category' => $category->id]) }}" class="category-card" style="border-top-color: {{ $category->color }}; text-decoration: none;">
                    <div class="category-icon" style="color: {{ $category->color }};">
                        <i class="fas fa-{{ $this->getCategoryIcon($category->name) }}"></i>
                    </div>
                    <h3 class="category-name">{{ $category->name }}</h3>
                    <p class="category-count">{{ $category->active_books_count }} buku tersedia</p>
                </a>
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('books') }}" class="btn btn-primary">
                <i class="fas fa-th-large"></i> Lihat Semua Kategori
            </a>
        </div>
    </div>
</section>
@endif

<!-- New Books -->
@if($newBooks->count() > 0)
<section class="section">
    <div class="container">
        <h2 class="section-title">Buku Terbaru</h2>
        <p class="section-subtitle">Koleksi buku terbaru yang baru saja ditambahkan ke perpustakaan</p>

        <div class="card-grid card-grid-4">
            @foreach($newBooks as $book)
                <div class="book-card">
                    <div class="book-cover" style="background-image: url('{{ $book->cover_image_url }}')">
                        <div class="book-badge" style="background: #10b981;">Baru</div>
                    </div>
                    <div class="book-content">
                        <h3 class="book-title">{{ Str::limit($book->title, 30) }}</h3>
                        <p class="book-author">{{ $book->author }}</p>
                        <span class="book-category" style="background-color: {{ $book->category->color }}20; color: {{ $book->category->color }};">
                            {{ $book->category->name }}
                        </span>
                        <div class="book-footer">
                            <span class="book-stock {{ $book->isAvailable() ? '' : 'unavailable' }}">
                                @if($book->isAvailable())
                                    <i class="fas fa-check-circle"></i> Tersedia
                                @else
                                    <i class="fas fa-times-circle"></i> Habis
                                @endif
                            </span>
                            <a href="{{ route('book.detail', $book->slug) }}" class="btn btn-primary" style="font-size: 0.75rem; padding: 0.375rem 0.75rem;">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('books') }}" class="btn btn-primary">
                <i class="fas fa-book-open"></i> Lihat Semua Buku
            </a>
        </div>
    </div>
</section>
@endif

<!-- Call to Action -->
<section class="section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container" style="text-align: center;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Bergabung dengan Perpustakaan Desa Prigi</h2>
        <p style="font-size: 1.25rem; margin-bottom: 2rem; opacity: 0.9;">
            Dapatkan akses ke ribuan koleksi buku dan nikmati layanan perpustakaan modern
        </p>
        @guest
            <a href="{{ route('register') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </a>
        @else
            <a href="{{ route('books') }}" class="btn btn-primary">
                <i class="fas fa-search"></i> Mulai Mencari Buku
            </a>
        @endguest
    </div>
</section>
@endsection

@php
function getCategoryIcon($categoryName) {
    $icons = [
        'Fiksi' => 'magic',
        'Non-Fiksi' => 'brain',
        'Sejarah' => 'landmark',
        'Sains' => 'flask',
        'Teknologi' => 'laptop-code',
        'Agama' => 'pray',
        'Pendidikan' => 'graduation-cap',
        'Kesehatan' => 'heartbeat',
        'Ekonomi' => 'chart-line',
        'Hukum' => 'balance-scale',
        'Seni' => 'palette',
        'Olahraga' => 'running',
        'Biografi' => 'user-circle',
        'Referensi' => 'book-open',
    ];

    return $icons[$categoryName] ?? 'book';
}
@endphp
