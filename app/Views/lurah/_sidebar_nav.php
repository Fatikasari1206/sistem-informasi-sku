<?php $active = $activeMenu ?? 'dashboard'; ?>
<a href="<?= base_url('lurah/dashboard') ?>" class="nav-link <?= $active === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
<a href="<?= base_url('lurah/antrian') ?>" class="nav-link <?= $active === 'antrian' ? 'active' : '' ?>">Antrian Masuk</a>
<a href="<?= base_url('lurah/pengesahan') ?>" class="nav-link <?= $active === 'pengesahan' ? 'active' : '' ?>">Pengesahan TTE</a>
<a href="<?= base_url('lurah/arsip') ?>" class="nav-link <?= $active === 'arsip' ? 'active' : '' ?>">Arsip SKU Terbit</a>
