@extends('layouts.app')

@section('title', 'Tentang Kami - Perpustakaan Desa Prigi')

@section('content')
<section class="hero" style="padding: 3rem 0;">
    <div class="container">
        <h1>Tentang Perpustakaan Desa Prigi</h1>
        <p>Menyediakan akses literasi dan informasi untuk masyarakat Desa Prigi</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; margin-bottom: 4rem;">
            <div>
                <h2 style="font-size: 2rem; margin-bottom: 1rem; color: #1f2937;">Sejarah Perpustakaan</h2>
                <p style="margin-bottom: 1rem; line-height: 1.8; color: #374151;">
                    Perpustakaan Desa Prigi didirikan pada tahun 2020 sebagai upaya meningkatkan literasi masyarakat
                    desa. Berawal dari koleksi sederhana yang disimpan di balai desa, kini perpustakaan telah berkembang
                    menjadi pusat informasi modern dengan ribuan koleksi buku.
                </p>
                <p style="line-height: 1.8; color: #374151;">
                    Dengan dukungan pemerintah daerah dan partisipasi masyarakat, perpustakaan terus berkembang
                    menyediakan layanan terbaik untuk seluruh warga Desa Prigi dan sekitarnya.
                </p>
            </div>
            <div style="text-align: center;">
                <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=500&h=350&fit=crop"
                     alt="Perpustakaan"
                     style="width: 100%; height: 350px; object-fit: cover; border-radius: 1rem; box-shadow: 0 8px 30px rgba(0,0,0,0.15);">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;">
            <div style="text-align: center;">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&h=350&fit=crop"
                     alt="Visi Misi"
                     style="width: 100%; height: 350px; object-fit: cover; border-radius: 1rem; box-shadow: 0 8px 30px rgba(0,0,0,0.15);">
            </div>
            <div>
                <h2 style="font-size: 2rem; margin-bottom: 2rem; color: #1f2937;">Visi & Misi</h2>

                <div style="margin-bottom: 2rem;">
                    <h3 style="font-size: 1.25rem; margin-bottom: 1rem; color: #667eea; font-weight: 600;">Visi</h3>
                    <p style="line-height: 1.8; color: #374151;">
                        Menjadi pusat informasi dan literasi terdepan yang mendukung peningkatan kualitas
                        sumber daya manusia di Desa Prigi dan sekitarnya.
                    </p>
                </div>

                <div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 1rem; color: #667eea; font-weight: 600;">Misi</h3>
                    <ul style="color: #374151; line-height: 1.8;">
                        <li>Menyediakan akses informasi yang mudah dan merata bagi seluruh masyarakat</li>
                        <li>Mengembangkan budaya baca dan literasi di kalangan masyarakat</li>
                        <li>Menyediakan layanan perpustakaan yang berkualitas dan modern</li>
                        <li>Mendukung pendidikan dan pengembangan pengetahuan masyarakat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background: #f9fafb;">
    <div class="container">
        <h2 class="section-title">Layanan Kami</h2>

        <div class="card-grid card-grid-3">
            <div class="category-card" style="border-top-color: #3b82f6;">
                <div class="category-icon" style="color: #3b82f6;">
                    <i class="fas fa-book-reader"></i>
                </div>
                <h3 class="category-name">Peminjaman Buku</h3>
                <p class="category-count">Layanan peminjaman buku dengan sistem yang mudah dan fleksible</p>
            </div>

            <div class="category-card" style="border-top-color: #10b981;">
                <div class="category-icon" style="color: #10b981;">
                    <i class="fas fa-laptop"></i>
                </div>
                <h3 class="category-name">Akses Digital</h3>
                <p class="category-count">Katalog online dan layanan digital untuk kemudahan akses</p>
            </div>

            <div class="category-card" style="border-top-color: #f59e0b;">
                <div class="category-icon" style="color: #f59e0b;">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="category-name">Ruang Baca</h3>
                <p class="category-count">Ruang baca yang nyaman dan kondusif untuk belajar</p>
            </div>

            <div class="category-card" style="border-top-color: #8b5cf6;">
                <div class="category-icon" style="color: #8b5cf6;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="category-name">Program Literasi</h3>
                <p class="category-count">Program peningkatan literasi untuk berbagai kalangan</p>
            </div>

            <div class="category-card" style="border-top-color: #ef4444;">
                <div class="category-icon" style="color: #ef4444;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 class="category-name">Event & Workshop</h3>
                <p class="category-count">Kegiatan dan workshop untuk pengembangan diri</p>
            </div>

            <div class="category-card" style="border-top-color: #06b6d4;">
                <div class="category-icon" style="color: #06b6d4;">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h3 class="category-name">Konsultasi</h3>
                <p class="category-count">Layanan konsultasi dan bantuan informasi</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="section-title">Tim Perpustakaan</h2>
        <p class="section-subtitle">Tim profesional yang siap melayani kebutuhan literasi Anda</p>

        <div class="card-grid card-grid-3">
            <div class="stat-card">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face"
                     alt="Kepala"
                     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem;">
                <h3 style="margin-bottom: 0.5rem;">Budi Santoso, S.Pd</h3>
                <p style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">Kepala Perpustakaan</p>
                <p style="font-size: 0.875rem; color: #6b7280;">Mengelola operasional dan pengembangan perpustakaan</p>
            </div>

            <div class="stat-card">
                <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=150&h=150&fit=crop&crop=face"
                     alt="Pustakawan"
                     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem;">
                <h3 style="margin-bottom: 0.5rem;">Siti Nurhaliza, S.IP</h3>
                <p style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">Pustakawan</p>
                <p style="font-size: 0.875rem; color: #6b7280;">Mengelola koleksi dan layanan peminjaman</p>
            </div>

            <div class="stat-card">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face"
                     alt="Admin"
                     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem;">
                <h3 style="margin-bottom: 0.5rem;">Ahmad Fauzi</h3>
                <p style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">Admin</p>
                <p style="font-size: 0.875rem; color: #6b7280;">Melayani administrasi dan sistem digital</p>
            </div>
        </div>
    </div>
</section>
@endsection
