<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Platform Donasi Bibit Pohon - Bersama-sama melakukan reboisasi dan menghijaukan bumi demi masa depan yang lebih baik.">
    <title>@yield('title', 'Lestari Bumi - Donasi Bibit Pohon')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <header>
        <div class="container navbar">
            <a href="{{ url('/') }}" class="logo">
                <span class="logo-icon">🌱</span> Donasi Bibit Pohon <br>Desa Labuhan Lombok 
            </a>
            
            <nav>
                <ul class="nav-links">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}" class="{{ Request::is('admin*') ? 'active' : '' }}">Panel Admin</a></li>
                        @else
                            <li><a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}">Katalog Bibit</a></li>
                            <li><a href="{{ route('dashboard') }}" class="{{ Request::is('dashboard*') ? 'active' : '' }}">Dashboard Saya</a></li>
                            <li><a href="{{ route('donations.create') }}" class="btn btn-primary btn-sm" style="padding: 6px 15px; font-size: 0.85rem; color: white;">Donasi Sekarang</a></li>
                        @endif
                        
                        <li style="margin-left: 10px; color: var(--primary-800); font-weight: 600;">
                            Halo, {{ Auth::user()->name }}
                        </li>
                        
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm" style="padding: 6px 15px; font-size: 0.85rem; cursor: pointer;">Keluar</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}">Katalog Bibit</a></li>
                        <li><a href="{{ route('login') }}" class="{{ Request::is('login') ? 'active' : '' }}">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="btn btn-outline btn-sm" style="padding: 6px 15px; font-size: 0.85rem;">Daftar</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container footer-content">
            <div class="footer-col">
                <h3>🌱 Donasi Bibit Pohon</h3>
                <p>Kami berkomitmen untuk memudahkan aksi penanaman pohon demi menciptakan lingkungan yang lebih bersih, hijau, dan lestari bagi generasi mendatang.</p>
            </div>
            <div class="footer-col">
                <h3>Program Donasi</h3>
                <p>Setiap bibit yang Anda donasikan akan disalurkan langsung ke petani lokal dan lahan-lahan gundul yang membutuhkan penghijauan kembali secara berkala.</p>
            </div>
            <div class="footer-col">
                <h3>Kontak Kami</h3>
                <p>Email: info@desalabuhanlombok.org<br>Telepon: +62 812-3456-7890<br>Alamat: Jl. Raya Pelabuhan Kayangan</p>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} Donasi Bibit Pohon. Desa Labuhan Lombok.
        </div>
    </footer>

    @yield('scripts')
</body>
</html>