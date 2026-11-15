<?php $active = $activeMenu ?? 'dashboard'; ?>
<a href="<?= base_url('staf/dashboard') ?>" class="nav-link <?= $active === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
<a href="<?= base_url('staf/antrian') ?>" class="nav-link <?= $active === 'antrian' ? 'active' : '' ?>">Antrian Masuk</a>
<a href="<?= base_url('staf/verifikasi') ?>" class="nav-link <?= $active === 'verifikasi' ? 'active' : '' ?>">Verifikasi Berkas</a>
<a href="<?= base_url('staf/penomoran') ?>" class="nav-link <?= $active === 'penomoran' ? 'active' : '' ?>">Penerbitan No. SKU</a>
