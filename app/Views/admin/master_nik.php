<?= $this->extend('layouts/dashboard') ?>

<?php
$errors       = session()->getFlashdata('errors') ?? [];
$hasFormError = !empty($errors);

$formatDigits = static function (?string $val): string {
    $val = trim((string) $val);
    return $val !== '' ? $val : '-';
};
?>

<?= $this->section('styles') ?>
    <?= $this->include('staf/_styles') ?>
    <style>
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .btn-add-nik {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            height: 36px;
            padding: 0 1.15rem;
            border: none;
            border-radius: 9999px;
            background: #5246f0;
            color: #ffffff;
            font-size: 0.77rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(82, 70, 240, 0.24);
            transition: opacity 0.15s ease, transform 0.15s ease;
        }

        .btn-add-nik:hover {
            opacity: 0.94;
            transform: translateY(-1px);
        }

        .table-wrap {
            min-height: 280px;
        }

        .btn-submit-nik {
            height: 38px;
            padding: 0 1.35rem;
            border: none;
            border-radius: 9999px;
            background: #5246f0;
            color: #ffffff;
            font-size: 0.77rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(82, 70, 240, 0.25);
        }

        .btn-submit-nik:hover {
            opacity: 0.94;
        }

        .address-subtext {
            display: block;
            font-size: 0.73rem;
            color: #64748b;
            margin-top: 0.12rem;
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('admin/_sidebar_nav', ['activeMenu' => 'master-nik']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- Tabel Informasi Master Data NIK -->
    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Master Data NIK Kependudukan Kalurahan Condongcatur</h2>
                <p class="panel-subtitle">Basis data induk kependudukan resmi Kalurahan Condongcatur untuk sinkronisasi pemohon SKU.</p>
            </div>

            <div class="header-actions">
                <select id="filter-padukuhan" class="filter-select" aria-label="Filter berdasarkan padukuhan">
                    <option value="">Padukuhan</option>
                    <?php foreach ($padukuhanList as $p): ?>
                        <option value="<?= esc($p) ?>"><?= esc($p) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="button" class="btn-add-nik" id="btn-open-tambah-nik">
                    <span>+ Tambah NIK</span>
                </button>
            </div>
        </div>

        <?php if (empty($pendudukList)): ?>
            <div class="empty-state">
                <strong>Belum ada data kependudukan NIK</strong>
                <p>Klik tombol "+ Tambah NIK" untuk menambahkan data warga kependudukan baru.</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="staf-table" id="master-nik-table">
                    <thead>
                        <tr>
                            <th>NIK</th>
                            <th>NAMA LENGKAP WARGA</th>
                            <th>NO. KARTU KELUARGA</th>
                            <th>ALAMAT</th>
                            <th>PADUKUHAN</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendudukList as $row): ?>
                            <?php
                                $hasAccount = !empty($row['user_id']);
                                $padukuhan  = !empty($row['padukuhan']) ? $row['padukuhan'] : '-';
                                $rtRw       = sprintf('RT %s / RW %s', $row['rt'] ?? '-', $row['rw'] ?? '-');
                                $alamat     = !empty($row['alamat']) ? $row['alamat'] : '-';
                            ?>
                            <tr data-padukuhan="<?= esc($padukuhan) ?>">
                                <td class="cell-strong"><?= esc($row['nik']) ?></td>
                                <td>
                                    <span class="pemohon-name"><?= esc($row['nama_lengkap']) ?></span>
                                </td>
                                <td><?= esc($formatDigits($row['no_kk'] ?? null)) ?></td>
                                <td>
                                    <span class="cell-strong"><?= esc($rtRw) ?></span>
                                    <span class="address-subtext"><?= esc($alamat) ?></span>
                                </td>
                                <td><?= esc($padukuhan) ?></td>
                                <td>
                                    <?php if ($hasAccount): ?>
                                        <span class="pill-badge status-green">✓ Akun Aktif</span>
                                    <?php else: ?>
                                        <span class="pill-badge status-neutral">Belum Registrasi</span>
                                    <?php endif; ?>
                                </td>
                                <td class="cell-actions">
                                    <div class="action-dropdown">
                                        <button type="button" class="btn-action-dots" aria-label="Menu Aksi" aria-expanded="false">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                <circle cx="12" cy="5" r="2"/>
                                                <circle cx="12" cy="12" r="2"/>
                                                <circle cx="12" cy="19" r="2"/>
                                            </svg>
                                        </button>
                                        <div class="action-menu">
                                            <button
                                                type="button"
                                                class="action-menu-item btn-edit-nik"
                                                data-id="<?= (int) $row['id'] ?>"
                                                data-nik="<?= esc($row['nik']) ?>"
                                                data-nama="<?= esc($row['nama_lengkap']) ?>"
                                                data-nokk="<?= esc($row['no_kk'] ?? '') ?>"
                                                data-padukuhan="<?= esc($row['padukuhan'] ?? '') ?>"
                                                data-rt="<?= esc($row['rt'] ?? '') ?>"
                                                data-rw="<?= esc($row['rw'] ?? '') ?>"
                                                data-alamat="<?= esc($row['alamat'] ?? '') ?>"
                                            >
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                                <span>Edit</span>
                                            </button>
                                            <form action="<?= base_url('admin/master-nik/' . (int) $row['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data NIK warga <?= esc(addslashes($row['nama_lengkap'])) ?>?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="action-menu-item danger">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="master-nik-pagination"></div>
        <?php endif; ?>
    </section>

    <!-- Pop-up Modal Tambah NIK -->
    <div class="modal-backdrop <?= $hasFormError ? 'is-open' : '' ?>" id="modal-tambah-nik" role="dialog" aria-modal="true" aria-labelledby="modal-nik-title">
        <div class="modal-card">
            <div class="modal-head">
                <div>
                    <h3 class="modal-title" id="modal-nik-title">Data NIK Baru</h3>
                    <p class="modal-subtitle">Lengkapi data kependudukan warga Kalurahan Condongcatur.</p>
                </div>
                <button type="button" class="modal-close" id="btn-close-tambah-nik" aria-label="Tutup">&times;</button>
            </div>

            <div class="modal-body">
                <?php if ($hasFormError): ?>
                    <div class="modal-errors">
                        <ul>
                            <?php foreach ($errors as $msg): ?>
                                <li><?= esc($msg) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/master-nik') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-field">
                        <label for="input-nik">NIK</label>
                        <input
                            type="text"
                            id="input-nik"
                            name="nik"
                            value="<?= esc(old('nik') ?? '') ?>"
                            placeholder="Masukkan 16 digit NIK"
                            maxlength="16"
                            pattern="\d{16}"
                            required
                        >
                    </div>

                    <div class="form-field">
                        <label for="input-nama-warga">Nama Lengkap</label>
                        <input
                            type="text"
                            id="input-nama-warga"
                            name="nama_lengkap"
                            value="<?= esc(old('nama_lengkap') ?? '') ?>"
                            placeholder="Masukkan nama lengkap warga sesuai KTP"
                            required
                        >
                    </div>

                    <div class="form-field">
                        <label for="input-no-kk">Nomor Kartu Keluarga</label>
                        <input
                            type="text"
                            id="input-no-kk"
                            name="no_kk"
                            value="<?= esc(old('no_kk') ?? '') ?>"
                            placeholder="Masukkan 16 digit Nomor Kartu Keluarga (opsional)"
                            maxlength="16"
                        >
                    </div>

                    <div class="form-grid-3">
                        <div class="form-field">
                            <label for="input-padukuhan">Padukuhan</label>
                            <select id="input-padukuhan" name="padukuhan" required>
                                <option value="">Pilih Padukuhan</option>
                                <?php $selectedPad = old('padukuhan') ?? ''; ?>
                                <?php foreach ($padukuhanList as $pad): ?>
                                    <option value="<?= esc($pad) ?>" <?= $selectedPad === $pad ? 'selected' : '' ?>>
                                        <?= esc($pad) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-field">
                            <label for="input-rt">RT</label>
                            <input
                                type="text"
                                id="input-rt"
                                name="rt"
                                value="<?= esc(old('rt') ?? '') ?>"
                                placeholder="01"
                                maxlength="5"
                                required
                            >
                        </div>

                        <div class="form-field">
                            <label for="input-rw">RW</label>
                            <input
                                type="text"
                                id="input-rw"
                                name="rw"
                                value="<?= esc(old('rw') ?? '') ?>"
                                placeholder="01"
                                maxlength="5"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="input-alamat">Alamat Lengkap</label>
                        <textarea
                            id="input-alamat"
                            name="alamat"
                            placeholder="Masukkan alamat jalan atau domisili warga"
                        ><?= esc(old('alamat') ?? '') ?></textarea>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" id="btn-cancel-tambah-nik">Batal</button>
                        <button type="submit" class="btn-submit-nik">Simpan Data NIK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pop-up Modal Edit NIK -->
    <div class="modal-backdrop" id="modal-edit-nik" role="dialog" aria-modal="true" aria-labelledby="modal-edit-nik-title">
        <div class="modal-card">
            <div class="modal-head">
                <div>
                    <h3 class="modal-title" id="modal-edit-nik-title">Edit Data NIK</h3>
                    <p class="modal-subtitle">Perbarui data kependudukan warga Kalurahan Condongcatur.</p>
                </div>
                <button type="button" class="modal-close" id="btn-close-edit-nik" aria-label="Tutup">&times;</button>
            </div>

            <div class="modal-body">
                <form id="form-edit-nik" action="" method="post">
                    <?= csrf_field() ?>

                    <div class="form-field">
                        <label for="edit-nik">NIK</label>
                        <input
                            type="text"
                            id="edit-nik"
                            name="nik"
                            placeholder="Masukkan 16 digit NIK"
                            maxlength="16"
                            pattern="\d{16}"
                            required
                        >
                    </div>

                    <div class="form-field">
                        <label for="edit-nama">Nama Lengkap</label>
                        <input
                            type="text"
                            id="edit-nama"
                            name="nama_lengkap"
                            placeholder="Masukkan nama lengkap warga sesuai KTP"
                            required
                        >
                    </div>

                    <div class="form-field">
                        <label for="edit-nokk">Nomor Kartu Keluarga</label>
                        <input
                            type="text"
                            id="edit-nokk"
                            name="no_kk"
                            placeholder="Masukkan 16 digit Nomor Kartu Keluarga (opsional)"
                            maxlength="16"
                        >
                    </div>

                    <div class="form-grid-3">
                        <div class="form-field">
                            <label for="edit-padukuhan">Padukuhan</label>
                            <select id="edit-padukuhan" name="padukuhan" required>
                                <option value="">Pilih Padukuhan</option>
                                <?php foreach ($padukuhanList as $pad): ?>
                                    <option value="<?= esc($pad) ?>"><?= esc($pad) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-field">
                            <label for="edit-rt">RT</label>
                            <input
                                type="text"
                                id="edit-rt"
                                name="rt"
                                placeholder="01"
                                maxlength="5"
                                required
                            >
                        </div>

                        <div class="form-field">
                            <label for="edit-rw">RW</label>
                            <input
                                type="text"
                                id="edit-rw"
                                name="rw"
                                placeholder="01"
                                maxlength="5"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="edit-alamat">Alamat Lengkap</label>
                        <textarea
                            id="edit-alamat"
                            name="alamat"
                            placeholder="Masukkan alamat jalan atau domisili warga"
                        ></textarea>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" id="btn-cancel-edit-nik">Batal</button>
                        <button type="submit" class="btn-submit-nik">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <?= view('partials/_pagination_script') ?>
<script>
(function () {
    // Modal Tambah Elements
    var modalTambah = document.getElementById('modal-tambah-nik');
    var openAddBtn  = document.getElementById('btn-open-tambah-nik');
    var closeAddBtn = document.getElementById('btn-close-tambah-nik');
    var cancelAdd   = document.getElementById('btn-cancel-tambah-nik');
    var nikInput    = document.getElementById('input-nik');

    // Modal Edit Elements
    var modalEdit    = document.getElementById('modal-edit-nik');
    var formEdit     = document.getElementById('form-edit-nik');
    var closeEditBtn = document.getElementById('btn-close-edit-nik');
    var cancelEdit   = document.getElementById('btn-cancel-edit-nik');
    var editNik      = document.getElementById('edit-nik');
    var editNama     = document.getElementById('edit-nama');
    var editNokk     = document.getElementById('edit-nokk');
    var editPad      = document.getElementById('edit-padukuhan');
    var editRt       = document.getElementById('edit-rt');
    var editRw       = document.getElementById('edit-rw');
    var editAlamat   = document.getElementById('edit-alamat');

    // Table & Filter Elements
    var filterSel = document.getElementById('filter-padukuhan');
    var table     = document.getElementById('master-nik-table');

    // Pagination
    var pagination = window.initTablePagination ? window.initTablePagination({
        tableId: 'master-nik-table',
        containerId: 'master-nik-pagination',
        pageSize: 6,
        itemLabel: 'data master NIK'
    }) : null;

    // Dropdown Action (3 Dots)
    function closeAllActionDropdowns() {
        document.querySelectorAll('.action-dropdown.is-open').forEach(function (dd) {
            dd.classList.remove('is-open');
            var btn = dd.querySelector('.btn-action-dots');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        });
    }

    document.addEventListener('click', function (e) {
        var trigger = e.target.closest('.btn-action-dots');
        if (trigger) {
            e.stopPropagation();
            var currentWrap = trigger.closest('.action-dropdown');
            var wasOpen     = currentWrap.classList.contains('is-open');

            closeAllActionDropdowns();

            if (!wasOpen) {
                currentWrap.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }
            return;
        }

        if (!e.target.closest('.action-dropdown')) {
            closeAllActionDropdowns();
        }
    });

    // Handle Edit Click
    document.addEventListener('click', function (e) {
        var editBtn = e.target.closest('.btn-edit-nik');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation();
            closeAllActionDropdowns();

            var id        = editBtn.getAttribute('data-id');
            var nik       = editBtn.getAttribute('data-nik') || '';
            var nama      = editBtn.getAttribute('data-nama') || '';
            var nokk      = editBtn.getAttribute('data-nokk') || '';
            var padukuhan = editBtn.getAttribute('data-padukuhan') || '';
            var rt        = editBtn.getAttribute('data-rt') || '';
            var rw        = editBtn.getAttribute('data-rw') || '';
            var alamat    = editBtn.getAttribute('data-alamat') || '';

            if (formEdit) {
                formEdit.action = '<?= base_url('admin/master-nik') ?>/' + id + '/update';
            }

            if (editNik) editNik.value = nik;
            if (editNama) editNama.value = nama;
            if (editNokk) editNokk.value = nokk;
            if (editPad) editPad.value = padukuhan;
            if (editRt) editRt.value = rt;
            if (editRw) editRw.value = rw;
            if (editAlamat) editAlamat.value = alamat;

            if (modalEdit) {
                modalEdit.classList.add('is-open');
                setTimeout(function () { if (editNama) editNama.focus(); }, 50);
            }
        }
    });

    if (filterSel) {
        if (window.initScrollablePadukuhanFilter) {
            window.initScrollablePadukuhanFilter(filterSel);
        }

        filterSel.addEventListener('change', function () {
            var selected = filterSel.value.toLowerCase().trim();
            if (!table) return;
            var rows = table.querySelectorAll('tbody tr');

            rows.forEach(function (row) {
                if (row.classList.contains('empty-filter-row')) return;
                var rowPad = (row.getAttribute('data-padukuhan') || '').toLowerCase().trim();
                if (!selected || rowPad === selected) {
                    row.removeAttribute('data-filter-hidden');
                } else {
                    row.setAttribute('data-filter-hidden', 'true');
                }
            });

            if (pagination) pagination.refresh();
        });
    }

    // Modal Tambah Events
    if (openAddBtn && modalTambah) {
        openAddBtn.addEventListener('click', function () {
            closeAllActionDropdowns();
            modalTambah.classList.add('is-open');
            if (nikInput) {
                setTimeout(function () { nikInput.focus(); }, 50);
            }
        });
    }

    if (closeAddBtn && modalTambah) {
        closeAddBtn.addEventListener('click', function () {
            modalTambah.classList.remove('is-open');
        });
    }

    if (cancelAdd && modalTambah) {
        cancelAdd.addEventListener('click', function () {
            modalTambah.classList.remove('is-open');
        });
    }

    if (modalTambah) {
        modalTambah.addEventListener('click', function (e) {
            if (e.target === modalTambah) modalTambah.classList.remove('is-open');
        });
    }

    // Modal Edit Events
    if (closeEditBtn && modalEdit) {
        closeEditBtn.addEventListener('click', function () {
            modalEdit.classList.remove('is-open');
        });
    }

    if (cancelEdit && modalEdit) {
        cancelEdit.addEventListener('click', function () {
            modalEdit.classList.remove('is-open');
        });
    }

    if (modalEdit) {
        modalEdit.addEventListener('click', function (e) {
            if (e.target === modalEdit) modalEdit.classList.remove('is-open');
        });
    }

    // Keydown Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllActionDropdowns();
            if (modalTambah && modalTambah.classList.contains('is-open')) {
                modalTambah.classList.remove('is-open');
            }
            if (modalEdit && modalEdit.classList.contains('is-open')) {
                modalEdit.classList.remove('is-open');
            }
        }
    });
})();
</script>
<?= $this->endSection() ?>
