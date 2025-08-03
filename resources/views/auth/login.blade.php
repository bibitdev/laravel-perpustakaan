@extends('layouts.app')

@section('title', 'Login - Perpustakaan Desa Prigi')

@section('content')
<section class="section">
    <div class="container">
        <div style="max-width: 400px; margin: 0 auto;">
            <div style="background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <i class="fas fa-book-open" style="font-size: 3rem; color: #667eea; margin-bottom: 1rem;"></i>
                    <h1 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Masuk ke Akun Anda</h1>
                    <p style="color: #6b7280;">Silakan masuk untuk mengakses layanan perpustakaan</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-input @error('email') error @enderror"
                               value="{{ old('email') }}"
                               required
                               autofocus>
                        @error('email')
                            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-input @error('password') error @enderror"
                               required>
                        @error('password')
                            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin: 1rem 0;">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span style="font-size: 0.875rem;">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </button>

                    <div style="text-align: center;">
                        <p style="color: #6b7280; font-size: 0.875rem;">
                            Belum memiliki akun?
                            <a href="{{ route('register') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
                                Daftar di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.form-input.error {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}
</style>
@endsection
