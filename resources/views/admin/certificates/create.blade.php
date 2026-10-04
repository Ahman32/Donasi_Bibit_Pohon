@extends('layouts.app')

@section('title', 'Terbitkan Sertifikat Penghargaan - Panel Admin')

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
            <h1 style="color: var(--primary-800); font-weight: 800; font-size: 2rem; margin-bottom: 5px;">Terbitkan Sertifikat Penghargaan</h1>
            <p style="color: #6c757d;">Isi formulir berikut untuk menerbitkan piagam sertifikat resmi yang dapat diunduh oleh donatur.</p>
        </div>

        <div class="card" style="max-width: 800px;">
            <form action="{{ route('admin.certificates.store') }}" method="POST">
                @csrf

                <!-- Pilih Transaksi Donasi -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="donation_id" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                        Pilih Transaksi Donasi Lunas <span style="color: red;">*</span>
                    </label>

                    @if($selectedDonation)
                        <input type="hidden" name="donation_id" id="donation_id" value="{{ $selectedDonation->id }}">
                        <div style="padding: 15px; background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">
                            <strong>#DON-{{ $selectedDonation->id }}</strong> — {{ $selectedDonation->user->name }} ({{ $selectedDonation->quantity }} bibit {{ $selectedDonation->seedling->name }} - Rp {{ number_format($selectedDonation->total_amount, 0, ',', '.') }})
                        </div>
                    @else
                        <select name="donation_id" id="donation_id" class="form-control" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;" onchange="updateDonationDetails(this)">
                            <option value="">-- Pilih Donasi yang Belum Bersertifikat --</option>
                            @foreach($eligibleDonations as $d)
                                <option value="{{ $d->id }}"
                                        data-name="{{ $d->user->name }}"
                                        data-seedling="{{ $d->seedling->name }}"
                                        data-quantity="{{ $d->quantity }}"
                                        {{ old('donation_id') == $d->id ? 'selected' : '' }}>
                                    #DON-{{ $d->id }} | {{ $d->user->name }} | {{ $d->quantity }} bibit {{ $d->seedling->name }} (Rp {{ number_format($d->total_amount, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @if($eligibleDonations->isEmpty())
                            <small style="color: #b45309; display: block; margin-top: 6px;">
                                Belum ada donasi berstatus lunas yang belum memiliki sertifikat.
                            </small>
                        @endif
                    @endif
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Nomor Sertifikat -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="certificate_number" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                            Nomor Sertifikat <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="certificate_number" id="certificate_number" class="form-control"
                               value="{{ old('certificate_number', $generatedNumber) }}" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                        <small style="color: #64748b; font-size: 0.8rem;">Dibuat otomatis namun dapat diubah sesuai format penomoran resmi Anda.</small>
                    </div>

                    <!-- Tanggal Terbit -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="issued_date" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                            Tanggal Terbit Sertifikat <span style="color: red;">*</span>
                        </label>
                        <input type="date" name="issued_date" id="issued_date" class="form-control"
                               value="{{ old('issued_date', date('Y-m-d')) }}" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>
                </div>

                <!-- Nama Penerima -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="recipient_name" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                        Nama Lengkap Penerima (Donatur) <span style="color: red;">*</span>
                    </label>
                    <input type="text" name="recipient_name" id="recipient_name" class="form-control"
                           value="{{ old('recipient_name', $selectedDonation->user->name ?? '') }}" required
                           placeholder="Nama yang akan tertera besar di piagam"
                           style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-weight: 600;">
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                    <!-- Nama Bibit Pohon -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="seedling_name" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                            Nama Bibit Pohon <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="seedling_name" id="seedling_name" class="form-control"
                               value="{{ old('seedling_name', $selectedDonation->seedling->name ?? '') }}" required
                               style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>

                    <!-- Jumlah Bibit Pohon -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="tree_count" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                            Jumlah Bibit <span style="color: red;">*</span>
                        </label>
                        <input type="number" name="tree_count" id="tree_count" class="form-control" min="1"
                               value="{{ old('tree_count', $selectedDonation->quantity ?? 1) }}" required
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
                               value="{{ old('signer_name', 'H. Muhammad Idris, S.Sos') }}" required
                               style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>

                    <!-- Jabatan Penandatangan -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="signer_title" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                            Jabatan Penandatangan <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="signer_title" id="signer_title" class="form-control"
                               value="{{ old('signer_title', 'Kepala Desa Labuhan Lombok') }}" required
                               style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>
                </div>

                <!-- Pesan Apresiasi -->
                <div class="form-group" style="margin-bottom: 30px;">
                    <label for="appreciation_message" style="font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">
                        Kalimat Apresiasi / Catatan Piagam
                    </label>
                    <textarea name="appreciation_message" id="appreciation_message" rows="3" class="form-control"
                              style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">{{ old('appreciation_message', 'Diberikan sebagai bentuk piagam penghargaan dan apresiasi atas kontribusi serta kepedulian nyata dalam aksi penanaman bibit pohon demi penghijauan dan kelestarian alam Desa Labuhan Lombok.') }}</textarea>
                </div>

                <div style="display: flex; gap: 15px; justify-content: flex-end;">
                    <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary" style="padding: 10px 20px;">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 25px; font-weight: 700;">
                        ✨ Terbitkan & Simpan Sertifikat
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
function updateDonationDetails(select) {
    var option = select.options[select.selectedIndex];
    if (option && option.value) {
        var name = option.getAttribute('data-name');
        var seedling = option.getAttribute('data-seedling');
        var quantity = option.getAttribute('data-quantity');

        if (name) document.getElementById('recipient_name').value = name;
        if (seedling) document.getElementById('seedling_name').value = seedling;
        if (quantity) document.getElementById('tree_count').value = quantity;
    }
}
</script>
@endsection
