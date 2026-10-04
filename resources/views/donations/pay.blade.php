@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran - Lestari Bumi')

@section('content')

<div class="container" style="margin-top: 40px; margin-bottom: 60px; max-width: 600px;">
    <div class="card" style="padding: 35px;">
        <h2 style="color: var(--primary-800); font-weight: 800; font-size: 1.6rem; text-align: center; margin-bottom: 10px;">Selesaikan Pembayaran Anda</h2>
        <p style="color: #6c757d; text-align: center; font-size: 0.95rem; margin-bottom: 30px;">ID Transaksi: #DON-{{ $donation->id }}</p>

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

        <!-- Order Summary Details -->
        <div style="background-color: var(--neutral-light-100); border: 1px solid var(--neutral-light-200); border-radius: 12px; padding: 20px; margin-bottom: 30px;">
            <h4 style="color: var(--primary-800); font-weight: 700; margin-bottom: 15px; font-size: 1.05rem;">Ringkasan Invoice</h4>
            <div class="summary-row">
                <span>Jenis Bibit:</span>
                <strong style="color: var(--primary-700);">{{ $donation->seedling->name }}</strong>
            </div>
            <div class="summary-row">
                <span>Jumlah:</span>
                <strong>{{ $donation->quantity }} bibit</strong>
            </div>
            <div class="summary-row" style="margin-bottom: 0;">
                <span>Total Bayar:</span>
                <strong style="color: var(--primary-800); font-size: 1.25rem;">Rp {{ number_format($donation->total_amount, 0, ',', '.') }}</strong>
            </div>
        </div>

        @if($donation->payment_method == 'qris')
            <!-- QRIS SECTION -->
            <div class="payment-box" style="text-align: center;">
                <div style="background: #ea2b2b; color: white; padding: 8px 15px; border-radius: 8px 8px 0 0; font-weight: 800; font-size: 1.2rem; letter-spacing: 2px; display: inline-block;">
                    QRIS
                </div>
                <div class="qr-code" style="margin-top: 15px; margin-bottom: 15px;">
                    <!-- Gambar QRIS -->
                    <img src="{{ asset('storage/seedlings/qris.png') }}"
                         alt="QRIS Lestari Bumi"
                         style="max-width: 280px; width: 100%; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                </div>
                <p style="font-size: 0.85rem; color: #6c757d; margin-bottom: 25px;">Pindai QR di atas menggunakan aplikasi e-wallet Anda (Gopay, OVO, Dana, LinkAja, Mobile Banking, dll.)</p>

                <div style="margin-top: 30px; border-top: 1px solid var(--neutral-light-200); padding-top: 25px; text-align: left;">
                    @if($donation->payment_proof)
                        <h4 style="font-weight: 700; margin-bottom: 15px;">Bukti Pembayaran QRIS Anda</h4>
                        <div style="border: 2px dashed var(--primary-300); padding: 15px; border-radius: 12px; text-align: center; background-color: var(--primary-100); margin-bottom: 15px;">
                            <span style="color: var(--primary-800); font-weight: 600; font-size: 0.9rem; display: block; margin-bottom: 10px;">✓ Bukti Pembayaran Telah Diunggah</span>
                            <img src="{{ asset('storage/' . $donation->payment_proof) }}" alt="Bukti QRIS" style="max-width: 100%; max-height: 250px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                        </div>
                        <p style="font-size: 0.9rem; color: #6c757d; text-align: center; margin-bottom: 20px;">Menunggu persetujuan admin untuk verifikasi pembayaran Anda.</p>
                    @endif

                    <form action="{{ route('donations.proof', $donation->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="payment_proof_qris" style="font-weight: 700;">{{ $donation->payment_proof ? 'Unggah Ulang Bukti Pembayaran QRIS (Opsional)' : 'Unggah Bukti Pembayaran QRIS' }}</label>
                            <input type="file" name="payment_proof" id="payment_proof_qris" class="form-control @error('payment_proof') is-invalid @enderror" {{ $donation->payment_proof ? '' : 'required' }} style="padding: 10px;">
                            @error('payment_proof')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small style="display: block; color: #6c757d; margin-top: 6px; font-size: 0.8rem;">Format: JPG, PNG, JPEG, GIF. Maks: 2MB</small>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">Kirim Bukti Pembayaran QRIS</button>
                    </form>
                </div>
            </div>
        @else
            <!-- BANK TRANSFER SECTION -->
            <div>
                <h4 style="font-weight: 700; color: var(--neutral-dark-800); margin-bottom: 10px;">Lakukan Transfer Bank Ke Rekening Berikut:</h4>
                
                <div class="bank-info">
                    <div>Bank Jago (Yayasan Lestari Bumi)</div>
                    <div class="bank-number">5037 6153 3557</div>
                    <div style="font-size: 0.85rem; color: var(--primary-800);">Nama Penerima: <strong>Lestari Bumi Foundation</strong></div>
                </div>

                <div class="bank-info" style="background-color: var(--neutral-light-200); border-left-color: #6c757d; margin-top: 10px;">
                    <div>Bank BNI (Yayasan Lestari Bumi)</div>
                    <div class="bank-number"></div>
                    <div style="font-size: 0.85rem; color: var(--neutral-dark-800);">Nama Penerima: <strong>Lestari Bumi Foundation</strong></div>
                </div>

                <div style="margin-top: 30px; border-top: 1px solid var(--neutral-light-200); padding-top: 25px;">
                    @if($donation->payment_proof)
                        <h4 style="font-weight: 700; margin-bottom: 15px;">Bukti Pembayaran Anda</h4>
                        <div style="border: 2px dashed var(--primary-300); padding: 15px; border-radius: 12px; text-align: center; background-color: var(--primary-100); margin-bottom: 15px;">
                            <span style="color: var(--primary-800); font-weight: 600; font-size: 0.9rem; display: block; margin-bottom: 10px;">✓ Bukti Pembayaran Telah Diunggah</span>
                            <img src="{{ asset('storage/' . $donation->payment_proof) }}" alt="Bukti Transfer" style="max-width: 100%; max-height: 250px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                        </div>
                        <p style="font-size: 0.9rem; color: #6c757d; text-align: center;">Menunggu persetujuan admin untuk verifikasi pembayaran Anda.</p>
                    @endif

                    <form action="{{ route('donations.proof', $donation->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
                        @csrf
                        <div class="form-group">
                            <label for="payment_proof_bank" style="font-weight: 700;">{{ $donation->payment_proof ? 'Unggah Ulang Bukti Pembayaran (Opsional)' : 'Unggah Bukti Pembayaran' }}</label>
                            <input type="file" name="payment_proof" id="payment_proof_bank" class="form-control @error('payment_proof') is-invalid @enderror" {{ $donation->payment_proof ? '' : 'required' }} style="padding: 10px;">
                            @error('payment_proof')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small style="display: block; color: #6c757d; margin-top: 6px; font-size: 0.8rem;">Format: JPG, PNG, JPEG, GIF. Maks: 2MB</small>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">Kirim Bukti Transfer</button>
                    </form>
                </div>
            </div>
        @endif

        <div style="margin-top: 30px; text-align: center; border-top: 1px solid var(--neutral-light-200); padding-top: 20px;">
            <a href="{{ route('dashboard') }}" style="text-decoration: none; color: #6c757d; font-size: 0.9rem; font-weight: 500;">
                Kembali ke Dashboard Saya
            </a>
        </div>
    </div>
</div>
@endsection