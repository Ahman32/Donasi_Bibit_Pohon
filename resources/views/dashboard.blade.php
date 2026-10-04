@extends('layouts.app')

@section('title', 'Dashboard Saya - Lestarikan Bumi')

@section('content')
<div class="dashboard-grid">
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li class="active">
                <a href="{{ route('dashboard') }}">
                    <span>📊</span> Ringkasan Donasi
                </a>
            </li>
            <li>
                <a href="{{ route('donations.create') }}">
                    <span>🌱</span> Donasi Baru
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

        <div style="margin-bottom: 40px;">
            <h1 style="color: var(--primary-800); font-weight: 800; font-size: 2rem; margin-bottom: 5px;">Ringkasan Dampak Anda</h1>
            <p style="color: #6c757d;">Terima kasih atas dedikasi Anda dalam mereboisasi bumi. Berikut rangkuman kontribusi Anda.</p>
        </div>

        <!-- Personal Stats Grid -->
        <div class="stats-grid" style="margin-bottom: 40px;">
            <div class="stat-card" style="text-align: left; display: flex; align-items: center; gap: 20px; padding: 25px;">
                <span class="stat-icon" style="font-size: 3rem; margin-bottom: 0;">🌳</span>
                <div>
                    <div class="stat-number" style="font-size: 2rem;">{{ number_format($mySeedlings, 0, ',', '.') }}</div>
                    <div class="stat-label" style="font-size: 0.8rem;">Bibit Didonasikan</div>
                </div>
            </div>
            
            <div class="stat-card" style="text-align: left; display: flex; align-items: center; gap: 20px; padding: 25px;">
                <span class="stat-icon" style="font-size: 3rem; margin-bottom: 0;">💰</span>
                <div>
                    <div class="stat-number" style="font-size: 2rem;">Rp {{ number_format($myTotalAmount, 0, ',', '.') }}</div>
                    <div class="stat-label" style="font-size: 0.8rem;">Total Kontribusi</div>
                </div>
            </div>
        </div>

        <!-- Quick Donation Action -->
        <div class="card" style="background: linear-gradient(135deg, var(--primary-800) 0%, var(--primary-700) 100%); color: white; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; border: none; margin-bottom: 30px;">
            <div style="max-width: 600px;">
                <h3 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 10px; color: white;">Mulai Donasikan Bibit Pohon Baru</h3>
                <p style="color: var(--primary-100); font-size: 0.95rem;">Pilih dari katalog bibit terbaik kami dan lakukan pembayaran mudah via QR Code (QRIS) atau transfer bank langsung.</p>
            </div>
            <a href="{{ route('donations.create') }}" class="btn btn-secondary" style="box-shadow: 0 4px 10px rgba(0,0,0,0.1); padding: 12px 25px;">Donasi Sekarang 🌱</a>
        </div>

        @php
            $myCertificates = $myDonations->filter(fn($d) => $d->certificate !== null)->map(fn($d) => $d->certificate);
        @endphp

        <!-- Certificates Showcase Section -->
        @if($myCertificates->isNotEmpty())
            <div class="card" style="margin-bottom: 30px; border-left: 5px solid #15803d; background-color: #f0fdf4;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <h3 style="color: #14532d; font-weight: 800; margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <span>📜</span> Piagam Sertifikat Penghargaan Anda ({{ $myCertificates->count() }})
                        </h3>
                        <p style="color: #166534; font-size: 0.9rem; margin-bottom: 0;">
                            Terima kasih atas dedikasi Anda dalam pelestarian alam. Sertifikat resmi telah diterbitkan dan siap diunduh dalam format PDF.
                        </p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px; margin-top: 15px;">
                    @foreach($myCertificates as $cert)
                        <div style="background: white; border: 1px solid #bbf7d0; border-radius: 10px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <span style="font-size: 0.75rem; background: #dcfce7; color: #15803d; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                        RESMI & TERVERIFIKASI
                                    </span>
                                    <span style="font-size: 0.75rem; color: #64748b;">{{ \Carbon\Carbon::parse($cert->issued_date)->format('d/m/Y') }}</span>
                                </div>
                                <h4 style="color: var(--primary-800); font-weight: 700; font-size: 1.05rem; margin-bottom: 4px;">
                                    {{ $cert->tree_count }} Bibit {{ $cert->seedling_name }}
                                </h4>
                                <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 12px;">
                                    Nomor: <strong>{{ $cert->certificate_number }}</strong>
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px; margin-top: 10px;">
                                <a href="{{ route('certificates.download', $cert->id) }}" class="btn btn-primary btn-sm" style="flex: 1; padding: 8px 12px; font-size: 0.85rem; font-weight: 700; background-color: #15803d; text-align: center; border-radius: 6px; box-shadow: 0 2px 5px rgba(21,128,61,0.2);">
                                    📥 Unduh PDF
                                </a>
                                <a href="{{ route('certificates.preview', $cert->id) }}" target="_blank" class="btn btn-secondary btn-sm" style="padding: 8px 12px; font-size: 0.85rem; background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px;" title="Pratinjau di Tab Baru">
                                    👁️
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Donation History Table -->
        <div class="card">
            <h3 class="card-title">Riwayat Donasi Anda</h3>
            
            @if($myDonations->isEmpty())
                <div style="text-align: center; padding: 30px; color: #6c757d;">
                    <p style="margin-bottom: 15px;">Anda belum melakukan donasi apapun.</p>
                    <a href="{{ route('donations.create') }}" class="btn btn-outline btn-sm">Mulai Donasi Pertama</a>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Bibit</th>
                                <th>Jumlah</th>
                                <th>Total Biaya</th>
                                <th>Metode Bayar</th>
                                <th>Status</th>
                                <th>Sertifikat / Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myDonations as $donation)
                                <tr>
                                    <td>{{ $donation->created_at->format('d M Y, H:i') }}</td>
                                    <td style="font-weight: 600; color: var(--primary-800);">{{ $donation->seedling->name }}</td>
                                    <td>{{ $donation->quantity }} bibit</td>
                                    <td style="font-weight: 600;">Rp {{ number_format($donation->total_amount, 0, ',', '.') }}</td>
                                    <td style="text-transform: uppercase; font-size: 0.85rem; font-weight: 600;">
                                        {{ str_replace('_', ' ', $donation->payment_method) }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $donation->payment_status }}">
                                            {{ $donation->payment_status == 'pending' ? 'Menunggu' : ($donation->payment_status == 'paid' ? 'Sukses' : 'Gagal') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($donation->payment_status == 'pending')
                                            <a href="{{ route('donations.pay', $donation->id) }}" class="btn btn-primary btn-sm" style="padding: 6px 12px; font-size: 0.8rem;">
                                                Selesaikan Pembayaran
                                            </a>
                                        @elseif($donation->payment_status == 'paid')
                                            @if($donation->certificate)
                                                <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                                                    <a href="{{ route('certificates.download', $donation->certificate->id) }}" class="btn btn-primary btn-sm" style="background-color: #15803d; border-radius: 6px; padding: 5px 10px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 5px rgba(21, 128, 61, 0.2);" title="Unduh Sertifikat PDF Resmi">
                                                        <span>📜</span> Unduh Sertifikat
                                                    </a>
                                                    <a href="{{ route('certificates.preview', $donation->certificate->id) }}" target="_blank" class="btn btn-secondary btn-sm" style="padding: 5px 8px; font-size: 0.75rem; background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px;" title="Pratinjau Sertifikat">
                                                        👁️
                                                    </a>
                                                </div>
                                            @else
                                                <span class="badge" style="background-color: #fef9c3; color: #a16207; font-size: 0.75rem; padding: 4px 8px; border-radius: 4px;">
                                                    ⏳ Sertifikat Diproses
                                                </span>
                                            @endif
                                        @else
                                            <span style="color: #6c757d; font-size: 0.85rem;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
