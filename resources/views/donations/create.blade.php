@extends('layouts.app')

@section('title', 'Buat Donasi - Lestari Bumi')

@section('content')
<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <div style="margin-bottom: 30px;">
        <a href="{{ route('dashboard') }}" style="text-decoration: none; color: var(--primary-700); font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="checkout-grid">
        <!-- Donation Form -->
        <div class="card" style="margin-bottom: 0;">
            <h2 style="color: var(--primary-800); font-weight: 800; font-size: 1.6rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <span>🌱</span> Formulir Donasi Bibit
            </h2>

            <form action="{{ route('donations.store') }}" method="POST" id="donation-form">
                @csrf

                <!-- Seedling Selector -->
                <div class="form-group">
                    <label for="seedling_id">Pilih Jenis Bibit Pohon</label>
                    <select name="seedling_id" id="seedling_id" class="form-control @error('seedling_id') is-invalid @enderror" required>
                        <option value="" data-price="0" data-stock="0">-- Pilih Bibit Pohon --</option>
                        @foreach($seedlings as $seedling)
                            <option value="{{ $seedling->id }}" 
                                    data-price="{{ $seedling->price }}" 
                                    data-stock="{{ $seedling->quantity_available }}"
                                    {{ (old('seedling_id') == $seedling->id || $selectedSeedlingId == $seedling->id) ? 'selected' : '' }}>
                                {{ $seedling->name }} - Rp {{ number_format($seedling->price, 0, ',', '.') }} (Sisa: {{ $seedling->quantity_available }})
                            </option>
                        @endforeach
                    </select>
                    @error('seedling_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Quantity -->
                <div class="form-group">
                    <label for="quantity">Jumlah Bibit</label>
                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" required placeholder="Contoh: 5">
                    <span id="stock-warning" style="display: none; color: var(--accent-red); font-size: 0.8rem; font-weight: 600; margin-top: 5px;"></span>
                    @error('quantity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div class="form-group" style="margin-bottom: 30px;">
                    <label>Pilih Metode Pembayaran</label>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 10px;">
                        <label style="border: 2px solid var(--neutral-light-200); border-radius: 8px; padding: 15px; display: flex; align-items: center; gap: 10px; cursor: pointer; transition: var(--transition-smooth);" id="label-qris">
                            <input type="radio" name="payment_method" value="qris" required style="accent-color: var(--primary-700); width: 18px; height: 18px;" {{ old('payment_method') == 'qris' ? 'checked' : '' }}>
                            <div>
                                <strong style="display: block; font-size: 0.95rem; color: var(--neutral-dark-800);">QRIS (QR Code)</strong>
                                <span style="font-size: 0.8rem; color: #6c757d; font-weight: normal;">GoPay, OVO, Dana, M-Banking</span>
                            </div>
                        </label>

                        <label style="border: 2px solid var(--neutral-light-200); border-radius: 8px; padding: 15px; display: flex; align-items: center; gap: 10px; cursor: pointer; transition: var(--transition-smooth);" id="label-bank">
                            <input type="radio" name="payment_method" value="bank_transfer" required style="accent-color: var(--primary-700); width: 18px; height: 18px;" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                            <div>
                                <strong style="display: block; font-size: 0.95rem; color: var(--neutral-dark-800);">Transfer Bank</strong>
                                <span style="font-size: 0.8rem; color: #6c757d; font-weight: normal;">Verifikasi Manual Admin</span>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" id="btn-submit" style="width: 100%; padding: 14px; font-size: 1rem;">Lanjutkan ke Pembayaran</button>
            </form>
        </div>

        <!-- Order Summary panel -->
        <div class="order-summary">
            <h3 style="color: var(--primary-800); font-weight: 700; margin-bottom: 20px;">Ringkasan Donasi</h3>
            
            <div id="summary-empty" style="text-align: center; color: #6c757d; padding: 30px 0;">
                <p>Silakan pilih jenis bibit pohon terlebih dahulu untuk melihat kalkulasi biaya.</p>
            </div>

            <div id="summary-details" style="display: none;">
                <div class="summary-row">
                    <span>Jenis Bibit:</span>
                    <strong id="sum-name">-</strong>
                </div>
                <div class="summary-row">
                    <span>Harga Satuan:</span>
                    <strong id="sum-price">Rp 0</strong>
                </div>
                <div class="summary-row">
                    <span>Jumlah:</span>
                    <strong id="sum-qty">0 bibit</strong>
                </div>
                <div class="summary-row">
                    <span>Metode Pembayaran:</span>
                    <strong id="sum-payment" style="text-transform: uppercase;">-</strong>
                </div>
                <div class="summary-row total">
                    <span>Total Donasi:</span>
                    <span id="sum-total">Rp 0</span>
                </div>
                <div style="background-color: var(--primary-200); padding: 15px; border-radius: 8px; font-size: 0.85rem; color: var(--primary-900); font-weight: 500; text-align: center; margin-top: 20px; line-height: 1.4;">
                    🌳 Setiap bibit yang Anda donasikan berkontribusi menyerap sekitar 22kg CO2 per tahun ketika dewasa!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const seedlingSelect = document.getElementById('seedling_id');
        const quantityInput = document.getElementById('quantity');
        const paymentRadios = document.getElementsByName('payment_method');
        const submitBtn = document.getElementById('btn-submit');
        const stockWarning = document.getElementById('stock-warning');
        
        // Labels
        const labelQris = document.getElementById('label-qris');
        const labelBank = document.getElementById('label-bank');

        // Summary elements
        const sumEmpty = document.getElementById('summary-empty');
        const sumDetails = document.getElementById('summary-details');
        const sumName = document.getElementById('sum-name');
        const sumPrice = document.getElementById('sum-price');
        const sumQty = document.getElementById('sum-qty');
        const sumPayment = document.getElementById('sum-payment');
        const sumTotal = document.getElementById('sum-total');

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }

        function updateSummary() {
            const selectedOption = seedlingSelect.options[seedlingSelect.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
            const name = selectedOption.text.split(' - ')[0];
            const qty = parseInt(quantityInput.value) || 0;

            let selectedPayment = '-';
            paymentRadios.forEach(radio => {
                if(radio.checked) {
                    selectedPayment = radio.value === 'qris' ? 'QRIS (QR Code)' : 'Transfer Bank';
                    
                    // Highlight selected box
                    if(radio.value === 'qris') {
                        labelQris.style.borderColor = 'var(--primary-700)';
                        labelQris.style.backgroundColor = 'var(--primary-100)';
                        labelBank.style.borderColor = 'var(--neutral-light-200)';
                        labelBank.style.backgroundColor = 'transparent';
                    } else {
                        labelBank.style.borderColor = 'var(--primary-700)';
                        labelBank.style.backgroundColor = 'var(--primary-100)';
                        labelQris.style.borderColor = 'var(--neutral-light-200)';
                        labelQris.style.backgroundColor = 'transparent';
                    }
                }
            });

            // Enforce stock warning
            if (seedlingSelect.value !== "") {
                if (qty > stock) {
                    stockWarning.style.display = 'block';
                    stockWarning.textContent = `Peringatan: Stok hanya tersisa ${stock} bibit.`;
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.6';
                    submitBtn.style.cursor = 'not-allowed';
                } else {
                    stockWarning.style.display = 'none';
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                }

                // Show Details
                sumEmpty.style.display = 'none';
                sumDetails.style.display = 'block';

                sumName.textContent = name;
                sumPrice.textContent = formatRupiah(price);
                sumQty.textContent = `${qty} bibit`;
                sumPayment.textContent = selectedPayment;
                sumTotal.textContent = formatRupiah(price * qty);
            } else {
                sumEmpty.style.display = 'block';
                sumDetails.style.display = 'none';
                stockWarning.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }
        }

        seedlingSelect.addEventListener('change', updateSummary);
        quantityInput.addEventListener('input', updateSummary);
        paymentRadios.forEach(radio => radio.addEventListener('change', updateSummary));

        // Initial running
        updateSummary();
    });
</script>
@endsection
