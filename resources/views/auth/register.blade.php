@extends('layouts.app')

@section('title', 'Daftar Akun - Lestari Bumi')

@section('content')
<div class="container">
    <div class="form-container">
        <div class="form-title">
            <h2>Daftar Akun Baru</h2>
            <p style="color: #6c757d; font-size: 0.9rem; margin-top: 5px;">Bergabung dengan ribuan donatur lainnya sekarang</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Masukkan nama lengkap Anda">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <div class="password-input-wrapper">
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password" placeholder="Minimal 8 karakter" oninput="checkPasswordStrength(this.value)">
                    <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('password', this)" title="Lihat/Sembunyikan Kata Sandi">
                        👁️
                    </button>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <!-- Password Strength Meter -->
                <div class="password-strength-meter" id="strength-meter-container" style="display: none;">
                    <div class="strength-bar-bg">
                        <div class="strength-bar-fill" id="strength-bar"></div>
                    </div>
                    <span class="strength-text" id="strength-text">Kekuatan Kata Sandi: -</span>
                    <ul class="strength-requirements">
                        <li id="req-length"><span>❌</span> Min. 8 Karakter</li>
                        <li id="req-case"><span>❌</span> Huruf Besar & Kecil</li>
                        <li id="req-number"><span>❌</span> Mengandung Angka</li>
                        <li id="req-special"><span>❌</span> Karakter Spesial (!@#$)</li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="password-confirm">Konfirmasi Kata Sandi</label>
                <div class="password-input-wrapper">
                    <input type="password" name="password_confirmation" id="password-confirm" class="form-control" required autocomplete="new-password" placeholder="Ulangi kata sandi Anda" oninput="checkPasswordMatch()">
                    <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('password-confirm', this)" title="Lihat/Sembunyikan Kata Sandi">
                        👁️
                    </button>
                </div>
                <small id="match-text" style="display: block; margin-top: 5px; font-weight: 500; font-size: 0.8rem;"></small>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">Daftar</button>
        </form>

        <div class="form-text">
            Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.innerHTML = '🙈';
            btn.title = "Sembunyikan Kata Sandi";
        } else {
            input.type = 'password';
            btn.innerHTML = '👁️';
            btn.title = "Lihat Kata Sandi";
        }
    }

    function checkPasswordStrength(password) {
        const container = document.getElementById('strength-meter-container');
        const bar = document.getElementById('strength-bar');
        const text = document.getElementById('strength-text');

        if (!password) {
            container.style.display = 'none';
            return;
        }

        container.style.display = 'block';

        // Check criteria
        const hasLength = password.length >= 8;
        const hasCase = /[a-z]/.test(password) && /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[^A-Za-z0-9]/.test(password);

        updateRequirement('req-length', hasLength, 'Min. 8 Karakter');
        updateRequirement('req-case', hasCase, 'Huruf Besar & Kecil');
        updateRequirement('req-number', hasNumber, 'Mengandung Angka');
        updateRequirement('req-special', hasSpecial, 'Karakter Spesial (!@#$)');

        let score = 0;
        if (hasLength) score += 25;
        if (hasCase) score += 25;
        if (hasNumber) score += 25;
        if (hasSpecial) score += 25;

        // Apply visual updates
        bar.style.width = score + '%';

        if (score <= 25) {
            bar.style.backgroundColor = '#ef4444'; // Merah
            text.innerHTML = 'Kekuatan Kata Sandi: <span style="color: #ef4444;">Sangat Lemah 🔴</span>';
        } else if (score <= 50) {
            bar.style.backgroundColor = '#f97316'; // Oranye
            text.innerHTML = 'Kekuatan Kata Sandi: <span style="color: #f97316;">Lemah 🟠</span>';
        } else if (score <= 75) {
            bar.style.backgroundColor = '#eab308'; // Kuning
            text.innerHTML = 'Kekuatan Kata Sandi: <span style="color: #ca8a04;">Sedang 🟡</span>';
        } else {
            bar.style.backgroundColor = '#22c55e'; // Hijau
            text.innerHTML = 'Kekuatan Kata Sandi: <span style="color: #16a34a;">Sangat Kuat 🟢</span>';
        }
    }

    function updateRequirement(elementId, isValid, labelText) {
        const el = document.getElementById(elementId);
        if (isValid) {
            el.className = 'valid';
            el.innerHTML = '<span>✅</span> ' + labelText;
        } else {
            el.className = '';
            el.innerHTML = '<span>❌</span> ' + labelText;
        }
    }

    function checkPasswordMatch() {
        const pass = document.getElementById('password').value;
        const confirmPass = document.getElementById('password-confirm').value;
        const matchText = document.getElementById('match-text');

        if (!confirmPass) {
            matchText.innerHTML = '';
            return;
        }

        if (pass === confirmPass) {
            matchText.style.color = '#16a34a';
            matchText.innerHTML = '✓ Kata sandi cocok';
        } else {
            matchText.style.color = '#ef4444';
            matchText.innerHTML = '✗ Kata sandi tidak cocok';
        }
    }
</script>
@endsection
