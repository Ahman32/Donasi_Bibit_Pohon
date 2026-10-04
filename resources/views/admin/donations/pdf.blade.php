<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Donasi - Lestari Bumi</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #166534;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #166534;
            font-size: 20px;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 0;
            color: #64748b;
            font-size: 10px;
        }
        .summary-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 4px 8px;
            font-size: 11px;
        }
        .summary-table td.label {
            color: #166534;
            font-weight: bold;
        }
        .summary-table td.val {
            font-weight: bold;
            text-align: right;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #166534;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px 6px;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.data-table td {
            padding: 7px 6px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
        }
        .badge-paid { background-color: #dcfce7; color: #15803d; }
        .badge-pending { background-color: #fef9c3; color: #a16207; }
        .badge-failed { background-color: #fee2e2; color: #b91c1c; }
        .footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #cbd5e1;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>🌱 Laporan Transaksi Donasi - Lestari Bumi</h1>
        <p>Dicetak otomatis pada: {{ date('d F Y, H:i') }} WIB | Sistem Informasi Donasi Bibit Pohon</p>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td class="label">Total Bibit Ditanam (Paid):</td>
                <td class="val">{{ number_format($totalSeedlings, 0, ',', '.') }} Bibit</td>
                <td class="label">Total Dana Terkumpul (Paid):</td>
                <td class="val">Rp {{ number_format($totalFunds, 0, ',', '.') }}</td>
                <td class="label">Total Donatur Unik:</td>
                <td class="val">{{ number_format($totalDonors, 0, ',', '.') }} Orang</td>
            </tr>
        </table>
    </div>

    <h3 style="margin-bottom: 8px; color: #0f172a; font-size: 13px;">Daftar Rincian Transaksi (Total: {{ count($donations) }} Record)</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 20%;">Donatur</th>
                <th style="width: 18%;">Jenis Bibit</th>
                <th style="width: 8%;">Jumlah</th>
                <th style="width: 14%;">Total Biaya</th>
                <th style="width: 10%;">Metode</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donations as $index => $donation)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $donation->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <strong>{{ $donation->user->name }}</strong><br>
                        <span style="color: #64748b; font-size: 8px;">{{ $donation->user->email }}</span>
                    </td>
                    <td>{{ $donation->seedling->name }}</td>
                    <td>{{ $donation->quantity }} bibit</td>
                    <td><strong>Rp {{ number_format($donation->total_amount, 0, ',', '.') }}</strong></td>
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

    <div class="footer">
        Lestari Bumi &copy; {{ date('Y') }} - Laporan Resmi Sistem Donasi Bibit Pohon Online. Dokumen ini dihasilkan secara otomatis.
    </div>

</body>
</html>
