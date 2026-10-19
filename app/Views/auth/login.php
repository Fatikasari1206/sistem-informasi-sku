<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="max-width: 480px; margin: 1.5rem auto;">
    <div class="card" style="padding: 2.25rem; box-shadow: var(--shadow-lg); border-radius: var(--radius-lg);">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%); color: white; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1rem; box-shadow: 0 6px 16px rgba(37,99,235,0.3);">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--secondary); letter-spacing: -0.02em;">Masuk ke Sistem</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.35rem;">Pelayanan Surat Keterangan Usaha (SKU)</p>
        </div>

        <form action="<?= base_url('login') ?>" method="POST" id="form-login">
            <?= csrf_field() ?>

            <div style="margin-bottom: 1.25rem;">
                <label for="input-email" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.4rem;">
                    Alamat Email
                </label>
                <div style="position: relative;">
                    <i class="fa-regular fa-envelope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="email" name="email" id="input-email" value="<?= old('email') ?>" required placeholder="nama@email.com" 
                        style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none; transition: var(--transition);">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="input-password" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.4rem;">
                    Kata Sandi
                </label>
                <div style="position: relative;">
                    <i class="fa-solid fa-key" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="password" name="password" id="input-password" required placeholder="••••••••" 
                        style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none; transition: var(--transition);">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="btn-submit-login" style="width: 100%; justify-content: center; padding: 0.85rem; font-size: 1rem; border-radius: var(--radius-md);">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk Sekarang
            </button>
        </form>

        <div style="margin-top: 1.75rem; text-align: center; font-size: 0.88rem; color: var(--text-muted);">
            Belum punya akun pemohon? 
            <a href="<?= base_url('register') ?>" style="color: var(--primary); font-weight: 700; text-decoration: none;">Daftar Akun Warga</a>
        </div>

        <!-- Quick Demo Switcher -->
        <div style="margin-top: 2rem; padding: 1.25rem; background: #f8fafc; border: 1px dashed var(--border); border-radius: var(--radius-md);">
            <div style="font-size: 0.78rem; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.4rem;">
                <i class="fa-solid fa-bolt text-warning"></i> Akun Cepat Pengujian (4 Role):
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="setLogin('admin@kalurahan.go.id', 'admin123')" style="font-size: 0.75rem; justify-content: flex-start;">
                    <i class="fa-solid fa-user-shield text-primary"></i> Admin
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="setLogin('staf@kalurahan.go.id', 'staf123')" style="font-size: 0.75rem; justify-content: flex-start;">
                    <i class="fa-solid fa-user-check text-info"></i> Staf
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="setLogin('lurah@kalurahan.go.id', 'lurah123')" style="font-size: 0.75rem; justify-content: flex-start;">
                    <i class="fa-solid fa-signature text-purple"></i> Lurah
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="setLogin('pemohon@gmail.com', 'pemohon123')" style="font-size: 0.75rem; justify-content: flex-start;">
                    <i class="fa-solid fa-user text-success"></i> Pemohon
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function setLogin(email, password) {
        document.getElementById('input-email').value = email;
        document.getElementById('input-password').value = password;
        document.getElementById('input-email').focus();
    }
</script>
<?= $this->endSection() ?>
