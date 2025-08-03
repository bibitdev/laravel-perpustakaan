@extends('layouts.app')

@section('title', 'Daftar - Perpustakaan Desa Prigi')

@section('content')
<section class="section">
    <div class="container">
        <div style="max-width: 500px; margin: 0 auto;">
            <div style="background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <i class="fas fa-user-plus" style="font-size: 3rem; color: #667eea; margin-bottom: 1rem;"></i>
                    <h1 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Daftar Anggota Baru</h1>
                    <p style="color: #6b7280;">Bergabunglah dengan Perpustakaan Desa Prigi</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap *</label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-input @error('name') error @enderror"
                               value="{{ old('name') }}"
                               required
                               autofocus>
                        @error('name')
                            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-input @error('email') error @enderror"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-input @error('password') error @enderror"
                                   required>
                            @error('password')
                                <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password *</label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="form-input"
                                   required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Nomor Telefon</label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               class="form-input @error('phone') error @enderror"
                               value="{{ old('phone') }}"
                               placeholder="081234567890">
                        @error('phone')
                            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea id="address"
                                  name="address"
                                  class="form-input @error('address') error @enderror"
                                  rows="3"
                                  placeholder="Alamat lengkap...">{{ old('address') }}</textarea>
                        @error('address')
                            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin: 1rem 0;">
                        <label style="display: flex; align-items: flex-start; gap: 0.5rem;">
                            <input type="checkbox" required style="margin-top: 0.25rem;">
                            <span style="font-size: 0.875rem; line-height: 1.4;">
                                Saya setuju dengan <a href="#" style="color: #667eea;">syarat dan ketentuan</a>
                                yang berlaku di Perpustakaan Desa Prigi
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </button>

                    <div style="text-align: center;">
                        <p style="color: #6b7280; font-size: 0.875rem;">
                            Sudah memiliki akun?
                            <a href="{{ route('login') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
                                Masuk di sini
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

@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
