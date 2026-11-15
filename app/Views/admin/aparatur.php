<?= $this->extend('layouts/dashboard') ?>

<?php
$errors       = session()->getFlashdata('errors') ?? [];
$hasFormError = !empty($errors);

$roleMeta = [
    'lurah' => [
        'jabatan'   => 'Lurah Condongcatur',
        'hak_akses' => 'Lurah',
        'badge'     => 'status-indigo',
    ],
    'staf' => [
        'jabatan'   => 'Staf Pelayanan Umum',
        'hak_akses' => 'Staff',
        'badge'     => 'status-indigo',
    ],
    'admin' => [
        'jabatan'   => 'Administrator Sistem',
        'hak_akses' => 'Admin',
        'badge'     => 'status-neutral',
    ],
];

$formatNip = static function (?string $nip): string {
    $digits = preg_replace('/\D/', '', (string) $nip);
    if ($digits === '') {
        return '-';
    }
    if (strlen($digits) === 18) {
        return substr($digits, 0, 8) . ' ' . substr($digits, 8, 6) . ' ' . substr($digits, 14, 1) . ' ' . substr($digits, 15, 3);
    }
    return $digits;
};
?>

<?= $this->section('styles') ?>
    <?= $this->include('staf/_styles') ?>
    <style>
        .btn-add-aparatur {
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

        .btn-add-aparatur:hover {
            opacity: 0.94;
            transform: translateY(-1px);
        }

        .hak-akses-label {
            font-size: 0.76rem;
            font-weight: 700;
            color: #4f46e5;
        }

        .btn-submit-aparatur {
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

        .btn-submit-aparatur:hover {
            opacity: 0.94;
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('admin/_sidebar_nav', ['activeMenu' => 'aparatur']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- Tabel Informasi Aparatur -->
    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Daftar Akun Aparatur Kalurahan Terdaftar</h2>
                <p class="panel-subtitle">Pusat penerbitan dan pengelolaan akun kedinasan Staf Pelayanan, Lurah, dan Administrator.</p>
            </div>

            <button type="button" class="btn-add-aparatur" id="btn-open-tambah-aparatur">
                <span>+ Tambah Aparatur</span>
            </button>
        </div>

        <?php if (empty($aparaturList)): ?>
            <div class="empty-state">
                <strong>Belum ada akun aparatur terdaftar</strong>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="staf-table" id="aparatur-table">
                    <thead>
                        <tr>
                            <th>NIP</th>
                            <th>NAMA </th>
                            <th>JABATAN</th>
                            <th>HAK AKSES</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $currentUserId = (int) (session()->get('userId') ?? 0); ?>
                        <?php foreach ($aparaturList as $row): ?>
                            <?php
                                $roleKey  = strtolower((string) ($row['role'] ?? 'staf'));
                                $meta     = $roleMeta[$roleKey] ?? ['jabatan' => ucfirst($roleKey), 'hak_akses' => ucfirst($roleKey), 'badge' => 'status-neutral'];
                                $jabatan  = !empty($row['jabatan']) ? $row['jabatan'] : $meta['jabatan'];
                                $isActive = (int) ($row['status_aktif'] ?? 0) === 1;
                                $isSelf   = ((int) $row['id']) === $currentUserId;
                            ?>
                            <tr>
                                <td class="cell-strong"><?= esc($formatNip($row['nip'] ?? null)) ?></td>
                                <td>
                                    <span class="pemohon-name"><?= esc($row['nama_lengkap']) ?></span>
                                    <span class="pemohon-nik"><?= esc($row['email']) ?></span>
                                </td>
                                <td><?= esc($jabatan) ?></td>
                                <td>
                                    <span class="hak-akses-label"><?= esc($meta['hak_akses']) ?></span>
                                </td>
                                <td>
                                    <span class="pill-badge <?= $isActive ? 'status-green' : 'status-red' ?>">
                                        <?= $isActive ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
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
                                                class="action-menu-item btn-edit-aparatur"
                                                data-id="<?= (int) $row['id'] ?>"
                                                data-nama="<?= esc($row['nama_lengkap']) ?>"
                                                data-nip="<?= esc($row['nip'] ?? '') ?>"
                                                data-role="<?= esc($row['role'] ?? 'staf') ?>"
                                                data-jabatan="<?= esc($jabatan) ?>"
                                                data-email="<?= esc($row['email'] ?? '') ?>"
                                            >
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                                <span>Edit</span>
                                            </button>

                                            <?php if (!$isSelf): ?>
                                                <form action="<?= base_url('admin/aparatur/' . (int) $row['id'] . '/status') ?>" method="post">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="action-menu-item <?= $isActive ? 'warning' : 'success' ?>">
                                                        <?php if ($isActive): ?>
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                                                            </svg>
                                                            <span>Nonaktifkan</span>
                                                        <?php else: ?>
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                            </svg>
                                                            <span>Aktifkan</span>
                                                        <?php endif; ?>
                                                    </button>
                                                </form>

                                                <form action="<?= base_url('admin/aparatur/' . (int) $row['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun aparatur <?= esc(addslashes($row['nama_lengkap'])) ?>?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="action-menu-item danger">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        </svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="aparatur-pagination"></div>
        <?php endif; ?>
    </section>

    <!-- Pop-up Modal Tambah User Aparatur -->
    <div class="modal-backdrop <?= $hasFormError ? 'is-open' : '' ?>" id="modal-tambah-aparatur" role="dialog" aria-modal="true" aria-labelledby="modal-aparatur-title">
        <div class="modal-card">
            <div class="modal-head">
                <div>
                    <h3 class="modal-title" id="modal-aparatur-title">Akun Baru</h3>
                    <p class="modal-subtitle">Lengkapi data aparatur untuk mengakses portal internal.</p>
                </div>
                <button type="button" class="modal-close" id="btn-close-tambah-aparatur" aria-label="Tutup">&times;</button>
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

                <form action="<?= base_url('admin/aparatur') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-field">
                        <label for="input-nama">Nama Lengkap</label>
                        <input
                            type="text"
                            id="input-nama"
                            name="nama_lengkap"
                            value="<?= esc(old('nama_lengkap') ?? '') ?>"
                            placeholder="Masukkan nama lengkap dan gelar"
                            required
                        >
                    </div>

                    <div class="form-field">
                        <label for="input-nip">NIP</label>
                        <input
                            type="text"
                            id="input-nip"
                            name="nip"
                            value="<?= esc(old('nip') ?? '') ?>"
                            placeholder="Masukkan 18 digit NIP"
                            required
                        >
                    </div>

                    <div class="form-grid-2">
                        <div class="form-field">
                            <label for="input-role">Hak Akses</label>
                            <select id="input-role" name="role" required>
                                <?php $selectedRole = old('role') ?? 'staf'; ?>
                                <option value="staf" <?= $selectedRole === 'staf' ? 'selected' : '' ?>>Staff</option>
                                <option value="lurah" <?= $selectedRole === 'lurah' ? 'selected' : '' ?>>Lurah</option>
                                <option value="admin" <?= $selectedRole === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label for="input-jabatan">Jabatan</label>
                            <input
                                type="text"
                                id="input-jabatan"
                                name="jabatan"
                                value="<?= esc(old('jabatan') ?? '') ?>"
                                placeholder="Contoh: Staf Pelayanan Umum"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="input-email">Email</label>
                        <input
                            type="email"
                            id="input-email"
                            name="email"
                            value="<?= esc(old('email') ?? '') ?>"
                            placeholder="nama@kalurahan.go.id"
                            required
                        >
                    </div>

                    <div class="form-field">
                        <label for="input-password">Password</label>
                        <input
                            type="password"
                            id="input-password"
                            name="password"
                            placeholder="Minimal 6 karakter"
                            minlength="6"
                            required
                        >
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" id="btn-cancel-tambah-aparatur">Batal</button>
                        <button type="submit" class="btn-submit-aparatur">Simpan Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pop-up Modal Edit User Aparatur -->
    <div class="modal-backdrop" id="modal-edit-aparatur" role="dialog" aria-modal="true" aria-labelledby="modal-edit-aparatur-title">
        <div class="modal-card">
            <div class="modal-head">
                <div>
                    <h3 class="modal-title" id="modal-edit-aparatur-title">Edit Akun Aparatur</h3>
                    <p class="modal-subtitle">Perbarui data kredensial aparatur kedinasan.</p>
                </div>
                <button type="button" class="modal-close" id="btn-close-edit-aparatur" aria-label="Tutup">&times;</button>
            </div>

            <div class="modal-body">
                <form id="form-edit-aparatur" action="" method="post">
                    <?= csrf_field() ?>

                    <div class="form-field">
                        <label for="edit-nama">Nama Lengkap</label>
                        <input
                            type="text"
                            id="edit-nama"
                            name="nama_lengkap"
                            placeholder="Masukkan nama lengkap dan gelar"
                            required
                        >
                    </div>

                    <div class="form-field">
                        <label for="edit-nip">NIP</label>
                        <input
                            type="text"
                            id="edit-nip"
                            name="nip"
                            placeholder="Masukkan NIP"
                            required
                        >
                    </div>

                    <div class="form-grid-2">
                        <div class="form-field">
                            <label for="edit-role">Hak Akses</label>
                            <select id="edit-role" name="role" required>
                                <option value="staf">Staff</option>
                                <option value="lurah">Lurah</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label for="edit-jabatan">Jabatan</label>
                            <input
                                type="text"
                                id="edit-jabatan"
                                name="jabatan"
                                placeholder="Contoh: Staf Pelayanan Umum"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="edit-email">Email</label>
                        <input
                            type="email"
                            id="edit-email"
                            name="email"
                            placeholder="nama@kalurahan.go.id"
                            required
                        >
                    </div>

                    <div class="form-field">
                        <label for="edit-password">Password (Opsional)</label>
                        <input
                            type="password"
                            id="edit-password"
                            name="password"
                            placeholder="Kosongkan jika tidak ingin mengubah kata sandi"
                            minlength="6"
                        >
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" id="btn-cancel-edit-aparatur">Batal</button>
                        <button type="submit" class="btn-submit-aparatur">Simpan Perubahan</button>
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
    // Pagination
    window.initTablePagination({
        tableId: 'aparatur-table',
        containerId: 'aparatur-pagination',
        pageSize: 6,
        itemLabel: 'data aparatur'
    });

    // Dropdowns Aksi Titik 3
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

    // Modal Tambah
    var modalAdd    = document.getElementById('modal-tambah-aparatur');
    var openAddBtn  = document.getElementById('btn-open-tambah-aparatur');
    var closeAddBtn = document.getElementById('btn-close-tambah-aparatur');
    var cancelAdd   = document.getElementById('btn-cancel-tambah-aparatur');
    var addRoleSel  = document.getElementById('input-role');
    var addJabatan  = document.getElementById('input-jabatan');
    var addNama     = document.getElementById('input-nama');

    var jabatanPlaceholders = {
        staf: 'Contoh: Staf Pelayanan Umum',
        lurah: 'Contoh: Lurah Condongcatur',
        admin: 'Contoh: Administrator Sistem'
    };

    if (addRoleSel && addJabatan) {
        addRoleSel.addEventListener('change', function () {
            addJabatan.placeholder = jabatanPlaceholders[addRoleSel.value] || 'Masukkan jabatan';
        });
    }

    if (openAddBtn && modalAdd) {
        openAddBtn.addEventListener('click', function () {
            closeAllActionDropdowns();
            modalAdd.classList.add('is-open');
            if (addNama) setTimeout(function () { addNama.focus(); }, 50);
        });
    }

    if (closeAddBtn && modalAdd) {
        closeAddBtn.addEventListener('click', function () { modalAdd.classList.remove('is-open'); });
    }
    if (cancelAdd && modalAdd) {
        cancelAdd.addEventListener('click', function () { modalAdd.classList.remove('is-open'); });
    }
    if (modalAdd) {
        modalAdd.addEventListener('click', function (e) { if (e.target === modalAdd) modalAdd.classList.remove('is-open'); });
    }

    // Modal Edit
    var modalEdit    = document.getElementById('modal-edit-aparatur');
    var formEdit     = document.getElementById('form-edit-aparatur');
    var closeEditBtn = document.getElementById('btn-close-edit-aparatur');
    var cancelEdit   = document.getElementById('btn-cancel-edit-aparatur');
    var editNama     = document.getElementById('edit-nama');
    var editNip      = document.getElementById('edit-nip');
    var editRole     = document.getElementById('edit-role');
    var editJabatan  = document.getElementById('edit-jabatan');
    var editEmail    = document.getElementById('edit-email');

    document.addEventListener('click', function (e) {
        var editBtn = e.target.closest('.btn-edit-aparatur');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation();
            closeAllActionDropdowns();

            var id      = editBtn.getAttribute('data-id');
            var nama    = editBtn.getAttribute('data-nama') || '';
            var nip     = editBtn.getAttribute('data-nip') || '';
            var role    = editBtn.getAttribute('data-role') || 'staf';
            var jabatan = editBtn.getAttribute('data-jabatan') || '';
            var email   = editBtn.getAttribute('data-email') || '';

            if (formEdit) {
                formEdit.action = '<?= base_url('admin/aparatur') ?>/' + id + '/update';
            }

            if (editNama) editNama.value = nama;
            if (editNip) editNip.value = nip;
            if (editRole) editRole.value = role;
            if (editJabatan) editJabatan.value = jabatan;
            if (editEmail) editEmail.value = email;

            var editPwd = document.getElementById('edit-password');
            if (editPwd) editPwd.value = '';

            if (modalEdit) {
                modalEdit.classList.add('is-open');
                setTimeout(function () { if (editNama) editNama.focus(); }, 50);
            }
        }
    });

    if (closeEditBtn && modalEdit) {
        closeEditBtn.addEventListener('click', function () { modalEdit.classList.remove('is-open'); });
    }
    if (cancelEdit && modalEdit) {
        cancelEdit.addEventListener('click', function () { modalEdit.classList.remove('is-open'); });
    }
    if (modalEdit) {
        modalEdit.addEventListener('click', function (e) { if (e.target === modalEdit) modalEdit.classList.remove('is-open'); });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllActionDropdowns();
            if (modalAdd && modalAdd.classList.contains('is-open')) modalAdd.classList.remove('is-open');
            if (modalEdit && modalEdit.classList.contains('is-open')) modalEdit.classList.remove('is-open');
        }
    });
})();
</script>
<?= $this->endSection() ?>
