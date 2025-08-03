<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Perpustakaan Desa Prigi - Kabupaten Banjarnegara')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            color: white;
        }

        .logo i {
            font-size: 2rem;
            color: #fbbf24;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-1px);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: #fbbf24;
            color: #1f2937;
        }

        .btn-primary:hover {
            background-color: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        }

        .btn-secondary {
            background-color: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background-color: white;
            color: #667eea;
        }

        /* Stats Section */
        .stats {
            background: white;
            padding: 4rem 0;
            margin-top: -2rem;
            position: relative;
            z-index: 10;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6b7280;
            font-weight: 500;
        }

        /* Section Styles */
        .section {
            padding: 4rem 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1f2937;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.125rem;
            color: #6b7280;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Card Grid */
        .card-grid {
            display: grid;
            gap: 2rem;
        }

        .card-grid-2 {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .card-grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .card-grid-4 {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        /* Book Card */
        .book-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .book-cover {
            height: 250px;
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }

        .book-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #fbbf24;
            color: #1f2937;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .book-content {
            padding: 1.5rem;
        }

        .book-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1f2937;
            line-height: 1.4;
        }

        .book-author {
            color: #6b7280;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
        }

        .book-category {
            display: inline-block;
            background: #e0e7ff;
            color: #3730a3;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .book-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .book-stock {
            font-size: 0.875rem;
            color: #059669;
            font-weight: 500;
        }

        .book-stock.unavailable {
            color: #dc2626;
        }

        /* Category Card */
        .category-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-top: 4px solid;
        }

        .category-card:hover {
            transform: translateY(-5px);
        }

        .category-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .category-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }

        .category-count {
            color: #6b7280;
            font-size: 0.875rem;
        }

        /* Search Section */
        .search-section {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }

        .search-form {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: end;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 3rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            color: #374151;
            text-decoration: none;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background-color: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination .active {
            background-color: #667eea;
            color: white;
            border-color: #667eea;
        }

        /* Footer */
        .footer {
            background: #1f2937;
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #fbbf24;
        }

        .footer-section p,
        .footer-section li {
            margin-bottom: 0.5rem;
            color: #d1d5db;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section a {
            color: #d1d5db;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: #fbbf24;
        }

        .footer-bottom {
            border-top: 1px solid #374151;
            padding-top: 1rem;
            text-align: center;
            color: #9ca3af;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-menu {
                display: none;
                width: 100%;
                flex-direction: column;
                gap: 0;
                margin-top: 1rem;
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-menu a {
                padding: 1rem;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }

            .mobile-menu-btn {
                display: block;
                position: absolute;
                right: 1rem;
                top: 1.25rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .search-form {
                flex-direction: column;
            }

            .form-group {
                min-width: 100%;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .card-grid-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .card-grid-4 {
                grid-template-columns: 1fr;
            }

            .hero {
                padding: 2rem 0;
            }

            .section {
                padding: 2rem 0;
            }
        }

        /* Alert Styles */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-info {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        /* Book Detail Styles */
        .book-detail {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .book-detail-header {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 2rem;
            padding: 2rem;
        }

        .book-detail-cover {
            width: 100%;
            height: 400px;
            background-size: cover;
            background-position: center;
            border-radius: 0.5rem;
        }

        .book-detail-info h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1f2937;
        }

        .book-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin: 1rem 0;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .meta-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        .meta-value {
            font-weight: 600;
            color: #1f2937;
        }

        .book-description {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .related-books {
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .book-detail-header {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .book-detail-cover {
                height: 300px;
                margin: 0 auto;
            }

            .book-meta {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="{{ route('home') }}" class="logo">
                    <i class="fas fa-book-open"></i>
                    <div>
                        <div>Perpustakaan</div>
                        <div style="font-size: 0.875rem; font-weight: 400;">Desa Prigi</div>
                    </div>
                </a>

                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>

                <nav class="nav-menu" id="navMenu">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                    <li><a href="{{ route('books') }}" class="{{ request()->routeIs('books*') ? 'active' : '' }}">Koleksi Buku</a></li>
                    <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Tentang</a></li>
                    <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a></li>
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
                            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        @endif
                        <li><a href="{{ route('profile') }}">Profil</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: white; cursor: pointer; padding: 0.5rem 1rem;">Logout</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Daftar</a></li>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="container" style="margin-top: 1rem;">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container" style="margin-top: 1rem;">
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Perpustakaan Desa Prigi</h3>
                    <p>Menyediakan akses literasi dan informasi untuk seluruh masyarakat Desa Prigi, Kabupaten Banjarnegara.</p>
                    <p><i class="fas fa-map-marker-alt"></i> Desa Prigi, Kec. Sigaluh, Kab. Banjarnegara, Jawa Tengah</p>
                    <p><i class="fas fa-phone"></i> (0286) 123-4567</p>
                    <p><i class="fas fa-envelope"></i> perpustakaan@prigi.desa.id</p>
                </div>

                <div class="footer-section">
                    <h3>Menu Utama</h3>
                    <ul>
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('books') }}">Koleksi Buku</a></li>
                        <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                        <li><a href="{{ route('contact') }}">Kontak</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Layanan</h3>
                    <ul>
                        <li><a href="#">Peminjaman Buku</a></li>
                        <li><a href="#">Perpanjangan</a></li>
                        <li><a href="#">Reservasi</a></li>
                        <li><a href="#">Panduan Anggota</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Jam Operasional</h3>
                    <ul>
                        <li>Senin - Kamis: 08:00 - 16:00</li>
                        <li>Jumat: 08:00 - 11:30</li>
                        <li>Sabtu: 08:00 - 14:00</li>
                        <li>Minggu: Tutup</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Perpustakaan Desa Prigi. Semua hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const navMenu = document.getElementById('navMenu');
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');

            if (!navMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                navMenu.classList.remove('active');
            }
        });

        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
    </script>
</body>
</html>
