<?= $this->extend('layouts/main') ?>

<?php
$isLoggedIn    = (bool) session()->get('isLoggedIn');
$namaKalurahan = $pengaturan['nama_kalurahan'] ?? 'Condongcatur';
?>

<?= $this->section('styles') ?>
<style>
    /* Hero Section (Jatimprov Style) */
    .gov-hero {
        position: relative;
        background:
            linear-gradient(90deg, rgba(6, 44, 128, 0.92) 0%, rgba(10, 61, 168, 0.82) 56%, rgba(14, 77, 198, 0.52) 100%),
            url('<?= base_url('images/dashboard1.jpg') ?>') center center / cover no-repeat;
        color: #ffffff;
        padding: 4.25rem 1.5rem 8.5rem;
    }

    .gov-hero-inner {
        max-width: 1240px;
        margin: 0 auto;
    }

    .gov-hero-title {
        font-size: clamp(2rem, 3.8vw, 3rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.02em;
        text-transform: uppercase;
        max-width: 680px;
        margin-bottom: 0.9rem;
    }

    .gov-hero-sub {
        font-size: 0.98rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 580px;
        margin-bottom: 1.85rem;
        font-weight: 400;
    }

    .hero-search-form {
        max-width: 540px;
        background: #ffffff;
        border-radius: 10px;
        padding: 6px 6px 6px 16px;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 10px 25px rgba(2, 24, 75, 0.25);
    }

    .hero-search-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 0.88rem;
        color: #0f172a;
        background: transparent;
        min-width: 0;
    }

    .hero-search-input::placeholder {
        color: #94a3b8;
    }

    .hero-search-btn {
        border: none;
        background: var(--gov-blue-accent);
        color: #ffffff;
        font-size: 0.86rem;
        font-weight: 600;
        padding: 0.62rem 1.35rem;
        border-radius: 7px;
        cursor: pointer;
        transition: background-color 0.15s ease;
        flex-shrink: 0;
    }

    .hero-search-btn:hover {
        background: #0b5ed7;
    }

    /* 2 Overlapping Highlight Photo Cards */
    .highlight-wrap {
        max-width: 1240px;
        margin: -5.25rem auto 0;
        padding: 0 1.5rem;
        position: relative;
        z-index: 10;
    }

    .highlight-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.5rem;
    }

    .highlight-card {
        position: relative;
        height: 290px;
        border-radius: 12px;
        overflow: hidden;
        background: #0f172a;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.14);
        display: block;
        text-decoration: none;
    }

    .highlight-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.35s ease;
    }

    .highlight-card:hover img {
        transform: scale(1.03);
    }

    .highlight-card-bright img {
        object-position: center 22%;
        filter: brightness(1.22) contrast(1.04);
    }

    .highlight-card-pamong img {
        object-position: center 25%;
    }

    .highlight-caption {
        position: absolute;
        inset: auto 0 0 0;
        padding: 2.25rem 1.25rem 1rem;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.82) 100%);
        color: #ffffff;
    }

    .highlight-tag {
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #93c5fd;
        margin-bottom: 0.2rem;
    }

    .highlight-title {
        font-size: 0.98rem;
        font-weight: 700;
        line-height: 1.3;
    }

    /* Section Shell */
    .gov-section {
        max-width: 1240px;
        margin: 0 auto;
        padding: 3.25rem 1.5rem 0;
    }

    .gov-section-last {
        padding-bottom: 4rem;
    }

    .section-header-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .section-title {
        font-size: 1.55rem;
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.02em;
        line-height: 1.25;
    }

    .section-subtitle {
        font-size: 0.88rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    /* Filter Category Pills (Jatimprov Tabs) */
    .category-tabs {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
    }

    .cat-tab {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.62rem 1.15rem;
        border-radius: 8px;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
        transition: all 0.15s ease;
    }

    .cat-tab:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }

    .cat-tab.is-active {
        background: var(--gov-blue-accent);
        border-color: var(--gov-blue-accent);
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.22);
    }

    /* Layanan Grid (4 columns like Jatimprov) */
    .service-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.25rem;
    }

    .service-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 12px;
        padding: 1.35rem;
        box-shadow: var(--shadow-card);
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }

    .service-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-hover);
        border-color: #cbd5e1;
    }

    .service-card.is-hidden {
        display: none;
    }

    .service-emblem {
        width: 34px;
        height: 34px;
        object-fit: contain;
        border-radius: 6px;
        margin-bottom: 0.95rem;
    }

    .service-card-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.35;
        margin-bottom: 0.45rem;
    }

    .service-card-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    /* Berita / Galeri Terkini Grid */
    .media-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.25rem;
    }

    .media-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-card);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .media-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-hover);
    }

    .media-thumb {
        width: 100%;
        height: 170px;
        object-fit: cover;
        display: block;
    }

    .media-body {
        padding: 1rem 1.15rem 1.15rem;
    }

    .media-tag {
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--gov-blue-accent);
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.35rem;
    }

    .media-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.38;
    }

    /* Dokumen / Panduan Cards (Screenshot 3 Top Style) */
    .doc-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.25rem;
    }

    .doc-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: var(--shadow-card);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 1.15rem;
    }

    .doc-card-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.38;
        margin-bottom: 0.35rem;
    }

    .doc-card-sub {
        font-size: 0.79rem;
        color: var(--text-muted);
        line-height: 1.45;
    }

    .doc-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding-top: 0.25rem;
    }

    .doc-type-label {
        font-size: 0.74rem;
        font-weight: 700;
        color: #475569;
    }

    .btn-preview {
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #1e293b;
        font-size: 0.76rem;
        font-weight: 600;
        padding: 0.42rem 0.72rem;
        border-radius: 7px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-preview:hover {
        background: #f1f5f9;
    }

    /* Preview Modal */
    .gov-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(2px);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        z-index: 1200;
    }

    .gov-modal-backdrop.is-open {
        display: flex;
    }

    .gov-modal-card {
        width: 100%;
        max-width: 480px;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 24px 50px rgba(15, 23, 42, 0.25);
        overflow: hidden;
    }

    .gov-modal-head {
        padding: 1.1rem 1.35rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .gov-modal-head h3 {
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .gov-modal-close {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        cursor: pointer;
        font-size: 1rem;
    }

    .gov-modal-body {
        padding: 1.2rem 1.35rem 1.4rem;
        font-size: 0.86rem;
        color: #334155;
    }

    .gov-modal-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    .gov-modal-list li {
        display: flex;
        align-items: flex-start;
        gap: 0.55rem;
    }

    .gov-modal-list i {
        color: #10b981;
        margin-top: 0.22rem;
    }

    @media (max-width: 1024px) {
        .service-grid,
        .media-grid,
        .doc-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 720px) {
        .gov-hero {
            padding: 3rem 1rem 6.75rem;
        }
        .highlight-wrap {
            margin-top: -4.25rem;
            padding: 0 1rem;
        }
        .highlight-grid {
            grid-template-columns: 1fr;
        }
        .highlight-card {
            height: 220px;
        }
        .gov-section {
            padding: 2.5rem 1rem 0;
        }
        .service-grid,
        .media-grid,
        .doc-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Scroll Reveal Animation (Muncul & Hilang Saat Scroll) */
    .scroll-reveal {
        opacity: 0;
        transform: translateY(28px) scale(0.97);
        transition:
            opacity 0.52s cubic-bezier(0.22, 1, 0.36, 1),
            transform 0.52s cubic-bezier(0.22, 1, 0.36, 1),
            box-shadow 0.2s ease,
            border-color 0.2s ease;
        will-change: opacity, transform;
    }

    .scroll-reveal.is-above {
        transform: translateY(-22px) scale(0.97);
    }

    .scroll-reveal.is-visible {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .highlight-card.scroll-reveal.is-visible:hover,
    .service-card.scroll-reveal.is-visible:hover,
    .media-card.scroll-reveal.is-visible:hover,
    .doc-card.scroll-reveal.is-visible:hover {
        transform: translateY(-3px) scale(1);
    }

    @media (prefers-reduced-motion: reduce) {
        .scroll-reveal {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- Hero Banner -->
    <section class="gov-hero">
        <div class="gov-hero-inner">
            <h1 class="gov-hero-title">
                Website Resmi Layanan<br>SKU <?= esc($namaKalurahan) ?>
            </h1>
            <p class="gov-hero-sub">
                Temukan informasi dan ajukan Surat Keterangan Usaha (SKU) Kalurahan <?= esc($namaKalurahan) ?> secara cepat dan transparan.
            </p>

            <form class="hero-search-form" id="hero-search-form">
                <input
                    type="text"
                    id="hero-search-input"
                    class="hero-search-input"
                    placeholder="Cari layanan atau syarat SKU yang Anda butuhkan"
                    aria-label="Cari layanan SKU"
                >
                <button type="submit" class="hero-search-btn">Cari</button>
            </form>
        </div>
    </section>

    <!-- 2 Banner Foto Sorotan -->
    <div class="highlight-wrap">
        <div class="highlight-grid">
            <a href="#dokumen-sku" class="highlight-card highlight-card-bright">
                <img src="<?= base_url('images/Hero Panel.png') ?>" alt="Transparansi APBKal dan Inovasi Layanan Condongcatur">
                <div class="highlight-caption">
                    <span class="highlight-tag">Transparansi &amp; Inovasi</span>
                    <div class="highlight-title">Inovasi Layanan &amp; Informasi APBKal Kalurahan Condongcatur</div>
                </div>
            </a>

            <a href="#layanan-terintegrasi" class="highlight-card highlight-card-pamong">
                <img src="<?= base_url('images/isian1.jpeg') ?>" alt="Pamong Kalurahan Condongcatur">
                <div class="highlight-caption">
                    <span class="highlight-tag">Pelayanan Masyarakat</span>
                    <div class="highlight-title">Pamong Kalurahan Siap Melayani Legalitas Usaha Warga</div>
                </div>
            </a>
        </div>
    </div>

    <!-- Layanan Terintegrasi -->
    <section class="gov-section" id="layanan-terintegrasi">
        <div class="section-header-row">
            <div>
                <h2 class="section-title">Layanan Terintegrasi</h2>
                <p class="section-subtitle">Informasi layanan pengajuan Surat Keterangan Usaha Kalurahan <?= esc($namaKalurahan) ?></p>
            </div>
        </div>

        <div class="category-tabs" role="tablist" aria-label="Kategori Layanan SKU">
            <button type="button" class="cat-tab is-active" data-cat="all">Semua Layanan</button>
            <button type="button" class="cat-tab" data-cat="pengajuan">Pengajuan &amp; Daftar</button>
            <button type="button" class="cat-tab" data-cat="pemantauan">Status &amp; Cetak SKU</button>
            <button type="button" class="cat-tab" data-cat="syarat">Persyaratan Berkas</button>
        </div>

        <div class="service-grid" id="layanan-grid">
            <div class="service-card" data-category="pengajuan" data-keywords="pengajuan sku baru daftar usaha">
                <img src="<?= base_url('images/logo%20sleman.jpeg') ?>" alt="" class="service-emblem">
                <div class="service-card-title">Pengajuan SKU Baru</div>
                <p class="service-card-desc">Formulir permohonan penerbitan Surat Keterangan Usaha baru secara daring.</p>
            </div>

            <div class="service-card" data-category="pengajuan" data-keywords="perpanjangan sku masa berlaku usaha">
                <img src="<?= base_url('images/logo%20sleman.jpeg') ?>" alt="" class="service-emblem">
                <div class="service-card-title">Perpanjangan SKU</div>
                <p class="service-card-desc">Pembaruan masa berlaku Surat Keterangan Usaha yang telah terdaftar sebelumnya.</p>
            </div>

            <div class="service-card" data-category="pemantauan" data-keywords="lacak status cetak pdf unduh tte">
                <img src="<?= base_url('images/logo%20sleman.jpeg') ?>" alt="" class="service-emblem">
                <div class="service-card-title">Status &amp; Unduh PDF SKU</div>
                <p class="service-card-desc">Pantau progres verifikasi staf, pengesahan Lurah, dan cetak surat resmi.</p>
            </div>

            <div class="service-card" data-category="pengajuan" data-keywords="registrasi nik warga ktp akun baru">
                <img src="<?= base_url('images/logo%20sleman.jpeg') ?>" alt="" class="service-emblem">
                <div class="service-card-title">Registrasi NIK Pemohon</div>
                <p class="service-card-desc">Aktivasi akun warga Condongcatur berbasis verifikasi NIK kependudukan.</p>
            </div>

            <div class="service-card" data-category="syarat" data-keywords="syarat berkas ktp kk pengantar rt rw foto usaha">
                <img src="<?= base_url('images/logo%20sleman.jpeg') ?>" alt="" class="service-emblem">
                <div class="service-card-title">Persyaratan Berkas SKU</div>
                <p class="service-card-desc">Daftar dokumen pengantar RT/RW, KTP, KK, dan foto lokasi usaha pemohon.</p>
            </div>
        </div>
    </section>

    <!-- Berita & Kegiatan Terkini -->
    <section class="gov-section" id="kegiatan-kalurahan">
        <div class="section-header-row">
            <div>
                <h2 class="section-title">Berita Terkini</h2>
                <p class="section-subtitle">Informasi kegiatan pemerintahan dan kemasyarakatan Kalurahan <?= esc($namaKalurahan) ?></p>
            </div>
        </div>

        <div class="media-grid">
            <article class="media-card">
                <img src="<?= base_url('images/isian2.jpeg') ?>" alt="Musyawarah Kalurahan Condongcatur" class="media-thumb">
                <div class="media-body">
                    <span class="media-tag">Pemerintahan</span>
                    <h3 class="media-title">Musyawarah Kalurahan Penyusunan RKPKal T.A 2027</h3>
                </div>
            </article>

            <article class="media-card">
                <img src="<?= base_url('images/isian3.jpeg') ?>" alt="Kirab Budaya Kalurahan Condongcatur" class="media-thumb">
                <div class="media-body">
                    <span class="media-tag">Budaya</span>
                    <h3 class="media-title">Kirab Budaya dan Bregada Masyarakat Condongcatur</h3>
                </div>
            </article>

            <article class="media-card">
                <img src="<?= base_url('images/isian4.jpeg') ?>" alt="Silaturahmi Pamong dan Lembaga Kalurahan" class="media-thumb">
                <div class="media-body">
                    <span class="media-tag">Kegiatan</span>
                    <h3 class="media-title">Sinergi Pamong dan Lembaga Kalurahan Condongcatur</h3>
                </div>
            </article>

            <article class="media-card">
                <img src="<?= base_url('images/dashboard1.jpg') ?>" alt="Kantor Kalurahan Condongcatur" class="media-thumb">
                <div class="media-body">
                    <span class="media-tag">Pelayanan</span>
                    <h3 class="media-title">Pelayanan Terpadu Pendopo Kalurahan Condongcatur</h3>
                </div>
            </article>
        </div>
    </section>

    <!-- Dokumen & Persyaratan SKU (Informasi & Pratinjau) -->
    <section class="gov-section gov-section-last" id="dokumen-sku">
        <div class="section-header-row">
            <div>
                <h2 class="section-title">Dokumen &amp; Panduan SKU</h2>
                <p class="section-subtitle">Informasi syarat berkas dan tahapan layanan Surat Keterangan Usaha</p>
            </div>
        </div>

        <div class="doc-grid">
            <div class="doc-card">
                <div>
                    <div class="doc-card-title">Syarat Pengajuan SKU Baru</div>
                    <p class="doc-card-sub">Kelengkapan dokumen wajib untuk permohonan surat keterangan usaha baru.</p>
                </div>
                <div class="doc-card-footer">
                    <span class="doc-type-label">SYARAT</span>
                    <button type="button" class="btn-preview" data-doc-key="sku-baru">
                        <i class="fa-regular fa-eye"></i> Pratinjau
                    </button>
                </div>
            </div>

            <div class="doc-card">
                <div>
                    <div class="doc-card-title">Syarat Perpanjangan SKU</div>
                    <p class="doc-card-sub">Dokumen yang diperlukan untuk pembaruan masa berlaku SKU tahunan.</p>
                </div>
                <div class="doc-card-footer">
                    <span class="doc-type-label">SYARAT</span>
                    <button type="button" class="btn-preview" data-doc-key="sku-perpanjangan">
                        <i class="fa-regular fa-eye"></i> Pratinjau
                    </button>
                </div>
            </div>

            <div class="doc-card">
                <div>
                    <div class="doc-card-title">Alur Verifikasi &amp; TTE Lurah</div>
                    <p class="doc-card-sub">Empat tahap pemrosesan mulai dari unggah berkas hingga PDF terbit.</p>
                </div>
                <div class="doc-card-footer">
                    <span class="doc-type-label">ALUR</span>
                    <button type="button" class="btn-preview" data-doc-key="alur-layanan">
                        <i class="fa-regular fa-eye"></i> Pratinjau
                    </button>
                </div>
            </div>

            <div class="doc-card">
                <div>
                    <div class="doc-card-title">Ketentuan &amp; Jam Layanan</div>
                    <p class="doc-card-sub">Jadwal operasional verifikasi staf pelayanan Kalurahan <?= esc($namaKalurahan) ?>.</p>
                </div>
                <div class="doc-card-footer">
                    <span class="doc-type-label">INFO</span>
                    <button type="button" class="btn-preview" data-doc-key="jam-layanan">
                        <i class="fa-regular fa-eye"></i> Pratinjau
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Pratinjau Panduan -->
    <div class="gov-modal-backdrop" id="guide-modal" role="dialog" aria-modal="true" aria-labelledby="guide-modal-title">
        <div class="gov-modal-card">
            <div class="gov-modal-head">
                <h3 id="guide-modal-title">Pratinjau</h3>
                <button type="button" class="gov-modal-close" id="guide-modal-close" aria-label="Tutup">&times;</button>
            </div>
            <div class="gov-modal-body">
                <ul class="gov-modal-list" id="guide-modal-list"></ul>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    // Filter kategori pada Layanan Terintegrasi
    var tabs  = document.querySelectorAll('.cat-tab');
    var cards = document.querySelectorAll('#layanan-grid .service-card');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            var cat = tab.getAttribute('data-cat');
            tabs.forEach(function (t) {
                t.classList.toggle('is-active', t === tab);
            });
            cards.forEach(function (card) {
                var cardCat = card.getAttribute('data-category');
                var visible = cat === 'all' || cardCat === cat;
                card.classList.toggle('is-hidden', !visible);
                if (visible) {
                    card.classList.add('is-visible');
                }
            });
        });
    });

    // Pencarian Hero menuju Layanan Terintegrasi
    var searchForm  = document.getElementById('hero-search-form');
    var searchInput = document.getElementById('hero-search-input');

    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var q = searchInput.value.trim().toLowerCase();

            tabs.forEach(function (t) {
                t.classList.toggle('is-active', t.getAttribute('data-cat') === 'all');
            });

            cards.forEach(function (card) {
                var hay = (card.textContent + ' ' + (card.getAttribute('data-keywords') || '')).toLowerCase();
                var match = q === '' || hay.indexOf(q) !== -1;
                card.classList.toggle('is-hidden', !match);
                if (match) {
                    card.classList.add('is-visible');
                }
            });

            var target = document.getElementById('layanan-terintegrasi');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

    // Modal Pratinjau Dokumen / Panduan
    var guideData = {
        'sku-baru': {
            title: 'Syarat Pengajuan SKU Baru',
            items: [
                'Surat Pengantar dari RT / RW atau Dukuh setempat (PDF/JPG/PNG, maks 2 MB)',
                'Scan atau foto KTP Pemohon yang masih berlaku',
                'Scan atau foto Kartu Keluarga (KK) Pemohon',
                'Foto tempat atau aktivitas usaha yang sedang dijalankan'
            ]
        },
        'sku-perpanjangan': {
            title: 'Syarat Perpanjangan SKU',
            items: [
                'Lembar Surat Keterangan Usaha (SKU) lama yang akan diperpanjang',
                'Scan atau foto KTP & Kartu Keluarga (KK) Pemohon',
                'Foto terbaru kondisi tempat usaha saat ini'
            ]
        },
        'alur-layanan': {
            title: 'Alur Pelayanan 4 Tahap',
            items: [
                '1. Pemohon mengisi data usaha & mengunggah berkas melalui akun terdaftar',
                '2. Staf Pelayanan memeriksa keabsahan berkas & menerbitkan nomor surat resmi',
                '3. Lurah Condongcatur mengesahkan dokumen dengan Tanda Tangan Elektronik (BSrE)',
                '4. Pemohon dapat langsung mengunduh & mencetak PDF SKU dari dashboard'
            ]
        },
        'jam-layanan': {
            title: 'Ketentuan & Jam Layanan',
            items: [
                'Pengajuan berkas daring dapat dilakukan kapan saja selama 24 jam',
                'Verifikasi staf pelayanan berlangsung Senin – Jumat pukul 08.00 – 15.30 WIB',
                'Layanan pembuatan dan perpanjangan SKU 100% bebas biaya (Gratis)'
            ]
        }
    };

    var modal      = document.getElementById('guide-modal');
    var modalTitle = document.getElementById('guide-modal-title');
    var modalList  = document.getElementById('guide-modal-list');
    var modalClose = document.getElementById('guide-modal-close');

    document.querySelectorAll('[data-doc-key]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var data = guideData[btn.getAttribute('data-doc-key')];
            if (!data || !modal) return;

            modalTitle.textContent = data.title;
            modalList.innerHTML = '';
            data.items.forEach(function (text) {
                var li = document.createElement('li');
                li.innerHTML = '<i class="fa-solid fa-circle-check"></i><span></span>';
                li.querySelector('span').textContent = text;
                modalList.appendChild(li);
            });
            modal.classList.add('is-open');
        });
    });

    if (modalClose && modal) {
        modalClose.addEventListener('click', function () {
            modal.classList.remove('is-open');
        });
        modal.addEventListener('click', function (e) {
            if (e.target === modal) modal.classList.remove('is-open');
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                modal.classList.remove('is-open');
            }
        });
    }

    // Animasi Scroll Muncul & Hilang pada Card
    var revealTargets = document.querySelectorAll('.highlight-card, .service-card, .media-card, .doc-card, .section-header-row');

    if ('IntersectionObserver' in window && revealTargets.length > 0) {
        revealTargets.forEach(function (el) {
            var siblingCards = el.parentElement ? Array.prototype.slice.call(el.parentElement.children) : [];
            var colIndex = siblingCards.indexOf(el);
            if (colIndex > 0) {
                el.style.transitionDelay = ((colIndex % 4) * 70) + 'ms';
            }
            el.classList.add('scroll-reveal');
        });

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                var el = entry.target;
                if (entry.isIntersecting) {
                    el.classList.remove('is-above');
                    el.classList.add('is-visible');
                } else {
                    el.classList.remove('is-visible');
                    if (entry.boundingClientRect.top < 0) {
                        el.classList.add('is-above');
                    } else {
                        el.classList.remove('is-above');
                    }
                }
            });
        }, {
            threshold: 0.14,
            rootMargin: '-20px 0px -30px 0px'
        });

        revealTargets.forEach(function (el) {
            observer.observe(el);
        });
    }
})();
</script>
<?= $this->endSection() ?>
