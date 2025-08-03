@extends('layouts.app')

@section('title', 'Koleksi Buku - Perpustakaan Desa Prigi')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="section-title">Koleksi Buku</h1>
        <p class="section-subtitle">Jelajahi koleksi lengkap buku Perpustakaan Desa Prigi</p>

        <!-- Search Section -->
        <div class="search-section">
            <form action="{{ route('books') }}" method="GET" class="search-form">
                <div class="form-group">
                    <label for="search" class="form-label">Cari Buku</label>
                    <input type="text"
                           id="search"
                           name="search"
                           class="form-input"
                           placeholder="Judul, penulis, atau ISBN..."
                           value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">Kategori</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="flex: 0;">
                    <button type="submit" class="btn btn-primary" style="margin-top: 1.75rem;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>

                @if(request()->hasAny(['search', 'category']))
                    <div class="form-group" style="flex: 0;">
                        <a href="{{ route('books') }}" class="btn btn-secondary" style="margin-top: 1.75rem;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Results Info -->
        <div style="margin-bottom: 2rem;">
            <p style="color: #6b7280;">
                Menampilkan {{ $books->firstItem() ?? 0 }} - {{ $books->lastItem() ?? 0 }} dari {{ $books->total() }} buku
                @if(request('search'))
                    untuk pencarian "<strong>{{ request('search') }}</strong>"
                @endif
                @if(request('category'))
                    @php $selectedCategory = $categories->find(request('category')) @endphp
                    @if($selectedCategory)
                        dalam kategori "<strong>{{ $selectedCategory->name }}</strong>"
                    @endif
                @endif
            </p>
        </div>

        <!-- Books Grid -->
        @if($books->count() > 0)
            <div class="card-grid card-grid-4">
                @foreach($books as $book)
                    <div class="book-card">
                        <div class="book-cover" style="background-image: url('{{ $book->cover_image_url }}')">
                            @if($book->is_featured)
                                <div class="book-badge">Pilihan</div>
                            @endif
                        </div>
                        <div class="book-content">
                            <h3 class="book-title">{{ Str::limit($book->title, 40) }}</h3>
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
                                <a href="{{ route('book.detail', $book->slug) }}" class="btn btn-primary" style="font-size: 0.75rem; padding: 0.375rem 0.75rem;">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $books->withQueryString()->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                <i class="fas fa-search" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: #374151;">Buku Tidak Ditemukan</h3>
                <p style="color: #6b7280; margin-bottom: 1.5rem;">
                    Maaf, tidak ada buku yang cocok dengan kriteria pencarian Anda.
                </p>
                <a href="{{ route('books') }}" class="btn btn-primary">
                    <i class="fas fa-book-open"></i> Lihat Semua Buku
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
