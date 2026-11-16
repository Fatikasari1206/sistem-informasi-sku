<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Portal Layanan SKU - Kalurahan Condongcatur') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --gov-blue: #093b9c;
            --gov-blue-dark: #03307c;
            --gov-blue-accent: #0d6efd;
            --gov-red: #ef4444;
            --surface: #ffffff;
            --bg-soft: #f8fafc;
            --border: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --shadow-card: 0 2px 10px rgba(15, 23, 42, 0.05);
            --shadow-hover: 0 10px 24px rgba(15, 23, 42, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-soft);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, .brand-title {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }

        /* Top Navbar (Jatimprov style) */
        .gov-navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .gov-navbar-inner {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-logo-img {
            width: 42px;
            height: 42px;
            object-fit: contain;
        }

        .brand-title {
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: var(--text-main);
            line-height: 1.15;
            text-transform: uppercase;
        }

        .brand-subtitle {
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--text-muted);
            display: block;
        }

        .nav-menu-group {
            display: flex;
            align-items: center;
            gap: 1.65rem;
        }

        .nav-link {
            font-size: 0.86rem;
            font-weight: 500;
            color: #1e293b;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: color 0.15s ease;
        }

        .nav-link:hover {
            color: var(--gov-blue-accent);
        }

        .nav-link i {
            font-size: 0.7rem;
            color: #64748b;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-shrink: 0;
        }

        .btn-nav-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.48rem 0.95rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #ffffff;
            color: #0f172a;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-nav-outline:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }

        .btn-nav-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.5rem 1.05rem;
            border: 1px solid var(--gov-blue-accent);
            border-radius: 8px;
            background: var(--gov-blue-accent);
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.15s ease;
        }

        .btn-nav-primary:hover {
            background: #0b5ed7;
        }

        .btn-nav-danger {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.48rem 0.9rem;
            border: 1px solid #fecaca;
            border-radius: 8px;
            background: #fef2f2;
            color: #dc2626;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-nav-danger:hover {
            background: #fee2e2;
        }

        /* Flash Alert */
        .flash-wrap {
            max-width: 1240px;
            margin: 1rem auto 0;
            padding: 0 1.5rem;
            width: 100%;
        }

        .alert {
            padding: 0.85rem 1.15rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.88rem;
            font-weight: 500;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Deep Navy Footer (Jatimprov style) */
        .gov-footer {
            background: var(--gov-blue-dark);
            color: #ffffff;
            margin-top: auto;
            padding: 3.5rem 1.5rem 1.75rem;
        }

        .gov-footer-inner {
            max-width: 1240px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1.1fr;
            gap: 2.5rem;
            padding-bottom: 2.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.14);
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 1.35rem;
        }

        .footer-brand img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            background: #ffffff;
            border-radius: 8px;
            padding: 4px;
        }

        .footer-brand-title {
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: 0.01em;
            color: #ffffff;
            text-transform: uppercase;
        }

        .footer-brand-sub {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.78);
        }

        .footer-info-block {
            margin-bottom: 1.1rem;
        }

        .footer-info-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.25rem;
        }

        .footer-info-text {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.82);
            line-height: 1.55;
        }

        .footer-col-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 1.05rem;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 0.84rem;
            transition: color 0.15s ease;
        }

        .footer-links a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        .social-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .social-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #ffffff;
            text-decoration: none;
        }

        .social-icon-box {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.88rem;
            flex-shrink: 0;
        }

        .social-meta strong {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .social-meta span {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.72);
        }

        .footer-bottom {
            padding-top: 1.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.75);
        }

        @media (max-width: 960px) {
            .nav-menu-group {
                display: none;
            }
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 600px) {
            .gov-navbar-inner {
                padding: 0.7rem 1rem;
            }
            .brand-subtitle {
                display: none;
            }
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 1.75rem;
            }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <?php
    $isLoggedIn   = (bool) session()->get('isLoggedIn');
    $roleKey      = strtolower((string) (session()->get('role') ?? 'pemohon'));
    $dashboardMap = [
        'admin'   => '/admin/dashboard',
        'staf'    => '/staf/dashboard',
        'lurah'   => '/lurah/dashboard',
        'pemohon' => '/pemohon/dashboard',
    ];
    $dashboardUrl = $dashboardMap[$roleKey] ?? '/pemohon/dashboard';
    $namaKalurahan = $pengaturan['nama_kalurahan'] ?? 'Condongcatur';
    ?>

    <header class="gov-navbar">
        <div class="gov-navbar-inner">
            <a href="<?= base_url() ?>" class="navbar-brand" id="nav-brand">
                <img src="<?= base_url('images/logo%20sleman.jpeg') ?>" alt="Logo Kalurahan <?= esc($namaKalurahan) ?>" class="brand-logo-img">
                <div>
                    <div class="brand-title"><?= esc(strtoupper($namaKalurahan)) ?></div>
                    <span class="brand-subtitle">Portal Layanan SKU Kalurahan <?= esc($namaKalurahan) ?></span>
                </div>
            </a>

            <nav class="nav-menu-group" aria-label="Menu Utama">
                <a href="#layanan-terintegrasi" class="nav-link">Informasi Layanan <i class="fa-solid fa-chevron-down"></i></a>
                <a href="#kegiatan-kalurahan" class="nav-link">Berita &amp; Galeri <i class="fa-solid fa-chevron-down"></i></a>
                <a href="#dokumen-sku" class="nav-link">Dokumen SKU <i class="fa-solid fa-chevron-down"></i></a>
            </nav>

            <div class="nav-actions">
                <?php if ($isLoggedIn): ?>
                    <a href="<?= base_url($dashboardUrl) ?>" class="btn-nav-primary" id="btn-nav-dashboard">
                        <i class="fa-solid fa-gauge"></i> Dashboard
                    </a>
                    <a href="<?= base_url('logout') ?>" class="btn-nav-danger" id="btn-logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn-nav-outline" id="btn-nav-login">Masuk</a>
                    <a href="<?= base_url('register') ?>" class="btn-nav-primary" id="btn-nav-register">Daftar SKU</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <?php if (session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
        <div class="flash-wrap">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success" id="flash-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span><?= esc(session()->getFlashdata('success')) ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error" id="flash-error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="gov-footer">
        <div class="gov-footer-inner">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        <img src="<?= base_url('images/logo%20sleman.jpeg') ?>" alt="Logo Kalurahan <?= esc($namaKalurahan) ?>">
                        <div>
                            <div class="footer-brand-title"><?= esc(strtoupper($namaKalurahan)) ?></div>
                            <div class="footer-brand-sub">Portal Layanan SKU Kalurahan <?= esc($namaKalurahan) ?></div>
                        </div>
                    </div>

                    <div class="footer-info-block">
                        <div class="footer-info-label">Kantor Kalurahan <?= esc($namaKalurahan) ?></div>
                        <div class="footer-info-text">
                            <?= esc($pengaturan['alamat_kantor'] ?? 'Jl. Wijaya Kusuma No. 1, Condongcatur, Depok, Sleman 55283') ?>
                        </div>
                    </div>

                    <div class="footer-info-block">
                        <div class="footer-info-label">Layanan Pengaduan &amp; Bantuan</div>
                        <div class="footer-info-text">
                            <?= esc($pengaturan['telepon'] ?? '(0274) 885678') ?><br>
                            <?= esc($pengaturan['email'] ?? 'kalurahan@condongcatur.desa.id') ?>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="footer-col-title">Akses Cepat</div>
                    <ul class="footer-links">
                        <li><a href="#layanan-terintegrasi">Layanan Terintegrasi</a></li>
                        <li><a href="#kegiatan-kalurahan">Berita Terkini</a></li>
                        <li><a href="#dokumen-sku">Dokumen &amp; Panduan SKU</a></li>
                    </ul>
                </div>

                <div>
                    <div class="footer-col-title">Layanan SKU</div>
                    <ul class="footer-links">
                        <li><a href="<?= base_url($isLoggedIn ? 'pemohon/sku/baru' : 'register') ?>">Pengajuan SKU Baru</a></li>
                        <li><a href="<?= base_url($isLoggedIn ? 'pemohon/sku/perpanjangan' : 'login') ?>">Perpanjangan SKU</a></li>
                        <li><a href="<?= base_url('login') ?>">Masuk Akun Pemohon</a></li>
                        <li><a href="<?= base_url('register') ?>">Registrasi NIK Warga</a></li>
                    </ul>
                </div>

                <div>
                    <div class="footer-col-title">Kanal Resmi</div>
                    <div class="social-list">
                        <a href="<?= esc($pengaturan['website'] ?? 'https://condongcatur.desa.id') ?>" target="_blank" rel="noopener" class="social-item">
                            <span class="social-icon-box"><i class="fa-solid fa-globe"></i></span>
                            <span class="social-meta">
                                <strong>Website Resmi</strong>
                                <span>condongcatur.desa.id</span>
                            </span>
                        </a>
                        <a href="mailto:<?= esc($pengaturan['email'] ?? 'kalurahan@condongcatur.desa.id') ?>" class="social-item">
                            <span class="social-icon-box"><i class="fa-regular fa-envelope"></i></span>
                            <span class="social-meta">
                                <strong>Email Kalurahan</strong>
                                <span><?= esc($pengaturan['email'] ?? 'kalurahan@condongcatur.desa.id') ?></span>
                            </span>
                        </a>
                        <a href="#dokumen-sku" class="social-item">
                            <span class="social-icon-box"><i class="fa-regular fa-clock"></i></span>
                            <span class="social-meta">
                                <strong>Jam Pelayanan</strong>
                                <span>Senin – Jumat, 08.00 – 15.30 WIB</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; <?= date('Y') ?> Pemerintah Kalurahan <?= esc($namaKalurahan) ?>. All rights reserved.</span>
                <span>Sistem Informasi Surat Keterangan Usaha Kalurahan <?= esc($namaKalurahan) ?></span>
            </div>
        </div>
    </footer>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
