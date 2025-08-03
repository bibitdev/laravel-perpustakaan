@extends('layouts.app')

@section('title', $book->title . ' - Perpustakaan Desa Prigi')

@section('content')
<section class="section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: 2rem;">
            <ol style="display: flex; list-style: none; gap: 0.5rem; align-items: center; color: #6b7280;">
                <li><a href="{{ route('home') }}" style="color: #667eea; text-decoration: none;">Beranda</a></li>
                <li><i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i></li>
                <li><a href="{{ route('books') }}" style="color: #667eea; text-decoration: none;">Koleksi Buku</a></li>
                <li><i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i></li>
                <li>{{ Str::limit($book->title, 30) }}</li>
            </ol>
        </nav>

        <!-- Book Detail -->
        <div class="book-detail">
            <div class="book-detail-header">
                <div class="book-detail-cover" style="background-image: url('{{ $book->cover_image_url }}')"></div>

                <div class="book-detail-info">
                    <h1>{{ $book->title }}</h1>
                    <p style="font-size: 1.125rem; color: #6b7280; margin-bottom: 1rem;">oleh <strong>{{ $book->author }}</strong></p>

                    <span class="book-category" style="background-color: {{ $book->category->color }}20; color: {{ $book->category->color }}; font-size: 1rem; padding: 0.5rem 1rem;">
                        {{ $book->category->name }}
                    </span>

                    <div class="book-meta">
                        <div class="meta-item">
                            <span class="meta-label">ISBN</span>
                            <span class="meta-value">{{ $book->isbn ?: '-' }}</span>
                        </div>

                        <div class="meta-item">
                            <span class="meta-label">Penerbit</span>
                            <span class="meta-value">{{ $book->publisher }}</span>
                        </div>

                        <div class="meta-item">
                            <span class="meta-label">Tahun Terbit</span>
                            <span class="meta-value">{{ $book->publication_year }}</span>
                        </div>

                        <div class="meta-item">
                            <span class="meta-label">Halaman</span>
                            <span class="meta-value">{{ $book->pages ?: '-' }} halaman</span>
                        </div>

                        <div class="meta-item">
                            <span class="meta-label">Lokasi</span>
                            <span class="meta-value">{{ $book->location ?: '-' }}</span>
                        </div>

                        <div class="meta-item">
                            <span class="meta-label">Ketersediaan</span>
                            <span class="meta-value {{ $book->isAvailable() ? '' : 'unavailable' }}">
                                @if($book->isAvailable())
                                    <i class="fas fa-check-circle" style="color: #10b981;"></i>
                                    Tersedia ({{ $book->available_stock }} dari {{ $book->stock }})
                                @else
                                    <i class="fas fa-times-circle" style="color: #ef4444;"></i>
                                    Tidak Tersedia
                                @endif
                            </span>
                        </div>
                    </div>

                    <div style="margin-top: 2rem;">
                        @auth
                            @if($book->isAvailable())
                                <button class="btn btn-primary" style="margin-right: 1rem;" onclick="borrowBook()">
                                    <i class="fas fa-book-reader"></i> Pinjam Buku
                                </button>
                            @else
                                <button class="btn" style="background: #d1d5db; color: #6b7280; cursor: not-allowed;" disabled>
                                    <i class="fas fa-times-circle"></i> Tidak Tersedia
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary" style="margin-right: 1rem;">
                                <i class="fas fa-sign-in-alt"></i> Login untuk Meminjam
                            </a>
                        @endauth

                        <button class="btn btn-secondary" onclick="addToWishlist()">
                            <i class="fas fa-heart"></i> Tambah ke Wishlist
                        </button>
                    </div>
                </div>
            </div>

            @if($book->description)
                <div class="book-description">
                    <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: #1f2937;">Deskripsi</h3>
                    <p style="line-height: 1.8; color: #374151;">{{ $book->description }}</p>
                </div>
            @endif
        </div>

        <!-- Related Books -->
        @if($relatedBooks->count() > 0)
            <div class="related-books">
                <h2 class="section-title">Buku Serupa</h2>
                <div class="card-grid card-grid-4">
                    @foreach($relatedBooks as $relatedBook)
                        <div class="book-card">
                            <div class="book-cover" style="background-image: url('{{ $relatedBook->cover_image_url }}')">
                                @if($relatedBook->is_featured)
                                    <div class="book-badge">Pilihan</div>
                                @endif
                            </div>
                            <div class="book-content">
                                <h3 class="book-title">{{ Str::limit($relatedBook->title, 40) }}</h3>
                                <p class="book-author">oleh {{ $relatedBook->author }}</p>
                                <span class="book-category" style="background-color: {{ $relatedBook->category->color }}20; color: {{ $relatedBook->category->color }};">
                                    {{ $relatedBook->category->name }}
                                </span>
                                <div class="book-footer">
                                    <span class="book-stock {{ $relatedBook->isAvailable() ? '' : 'unavailable' }}">
                                        @if($relatedBook->isAvailable())
                                            <i class="fas fa-check-circle"></i> Tersedia
                                        @else
                                            <i class="fas fa-times-circle"></i> Habis
                                        @endif
                                    </span>
                                    <a href="{{ route('book.detail', $relatedBook->slug) }}" class="btn btn-primary" style="font-size: 0.75rem; padding: 0.375rem 0.75rem;">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<script>
function borrowBook() {
    if (confirm('Apakah Anda yakin ingin meminjam buku ini?')) {
        // Ajax request to borrow book
        fetch('{{ route("borrow.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                book_id: {{ $book->id }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Permintaan peminjaman berhasil dikirim!');
                location.reload();
            } else {
                alert('Terjadi kesalahan: ' + data.message);
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan sistem');
        });
    }
}

function addToWishlist() {
    alert('Fitur wishlist akan segera hadir!');
}
</script>
@endsection
