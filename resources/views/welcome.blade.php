@extends('layouts.app')

@section('title', 'Lestarikan Bumi Bersama Kami- Donasi Bibit Pohon ')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <span class="hero-badge">🌱 Gerakan Reboisasi Digital</span>
                <h1 class="hero-title">Hijaukan Bumi, Mulai Dari <span class="highlight-text">Satu Bibit Pohon</span></h1>
                <p class="hero-subtitle">Salurkan kontribusi nyata Anda untuk kelestarian lingkungan. Donasikan setengah harta anda untuk membeli bibit pohon pilihan Anda, pantau pertumbuhan kontribusi secara real-time, dan dukung bumi yang lebih asri.</p>
                <div class="hero-cta">
                    <a href="{{ route('donations.create') }}" class="btn btn-primary btn-lg">
                        <span>Donasi Sekarang</span> ➔
                    </a>
                    <a href="#catalog" class="btn btn-outline btn-lg">Lihat Katalog</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Stats Dashboard -->
    <section class="container relative-stats-container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon-wrapper bg-green-light">
                    <span class="stat-icon">🌳</span>
                </div>
                <div class="stat-details">
                    <div class="stat-number" id="stat-total-seedlings">{{ number_format($totalSeedlings, 0, ',', '.') }}</div>
                    <div class="stat-label">Bibit Didonasikan</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon-wrapper bg-emerald-light">
                    <span class="stat-icon">💰</span>
                </div>
                <div class="stat-details">
                    <div class="stat-number" id="stat-total-funds">Rp {{ number_format($totalFunds, 0, ',', '.') }}</div>
                    <div class="stat-label">Dana Terkumpul</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon-wrapper bg-blue-light">
                    <span class="stat-icon">👥</span>
                </div>
                <div class="stat-details">
                    <div class="stat-number" id="stat-total-donors">{{ number_format($totalDonors, 0, ',', '.') }}</div>
                    <div class="stat-label">Donatur Aktif</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Catalog Section -->
    <section class="container section-padding" id="catalog">
        <div class="section-title">
            <span class="section-subtitle">Pilih Aksi Nyata Anda</span>
            <h2>Katalog Bibit Pohon</h2>
            <p>Pilih jenis bibit pohon yang ingin Anda donasikan untuk ditanam di area reboisasi.</p>
        </div>

        @if($seedlings->isEmpty())
            <div class="empty-state-card">
                <div class="empty-icon">🌱</div>
                <h3>Katalog Masih Kosong</h3>
                <p>Belum ada jenis bibit pohon yang terdaftar di katalog. Hubungi admin untuk menambahkan.</p>
            </div>
        @else
            <div class="catalog-grid">
                @foreach($seedlings as $seedling)
                    <div class="seedling-card">
                        <div class="seedling-img-container">
                            @if($seedling->image_path)
                                <img src="{{ asset('storage/' . $seedling->image_path) }}" alt="{{ $seedling->name }}" class="seedling-img">
                            @else
                                <div class="seedling-placeholder">🌳</div>
                            @endif
                            <span class="badge {{ $seedling->quantity_available > 0 ? 'badge-success' : 'badge-danger' }}">
                                {{ $seedling->quantity_available > 0 ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>
                        <div class="seedling-body">
                            <h3 class="seedling-name">{{ $seedling->name }}</h3>
                            <p class="seedling-desc">{{ $seedling->description }}</p>
                            
                            <div class="seedling-footer">
                                <div class="price-info">
                                    <span class="price-label">Harga per bibit</span>
                                    <div class="seedling-price">Rp {{ number_format($seedling->price, 0, ',', '.') }}</div>
                                    <span class="seedling-stock">Stok: <strong>{{ $seedling->quantity_available }}</strong></span>
                                </div>
                                @if($seedling->quantity_available > 0)
                                    <a href="{{ route('donations.create', ['seedling_id' => $seedling->id]) }}" class="btn btn-primary btn-sm">Pilih Bibit</a>
                                @else
                                    <button class="btn btn-disabled btn-sm" disabled>Habis</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Proof of Tree Planting & Donation Gallery Section -->
    <section class="gallery-section section-padding" id="proof-gallery">
        <div class="container">
            <div class="section-title">
                <span class="section-subtitle">Transparansi Penanaman</span>
                <h2>Galeri Bukti Penanaman & Donasi</h2>
                <p>Dokumentasi nyata kegiatan penanaman bibit pohon di lapangan serta bukti penyaluran kontribusi para donatur.</p>
            </div>

            <!-- Filter Tab Buttons -->
            <div class="filter-container">
                <button class="proof-filter-btn active" data-filter="all">Semua Bukti</button>
                <button class="proof-filter-btn" data-filter="planting">🌱 Penanaman Pohon</button>
                <button class="proof-filter-btn" data-filter="donation">🤝 Penyaluran Donasi</button>
            </div>

            @if(!isset($proofs) || $proofs->isEmpty())
                <div class="empty-state-card">
                    <div class="empty-icon">📷</div>
                    <h3>Dokumentasi Sedang Diproses</h3>
                    <p>Admin akan memperbarui galeri foto penanaman secara berkala.</p>
                </div>
            @else
                <div class="proof-grid">
                    @foreach($proofs as $proof)
                        <div class="proof-card proof-item" data-type="{{ $proof->type }}">
                            <div class="proof-img-box" onclick="openLightbox('{{ asset('storage/' . $proof->image_path) }}', '{{ addslashes($proof->title) }}', '{{ addslashes($proof->location ?? '') }}', '{{ $proof->planted_at ? \Carbon\Carbon::parse($proof->planted_at)->format('d M Y') : '' }}', '{{ addslashes($proof->description ?? '') }}')">
                                <img src="{{ asset('storage/' . $proof->image_path) }}" alt="{{ $proof->title }}">
                                <span class="proof-badge {{ $proof->type == 'planting' ? 'badge-green' : 'badge-blue' }}">
                                    {{ $proof->type == 'planting' ? '🌱 Penanaman' : '🤝 Donasi' }}
                                </span>
                                <div class="proof-overlay">
                                    <span>🔍 Perbesar Foto</span>
                                </div>
                            </div>
                            <div class="proof-body">
                                <h3 class="proof-title">{{ $proof->title }}</h3>
                                <div class="proof-meta">
                                    @if($proof->location)
                                        <span class="meta-item">📍 {{ $proof->location }}</span>
                                    @endif
                                    @if($proof->planted_at)
                                        <span class="meta-item">📅 {{ \Carbon\Carbon::parse($proof->planted_at)->format('d M Y') }}</span>
                                    @endif
                                </div>
                                @if($proof->description)
                                    <p class="proof-desc">{{ $proof->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Recent Donations Section -->
    <section class="recent-donations-section section-padding">
        <div class="container">
            <div class="section-title">
                <span class="section-subtitle">Real-Time Activity</span>
                <h2>Aktivitas Donasi Terbaru</h2>
                <p>Terima kasih kepada para donatur yang baru saja berkontribusi menghijaukan bumi hari ini.</p>
            </div>

            <div class="donation-ticker-container" id="donation-ticker">
                <div id="ticker-items-wrapper">
                    <div class="loading-state">Memuat data donasi terbaru...</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div id="proof-lightbox" class="lightbox-modal">
        <div class="lightbox-content">
            <button onclick="closeLightbox()" class="lightbox-close">✕</button>
            <div class="lightbox-img-wrapper">
                <img id="lightbox-img" src="" alt="">
            </div>
            <div class="lightbox-info">
                <h3 id="lightbox-title"></h3>
                <div class="lightbox-meta">
                    <span id="lightbox-location">📍 <span id="lightbox-location-text"></span></span>
                    <span id="lightbox-date">📅 <span id="lightbox-date-text"></span></span>
                </div>
                <p id="lightbox-desc"></p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const seedlingStat = document.getElementById('stat-total-seedlings');
        const fundsStat = document.getElementById('stat-total-funds');
        const donorsStat = document.getElementById('stat-total-donors');
        const tickerWrapper = document.getElementById('ticker-items-wrapper');

        function fetchRealtimeStats() {
            fetch('{{ route("stats.json") }}')
                .then(response => response.json())
                .then(data => {
                    seedlingStat.textContent = data.total_seedlings;
                    fundsStat.textContent = data.total_funds;
                    donorsStat.textContent = data.total_donors;

                    if (data.recent.length === 0) {
                        tickerWrapper.innerHTML = `<div class="empty-ticker">Belum ada donasi terverifikasi. Jadilah yang pertama!</div>`;
                    } else {
                        let html = '';
                        data.recent.forEach(donation => {
                            const initial = donation.donor_name.charAt(0).toUpperCase();
                            html += `
                                <div class="ticker-item">
                                    <div class="ticker-avatar">${initial}</div>
                                    <div class="ticker-info">
                                        <h4>${donation.donor_name}</h4>
                                        <p>Mendonasikan <strong>${donation.quantity} bibit ${donation.seedling_name}</strong> (Rp ${donation.total_amount})</p>
                                    </div>
                                    <div class="ticker-time">${donation.time_ago}</div>
                                </div>
                            `;
                        });
                        tickerWrapper.innerHTML = html;
                    }
                })
                .catch(error => console.error("Gagal memperbarui stats:", error));
        }

        fetchRealtimeStats();
        setInterval(fetchRealtimeStats, 5000);

        // Filter proof items
        const filterBtns = document.querySelectorAll('.proof-filter-btn');
        const proofItems = document.querySelectorAll('.proof-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');
                proofItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-type') === filter) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });

    function openLightbox(imgSrc, title, location, date, desc) {
        const lightbox = document.getElementById('proof-lightbox');
        document.getElementById('lightbox-img').src = imgSrc;
        document.getElementById('lightbox-title').textContent = title;

        const locElem = document.getElementById('lightbox-location');
        if (location) {
            document.getElementById('lightbox-location-text').textContent = location;
            locElem.style.display = 'inline-block';
        } else {
            locElem.style.display = 'none';
        }

        const dateElem = document.getElementById('lightbox-date');
        if (date) {
            document.getElementById('lightbox-date-text').textContent = date;
            dateElem.style.display = 'inline-block';
        } else {
            dateElem.style.display = 'none';
        }

        document.getElementById('lightbox-desc').textContent = desc || '';
        lightbox.classList.add('active');
    }

    function closeLightbox() {
        document.getElementById('proof-lightbox').classList.remove('active');
    }
</script>

<style>
    /* Utility & General Style */
    :root {
        --primary: #16a34a;
        --primary-hover: #15803d;
        --primary-light: #f0fdf4;
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --bg-light: #f8fafc;
        --border-color: #e2e8f0;
    }

    .section-padding { padding: 60px 0; }
    
    .section-title {
        text-align: center;
        max-width: 600px;
        margin: 0 auto 40px auto;
    }
    .section-subtitle {
        color: var(--primary);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .section-title h2 {
        font-size: 2.2rem;
        color: var(--text-dark);
        margin: 8px 0;
        font-weight: 800;
    }
    .section-title p {
        color: var(--text-muted);
        font-size: 1rem;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 90px 0 120px 0;
        color: white;
        text-align: center;
        border-radius: 0 0 30px 30px;
    }
    .hero-badge {
        background: rgba(22, 163, 74, 0.2);
        color: #4ade80;
        border: 1px solid rgba(74, 222, 128, 0.3);
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 20px;
    }
    .hero-title {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 16px;
    }
    .highlight-text {
        background: linear-gradient(120deg, #4ade80, #22c55e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .hero-subtitle {
        color: #94a3b8;
        font-size: 1.1rem;
        max-width: 680px;
        margin: 0 auto 30px auto;
        line-height: 1.6;
    }
    .hero-cta { display: flex; gap: 15px; justify-content: center; }

    /* Buttons */
    .btn {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
    }
    .btn-lg { padding: 14px 28px; font-size: 1rem; }
    .btn-sm { padding: 8px 16px; font-size: 0.88rem; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: var(--primary-hover); transform: translateY(-2px); }
    .btn-outline { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); }
    .btn-outline:hover { background: rgba(255,255,255,0.2); }
    .btn-disabled { background: #cbd5e1; color: #64748b; cursor: not-allowed; }

    /* Stats Dashboard */
    .relative-stats-container { margin-top: -60px; z-index: 10; position: relative; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; }
    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 20px;
        border: 1px solid var(--border-color);
    }
    .stat-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }
    .bg-green-light { background: #dcfce7; }
    .bg-emerald-light { background: #ecfdf5; }
    .bg-blue-light { background: #e0f2fe; }
    .stat-number { font-size: 1.5rem; font-weight: 800; color: var(--text-dark); }
    .stat-label { font-size: 0.88rem; color: var(--text-muted); margin-top: 2px; }

    /* Catalog Cards */
    .catalog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; }
    .seedling-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    .seedling-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.08); }
    .seedling-img-container { height: 200px; position: relative; background: #f1f5f9; overflow: hidden; }
    .seedling-img { width: 100%; height: 100%; object-fit: cover; }
    .seedling-placeholder { font-size: 4rem; display: flex; align-items: center; justify-content: center; height: 100%; }
    .badge {
        position: absolute; top: 12px; right: 12px;
        padding: 4px 12px; border-radius: 20px;
        font-size: 0.75rem; font-weight: 700;
    }
    .badge-success { background: #dcfce7; color: #15803d; }
    .badge-danger { background: #fee2e2; color: #b91c1c; }
    
    .seedling-body { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
    .seedling-name { font-size: 1.15rem; color: var(--text-dark); font-weight: 700; margin-bottom: 8px; }
    .seedling-desc { font-size: 0.88rem; color: var(--text-muted); line-height: 1.5; flex-grow: 1; margin-bottom: 20px; }
    .seedling-footer { display: flex; justify-content: space-between; align-items: flex-end; padding-top: 15px; border-top: 1px dashed var(--border-color); }
    .price-label { font-size: 0.75rem; color: var(--text-muted); display: block; }
    .seedling-price { font-size: 1.1rem; font-weight: 800; color: var(--primary); }
    .seedling-stock { font-size: 0.75rem; color: var(--text-muted); display: block; }

    /* Gallery Proof */
    .gallery-section { background: var(--bg-light); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }
    .filter-container { display: flex; justify-content: center; gap: 10px; margin-bottom: 30px; flex-wrap: wrap; }
    .proof-filter-btn {
        padding: 8px 20px; border-radius: 30px; border: 1px solid var(--border-color);
        background: white; color: var(--text-muted); font-weight: 600; font-size: 0.88rem; cursor: pointer; transition: all 0.2s;
    }
    .proof-filter-btn.active, .proof-filter-btn:hover { background: var(--primary); color: white; border-color: var(--primary); }
    
    .proof-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px; }
    .proof-card { background: white; border-radius: 16px; border: 1px solid var(--border-color); overflow: hidden; display: flex; flex-direction: column; }
    .proof-img-box { position: relative; height: 200px; cursor: pointer; overflow: hidden; }
    .proof-img-box img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
    .proof-img-box:hover img { transform: scale(1.08); }
    .proof-overlay {
        position: absolute; inset: 0; background: rgba(0,0,0,0.4);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 600; opacity: 0; transition: opacity 0.3s ease;
    }
    .proof-img-box:hover .proof-overlay { opacity: 1; }
    .proof-badge { position: absolute; top: 12px; left: 12px; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; color: white; }
    .badge-green { background: #15803d; }
    .badge-blue { background: #0284c7; }
    
    .proof-body { padding: 18px; }
    .proof-title { font-size: 1.05rem; color: var(--text-dark); font-weight: 700; margin-bottom: 8px; }
    .proof-meta { display: flex; gap: 12px; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 10px; }
    .proof-desc { font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    /* Recent Ticker */
    .donation-ticker-container { background: white; border-radius: 16px; border: 1px solid var(--border-color); padding: 10px; }
    .ticker-item { display: flex; align-items: center; gap: 15px; padding: 12px 16px; border-bottom: 1px solid var(--bg-light); animation: fadeIn 0.4s ease; }
    .ticker-item:last-child { border-bottom: none; }
    .ticker-avatar {
        width: 42px; height: 42px; border-radius: 50%; background: var(--primary-light); color: var(--primary);
        font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;
    }
    .ticker-info { flex-grow: 1; }
    .ticker-info h4 { margin: 0; font-size: 0.95rem; color: var(--text-dark); font-weight: 700; }
    .ticker-info p { margin: 2px 0 0 0; font-size: 0.85rem; color: var(--text-muted); }
    .ticker-time { font-size: 0.75rem; color: var(--text-muted); background: var(--bg-light); padding: 4px 8px; border-radius: 6px; }

    /* Empty States */
    .empty-state-card { background: white; padding: 40px; text-align: center; border-radius: 16px; border: 1px dashed var(--border-color); color: var(--text-muted); }
    .empty-icon { font-size: 2.5rem; margin-bottom: 10px; }

    /* Lightbox Modal */
    .lightbox-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999; padding: 20px; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
    .lightbox-modal.active { display: flex; }
    .lightbox-content { background: white; border-radius: 16px; max-width: 700px; width: 100%; overflow: hidden; position: relative; max-height: 90vh; display: flex; flex-direction: column; }
    .lightbox-close { position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.5); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; z-index: 2; }
    .lightbox-img-wrapper { background: black; max-height: 50vh; overflow: hidden; display: flex; align-items: center; justify-content: center; }
    .lightbox-img-wrapper img { max-width: 100%; max-height: 50vh; object-fit: contain; }
    .lightbox-info { padding: 20px; overflow-y: auto; }
    .lightbox-info h3 { margin: 0 0 8px 0; color: var(--text-dark); font-size: 1.2rem; }
    .lightbox-meta { display: flex; gap: 15px; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 10px; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection