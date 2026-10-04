@extends('layouts.app')

@section('title', 'Panel Admin - Lestari Bumi')

@section('content')
<div class="dashboard-grid">
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li class="active">
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
                <h1 style="color: var(--primary-800); font-weight: 800; font-size: 2rem; margin-bottom: 5px;">Panel Administrasi</h1>
                <p style="color: #6c757d;">Pantau statistik donasi global dan verifikasi pembayaran masuk secara real-time.</p>
            </div>
        </div>

        <!-- Global Stats Grid -->
        <div class="stats-grid" style="margin-bottom: 40px;">
            <div class="stat-card" style="text-align: left; display: flex; align-items: center; gap: 20px; padding: 25px;">
                <span class="stat-icon" style="font-size: 3rem; margin-bottom: 0;">🌳</span>
                <div>
                    <div class="stat-number" style="font-size: 2rem;">{{ number_format($totalSeedlings, 0, ',', '.') }}</div>
                    <div class="stat-label" style="font-size: 0.8rem;">Bibit Ditanam (Global)</div>
                </div>
            </div>
            
            <div class="stat-card" style="text-align: left; display: flex; align-items: center; gap: 20px; padding: 25px;">
                <span class="stat-icon" style="font-size: 3rem; margin-bottom: 0;">💰</span>
                <div>
                    <div class="stat-number" style="font-size: 2rem;">Rp {{ number_format($totalFunds, 0, ',', '.') }}</div>
                    <div class="stat-label" style="font-size: 0.8rem;">Dana Terkumpul (Global)</div>
                </div>
            </div>

            <div class="stat-card" style="text-align: left; display: flex; align-items: center; gap: 20px; padding: 25px;">
                <span class="stat-icon" style="font-size: 3rem; margin-bottom: 0;">👥</span>
                <div>
                    <div class="stat-number" style="font-size: 2rem;">{{ number_format($totalDonors, 0, ',', '.') }}</div>
                    <div class="stat-label" style="font-size: 0.8rem;">Total Donatur</div>
                </div>
            </div>
        </div>

        <!-- Verification Table -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                <h3 class="card-title" style="margin-bottom: 0;">Daftar Transaksi Donasi</h3>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('admin.donations.pdf') }}" class="btn btn-secondary btn-sm" style="background-color: #0284c7; color: white; display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; font-weight: 600; border-radius: 6px;">
                        📄 Download PDF
                    </a>
                    <a href="{{ route('admin.donations.print') }}" target="_blank" class="btn btn-secondary btn-sm" style="background-color: #475569; color: white; display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; font-weight: 600; border-radius: 6px;">
                        🖨️ Cetak Laporan
                    </a>
                </div>
            </div>
            
            @if($allDonations->isEmpty())
                <div style="text-align: center; padding: 30px; color: #6c757d;">
                    <p>Belum ada transaksi donasi dalam sistem.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Donatur</th>
                                <th>Jenis Bibit</th>
                                <th>Jumlah</th>
                                <th>Total Biaya</th>
                                <th>Metode / Bukti</th>
                                <th>Status</th>
                                <th>Sertifikat</th>
                                <th>Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allDonations as $donation)
                                <tr>
                                    <td>{{ $donation->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <div style="font-weight: 600; color: var(--neutral-dark-800);">{{ $donation->user->name }}</div>
                                        <div style="font-size: 0.8rem; color: #6c757d;">{{ $donation->user->email }}</div>
                                    </td>
                                    <td style="font-weight: 600; color: var(--primary-700);">{{ $donation->seedling->name }}</td>
                                    <td>{{ $donation->quantity }} bibit</td>
                                    <td style="font-weight: 600;">Rp {{ number_format($donation->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <div style="text-transform: uppercase; font-size: 0.8rem; font-weight: 700; margin-bottom: 5px;">
                                             {{ str_replace('_', ' ', $donation->payment_method) }}
                                        </div>
                                        @if($donation->payment_proof)
                                            <a href="{{ asset('storage/' . $donation->payment_proof) }}" target="_blank" class="btn btn-secondary btn-sm" style="padding: 4px 8px; font-size: 0.75rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                                🖼️ Lihat Bukti
                                            </a>
                                        @else
                                            <span style="font-size: 0.75rem; color: var(--accent-red); font-weight: 500;">Belum unggah bukti</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $donation->payment_status }}">
                                            {{ $donation->payment_status == 'pending' ? 'Menunggu' : ($donation->payment_status == 'paid' ? 'Sukses' : 'Gagal') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($donation->payment_status == 'paid')
                                            @if($donation->certificate)
                                                <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                                    <span class="badge badge-paid" style="font-size: 0.7rem;">✓ Terbit</span>
                                                    <div style="display: flex; gap: 4px;">
                                                        <a href="{{ route('certificates.download', $donation->certificate->id) }}" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 0.7rem; background-color: #0284c7; color: white;" title="Unduh Sertifikat">
                                                            📥 PDF
                                                        </a>
                                                        <a href="{{ route('admin.certificates.edit', $donation->certificate->id) }}" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 0.7rem; background-color: #eab308; color: #1e293b;" title="Edit">
                                                            ✏️
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <div style="display: flex; gap: 4px;">
                                                    <form action="{{ route('admin.certificates.quick_generate', $donation->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary btn-sm" style="padding: 4px 8px; font-size: 0.7rem; background-color: #15803d;" title="Terbitkan Otomatis">
                                                            ⚡ Buat
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.certificates.create', ['donation_id' => $donation->id]) }}" class="btn btn-secondary btn-sm" style="padding: 4px 6px; font-size: 0.7rem; background-color: #0284c7; color: white;" title="Kustomisasi">
                                                        📝
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <span style="color: #94a3b8; font-size: 0.75rem;">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($donation->payment_status == 'pending')
                                            <div style="display: flex; gap: 5px;">
                                                <form action="{{ route('admin.donations.approve', $donation->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm" style="padding: 6px 10px; font-size: 0.75rem; background-color: var(--primary-700);" onclick="return confirm('Apakah Anda yakin ingin menyetujui pembayaran donasi ini?')">
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.donations.reject', $donation->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm" style="padding: 6px 10px; font-size: 0.75rem; background-color: var(--accent-red);" onclick="return confirm('Apakah Anda yakin ingin menolak donasi ini?')">
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span style="color: #6c757d; font-size: 0.85rem;">Terverifikasi</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Custom styled simple pagination -->
                <div style="margin-top: 25px;">
                    {{ $allDonations->links() }}
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
