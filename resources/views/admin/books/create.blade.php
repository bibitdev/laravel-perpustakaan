@extends('layouts.admin')

@section('title', 'Tambah Buku Baru')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Buku Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Judul Buku*</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                           id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control @error('isbn') is-invalid @enderror"
                           id="isbn" name="isbn" value="{{ old('isbn') }}">
                    @error('isbn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="author" class="form-label">Penulis*</label>
                    <input type="text" class="form-control @error('author') is-invalid @enderror"
                           id="author" name="author" value="{{ old('author') }}" required>
                    @error('author')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="publisher" class="form-label">Penerbit*</label>
                            <input type="text" class="form-control @error('publisher') is-invalid @enderror"
                                   id="publisher" name="publisher" value="{{ old('publisher') }}" required>
                            @error('publisher')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="publication_year" class="form-label">Tahun Terbit*</label>
                            <input type="number" class="form-control @error('publication_year') is-invalid @enderror"
                                   id="publication_year" name="publication_year"
                                   value="{{ old('publication_year') }}" required>
                            @error('publication_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori*</label>
                            <select class="form-select @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stok*</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                   id="stock" name="stock" value="{{ old('stock') }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="cover_image" class="form-label">Cover Buku</label>
                    <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                           id="cover_image" name="cover_image">
                    <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                    @error('cover_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_featured"
                               name="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            Tampilkan di Halaman Utama
                        </label>
                    </div>
                </div> --}}

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
