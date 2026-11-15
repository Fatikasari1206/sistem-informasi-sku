<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MasterPendudukModel;

class MasterNik extends BaseController
{
    protected MasterPendudukModel $pendudukModel;

    public function __construct()
    {
        $this->pendudukModel = new MasterPendudukModel();
    }

    public function index()
    {
        $selectedPadukuhan = trim((string) $this->request->getGet('padukuhan'));
        $keyword            = trim((string) $this->request->getGet('q'));

        $builder = $this->pendudukModel
            ->select('master_penduduk.*, users.id AS user_id, users.status_aktif AS user_status')
            ->join('users', 'users.nik = master_penduduk.nik', 'left');

        if ($selectedPadukuhan !== '') {
            $builder->where('master_penduduk.padukuhan', $selectedPadukuhan);
        }

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('master_penduduk.nik', $keyword)
                ->orLike('master_penduduk.nama_lengkap', $keyword)
                ->orLike('master_penduduk.no_kk', $keyword)
                ->orLike('master_penduduk.alamat', $keyword)
            ->groupEnd();
        }

        $pendudukList = $builder
            ->orderBy('master_penduduk.created_at', 'DESC')
            ->findAll();

        return view('admin/master_nik', [
            'title'             => 'Master Data NIK - Sistem SKU',
            'logoutLabel'       => 'Keluar Admin',
            'pendudukList'      => $pendudukList,
            'padukuhanList'     => self::PADUKUHAN_LIST,
            'selectedPadukuhan' => $selectedPadukuhan,
            'keyword'           => $keyword,
        ]);
    }

    public function store()
    {
        $nik         = trim((string) $this->request->getPost('nik'));
        $namaLengkap = trim((string) $this->request->getPost('nama_lengkap'));
        $noKk        = trim((string) $this->request->getPost('no_kk'));
        $padukuhan   = trim((string) $this->request->getPost('padukuhan'));
        $rt          = trim((string) $this->request->getPost('rt'));
        $rw          = trim((string) $this->request->getPost('rw'));
        $alamat      = trim((string) $this->request->getPost('alamat'));

        $padukuhanRules = implode(',', self::PADUKUHAN_LIST);

        $rules = [
            'nik'          => 'required|numeric|exact_length[16]|is_unique[master_penduduk.nik]',
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'no_kk'        => 'permit_empty|numeric|exact_length[16]',
            'padukuhan'    => "required|in_list[{$padukuhanRules}]",
            'rt'           => 'required|numeric|max_length[5]',
            'rw'           => 'required|numeric|max_length[5]',
            'alamat'       => 'permit_empty|max_length[500]',
        ];

        $messages = [
            'nik' => [
                'required'     => 'NIK wajib diisi.',
                'numeric'      => 'NIK harus berupa angka.',
                'exact_length' => 'NIK harus tepat 16 digit.',
                'is_unique'    => 'NIK sudah terdaftar dalam master data.',
            ],
            'nama_lengkap' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'no_kk' => [
                'numeric'      => 'Nomor Kartu Keluarga harus berupa angka.',
                'exact_length' => 'Nomor Kartu Keluarga harus tepat 16 digit.',
            ],
            'padukuhan' => [
                'required' => 'Wilayah padukuhan wajib dipilih.',
                'in_list'  => 'Wilayah padukuhan tidak valid.',
            ],
            'rt' => [
                'required' => 'Nomor RT wajib diisi.',
                'numeric'  => 'Nomor RT harus berupa angka.',
            ],
            'rw' => [
                'required' => 'Nomor RW wajib diisi.',
                'numeric'  => 'Nomor RW harus berupa angka.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Format RT & RW dua digit jika angka biasa
        $rtFormatted = is_numeric($rt) ? sprintf('%02d', (int) $rt) : $rt;
        $rwFormatted = is_numeric($rw) ? sprintf('%02d', (int) $rw) : $rw;

        if ($alamat === '') {
            $alamat = sprintf('Padukuhan %s, Condongcatur, Depok, Sleman', $padukuhan);
        }

        $this->pendudukModel->insert([
            'nik'          => $nik,
            'nama_lengkap' => $namaLengkap,
            'no_kk'        => $noKk !== '' ? $noKk : null,
            'padukuhan'    => $padukuhan,
            'rt'           => $rtFormatted,
            'rw'           => $rwFormatted,
            'alamat'       => $alamat,
        ]);

        return redirect()->to('/admin/master-nik')
            ->with('success', 'Data NIK warga ' . $namaLengkap . ' (' . $nik . ') berhasil ditambahkan ke master kependudukan.');
    }

    public function update(int $id)
    {
        $penduduk = $this->pendudukModel->find($id);

        if (!$penduduk) {
            return redirect()->to('/admin/master-nik')->with('error', 'Data NIK tidak ditemukan.');
        }

        $nik         = trim((string) $this->request->getPost('nik'));
        $namaLengkap = trim((string) $this->request->getPost('nama_lengkap'));
        $noKk        = trim((string) $this->request->getPost('no_kk'));
        $padukuhan   = trim((string) $this->request->getPost('padukuhan'));
        $rt          = trim((string) $this->request->getPost('rt'));
        $rw          = trim((string) $this->request->getPost('rw'));
        $alamat      = trim((string) $this->request->getPost('alamat'));

        $padukuhanRules = implode(',', self::PADUKUHAN_LIST);

        $rules = [
            'nik'          => "required|numeric|exact_length[16]|is_unique[master_penduduk.nik,id,{$id}]",
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'no_kk'        => 'permit_empty|numeric|exact_length[16]',
            'padukuhan'    => "required|in_list[{$padukuhanRules}]",
            'rt'           => 'required|numeric|max_length[5]',
            'rw'           => 'required|numeric|max_length[5]',
            'alamat'       => 'permit_empty|max_length[500]',
        ];

        $messages = [
            'nik' => [
                'required'     => 'NIK wajib diisi.',
                'numeric'      => 'NIK harus berupa angka.',
                'exact_length' => 'NIK harus tepat 16 digit.',
                'is_unique'    => 'NIK sudah digunakan oleh data warga lain.',
            ],
            'nama_lengkap' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'no_kk' => [
                'numeric'      => 'Nomor Kartu Keluarga harus berupa angka.',
                'exact_length' => 'Nomor Kartu Keluarga harus tepat 16 digit.',
            ],
            'padukuhan' => [
                'required' => 'Wilayah padukuhan wajib dipilih.',
                'in_list'  => 'Wilayah padukuhan tidak valid.',
            ],
            'rt' => [
                'required' => 'Nomor RT wajib diisi.',
                'numeric'  => 'Nomor RT harus berupa angka.',
            ],
            'rw' => [
                'required' => 'Nomor RW wajib diisi.',
                'numeric'  => 'Nomor RW harus berupa angka.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $rtFormatted = is_numeric($rt) ? sprintf('%02d', (int) $rt) : $rt;
        $rwFormatted = is_numeric($rw) ? sprintf('%02d', (int) $rw) : $rw;

        if ($alamat === '') {
            $alamat = sprintf('Padukuhan %s, Condongcatur, Depok, Sleman', $padukuhan);
        }

        $this->pendudukModel->update($id, [
            'nik'          => $nik,
            'nama_lengkap' => $namaLengkap,
            'no_kk'        => $noKk !== '' ? $noKk : null,
            'padukuhan'    => $padukuhan,
            'rt'           => $rtFormatted,
            'rw'           => $rwFormatted,
            'alamat'       => $alamat,
        ]);

        $userModel  = new \App\Models\UserModel();
        $linkedUser = $userModel->where('nik', $penduduk['nik'])->first();
        if ($linkedUser) {
            $userModel->update((int) $linkedUser['id'], [
                'nik'          => $nik,
                'nama_lengkap' => $namaLengkap,
                'rt'           => $rtFormatted,
                'rw'           => $rwFormatted,
                'alamat'       => $alamat,
            ]);
        }

        return redirect()->to('/admin/master-nik')
            ->with('success', 'Data NIK warga ' . $namaLengkap . ' berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $penduduk = $this->pendudukModel->find($id);

        if (!$penduduk) {
            return redirect()->to('/admin/master-nik')->with('error', 'Data NIK tidak ditemukan.');
        }

        $linkedUser = (new \App\Models\UserModel())->where('nik', $penduduk['nik'])->first();
        if ($linkedUser) {
            return redirect()->to('/admin/master-nik')
                ->with('error', 'Data NIK ' . $penduduk['nama_lengkap'] . ' tidak dapat dihapus karena sudah terhubung dengan akun warga aktif.');
        }

        $linkedSku = (new \App\Models\PermohonanSkuModel())->where('nik_pemohon', $penduduk['nik'])->countAllResults();
        if ($linkedSku > 0) {
            return redirect()->to('/admin/master-nik')
                ->with('error', 'Data NIK ' . $penduduk['nama_lengkap'] . ' tidak dapat dihapus karena memiliki riwayat pengajuan SKU.');
        }

        $this->pendudukModel->delete($id);

        return redirect()->to('/admin/master-nik')
            ->with('success', 'Data NIK ' . $penduduk['nama_lengkap'] . ' berhasil dihapus dari master kependudukan.');
    }
}
