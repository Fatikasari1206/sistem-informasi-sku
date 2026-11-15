<?= $this->extend('layouts/auth') ?>

<?php
$this->setVar('cardMaxWidth', '440px');
$this->setVar('cardPadding', '1.85rem 2.25rem 2rem');
?>

<?= $this->section('styles') ?>
<style>
    .forgot-link {
        font-size: 0.74rem;
        font-weight: 500;
        color: #60a5fa;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .forgot-link:hover {
        color: #93c5fd;
        text-decoration: underline;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('card') ?>
    <div class="card-header">
        <h2 id="auth-heading">Masuk Akun Pemohon</h2>
        <p>Gunakan alamat email yang telah terdaftar di Kalurahan Condongcatur.</p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success" id="flash-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

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

    <div class="alert alert-info" id="forgot-password-info">
        Untuk reset kata sandi, silakan hubungi Admin atau petugas pelayanan di Kantor Kalurahan Condongcatur dengan membawa KTP asli.
    </div>

    <form action="<?= base_url('login') ?>" method="POST" id="form-login">
        <?= csrf_field() ?>

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
                placeholder="nama@email.com"
                required
                autofocus
            >
        </div>

        <div class="form-group">
            <div class="label-row">
                <label for="password" class="form-label">Kata Sandi</label>
                <a href="#lupa-sandi" class="forgot-link" id="link-forgot-password">Lupa Kata Sandi?</a>
            </div>
            <?= view('auth/_password_input', [
                'id'           => 'password',
                'name'         => 'password',
                'autocomplete' => 'current-password',
                'placeholder'  => 'Masukkan kata sandi',
            ]) ?>
        </div>

        <div class="divider"></div>

        <button type="submit" class="btn-auth" id="btn-submit-login">Masuk</button>
    </form>

    <p class="auth-footer-link">
        Belum memiliki akun warga?
        <a href="<?= base_url('register') ?>">Registrasi NIK Mandiri &rarr;</a>
    </p>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('link-forgot-password').addEventListener('click', function (e) {
        e.preventDefault();
        var info = document.getElementById('forgot-password-info');
        info.style.display = info.style.display === 'block' ? 'none' : 'block';
    });
</script>
<?= $this->endSection() ?>
