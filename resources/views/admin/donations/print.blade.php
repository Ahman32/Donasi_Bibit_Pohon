<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Transaksi Donasi - Lestari Bumi</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            border: none;
        }
        .btn-primary {
            background-color: #15803d;
            color: #ffffff;
        }
        .btn-secondary {
            background-color: #64748b;
            color: #ffffff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #166534;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #166534;
            margin: 0 0 10px 0;
            font-size: 1.8rem;
        }
        .header p {
            color: #64748b;
            margin: 0;
            font-size: 0.95rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 15px 20px;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #166534;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 1.4rem;
            font-weight: 800;
            color: #14532d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.88rem;
        }
        th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-paid { background-color: #dcfce7; color: #15803d; }
        .badge-pending { background-color: #fef9c3; color: #a16207; }
        .badge-failed { background-color: #fee2e2; color: #b91c1c; }

        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .action-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Action Bar (hidden on print) -->
        <div class="action-bar">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                ⬅️ Kembali ke Dashboard
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ Cetak / Print Laporan
            </button>
        </div>

        <div class="header">
            <h1>🌱 LAPORAN TRANSAKSI DONASI - LESTARI BUMI</h1>
            <p>Tanggal Cetak: {{ date('d F Y, H:i') }} WIB | Dokumentasi Sistem Informasi Reboisasi</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">TOTAL BIBIT DITANAM</div>
                <div class="stat-value">{{ number_format($totalSeedlings, 0, ',', '.') }} Bibit</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">TOTAL DANA TERKUMPUL</div>
                <div class="stat-value">Rp {{ number_format($totalFunds, 0, ',', '.') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">TOTAL DONATUR AKTIF</div>
                <div class="stat-value">{{ number_format($totalDonors, 0, ',', '.') }} Donatur</div>
            </div>
        </div>

        <h3 style="margin-bottom: 10px; color: #1e293b;">Daftar Rincian Seluruh Transaksi Donasi</h3>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Donatur</th>
                    <th>Jenis Bibit</th>
                    <th>Jumlah</th>
                    <th>Total Biaya</th>
                    <th>Metode Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donations as $index => $donation)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $donation->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <strong style="color: #0f172a;">{{ $donation->user->name }}</strong><br>
                            <span style="color: #64748b; font-size: 0.8rem;">{{ $donation->user->email }}</span>
                        </td>
                        <td style="font-weight: 600; color: #15803d;">{{ $donation->seedling->name }}</td>
                        <td>{{ $donation->quantity }} bibit</td>
                        <td style="font-weight: 700;">Rp {{ number_format($donation->total_amount, 0, ',', '.') }}</td>
                        <td style="text-transform: uppercase;">{{ str_replace('_', ' ', $donation->payment_method) }}</td>
                        <td>
                            @if($donation->payment_status == 'paid')
                                <span class="badge badge-paid">Sukses</span>
                            @elseif($donation->payment_status == 'pending')
                                <span class="badge badge-pending">Menunggu</span>
                            @else
                                <span class="badge badge-failed">Gagal</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px; color: #94a3b8;">Belum ada data transaksi donasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        // Auto trigger print window when page loads
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>

</body>
</html>