<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="max-width: 640px; margin: 1.5rem auto;">
    <div class="card" style="padding: 2.25rem; box-shadow: var(--shadow-lg); border-radius: var(--radius-lg);">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--success) 0%, #059669 100%); color: white; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1rem; box-shadow: 0 6px 16px rgba(16,185,129,0.3);">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--secondary); letter-spacing: -0.02em;">Pendaftaran Akun Warga</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.35rem;">Daftar untuk mengajukan Surat Keterangan Usaha (SKU) Baru & Perpanjangan</p>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div style="font-size: 0.85rem;">
                    <strong>Perhatian:</strong>
                    <ul style="margin-left: 1rem; margin-top: 0.25rem;">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('register') ?>" method="POST" id="form-register">
            <?= csrf_field() ?>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label for="input-nik" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.4rem;">
                        NIK KTP (16 Digit) <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="text" name="nik" id="input-nik" maxlength="16" value="<?= old('nik') ?>" required placeholder="Contoh: 340401..." 
                        style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none;">
                </div>

                <div>
                    <label for="input-nama" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.4rem;">
                        Nama Lengkap (Sesuai KTP) <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="text" name="nama_lengkap" id="input-nama" value="<?= old('nama_lengkap') ?>" required placeholder="Nama lengkap Anda" 
                        style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label for="input-email" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.4rem;">
                        Alamat Email <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="email" name="email" id="input-email" value="<?= old('email') ?>" required placeholder="nama@email.com" 
                        style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none;">
                </div>

                <div>
                    <label for="input-nohp" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.4rem;">
                        Nomor HP / WhatsApp <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="tel" name="no_hp" id="input-nohp" value="<?= old('no_hp') ?>" required placeholder="Contoh: 08123456789" 
                        style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none;">
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="input-alamat" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.4rem;">
                    Alamat Domisili / KTP <span style="color: var(--danger);">*</span>
                </label>
                <textarea name="alamat" id="input-alamat" rows="2" required placeholder="Nama jalan, nomor rumah, padukuhan / dusun" 
                    style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none; resize: vertical;"><?= old('alamat') ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label for="input-rt" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.4rem;">
                        RT
                    </label>
                    <input type="text" name="rt" id="input-rt" maxlength="5" value="<?= old('rt') ?>" placeholder="01" 
                        style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none;">
                </div>

                <div>
                    <label for="input-rw" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.4rem;">
                        RW
                    </label>
                    <input type="text" name="rw" id="input-rw" maxlength="5" value="<?= old('rw') ?>" placeholder="02" 
                        style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.75rem;">
                <div>
                    <label for="input-password" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.4rem;">
                        Kata Sandi (Min. 6 Karakter) <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="password" name="password" id="input-password" required placeholder="••••••••" 
                        style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none;">
                </div>

                <div>
                    <label for="input-confirm" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.4rem;">
                        Konfirmasi Kata Sandi <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="password" name="password_confirm" id="input-confirm" required placeholder="••••••••" 
                        style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="btn-submit-register" style="width: 100%; justify-content: center; padding: 0.85rem; font-size: 1rem; border-radius: var(--radius-md);">
                <i class="fa-solid fa-check"></i> Selesaikan Pendaftaran
            </button>
        </form>

        <div style="margin-top: 1.5rem; text-align: center; font-size: 0.88rem; color: var(--text-muted);">
            Sudah memiliki akun? 
            <a href="<?= base_url('login') ?>" style="color: var(--primary); font-weight: 700; text-decoration: none;">Masuk ke Akun Anda</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
