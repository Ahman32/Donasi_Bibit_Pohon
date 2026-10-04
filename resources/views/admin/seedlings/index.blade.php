@extends('layouts.app')

@section('title', 'Kelola Bibit - Panel Admin')

@section('content')
<div class="dashboard-grid">
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <span>📈</span> Verifikasi Donasi
                </a>
            </li>
            <li class="active">
                <a href="{{ route('admin.seedlings.index') }}">
                    <span>🌳</span> Kelola Katalog Bibit
                </a>
            </li>
            <li>
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

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div style="margin-bottom: 40px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1 style="color: var(--primary-800); font-weight: 800; font-size: 2rem; margin-bottom: 5px;">Kelola Katalog Bibit</h1>
                <p style="color: #6c757d;">Tambahkan, perbarui, atau hapus jenis bibit pohon yang ditawarkan untuk donatur.</p>
            </div>
            <a href="{{ route('admin.seedlings.create') }}" class="btn btn-primary">
                <span>➕</span> Tambah Bibit Baru
            </a>
        </div>

        <div class="card">
            <h3 class="card-title">Katalog Bibit Pohon Aktif</h3>

            @if($seedlings->isEmpty())
                <div style="text-align: center; padding: 40px; color: #6c757d;">
                    <p style="margin-bottom: 15px;">Belum ada bibit pohon terdaftar di katalog.</p>
                    <a href="{{ route('admin.seedlings.create') }}" class="btn btn-outline btn-sm">Tambah Bibit Pertama</a>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nama Bibit</th>
                                <th>Deskripsi</th>
                                <th>Harga Satuan</th>
                                <th>Sisa Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seedlings as $seedling)
                                <tr>
                                    <td style="width: 80px;">
                                        @if($seedling->image_path)
                                            <img src="{{ asset('storage/' . $seedling->image_path) }}" alt="{{ $seedling->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div style="width: 60px; height: 60px; border-radius: 8px; background-color: var(--primary-100); display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                                                🌳
                                            </div>
                                        @endif
                                    </td>
                                    <td style="font-weight: 700; color: var(--primary-800); min-width: 150px;">
                                        {{ $seedling->name }}
                                    </td>
                                    <td style="max-width: 300px; font-size: 0.9rem; color: #555;">
                                        {{ Str::limit($seedling->description, 100) }}
                                    </td>
                                    <td style="font-weight: 600;">
                                        Rp {{ number_format($seedling->price, 0, ',', '.') }}
                                    </td>
                                    <td style="font-weight: 600; color: {{ $seedling->quantity_available > 0 ? 'inherit' : 'var(--accent-red)' }}">
                                        {{ $seedling->quantity_available }} bibit
                                    </td>
                                    <td style="min-width: 150px;">
                                        <div style="display: flex; gap: 8px;">
                                            <a href="{{ route('admin.seedlings.edit', $seedling->id) }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.8rem;">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.seedlings.destroy', $seedling->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bibit pohon ini? Semua data donasi terkait akan tetap tersimpan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 6px 12px; font-size: 0.8rem; background-color: var(--accent-red);">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 25px;">
                    {{ $seedlings->links() }}
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
