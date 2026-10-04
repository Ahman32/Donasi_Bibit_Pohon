@extends('layouts.app')

@section('title', 'Masuk - Lestari Bumi')

@section('content')
<div class="container">
    <div class="form-container">
        <div class="form-title">
            <h2>Masuk Akun</h2>
            <p style="color: #6c757d; font-size: 0.9rem; margin-top: 5px;">Masuk untuk mulai mendonasikan bibit pohon</p>
        </div>

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

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nama@email.com">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <div class="password-input-wrapper">
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="Minimal 8 karakter">
                    <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('password', this)" title="Lihat/Sembunyikan Kata Sandi">
                        👁️
                    </button>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-bottom: 25px;">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="cursor: pointer; width: 16px; height: 16px; accent-color: var(--primary-700);">
                <label for="remember" style="margin-bottom: 0; cursor: pointer; font-weight: 500; font-size: 0.85rem; color: #555;">Ingat Saya</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">Masuk</button>
        </form>

        <div class="form-text">
            Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
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
</script>
@endsection
