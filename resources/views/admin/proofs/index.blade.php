@extends('layouts.app')

@section('title', 'Kelola Bukti Penanaman & Donasi - Admin')

@section('content')
<div class="dashboard-grid">
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <span>📈</span> Verifikasi Donasi
                </a>
            </li>
            <li>
                <a href="{{ route('admin.seedlings.index') }}">
                    <span>🌳</span> Kelola Katalog Bibit
                </a>
            </li>
            <li class="active">
                <a href="{{ route('admin.proofs.index') }}">
                    <span>📸</span> Bukti Penanaman & Donasi
                </a>
            </li>
            <li>
                <a href="{{ route('admin.certificates.index') }}">
                    <span>📜</span> Kelola Sertifikat
                </a>
            </li>
            <li style="margin-top: 30px; border-top: 1px solid var(--neutral-light-200); padding-top: 20px;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="background: none; border: none; display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--accent-red); font-weight: 600; cursor: pointer; width: 100%; text-align: left; font-family: var(--font-inter); font-size: 0.95rem;">
                        <span>🚪</span> Keluar Sesi
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1 style="color: var(--primary-800); font-weight: 800; font-size: 2rem; margin-bottom: 5px;">Bukti Penanaman & Donasi</h1>
                <p style="color: #6c757d;">Unggah dan kelola foto bukti penanaman pohon serta dokumentasi kegiatan donasi untuk ditampilkan di halaman landing page.</p>
            </div>
        </div>

        <!-- Form Tambah Bukti -->
        <div class="card" style="margin-bottom: 40px;">
            <h3 class="card-title" style="margin-bottom: 20px; font-weight: 700; color: var(--primary-800);">➕ Tambah Foto Bukti Baru</h3>
            <form action="{{ route('admin.proofs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label for="title">Judul / Keterangan Bukti <span style="color: red;">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Contoh: Penanaman 50 Bibit Mahoni di Hutan Kota" required value="{{ old('title') }}">
                    </div>

                    <div class="form-group">
                        <label for="type">Jenis Bukti <span style="color: red;">*</span></label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="planting" {{ old('type') == 'planting' ? 'selected' : '' }}>🌱 Bukti Penanaman Pohon</option>
                            <option value="donation" {{ old('type') == 'donation' ? 'selected' : '' }}>🤝 Bukti / Dokumen Kegiatan Donasi</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="location">Lokasi Penanaman / Kegiatan</label>
                        <input type="text" id="location" name="location" class="form-control" placeholder="Contoh: Hutan Kota Bandung, Jawa Barat" value="{{ old('location') }}">
                    </div>

                    <div class="form-group">
                        <label for="planted_at">Tanggal Penanaman / Donasi</label>
                        <input type="date" id="planted_at" name="planted_at" class="form-control" value="{{ old('planted_at', date('Y-m-d')) }}">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="image">File Gambar / Foto <span style="color: red;">*</span> (Max: 4MB)</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="description">Catatan Tambahan (Opsional)</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Tuliskan catatan singkat tentang penanaman atau penyerahan donasi ini...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div style="margin-top: 20px; text-align: right;">
                    <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-weight: 600;">
                        📤 Unggah Bukti
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Bukti -->
        <div class="card">
            <h3 class="card-title" style="margin-bottom: 20px; font-weight: 700; color: var(--primary-800);">🖼️ Daftar Galeri Bukti Terunggah</h3>

            @if($proofs->isEmpty())
                <div style="text-align: center; padding: 40px; color: #6c757d;">
                    <p style="font-size: 1.1rem; margin-bottom: 10px;">Belum ada gambar bukti yang diunggah.</p>
                    <p style="font-size: 0.9rem;">Gunakan formulir di atas untuk mengunggah foto bukti penanaman pohon atau dokumentasi donasi pertama Anda.</p>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
                    @foreach($proofs as $proof)
                        <div style="border: 1px solid var(--neutral-light-200, #e2e8f0); border-radius: 12px; overflow: hidden; background: #ffffff; display: flex; flex-direction: column; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: transform 0.2s;">
                            <div style="position: relative; height: 180px; background: #f1f5f9; overflow: hidden;">
                                <img src="{{ asset('storage/' . $proof->image_path) }}" alt="{{ $proof->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                <span style="position: absolute; top: 10px; left: 10px; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; color: #fff; background: {{ $proof->type == 'planting' ? 'var(--primary-700, #15803d)' : '#0284c7' }};">
                                    {{ $proof->type == 'planting' ? '🌱 Penanaman' : '🤝 Donasi' }}
                                </span>
                            </div>
                            <div style="padding: 16px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <h4 style="margin: 0 0 8px 0; font-size: 1rem; color: var(--neutral-dark-800, #1e293b); font-weight: 700;">{{ $proof->title }}</h4>
                                    @if($proof->location)
                                        <p style="margin: 0 0 6px 0; font-size: 0.85rem; color: #64748b; display: flex; align-items: center; gap: 4px;">
                                            <span>📍</span> {{ $proof->location }}
                                        </p>
                                    @endif
                                    @if($proof->planted_at)
                                        <p style="margin: 0 0 8px 0; font-size: 0.8rem; color: #94a3b8; display: flex; align-items: center; gap: 4px;">
                                            <span>📅</span> {{ \Carbon\Carbon::parse($proof->planted_at)->format('d M Y') }}
                                        </p>
                                    @endif
                                    @if($proof->description)
                                        <p style="margin: 0; font-size: 0.85rem; color: #475569; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ $proof->description }}
                                        </p>
                                    @endif
                                </div>
                                <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                                    <a href="{{ asset('storage/' . $proof->image_path) }}" target="_blank" style="font-size: 0.8rem; color: var(--primary-700, #15803d); text-decoration: none; font-weight: 600;">
                                        🔍 Lihat Resolusi Penuh
                                    </a>
                                    <form action="{{ route('admin.proofs.destroy', $proof->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto bukti ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; cursor: pointer; font-weight: 600;">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 25px;">
                    {{ $proofs->links() }}
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
