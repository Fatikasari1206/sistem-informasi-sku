<?= $this->extend('layouts/auth') ?>

<?php
$this->setVar('cardMaxWidth', '610px');
$this->setVar('cardPadding', '1.45rem 2rem 1.55rem');
?>

<?= $this->section('styles') ?>
<style>
    .nik-search-row {
        display: flex;
        gap: 0.65rem;
        align-items: stretch;
    }

    .nik-search-input {
        flex: 1;
        border: 1.5px solid #2dd4bf;
    }

    .nik-search-input:focus {
        border-color: #14b8a6;
        box-shadow: 0 0 0 3px rgba(45, 212, 191, 0.25);
    }

    .btn-cek-nik {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        height: 40px;
        padding: 0 1rem;
        background: #e2f8ed;
        color: #0f172a;
        border: 1.5px solid #a7f3d0;
        border-radius: 9px;
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.15s ease;
    }

    .btn-cek-nik:hover:not(:disabled) {
        background: #ccf4df;
        transform: translateY(-1px);
    }

    .btn-cek-nik:disabled {
        opacity: 0.75;
        cursor: wait;
    }

    .nik-status {
        margin-top: 0.35rem;
        font-size: 0.74rem;
        line-height: 1.35;
        display: none;
    }

    .nik-status.is-success {
        display: block;
        color: #6ee7b7;
    }

    .nik-status.is-error {
        display: block;
        color: #fca5a5;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 1rem;
    }

    .consent-row {
        display: flex;
        align-items: flex-start;
        gap: 0.55rem;
        margin: 0.15rem 0 1rem;
        font-size: 0.74rem;
        color: #d1d5db;
        line-height: 1.4;
        cursor: pointer;
    }

    .consent-row input[type="checkbox"] {
        width: 15px;
        height: 15px;
        margin-top: 1px;
        accent-color: #5246f0;
        cursor: pointer;
        flex-shrink: 0;
    }

    @media (max-width: 680px) {
        .nik-search-row {
            flex-direction: column;
        }

        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('card') ?>
    <div class="card-header">
        <h2 id="auth-heading">Registrasi Akun Pemohon</h2>
        <p>Khusus warga ber-KTP Condongcatur. Data KTP akan terisi otomatis.</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error" id="flash-error">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error" id="flash-errors">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('register') ?>" method="POST" id="form-register" autocomplete="off">
        <?= csrf_field() ?>

        <div class="form-group">
            <div class="label-row">
                <label for="nik" class="form-label">Nomor Induk Kependudukan (NIK)</label>
            </div>
            <div class="nik-search-row">
                <input
                    type="text"
                    name="nik"
                    id="nik"
                    class="form-control nik-search-input"
                    inputmode="numeric"
                    maxlength="16"
                    pattern="[0-9]{16}"
                    autocomplete="off"
                    value="<?= esc(old('nik')) ?>"
                    placeholder="Ketik 16 angka NIK KTP Anda"
                    required
                    autofocus
                >
                <button type="button" class="btn-cek-nik" id="btn-cek-nik">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                        <circle cx="9" cy="10" r="2"></circle>
                        <path d="M15 8h2"></path>
                        <path d="M15 12h2"></path>
                        <path d="M7 16h10"></path>
                    </svg>
                    <span>Cek NIK</span>
                </button>
            </div>
            <div id="nik-status" class="nik-status" role="status" aria-live="polite"></div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <div class="label-row">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap sesuai KTP</label>
                </div>
                <input
                    type="text"
                    name="nama_lengkap"
                    id="nama_lengkap"
                    class="form-control"
                    autocomplete="name"
                    value="<?= esc(old('nama_lengkap')) ?>"
                    placeholder="Otomatis muncul setelah cek NIK"
                    readonly
                    required
                >
            </div>

            <div class="form-group">
                <div class="label-row">
                    <label for="wilayah_domisili" class="form-label">Padukuhan / RT &amp; RW</label>
                </div>
                <input
                    type="text"
                    name="wilayah_domisili"
                    id="wilayah_domisili"
                    class="form-control"
                    value="<?= esc(old('wilayah_domisili')) ?>"
                    placeholder="Otomatis muncul setelah cek NIK"
                    readonly
                    required
                >
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <div class="label-row">
                    <label for="no_hp" class="form-label">Nomor WhatsApp Aktif</label>
                </div>
                <input
                    type="tel"
                    name="no_hp"
                    id="no_hp"
                    class="form-control"
                    autocomplete="tel"
                    value="<?= esc(old('no_hp')) ?>"
                    placeholder="Contoh: 081234567890"
                    required
                >
            </div>

            <div class="form-group">
                <div class="label-row">
                    <label for="email" class="form-label">Alamat Email</label>
                </div>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control"
                    autocomplete="email"
                    value="<?= esc(old('email')) ?>"
                    placeholder="Contoh: nama@email.com"
                    required
                >
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <div class="label-row">
                    <label for="password" class="form-label">Kata Sandi</label>
                </div>
                <?= view('auth/_password_input', [
                    'id'           => 'password',
                    'name'         => 'password',
                    'autocomplete' => 'new-password',
                    'placeholder'  => 'Minimal 6 karakter',
                    'minlength'    => 6,
                ]) ?>
            </div>

            <div class="form-group">
                <div class="label-row">
                    <label for="password_confirm" class="form-label">Ulangi Kata Sandi</label>
                </div>
                <?= view('auth/_password_input', [
                    'id'           => 'password_confirm',
                    'name'         => 'password_confirm',
                    'autocomplete' => 'new-password',
                    'placeholder'  => 'Ketik ulang kata sandi di atas',
                    'minlength'    => 6,
                ]) ?>
            </div>
        </div>

        <label class="consent-row" for="persetujuan">
            <input type="checkbox" name="persetujuan" id="persetujuan" value="1" <?= old('persetujuan') ? 'checked' : '' ?> required>
            <span>Saya menyatakan data kependudukan dan NIK di atas benar serta sesuai KTP asli.</span>
        </label>

        <button type="submit" class="btn-auth" id="btn-submit-register">Daftar Sekarang</button>
    </form>

    <p class="auth-footer-link">
        Sudah memiliki akun warga?
        <a href="<?= base_url('login') ?>">Masuk ke Portal Warga &rarr;</a>
    </p>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function () {
        var nikInput = document.getElementById('nik');
        var namaInput = document.getElementById('nama_lengkap');
        var wilayahInput = document.getElementById('wilayah_domisili');
        var btnCek = document.getElementById('btn-cek-nik');
        var statusEl = document.getElementById('nik-status');
        var cekNikUrl = '<?= base_url('register/cek-nik') ?>';

        function setStatus(type, text) {
            statusEl.className = 'nik-status ' + (type === 'success' ? 'is-success' : 'is-error');
            statusEl.textContent = text;
        }

        function resetSyncedFields() {
            namaInput.value = '';
            wilayahInput.value = '';
        }

        async function cekNik() {
            var nik = nikInput.value.trim();

            if (!/^\d{16}$/.test(nik)) {
                resetSyncedFields();
                setStatus('error', 'Masukkan 16 digit angka NIK terlebih dahulu.');
                nikInput.focus();
                return;
            }

            btnCek.disabled = true;
            setStatus('success', 'Memeriksa NIK pada data master Kalurahan Condongcatur...');

            try {
                var response = await fetch(cekNikUrl + '?nik=' + encodeURIComponent(nik), {
                    headers: { 'Accept': 'application/json' }
                });
                var payload = await response.json();

                if (!response.ok || !payload.success) {
                    resetSyncedFields();
                    setStatus('error', payload.message || 'NIK gagal diverifikasi.');
                    return;
                }

                namaInput.value = payload.data.nama_lengkap;
                wilayahInput.value = payload.data.wilayah_padukuhan;
                setStatus('success', payload.message);
            } catch (err) {
                resetSyncedFields();
                setStatus('error', 'Gagal terhubung ke server untuk memeriksa NIK.');
            } finally {
                btnCek.disabled = false;
            }
        }

        nikInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 16);
            resetSyncedFields();
            statusEl.className = 'nik-status';
            statusEl.textContent = '';
        });

        nikInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                cekNik();
            }
        });

        btnCek.addEventListener('click', cekNik);
    })();
</script>
<?= $this->endSection() ?>
