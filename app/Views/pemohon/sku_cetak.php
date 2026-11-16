<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Cetak Surat Keterangan Usaha (SKU)') ?></title>
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
            padding: 1.25rem 1.5rem 2rem;
        }

        .print-shell {
            max-width: 880px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .print-topbar {
            background: #ffffff;
            border-radius: 12px;
            padding: 0.85rem 1.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
        }

        .back-link {
            font-size: 0.82rem;
            font-weight: 700;
            color: #4f46e5;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .btn-print {
            height: 36px;
            padding: 0 1.25rem;
            border: none;
            border-radius: 9999px;
            background: #5246f0;
            color: #ffffff;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(82, 70, 240, 0.25);
        }

        @media print {
            .print-topbar {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="print-shell">
        <header class="print-topbar">
            <a href="<?= base_url('pemohon/dashboard') ?>" class="back-link">&larr; Kembali ke Dashboard Pemohon</a>
            <button type="button" class="btn-print" onclick="window.print()">Cetak / Simpan PDF</button>
        </header>

        <main>
            <?= view('partials/_sku_letter_sheet', ['permohonan' => $permohonan, 'pengaturan' => $pengaturan]) ?>
        </main>
    </div>
</body>
</html>
