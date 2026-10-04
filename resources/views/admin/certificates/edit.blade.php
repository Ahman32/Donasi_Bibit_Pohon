@extends('layouts.app')

@section('title', 'Edit Sertifikat Donasi - Panel Admin')

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
            <li>
                <a href="{{ route('admin.proofs.index') }}">
                    <span>📸</span> Bukti Penanaman & Donasi
                </a>
            </li>
            <li class="active">
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
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
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

        <div style="margin-bottom: 30px;">
            <a href="{{ route('admin.certificates.index') }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 10px;">
                ← Kembali ke Daftar Sertifikat
            </a>
            <h1 style="color: var(--primary-800); font-weight: 800; font-size: 2rem; margin-bottom: 5px;">Edit Data Sertifikat</h1>
            <p style="color: #6c757d;">Perbarui data atau format sertifikat #{{ $certificate->certificate_number }}.</p>
        </div>

        <div class="card" style="max-width: 800px;">
            <form action="{{ route('admin.certificates.update', $certificate->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="padding: 15px; background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; margin-bottom: 25px;">
                    <strong>Terkait Donasi:</strong> #DON-{{ $certificate->donation_id }} — Akun Donatur: {{ $certificate->donation->user->name ?? '-' }} ({{ $certificate->donation->user->email ?? '-' }})
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Nomor Sertifikat -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="certificate_number" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                            Nomor Sertifikat <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="certificate_number" id="certificate_number" class="form-control"
                               value="{{ old('certificate_number', $certificate->certificate_number) }}" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>

                    <!-- Tanggal Terbit -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="issued_date" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                            Tanggal Terbit Sertifikat <span style="color: red;">*</span>
                        </label>
                        <input type="date" name="issued_date" id="issued_date" class="form-control"
                               value="{{ old('issued_date', \Carbon\Carbon::parse($certificate->issued_date)->format('Y-m-d')) }}" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>
                </div>

                <!-- Nama Penerima -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="recipient_name" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                        Nama Lengkap Penerima (Donatur) <span style="color: red;">*</span>
                    </label>
                    <input type="text" name="recipient_name" id="recipient_name" class="form-control"
                           value="{{ old('recipient_name', $certificate->recipient_name) }}" required
                           style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-weight: 600;">
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                    <!-- Nama Bibit Pohon -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="seedling_name" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                            Nama Bibit Pohon <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="seedling_name" id="seedling_name" class="form-control"
                               value="{{ old('seedling_name', $certificate->seedling_name) }}" required
                               style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>

                    <!-- Jumlah Bibit Pohon -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="tree_count" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                            Jumlah Bibit <span style="color: red;">*</span>
                        </label>
                        <input type="number" name="tree_count" id="tree_count" class="form-control" min="1"
                               value="{{ old('tree_count', $certificate->tree_count) }}" required
                               style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Nama Penandatangan -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="signer_name" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                            Nama Pejabat Penandatangan <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="signer_name" id="signer_name" class="form-control"
                               value="{{ old('signer_name', $certificate->signer_name) }}" required
                               style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>

                    <!-- Jabatan Penandatangan -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="signer_title" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                            Jabatan Penandatangan <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="signer_title" id="signer_title" class="form-control"
                               value="{{ old('signer_title', $certificate->signer_title) }}" required
                               style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>
                </div>

                <!-- Pesan Apresiasi -->
                <div class="form-group" style="margin-bottom: 30px;">
                    <label for="appreciation_message" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                        Kalimat Apresiasi / Catatan Piagam
                    </label>
                    <textarea name="appreciation_message" id="appreciation_message" rows="3" class="form-control"
                              style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">{{ old('appreciation_message', $certificate->appreciation_message) }}</textarea>
                </div>

                <div style="display: flex; gap: 15px; justify-content: flex-end;">
                    <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary" style="padding: 10px 20px;">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 25px; font-weight: 700;">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
