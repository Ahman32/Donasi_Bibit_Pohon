@extends('layouts.app')

@section('title', 'Kelola Sertifikat Donasi - Panel Admin')

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

        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1 style="color: var(--primary-800); font-weight: 800; font-size: 2rem; margin-bottom: 5px;">Kelola Sertifikat Donasi</h1>
                <p style="color: #6c757d;">Terbitkan dan kelola piagam sertifikat penghargaan resmi untuk para donatur bibit pohon.</p>
            </div>
            <div>
                <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;">
                    <span>✨</span> Terbitkan Sertifikat Baru
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-grid" style="margin-bottom: 35px;">
            <div class="stat-card" style="text-align: left; display: flex; align-items: center; gap: 20px; padding: 25px;">
                <span class="stat-icon" style="font-size: 3rem; margin-bottom: 0;">📜</span>
                <div>
                    <div class="stat-number" style="font-size: 2rem;">{{ number_format($totalCertificates, 0, ',', '.') }}</div>
                    <div class="stat-label" style="font-size: 0.8rem;">Sertifikat Diterbitkan</div>
                </div>
            </div>

            <div class="stat-card" style="text-align: left; display: flex; align-items: center; gap: 20px; padding: 25px;">
                <span class="stat-icon" style="font-size: 3rem; margin-bottom: 0;">🎖️</span>
                <div>
                    <div class="stat-number" style="font-size: 2rem;">{{ number_format($totalDonorsWithCert, 0, ',', '.') }}</div>
                    <div class="stat-label" style="font-size: 0.8rem;">Donatur Tersertifikasi</div>
                </div>
            </div>

            <div class="stat-card" style="text-align: left; display: flex; align-items: center; gap: 20px; padding: 25px;">
                <span class="stat-icon" style="font-size: 3rem; margin-bottom: 0;">⏳</span>
                <div>
                    <div class="stat-number" style="font-size: 2rem; color: #b45309;">{{ count($eligibleDonations) }}</div>
                    <div class="stat-label" style="font-size: 0.8rem;">Donasi Siap Diberi Sertifikat</div>
                </div>
            </div>
        </div>

        <!-- Section: Eligible Donations without Certificate -->
        @if($eligibleDonations->isNotEmpty())
            <div class="card" style="margin-bottom: 35px; border-left: 5px solid #ca8a04; background-color: #fffbeb;">
                <h3 style="color: #854d0e; font-weight: 700; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <span>⚡</span> Donasi Lunas yang Belum Diterbitkan Sertifikat ({{ count($eligibleDonations) }})
                </h3>
                <p style="color: #713f12; font-size: 0.9rem; margin-bottom: 15px;">
                    Donasi di bawah ini telah diverifikasi pembayarannya. Anda dapat langsung menerbitkan sertifikat instan atau mengkustomisasi nama dan tanda tangan.
                </p>

                <div class="table-responsive" style="background: white; border-radius: 8px; border: 1px solid #fde68a;">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Donasi</th>
                                <th>Donatur</th>
                                <th>Jenis Bibit & Jumlah</th>
                                <th>Total Donasi</th>
                                <th>Tgl Donasi</th>
                                <th>Aksi Penerbitan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eligibleDonations as $donation)
                                <tr>
                                    <td><strong>#DON-{{ $donation->id }}</strong></td>
                                    <td>
                                        <div style="font-weight: 600; color: var(--primary-800);">{{ $donation->user->name }}</div>
                                        <div style="font-size: 0.8rem; color: #6c757d;">{{ $donation->user->email }}</div>
                                    </td>
                                    <td>
                                        <span style="font-weight: 600;">{{ $donation->quantity }} bibit</span>
                                        <span style="color: #64748b;">{{ $donation->seedling->name }}</span>
                                    </td>
                                    <td><strong>Rp {{ number_format($donation->total_amount, 0, ',', '.') }}</strong></td>
                                    <td>{{ $donation->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                            <form action="{{ route('admin.certificates.quick_generate', $donation->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm" style="padding: 5px 12px; font-size: 0.75rem; background-color: #15803d;" title="Terbitkan sertifikat otomatis">
                                                    ⚡ Terbitkan Instan
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.certificates.create', ['donation_id' => $donation->id]) }}" class="btn btn-secondary btn-sm" style="padding: 5px 10px; font-size: 0.75rem; background-color: #0284c7; color: white;">
                                                📝 Kustomisasi
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Section: Issued Certificates Table -->
        <div class="card">
            <h3 class="card-title">Daftar Sertifikat yang Telah Diterbitkan</h3>

            @if($certificates->isEmpty())
                <div style="text-align: center; padding: 40px; color: #6c757d;">
                    <span style="font-size: 3rem; display: block; margin-bottom: 10px;">📜</span>
                    <p style="margin-bottom: 15px; font-size: 1.05rem;">Belum ada sertifikat donasi yang diterbitkan.</p>
                    <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary btn-sm">Terbitkan Sertifikat Pertama</a>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No. Sertifikat</th>
                                <th>Penerima (Donatur)</th>
                                <th>Bibit Didonasikan</th>
                                <th>Tgl Terbit</th>
                                <th>Penandatangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificates as $cert)
                                <tr>
                                    <td>
                                        <strong style="color: var(--primary-700); font-size: 0.85rem;">{{ $cert->certificate_number }}</strong>
                                        <div style="font-size: 0.75rem; color: #94a3b8;">Donasi #DON-{{ $cert->donation_id }}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #0f172a;">{{ $cert->recipient_name }}</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">{{ $cert->donation->user->email ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <strong>{{ $cert->tree_count }} bibit</strong> {{ $cert->seedling_name }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($cert->issued_date)->format('d M Y') }}</td>
                                    <td>
                                        <div style="font-weight: 600; font-size: 0.85rem;">{{ $cert->signer_name }}</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">{{ $cert->signer_title }}</div>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                            <a href="{{ route('certificates.preview', $cert->id) }}" target="_blank" class="btn btn-secondary btn-sm" style="padding: 4px 8px; font-size: 0.75rem; background-color: #475569; color: white;" title="Pratinjau Sertifikat">
                                                👁️ Lihat
                                            </a>
                                            <a href="{{ route('certificates.download', $cert->id) }}" class="btn btn-secondary btn-sm" style="padding: 4px 8px; font-size: 0.75rem; background-color: #0284c7; color: white;" title="Unduh PDF">
                                                📥 PDF
                                            </a>
                                            <a href="{{ route('admin.certificates.edit', $cert->id) }}" class="btn btn-secondary btn-sm" style="padding: 4px 8px; font-size: 0.75rem; background-color: #eab308; color: #1e293b;" title="Edit Data Sertifikat">
                                                ✏️ Edit
                                            </a>
                                            <form action="{{ route('admin.certificates.destroy', $cert->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin mencabut/menghapus sertifikat #{{ $cert->certificate_number }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 0.75rem; background-color: var(--accent-red);" title="Hapus Sertifikat">
                                                    🗑️
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
                    {{ $certificates->links() }}
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
