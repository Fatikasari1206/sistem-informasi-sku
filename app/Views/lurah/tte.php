<?php
$isPendingTte  = ($permohonan['status'] ?? '') === 'menunggu_persetujuan_lurah';
$isSigned      = in_array($permohonan['status'] ?? '', ['disetujui', 'selesai'], true);
$namaKalurahan = strtoupper((string) ($pengaturan['nama_kalurahan'] ?? 'Condongcatur'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Portal Pengesahan SKU (TTE) - Sistem SKU') ?></title>
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
            padding: 1.15rem 1.5rem 1.65rem;
        }

        .tte-shell {
            max-width: 1320px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .tte-topbar {
            background: #ffffff;
            border-radius: 12px;
            padding: 0.9rem 1.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 700;
            color: #4f46e5;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .topbar-title {
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.03em;
            color: #1e293b;
            text-transform: uppercase;
        }

        .flash-banner {
            padding: 0.78rem 1rem;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .flash-banner.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .flash-banner.success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .tte-grid {
            display: grid;
            grid-template-columns: 310px 1fr;
            gap: 1.15rem;
            align-items: start;
        }

        .queue-panel,
        .letter-panel {
            background: #ffffff;
            border-radius: 14px;
            padding: 1.25rem;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
        }

        .queue-heading {
            font-size: 0.73rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            color: #334155;
            text-transform: uppercase;
            margin-bottom: 0.9rem;
        }

        .queue-list {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .queue-item {
            display: block;
            text-decoration: none;
            padding: 0.82rem 0.92rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            transition: border-color 0.15s ease, background-color 0.15s ease, transform 0.15s ease;
        }

        .queue-item:hover {
            border-color: #a5b4fc;
            background: #ffffff;
            transform: translateY(-1px);
        }

        .queue-item.is-active {
            border: 1.5px solid #5246f0;
            background: #f5f3ff;
        }

        .queue-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: #0f172a;
        }

        .queue-sub {
            font-size: 0.72rem;
            color: #64748b;
            margin-top: 0.2rem;
        }

        .queue-nomor {
            font-size: 0.71rem;
            font-weight: 600;
            color: #4f46e5;
            margin-top: 0.3rem;
        }

        .queue-verified {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 700;
            color: #059669;
            margin-top: 0.28rem;
        }

        .queue-empty {
            padding: 1.2rem 0.75rem;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            text-align: center;
            font-size: 0.76rem;
            color: #64748b;
        }

        /* Letter Document Sheet */
        <?= view('partials/_sku_letter_sheet', ['part' => 'styles'], ['saveData' => false]) ?>

        .tte-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.7rem;
            margin-top: 1.25rem;
            flex-wrap: wrap;
        }

        .btn-return {
            height: 40px;
            padding: 0 1.2rem;
            border-radius: 9999px;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 0.79rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .btn-return:hover {
            background: #fee2e2;
        }

        .btn-sahkan {
            height: 40px;
            padding: 0 1.45rem;
            border-radius: 9999px;
            border: none;
            background: #5246f0;
            color: #ffffff;
            font-size: 0.79rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(82, 70, 240, 0.24);
            transition: opacity 0.15s ease, transform 0.15s ease;
        }

        .btn-sahkan:hover {
            opacity: 0.94;
            transform: translateY(-1px);
        }

        .btn-print {
            height: 40px;
            padding: 0 1.35rem;
            border-radius: 9999px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #0f172a;
            font-size: 0.79rem;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-print:hover {
            background: #f8fafc;
        }

        .signed-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 0.95rem;
            border-radius: 9999px;
            background: #dcfce7;
            color: #15803d;
            font-size: 0.76rem;
            font-weight: 700;
        }

        /* Return Modal */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(2px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            z-index: 9999;
        }

        .modal-backdrop.is-open {
            display: flex;
        }

        .modal-card {
            width: 100%;
            max-width: 460px;
            background: #ffffff;
            border-radius: 14px;
            padding: 1.25rem 1.35rem;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.25);
        }

        .modal-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.3rem;
        }

        .modal-desc {
            font-size: 0.76rem;
            color: #64748b;
            margin-bottom: 0.85rem;
        }

        .modal-textarea {
            width: 100%;
            min-height: 92px;
            padding: 0.65rem 0.8rem;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 0.8rem;
            color: #0f172a;
            outline: none;
            resize: vertical;
        }

        .modal-textarea:focus {
            border-color: #5246f0;
            box-shadow: 0 0 0 3px rgba(82, 70, 240, 0.12);
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.6rem;
            margin-top: 0.95rem;
        }

        .btn-modal-cancel {
            height: 36px;
            padding: 0 1rem;
            border-radius: 9999px;
            border: none;
            background: #f1f5f9;
            color: #334155;
            font-size: 0.76rem;
            font-weight: 600;
            cursor: pointer;
        }

        @media (max-width: 980px) {
            .tte-grid {
                grid-template-columns: 1fr;
            }

            .letter-sheet {
                padding: 1.35rem 1.15rem;
            }
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .tte-topbar,
            .queue-panel,
            .tte-actions,
            .flash-banner {
                display: none !important;
            }

            .tte-grid {
                grid-template-columns: 1fr;
            }

            .letter-panel {
                box-shadow: none;
                padding: 0;
            }

            .letter-sheet {
                border: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="tte-shell">
        <header class="tte-topbar">
            <a href="<?= base_url($isSigned ? 'lurah/arsip' : 'lurah/antrian') ?>" class="back-link">&larr; Kembali ke Antrian</a>
            <div class="topbar-title">PORTAL PENGESAHAN SURAT KETERANGAN USAHA (SKU) - LURAH <?= esc($namaKalurahan) ?></div>
        </header>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-banner error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-banner success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <div class="tte-grid">
            <aside class="queue-panel">
                <div class="queue-heading">BERKAS SIAP TTE DIGITAL (<?= count($antreanTte) ?>)</div>

                <?php if (empty($antreanTte)): ?>
                    <div class="queue-empty">
                        Tidak ada berkas lain dalam antrian TTE Digital.
                    </div>
                <?php else: ?>
                    <div class="queue-list">
                        <?php foreach ($antreanTte as $item): ?>
                            <?php
                                $isActive  = (int) $item['id'] === (int) $permohonan['id'];
                                $itemDukuh = !empty($item['padukuhan']) ? 'Padukuhan ' . $item['padukuhan'] : 'Condongcatur';
                            ?>
                            <a href="<?= base_url('lurah/tte/' . (int) $item['id']) ?>" class="queue-item <?= $isActive ? 'is-active' : '' ?>">
                                <div class="queue-name"><?= esc($item['nama_pemohon']) ?></div>
                                <div class="queue-sub"><?= esc($item['nama_usaha']) ?> - <?= esc($itemDukuh) ?></div>
                                <div class="queue-nomor">No: <?= esc($item['nomor_surat'] ?: $item['kode_pengajuan']) ?></div>
                                <?php if ($isActive): ?>
                                    <span class="queue-verified">&#10003; Diverifikasi Staf</span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </aside>

            <main class="letter-panel">
                <?= view('partials/_sku_letter_sheet', ['part' => 'body', 'permohonan' => $permohonan, 'pengaturan' => $pengaturan]) ?>

                <?php if ($isPendingTte): ?>
                    <div class="tte-actions">
                        <button type="button" class="btn-return" id="btnOpenReturnModal">Kembalikan ke Staf</button>
                        <form action="<?= base_url('lurah/tte/' . (int) $permohonan['id']) ?>" method="post" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="aksi" value="sahkan">
                            <button type="submit" class="btn-sahkan">Sahkan TTE Digital</button>
                        </form>
                    </div>
                <?php elseif ($isSigned): ?>
                    <div class="tte-actions">
                        <span class="signed-pill">&#10003; Telah Disahkan Secara Elektronik</span>
                        <button type="button" class="btn-print" onclick="window.print()">Cetak Surat</button>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <?php if ($isPendingTte): ?>
        <div class="modal-backdrop" id="returnModal" role="dialog" aria-modal="true" aria-labelledby="returnModalTitle">
            <div class="modal-card">
                <div class="modal-title" id="returnModalTitle">Kembalikan ke Staf Pelayanan</div>
                <p class="modal-desc">Berikan catatan perbaikan agar Staf Pelayanan dapat memeriksa ulang draft surat ini.</p>
                <form action="<?= base_url('lurah/tte/' . (int) $permohonan['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="aksi" value="kembalikan">
                    <textarea
                        name="catatan"
                        class="modal-textarea"
                        placeholder="Contoh: Mohon sesuaikan kode KBLI dan pastikan penulisan alamat usaha lengkap..."
                        required><?= esc(old('catatan')) ?></textarea>
                    <div class="modal-actions">
                        <button type="button" class="btn-modal-cancel" id="btnCloseReturnModal">Batal</button>
                        <button type="submit" class="btn-return">Kirim ke Staf</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            (function () {
                var modal = document.getElementById('returnModal');
                var openBtn = document.getElementById('btnOpenReturnModal');
                var closeBtn = document.getElementById('btnCloseReturnModal');
                if (!modal || !openBtn || !closeBtn) return;

                openBtn.addEventListener('click', function () {
                    modal.classList.add('is-open');
                });
                closeBtn.addEventListener('click', function () {
                    modal.classList.remove('is-open');
                });
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) modal.classList.remove('is-open');
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') modal.classList.remove('is-open');
                });
            })();
        </script>
    <?php endif; ?>
</body>
</html>
