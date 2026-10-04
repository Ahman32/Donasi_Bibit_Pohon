@extends('layouts.app')

@section('title', 'Edit Bibit - Panel Admin')

@section('content')
<div class="container" style="margin-top: 40px; margin-bottom: 60px; max-width: 600px;">
    <div style="margin-bottom: 30px;">
        <a href="{{ route('admin.seedlings.index') }}" style="text-decoration: none; color: var(--primary-700); font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
            ← Kembali ke Katalog
        </a>
    </div>

    <div class="card" style="padding: 35px;">
        <h2 style="color: var(--primary-800); font-weight: 800; font-size: 1.6rem; margin-bottom: 5px;">Perbarui Data Bibit</h2>
        <p style="color: #6c757d; font-size: 0.9rem; margin-bottom: 25px;">Modifikasi detail informasi atau persediaan bibit pohon dalam sistem.</p>

        <form action="{{ route('admin.seedlings.update', $seedling->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama Bibit Pohon</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $seedling->name) }}" required placeholder="Contoh: Mahoni (Swietenia mahagoni)" autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Jelaskan karakteristik, kegunaan, dan manfaat penanaman pohon ini..." style="resize: vertical;">{{ old('description', $seedling->description) }}</textarea>
                @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="price">Harga Satuan (Rupiah)</label>
                    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', intval($seedling->price)) }}" required min="0" placeholder="Contoh: 15000">
                    @error('price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quantity_available">Stok Tersisa (Bibit)</label>
                    <input type="number" name="quantity_available" id="quantity_available" class="form-control @error('quantity_available') is-invalid @enderror" value="{{ old('quantity_available', $seedling->quantity_available) }}" required min="0" placeholder="Contoh: 100">
                    @error('quantity_available')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label for="image">Ganti Foto Bibit (Optional)</label>
                
                @if($seedling->image_path)
                    <div style="margin-bottom: 12px; border: 1px solid var(--neutral-light-200); padding: 10px; border-radius: 8px; background-color: var(--neutral-light-100); display: inline-flex; align-items: center; gap: 10px;">
                        <img src="{{ asset('storage/' . $seedling->image_path) }}" alt="Foto saat ini" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                        <span style="font-size: 0.8rem; color: #6c757d;">Foto saat ini aktif</span>
                    </div>
                @endif

                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" style="padding: 10px;">
                @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <small style="display: block; color: #6c757d; margin-top: 5px; font-size: 0.8rem;">Format: JPG, JPEG, PNG. Maksimal: 2MB</small>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 13px; font-size: 0.95rem;">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
