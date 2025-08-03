@extends('layouts.app')

@section('title', 'Kontak - Perpustakaan Desa Prigi')

@section('content')
<section class="hero" style="padding: 4rem 0; background: linear-gradient(90deg, #667eea 0%, #667eea 100%); color: white;">
    <div class="container text-center">
        <h1 style="font-size: 2.5rem; font-weight: bold;">Hubungi Kami</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem;">Ada pertanyaan, kritik, atau saran? Silakan isi form di bawah ini.</p>
    </div>
</section>

<section class="section" style="padding: 4rem 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
            <div style="animation: fadeIn 1s ease;">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=600&h=400&fit=crop"
                     alt="Hubungi Kami"
                     style="width: 100%; height: auto; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            </div>

            <div style="background: #f9fafb; padding: 2rem; border-radius: 1rem; box-shadow: 0 8px 25px rgba(0,0,0,0.07); animation: slideIn 1s ease;">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                    @csrf

                    <div>
                        <label for="name" style="font-weight: 600; color: #374151;">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               placeholder="Masukkan nama lengkap"
                               class="form-control" style="padding: 0.75rem; border-radius: 0.5rem;">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div>
                        <label for="email" style="font-weight: 600; color: #374151;">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               class="form-control" style="padding: 0.75rem; border-radius: 0.5rem;">
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div>
                        <label for="subject" style="font-weight: 600; color: #374151;">Subjek</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                               placeholder="Judul pesannya apa?"
                               class="form-control" style="padding: 0.75rem; border-radius: 0.5rem;">
                        @error('subject') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div>
                        <label for="message" style="font-weight: 600; color: #374151;">Pesan</label>
                        <textarea name="message" id="message" rows="5"
                                  placeholder="Tulis pesan kamu..."
                                  class="form-control"
                                  style="padding: 0.75rem; border-radius: 0.5rem;">{{ old('message') }}</textarea>
                        @error('message') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div style="text-align: right;">
                        <button type="submit"
                                class="btn"
                                style="background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 999px; font-weight: 600; box-shadow: 0 4px 14px rgba(59,130,246,0.3); transition: 0.3s;">
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div style="margin-top: 4rem; text-align: center;">
            <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem;">Atau hubungi kami langsung</h2>
            <p style="color: #4b5563;">📍 Jl. Raya Prigi No. 88, Desa Prigi, Banjarnegara</p>
            <p style="color: #4b5563;">📧 <a href="mailto:perpustakaan@desaprigi.id">perpustakaan@desaprigi.id</a></p>
            <p style="color: #4b5563;">📱 WhatsApp: 085776289482</p>
        </div>
    </div>
</section>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes slideIn {
    from { opacity: 0; transform: translateX(50px); }
    to   { opacity: 1; transform: translateX(0); }
}
</style>
@endsection
