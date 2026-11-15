<?= $this->extend('layouts/auth') ?>

<?php
$this->setVar('heroTitleHtml', 'Portal Internal Kalurahan<br>Condongcatur');
$this->setVar('heroSubtitle', 'Gerbang otorisasi kedinasan khusus Staf Pelayanan, Lurah, dan Administrator Sistem Kalurahan Condongcatur.');
$this->setVar('cardMaxWidth', '440px');
$this->setVar('cardPadding', '1.85rem 2.25rem 2rem');
?>

<?= $this->section('card') ?>
    <div class="card-header">
        <h2 id="auth-heading">Masuk Sistem SKU</h2>
        <p>Pilih unit otorisasi kedinasan Anda untuk melanjutkan proses operasional SKU.</p>
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

    <form action="<?= base_url('admin') ?>" method="POST" id="form-admin-login">
        <?= csrf_field() ?>

        <div class="form-group">
            <div class="label-row">
                <label for="nip" class="form-label">NIP</label>
            </div>
            <input
                type="text"
                name="nip"
                id="nip"
                class="form-control"
                inputmode="numeric"
                autocomplete="username"
                value="<?= esc(old('nip')) ?>"
                placeholder="Masukkan NIP"
                required
                autofocus
            >
        </div>

        <div class="form-group">
            <div class="label-row">
                <label for="password" class="form-label">Kata Sandi</label>
            </div>
            <?= view('auth/_password_input', [
                'id'           => 'password',
                'name'         => 'password',
                'autocomplete' => 'current-password',
                'placeholder'  => 'Masukkan kata sandi',
            ]) ?>
        </div>

        <div class="divider"></div>

        <button type="submit" class="btn-auth" id="btn-submit-admin-login">Login</button>
    </form>
<?= $this->endSection() ?>
