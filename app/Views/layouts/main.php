<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistem Informasi SKU Kalurahan') ?></title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --secondary: #0f172a;
            --success: #10b981;
            --success-light: #ecfdf5;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --purple: #8b5cf6;
            --purple-light: #f5f3ff;
            --surface: #ffffff;
            --background: #f8fafc;
            --border: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --radius-lg: 16px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.06);
            --shadow-lg: 0 12px 28px rgba(0,0,0,0.08);
            --transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--background);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        /* Top Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0.85rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
            color: var(--secondary);
        }

        .brand-logo-wrapper {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.12));
            transition: var(--transition);
        }

        .brand-logo-wrapper:hover {
            transform: scale(1.05);
        }

        .brand-logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-text h1 {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--secondary);
            margin: 0;
        }

        .brand-text span {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: block;
            margin-top: -2px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--primary-light);
            padding: 0.4rem 0.85rem 0.4rem 0.5rem;
            border-radius: 9999px;
            border: 1px solid rgba(37, 99, 235, 0.15);
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--secondary);
        }

        .user-role-badge {
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--primary);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            font-size: 0.88rem;
            font-weight: 600;
            border-radius: var(--radius-sm);
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }

        .btn-secondary {
            background: var(--surface);
            color: var(--text-main);
            border-color: var(--border);
        }

        .btn-secondary:hover {
            background: #f1f5f9;
        }

        .btn-danger-soft {
            background: var(--danger-light);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-danger-soft:hover {
            background: var(--danger);
            color: white;
        }

        .btn-sm {
            padding: 0.4rem 0.85rem;
            font-size: 0.78rem;
        }

        /* Flash Message Alert */
        .alert-container {
            max-width: 1200px;
            margin: 1.5rem auto 0;
            padding: 0 1.5rem;
            width: 100%;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            animation: slideDown 0.3s ease;
        }

        .alert-success {
            background: var(--success-light);
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .alert-error {
            background: var(--danger-light);
            color: #991b1b;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* App Container */
        .app-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
            width: 100%;
            flex: 1;
        }

        /* Modern Cards */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        /* Stat Grid */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-icon.blue { background: var(--primary-light); color: var(--primary); }
        .stat-icon.green { background: var(--success-light); color: var(--success); }
        .stat-icon.yellow { background: var(--warning-light); color: var(--warning); }
        .stat-icon.purple { background: var(--purple-light); color: var(--purple); }
        .stat-icon.red { background: var(--danger-light); color: var(--danger); }

        .stat-meta h3 {
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--secondary);
            line-height: 1.1;
        }

        .stat-meta p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
            font-weight: 500;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: capitalize;
        }

        .badge-diajukan { background: var(--warning-light); color: #b45309; border: 1px solid #fde68a; }
        .badge-verifikasi_staf { background: var(--primary-light); color: var(--primary); border: 1px solid #bfdbfe; }
        .badge-revisi { background: var(--danger-light); color: var(--danger); border: 1px solid #fecaca; }
        .badge-menunggu_persetujuan_lurah { background: var(--purple-light); color: var(--purple); border: 1px solid #ddd6fe; }
        .badge-disetujui, .badge-selesai { background: var(--success-light); color: var(--success); border: 1px solid #a7f3d0; }
        .badge-ditolak { background: #f1f5f9; color: var(--text-muted); border: 1px solid var(--border); }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }

        .table-modern {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .table-modern th {
            background: #f8fafc;
            color: var(--text-muted);
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border);
        }

        .table-modern td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
            color: var(--text-main);
            vertical-align: middle;
        }

        .table-modern tr:hover td {
            background-color: #f8fafc;
        }

        /* Footer */
        .app-footer {
            background: white;
            border-top: 1px solid var(--border);
            padding: 1.5rem 2rem;
            text-align: center;
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: auto;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .navbar { padding: 0.75rem 1rem; }
            .app-container { padding: 1.25rem 1rem 3rem; }
            .brand-text span { display: none; }
        }
    </style>
</head>
<body>

    <!-- Top Navigation -->
    <header class="navbar">
        <a href="<?= base_url() ?>" class="navbar-brand" id="nav-brand">
            <div class="brand-logo-wrapper">
                <img src="<?= base_url('images/logo-kalurahan.svg') ?>" alt="Logo Kalurahan" class="brand-logo-img">
            </div>
            <div class="brand-text">
                <h1>Sistem SKU</h1>
                <span>Kalurahan <?= esc($pengaturan['nama_kalurahan'] ?? 'Condongcatur') ?></span>
            </div>
        </a>

        <div class="nav-right">
            <?php if (session()->get('isLoggedIn')): ?>
                <div class="user-badge" id="nav-user-badge">
                    <div class="user-avatar">
                        <?= strtoupper(substr(session()->get('namaLengkap') ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="user-info">
                        <span class="user-name"><?= esc(session()->get('namaLengkap')) ?></span>
                        <span class="user-role-badge"><i class="fa-solid fa-shield-halved"></i> <?= esc(session()->get('role')) ?></span>
                    </div>
                </div>

                <a href="<?= base_url('logout') ?>" class="btn btn-danger-soft btn-sm" id="btn-logout" title="Keluar">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                </a>
            <?php else: ?>
                <a href="<?= base_url('login') ?>" class="btn btn-secondary btn-sm" id="btn-nav-login">
                    <i class="fa-solid fa-right-to-bracket"></i> Masuk
                </a>
                <a href="<?= base_url('register') ?>" class="btn btn-primary btn-sm" id="btn-nav-register">
                    <i class="fa-solid fa-user-plus"></i> Daftar
                </a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="alert-container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" id="flash-success">
                <i class="fa-solid fa-circle-check fa-lg"></i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error" id="flash-error">
                <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content Slot -->
    <main class="app-container">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="app-footer">
        <p>&copy; <?= date('Y') ?> Pemerintah Kalurahan Condongcatur • Sistem Informasi Pembuatan & Perpanjangan Surat Keterangan Usaha (SKU).</p>
    </footer>

</body>
</html>
