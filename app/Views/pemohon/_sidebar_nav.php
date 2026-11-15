<?php $activeMenu = $activeMenu ?? 'dashboard'; ?>
<a href="<?= base_url('pemohon/dashboard') ?>" class="nav-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
<a href="<?= base_url('pemohon/sku/baru') ?>" class="nav-link <?= $activeMenu === 'baru' ? 'active' : '' ?>">Permohonan SKU Baru</a>
<a href="<?= base_url('pemohon/sku/perpanjangan') ?>" class="nav-link <?= $activeMenu === 'perpanjangan' ? 'active' : '' ?>">Perpanjangan SKU</a>
