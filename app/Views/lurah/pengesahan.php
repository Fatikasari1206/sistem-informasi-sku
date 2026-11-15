<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
    <?= $this->include('staf/_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('lurah/_sidebar_nav', ['activeMenu' => 'pengesahan']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Portal Pengesahan Tanda Tangan Elektronik (TTE)</h2>
                <p class="panel-subtitle">Tinjau draft Surat Keterangan Usaha yang telah diverifikasi Staf Pelayanan sebelum disahkan.</p>
            </div>
        </div>

        <div class="empty-state">
            <strong>Tidak ada surat yang menunggu pengesahan saat ini</strong>
            <span>Surat akan muncul di sini setelah Staf Pelayanan menyelesaikan pemeriksaan dan penomoran SKU.</span>
        </div>
    </section>
<?= $this->endSection() ?>
