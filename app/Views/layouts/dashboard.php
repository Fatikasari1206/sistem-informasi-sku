<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistem SKU Condongcatur') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: #e3e6eb;
            color: #0f172a;
        }

        .app-shell {
            min-height: 100vh;
            display: flex;
            width: 100%;
        }

        /* Sidebar */
        .sidebar {
            width: 230px;
            flex-shrink: 0;
            background: #272c50;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1rem 1.35rem;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .sidebar-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0.7rem;
            padding-bottom: 1.35rem;
        }

        .sidebar-brand img {
            width: 90px;
            height: auto;
            display: block;
            border-radius: 7px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-brand span {
            font-size: 0.82rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.01em;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .nav-link {
            display: block;
            padding: 0.68rem 1rem;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #a5aec8;
            text-decoration: none;
            transition: background 0.18s ease, color 0.18s ease;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .nav-link.active {
            background: #5246f0;
            color: #ffffff;
            font-weight: 600;
        }

        .sidebar-footer {
            margin-top: auto;
            text-align: center;
            padding-top: 1rem;
        }

        .logout-link {
            display: inline-block;
            padding: 0.45rem 0.9rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #f87171;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.18s ease, color 0.18s ease;
        }

        .logout-link:hover {
            background: rgba(248, 113, 113, 0.12);
            color: #fca5a5;
        }

        /* Main Area */
        .main-workspace {
            flex: 1;
            min-width: 0;
            padding: 1.15rem 1.6rem 1.35rem;
            display: flex;
            flex-direction: column;
            gap: 0.95rem;
        }

        /* Top Bar */
        .topbar {
            background: #ffffff;
            border-radius: 14px;
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.9rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }

        .role-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            flex-shrink: 0;
            padding-left: 0.25rem;
        }

        .user-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            margin-left: auto;
            background: #f1f5f9;
            padding: 0.45rem 0.95rem;
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 600;
            color: #0f172a;
            white-space: nowrap;
        }

        .alert-flash {
            padding: 0.65rem 1rem;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .alert-flash.success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-flash.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        @media (max-width: 900px) {
            .app-shell {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: static;
                padding: 1rem;
            }

            .sidebar-brand {
                flex-direction: row;
                justify-content: flex-start;
                padding-bottom: 0.85rem;
            }

            .sidebar-brand img {
                width: 64px;
            }

            .sidebar-nav {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .sidebar-footer {
                margin-top: 0.75rem;
                text-align: left;
                padding-top: 0.5rem;
            }

            .main-workspace {
                padding: 1rem;
            }

            .topbar {
                flex-wrap: wrap;
            }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <img src="<?= base_url('images/logo-baru.png') ?>" alt="Pemerintah Kalurahan Condongcatur">
                <span>Sistem SKU Condongcatur</span>
            </div>

            <nav class="sidebar-nav" aria-label="Navigasi Utama">
                <?= $this->renderSection('sidebar_nav') ?>
            </nav>

            <div class="sidebar-footer">
                <a href="<?= base_url('logout') ?>" class="logout-link" id="btn-logout"><?= esc($logoutLabel ?? 'Keluar Akun') ?></a>
            </div>
        </aside>

        <div class="main-workspace">
            <?php
            $roleKey = strtolower((string) (session()->get('role') ?? ''));
            $roleLabelMap = [
                'admin'   => 'Admin',
                'pemohon' => 'Pemohon',
                'staf'    => 'Staff',
                'lurah'   => 'Lurah',
            ];
            $roleText = $roleLabelMap[$roleKey] ?? ucfirst($roleKey ?: 'User');
            ?>
            <header class="topbar">
                <span class="role-label" id="nav-role-label"><?= esc($roleText) ?></span>

                <div class="user-pill" id="nav-user-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#334155" aria-hidden="true">
                        <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                    </svg>
                    <?php
                    $cleanNama = preg_replace('/\s*\((?:Lurah|Staf(?:\s+Pelayanan)?|Staff|Admin(?:istrator)?|Warga(?:\s*\/\s*Pemohon)?|Pemohon)\)\s*$/i', '', (string) (session()->get('namaLengkap') ?? 'Pengguna'));
                    ?>
                    <span><?= esc($cleanNama) ?></span>
                </div>
            </header>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert-flash success" id="flash-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-flash error" id="flash-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
