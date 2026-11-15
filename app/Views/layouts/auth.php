<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Portal Layanan Warga - Sistem SKU') ?></title>
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
            color: #ffffff;
        }

        .auth-page {
            min-height: 100vh;
            display: flex;
            width: 100%;
        }

        /* Left Hero Panel */
        .hero-panel {
            width: clamp(340px, 33vw, 480px);
            flex-shrink: 0;
            position: relative;
            background-image: url('<?= base_url('images/Hero%20Panel.png') ?>');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2.5rem 2.25rem 6rem;
            overflow: hidden;
        }

        .hero-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.12);
            pointer-events: none;
        }

        .hero-copy {
            position: relative;
            z-index: 1;
            max-width: 390px;
        }

        .hero-copy h1 {
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1.25;
            letter-spacing: -0.02em;
            color: #ffffff;
            margin-bottom: 0.75rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.28);
        }

        .hero-copy p {
            font-size: 0.84rem;
            line-height: 1.55;
            color: rgba(241, 245, 249, 0.92);
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.3);
        }

        /* Right Form Area */
        .form-area {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 1.75rem;
            background: #e3e6eb;
        }

        .auth-card {
            width: 100%;
            max-width: <?= esc($cardMaxWidth ?? '450px') ?>;
            background: #272c50;
            border-radius: 14px;
            padding: <?= esc($cardPadding ?? '1.75rem 2.15rem 1.9rem') ?>;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14);
        }

        .card-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 0.9rem;
        }

        .card-logo img {
            width: 122px;
            height: auto;
            display: block;
            border-radius: 7px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.18);
        }

        .card-header {
            text-align: center;
            margin-bottom: 1.15rem;
        }

        .card-header h2 {
            font-size: 1.32rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.015em;
            margin-bottom: 0.3rem;
        }

        .card-header p {
            font-size: 0.77rem;
            color: #9ba3c2;
            line-height: 1.4;
        }

        .alert {
            padding: 0.6rem 0.85rem;
            border-radius: 8px;
            font-size: 0.76rem;
            margin-bottom: 0.9rem;
            line-height: 1.4;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.16);
            border: 1px solid rgba(248, 113, 113, 0.4);
            color: #fecaca;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.16);
            border: 1px solid rgba(52, 211, 153, 0.4);
            color: #a7f3d0;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.16);
            border: 1px solid rgba(96, 165, 250, 0.4);
            color: #bfdbfe;
            display: none;
        }

        .alert ul {
            margin-left: 1rem;
        }

        .form-group {
            margin-bottom: 0.85rem;
        }

        .label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.35rem;
        }

        .form-label {
            display: block;
            font-size: 0.76rem;
            font-weight: 500;
            color: #eef2ff;
        }

        .form-control {
            width: 100%;
            height: 40px;
            padding: 0 0.9rem;
            background: #ffffff;
            color: #0f172a;
            border: 1.5px solid transparent;
            border-radius: 9px;
            font-size: 0.82rem;
            font-weight: 500;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .form-control:focus {
            border-color: #5246f0;
            box-shadow: 0 0 0 3px rgba(82, 70, 240, 0.25);
        }

        .form-control[readonly] {
            background: #f1f5f9;
            color: #1e293b;
            cursor: default;
        }

        .password-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-input {
            padding-right: 2.6rem;
        }

        .password-toggle {
            position: absolute;
            right: 0.45rem;
            width: 30px;
            height: 30px;
            border: none;
            background: transparent;
            color: #64748b;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: color 0.15s ease, background-color 0.15s ease;
        }

        .password-toggle:hover {
            color: #1e293b;
            background: #f1f5f9;
        }

        .password-toggle .icon-eye-off {
            display: none;
        }

        .password-toggle.is-visible .icon-eye-open {
            display: none;
        }

        .password-toggle.is-visible .icon-eye-off {
            display: block;
        }

        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.16);
            margin: 1.1rem 0;
        }

        .btn-auth {
            width: 100%;
            height: 42px;
            border: none;
            border-radius: 9999px;
            background: #5246f0;
            color: #ffffff;
            font-size: 0.86rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
        }

        .btn-auth:hover {
            background: #463ae0;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(82, 70, 240, 0.32);
        }

        .btn-auth:active {
            transform: translateY(0);
        }

        .auth-footer-link {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.76rem;
            color: #d1d5db;
        }

        .auth-footer-link a {
            color: #60a5fa;
            font-weight: 600;
            text-decoration: none;
            margin-left: 0.2rem;
        }

        .auth-footer-link a:hover {
            color: #93c5fd;
            text-decoration: underline;
        }

        @media (max-width: 960px) {
            .auth-page {
                flex-direction: column;
            }

            .hero-panel {
                width: 100%;
                min-height: 200px;
                padding: 1.75rem 1.25rem;
                justify-content: flex-end;
            }

            .form-area {
                padding: 1.25rem 1rem 2rem;
            }

            .auth-card {
                padding: 1.75rem 1.25rem;
            }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="auth-page">
        <aside class="hero-panel">
            <div class="hero-copy">
                <h1><?= $heroTitleHtml ?? 'Sistem SKU<br>Portal Layanan Warga' ?></h1>
                <p><?= esc($heroSubtitle ?? 'Pelayanan online pembuatan Surat Keterangan Usaha (SKU) Baru dan Perpanjangan bagi warga Kalurahan Condongcatur.') ?></p>
            </div>
        </aside>

        <main class="form-area">
            <section class="auth-card" aria-labelledby="auth-heading">
                <div class="card-logo">
                    <img src="<?= base_url('images/logo-baru.png') ?>" alt="Pemerintah Kalurahan Condongcatur">
                </div>

                <?= $this->renderSection('card') ?>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-toggle-password]');
            if (!btn) return;
            var input = document.getElementById(btn.dataset.togglePassword);
            if (!input) return;

            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.classList.toggle('is-visible', show);
            btn.setAttribute('aria-pressed', show ? 'true' : 'false');
            btn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
