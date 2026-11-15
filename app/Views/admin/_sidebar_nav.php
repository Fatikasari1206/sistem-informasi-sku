<?php $active = $activeMenu ?? 'dashboard'; ?>
<a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= $active === 'dashboard' ? 'active' : '' ?>">Ringkasan Sistem</a>
<a href="<?= base_url('admin/aparatur') ?>" class="nav-link <?= $active === 'aparatur' ? 'active' : '' ?>">Kelola Akun Aparatur</a>
<a href="<?= base_url('admin/master-nik') ?>" class="nav-link <?= $active === 'master-nik' ? 'active' : '' ?>">Master Data NIK</a>
