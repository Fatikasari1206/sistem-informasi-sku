<?php

namespace App\Controllers\Pemohon;

use App\Controllers\BaseController;
use App\Models\DokumenPersyaratanModel;
use App\Models\PermohonanSkuModel;
use App\Models\RiwayatStatusModel;

class Sku extends BaseController
{
    private const DOKUMEN_BARU = [
        'pengantar_rt_rw' => [
            'input'    => 'dokumen_pengantar_rt_rw',
            'label'    => 'Surat Pengantar RT/RW/Dukuh',
            'mimes'    => 'application/pdf,image/jpeg,image/png',
            'exts'     => 'pdf,jpg,jpeg,png',
            'required' => true,
        ],
        'ktp' => [
            'input'    => 'dokumen_ktp',
            'label'    => 'KTP Pemohon',
            'mimes'    => 'application/pdf,image/jpeg,image/png',
            'exts'     => 'pdf,jpg,jpeg,png',
            'required' => true,
        ],
        'kk' => [
            'input'    => 'dokumen_kk',
            'label'    => 'Kartu Keluarga (KK)',
            'mimes'    => 'application/pdf,image/jpeg,image/png',
            'exts'     => 'pdf,jpg,jpeg,png',
            'required' => true,
        ],
        'foto_usaha' => [
            'input'    => 'dokumen_foto_usaha',
            'label'    => 'Foto Tempat Usaha',
            'mimes'    => 'image/jpeg,image/png',
            'exts'     => 'jpg,jpeg,png',
            'required' => true,
        ],
    ];

    private const DOKUMEN_PERPANJANGAN = [
        'sku_lama' => [
            'input'    => 'dokumen_perpanjangan_sku_lama',
            'label'    => 'Lembar SKU Asli (Lama)',
            'mimes'    => 'application/pdf,image/jpeg,image/png',
            'exts'     => 'pdf,jpg,jpeg,png',
            'required' => true,
        ],
        'pengantar_rt_rw' => [
            'input'    => 'dokumen_perpanjangan_pengantar',
            'label'    => 'Surat Pengantar RT/RW/Dukuh Terbaru',
            'mimes'    => 'application/pdf,image/jpeg,image/png',
            'exts'     => 'pdf,jpg,jpeg,png',
            'required' => true,
        ],
        'ktp' => [
            'input'    => 'dokumen_perpanjangan_identitas',
            'label'    => 'Identitas Diri (KTP & KK)',
            'mimes'    => 'application/pdf,image/jpeg,image/png',
            'exts'     => 'pdf,jpg,jpeg,png',
            'required' => true,
        ],
        'foto_usaha' => [
            'input'    => 'dokumen_perpanjangan_foto_usaha',
            'label'    => 'Foto Tempat Usaha Terkini',
            'mimes'    => 'image/jpeg,image/png',
            'exts'     => 'jpg,jpeg,png',
            'required' => false,
        ],
    ];

    public function baru()
    {
        return view('pemohon/sku_baru', [
            'title'         => 'Permohonan SKU Baru - Sistem SKU',
            'padukuhanList' => self::PADUKUHAN_LIST,
            'user'          => session()->get(),
        ]);
    }

    public function perpanjangan()
    {
        $userId   = (int) session()->get('userId');
        $skuModel = new PermohonanSkuModel();

        $skuLamaList = $skuModel->where('user_id', $userId)
            ->whereIn('status', ['disetujui', 'selesai'])
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('pemohon/sku_perpanjangan', [
            'title'       => 'Perpanjangan SKU - Sistem SKU',
            'skuLamaList' => $skuLamaList,
            'user'        => session()->get(),
        ]);
    }

    public function detail(int $id)
    {
        $userId       = (int) session()->get('userId');
        $skuModel     = new PermohonanSkuModel();
        $dokumenModel = new DokumenPersyaratanModel();
        $riwayatModel = new RiwayatStatusModel();

        $permohonan = $skuModel->select('permohonan_sku.*, COALESCE(permohonan_sku.padukuhan_usaha, master_penduduk.padukuhan) AS padukuhan')
            ->join('master_penduduk', 'master_penduduk.nik = permohonan_sku.nik_pemohon', 'left')
            ->where('permohonan_sku.id', $id)
            ->where('permohonan_sku.user_id', $userId)
            ->first();

        if (!$permohonan) {
            return redirect()->to('/pemohon/dashboard')->with('error', 'Data permohonan SKU tidak ditemukan.');
        }

        return view('pemohon/sku_detail', [
            'title'       => 'Info Status ' . $permohonan['kode_pengajuan'] . ' - Sistem SKU',
            'permohonan'  => $permohonan,
            'dokumenList' => $dokumenModel->getByPermohonanId($id),
            'timeline'    => $riwayatModel->getTimeline($id),
            'user'        => session()->get(),
        ]);
    }

    public function cetak(int $id)
    {
        $userId     = (int) session()->get('userId');
        $skuModel   = new PermohonanSkuModel();
        $permohonan = $skuModel->select([
                'permohonan_sku.*',
                'COALESCE(permohonan_sku.padukuhan_usaha, master_penduduk.padukuhan) AS padukuhan',
                'master_penduduk.alamat AS domisili_alamat',
                'master_penduduk.rt AS domisili_rt',
                'master_penduduk.rw AS domisili_rw',
                'master_penduduk.padukuhan AS domisili_padukuhan',
            ])
            ->join('master_penduduk', 'master_penduduk.nik = permohonan_sku.nik_pemohon', 'left')
            ->where('permohonan_sku.id', $id)
            ->where('permohonan_sku.user_id', $userId)
            ->first();

        if (!$permohonan) {
            return redirect()->to('/pemohon/dashboard')->with('error', 'Data permohonan SKU tidak ditemukan.');
        }

        if (!in_array($permohonan['status'] ?? '', ['disetujui', 'selesai'], true)) {
            return redirect()->to('/pemohon/sku/detail/' . $id)
                ->with('error', 'Surat Keterangan Usaha belum disahkan sehingga belum dapat dicetak.');
        }

        $pengaturan = (new \App\Models\PengaturanModel())->getPengaturan();

        return view('pemohon/sku_cetak', [
            'title'      => 'Cetak SKU ' . ($permohonan['nomor_surat'] ?: $permohonan['kode_pengajuan']),
            'permohonan' => $permohonan,
            'pengaturan' => $pengaturan,
        ]);
    }

    public function dokumen(int $dokId)
    {
        $userId       = (int) session()->get('userId');
        $dokumenModel = new DokumenPersyaratanModel();

        $dok = $dokumenModel->select('dokumen_persyaratan.*')
            ->join('permohonan_sku', 'permohonan_sku.id = dokumen_persyaratan.permohonan_id')
            ->where('dokumen_persyaratan.id', $dokId)
            ->where('permohonan_sku.user_id', $userId)
            ->first();

        if (!$dok) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        $baseDir = realpath(WRITEPATH . 'uploads');
        $absPath = realpath(WRITEPATH . $dok['file_path']);
        if (!$baseDir || !$absPath || strpos($absPath, $baseDir) !== 0 || !is_file($absPath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File dokumen tidak tersedia.');
        }

        $mime = $dok['tipe_file'] ?: 'application/octet-stream';

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($dok['nama_file']) . '"')
            ->setBody((string) file_get_contents($absPath));
    }

    public function perbaiki(int $id)
    {
        $userId     = (int) session()->get('userId');
        $skuModel   = new PermohonanSkuModel();
        $permohonan = $skuModel->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$permohonan) {
            return redirect()->to('/pemohon/dashboard')
                ->with('error', 'Data permohonan SKU tidak ditemukan.');
        }

        if ($permohonan['status'] !== 'revisi') {
            return redirect()->to('/pemohon/sku/detail/' . $id)
                ->with('error', 'Permohonan ini tidak sedang dalam status revisi.');
        }

        $dokumenList    = (new DokumenPersyaratanModel())->getByPermohonanId($id);
        $dokumenByJenis = [];
        foreach ($dokumenList as $dok) {
            $dokumenByJenis[$dok['jenis_dokumen']] = $dok;
        }

        $timeline     = (new RiwayatStatusModel())->getTimeline($id);
        $dokumenSpecs = $this->getDokumenSpecsForType((string) $permohonan['tipe_pengajuan'], false);

        return view('pemohon/sku_revisi', [
            'title'          => 'Perbaiki Pengajuan SKU - Sistem SKU Condongcatur',
            'activeMenu'     => 'dashboard',
            'permohonan'     => $permohonan,
            'dokumenByJenis' => $dokumenByJenis,
            'dokumenSpecs'   => $dokumenSpecs,
            'timeline'       => $timeline,
            'padukuhanList'  => self::PADUKUHAN_LIST,
        ]);
    }

    public function updateRevisi(int $id)
    {
        $userId     = (int) session()->get('userId');
        $skuModel   = new PermohonanSkuModel();
        $permohonan = $skuModel->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$permohonan || $permohonan['status'] !== 'revisi') {
            return redirect()->to('/pemohon/dashboard')
                ->with('error', 'Permohonan revisi tidak valid atau sudah diproses.');
        }

        $tipe         = (string) $permohonan['tipe_pengajuan'];
        $dokumenSpecs = $this->getDokumenSpecsForType($tipe, false);

        if ($tipe === 'perpanjangan') {
            $input = [
                'alamat_usaha'    => trim((string) $this->request->getPost('alamat_usaha')),
                'omset_per_bulan' => preg_replace('/\D/', '', (string) $this->request->getPost('omset_per_bulan')),
                'deskripsi_usaha' => trim((string) $this->request->getPost('deskripsi_usaha')),
            ];
            $rules = [
                'alamat_usaha'    => 'required|max_length[500]',
                'omset_per_bulan' => 'required|integer|greater_than_equal_to[0]',
                'deskripsi_usaha' => 'permit_empty|max_length[500]',
            ];
            $messages = [
                'alamat_usaha'    => ['required' => 'Alamat usaha wajib diisi.'],
                'omset_per_bulan' => ['required' => 'Perkiraan omset wajib diisi.'],
            ];
            $updateFields = [
                'alamat_usaha'    => $input['alamat_usaha'],
                'omset_per_bulan' => (int) $input['omset_per_bulan'],
                'deskripsi_usaha' => $input['deskripsi_usaha'],
            ];
        } else {
            [$input, $rules, $messages] = $this->extractBaruValidation();
            $updateFields = [
                'nama_usaha'          => $input['nama_usaha'],
                'bidang_usaha'        => $input['bidang_usaha'],
                'alamat_usaha'        => $input['alamat_usaha'],
                'padukuhan_usaha'     => $input['padukuhan_usaha'],
                'lama_usaha_tahun'    => (int) $input['lama_usaha_tahun'],
                'omset_per_bulan'     => (int) $input['omset_per_bulan'],
                'status_tempat_usaha' => $input['status_tempat_usaha'],
            ];
        }

        $this->appendDocumentValidation($rules, $messages, $dokumenSpecs);

        if (!$this->validateWithInput($input, $rules, $messages)) {
            return redirect()->to("/pemohon/sku/perbaiki/{$id}")->withInput();
        }

        $dokumenModel = new DokumenPersyaratanModel();
        $riwayatModel = new RiwayatStatusModel();
        $uploadRelDir = 'uploads/sku/' . $permohonan['kode_pengajuan'];
        $uploadAbsDir = WRITEPATH . $uploadRelDir;

        if (!is_dir($uploadAbsDir) && !mkdir($uploadAbsDir, 0755, true) && !is_dir($uploadAbsDir)) {
            return redirect()->to("/pemohon/sku/perbaiki/{$id}")
                ->withInput()
                ->with('error', 'Gagal menyiapkan folder penyimpanan dokumen.');
        }

        $movedPaths   = [];
        $oldFilePaths = [];
        $db           = \Config\Database::connect();
        $db->transBegin();

        try {
            $skuModel->update($id, array_merge($updateFields, [
                'status'         => 'diajukan',
                'catatan_revisi' => null,
            ]));

            $now = date('Y-m-d H:i:s');

            foreach ($dokumenSpecs as $jenis => $spec) {
                $file = $this->request->getFile($spec['input']);
                if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
                    continue;
                }

                $fileMeta = $this->moveUploadedDocument($file, $spec['label'], $uploadRelDir, $uploadAbsDir, $movedPaths);
                $existing = $dokumenModel->where('permohonan_id', $id)
                    ->where('jenis_dokumen', $jenis)
                    ->first();

                $payload = array_merge($fileMeta, [
                    'status_verifikasi' => 'pending',
                    'catatan'           => null,
                    'created_at'        => $now,
                ]);

                if ($existing) {
                    $dokumenModel->update((int) $existing['id'], $payload);
                    if (!empty($existing['file_path'])) {
                        $oldFilePaths[] = WRITEPATH . ltrim((string) $existing['file_path'], '/');
                    }
                } else {
                    $dokumenModel->insert(array_merge($payload, [
                        'permohonan_id' => $id,
                        'jenis_dokumen' => $jenis,
                    ]));
                }
            }

            $riwayatModel->catat(
                $id,
                'revisi',
                'diajukan',
                $userId,
                'Pemohon mengirim perbaikan data dan berkas pengajuan.'
            );

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi database gagal.');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            foreach ($movedPaths as $path) {
                if (is_file($path)) {
                    @unlink($path);
                }
            }

            return redirect()->to("/pemohon/sku/perbaiki/{$id}")
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan perbaikan: ' . $e->getMessage());
        }

        foreach ($oldFilePaths as $oldPath) {
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return redirect()->to('/pemohon/dashboard')
            ->with('success', "Perbaikan {$permohonan['kode_pengajuan']} berhasil dikirim dan menunggu verifikasi staf.");
    }

    public function storeBaru()
    {
        [$input, $rules, $messages] = $this->extractBaruValidation();

        $this->appendDocumentValidation($rules, $messages, self::DOKUMEN_BARU);

        if (!$this->validateWithInput($input, $rules, $messages)) {
            return redirect()->to('/pemohon/sku/baru')->withInput();
        }

        $permohonanData = [
            'tipe_pengajuan'      => 'baru',
            'nama_usaha'          => $input['nama_usaha'],
            'bidang_usaha'        => $input['bidang_usaha'],
            'alamat_usaha'        => $input['alamat_usaha'],
            'padukuhan_usaha'     => $input['padukuhan_usaha'],
            'rt_usaha'            => session()->get('rt'),
            'rw_usaha'            => session()->get('rw'),
            'lama_usaha_tahun'    => (int) $input['lama_usaha_tahun'],
            'omset_per_bulan'     => (int) $input['omset_per_bulan'],
            'status_tempat_usaha' => $input['status_tempat_usaha'],
        ];

        return $this->persistPermohonan($permohonanData, self::DOKUMEN_BARU, 'baru', 'Pengajuan SKU baru dikirim oleh pemohon.');
    }

    public function storePerpanjangan()
    {
        $userId      = (int) session()->get('userId');
        $adaPembaruan = (bool) $this->request->getPost('ada_pembaruan');

        $input = [
            'permohonan_sebelumnya_id' => trim((string) $this->request->getPost('permohonan_sebelumnya_id')),
            'omset_per_bulan'          => preg_replace('/\D/', '', (string) $this->request->getPost('omset_per_bulan')),
            'alamat_usaha'             => trim((string) $this->request->getPost('alamat_usaha')),
            'deskripsi_usaha'          => trim((string) $this->request->getPost('deskripsi_usaha')),
        ];

        $rules = [
            'permohonan_sebelumnya_id' => 'required|integer',
            'omset_per_bulan'          => $adaPembaruan ? 'required|integer|greater_than_equal_to[0]' : 'permit_empty|integer',
            'alamat_usaha'             => $adaPembaruan ? 'required|max_length[500]' : 'permit_empty|max_length[500]',
            'deskripsi_usaha'          => 'permit_empty|max_length[1000]',
        ];

        $messages = [
            'permohonan_sebelumnya_id' => ['required' => 'SKU lama wajib dipilih.'],
            'omset_per_bulan'          => ['required' => 'Omset baru wajib diisi saat memperbarui data.'],
            'alamat_usaha'             => ['required' => 'Alamat usaha wajib diisi saat memperbarui data.'],
        ];

        $this->appendDocumentValidation($rules, $messages, self::DOKUMEN_PERPANJANGAN);

        if (!$this->validateWithInput($input, $rules, $messages)) {
            return redirect()->to('/pemohon/sku/perpanjangan')->withInput();
        }

        $skuModel = new PermohonanSkuModel();
        $skuLama  = $skuModel->where('id', (int) $input['permohonan_sebelumnya_id'])
            ->where('user_id', $userId)
            ->whereIn('status', ['disetujui', 'selesai'])
            ->first();

        if (!$skuLama) {
            return redirect()->to('/pemohon/sku/perpanjangan')
                ->withInput()
                ->with('error', 'SKU lama yang dipilih tidak valid atau belum diterbitkan.');
        }

        $permohonanData = [
            'tipe_pengajuan'           => 'perpanjangan',
            'permohonan_sebelumnya_id' => (int) $skuLama['id'],
            'nama_usaha'               => $skuLama['nama_usaha'],
            'bidang_usaha'             => $skuLama['bidang_usaha'],
            'alamat_usaha'             => ($adaPembaruan && $input['alamat_usaha'] !== '') ? $input['alamat_usaha'] : $skuLama['alamat_usaha'],
            'padukuhan_usaha'          => $skuLama['padukuhan_usaha'],
            'rt_usaha'                 => $skuLama['rt_usaha'],
            'rw_usaha'                 => $skuLama['rw_usaha'],
            'lama_usaha_tahun'         => (int) $skuLama['lama_usaha_tahun'] + 1,
            'modal_usaha'              => $skuLama['modal_usaha'],
            'omset_per_bulan'          => ($adaPembaruan && $input['omset_per_bulan'] !== '') ? (int) $input['omset_per_bulan'] : $skuLama['omset_per_bulan'],
            'jumlah_karyawan'          => $skuLama['jumlah_karyawan'],
            'status_tempat_usaha'      => $skuLama['status_tempat_usaha'],
            'deskripsi_usaha'          => $input['deskripsi_usaha'] !== '' ? $input['deskripsi_usaha'] : $skuLama['deskripsi_usaha'],
        ];

        return $this->persistPermohonan(
            $permohonanData,
            self::DOKUMEN_PERPANJANGAN,
            'perpanjangan',
            'Pengajuan perpanjangan SKU dikirim oleh pemohon.'
        );
    }

    private function appendDocumentValidation(array &$rules, array &$messages, array $dokumenSpecs): void
    {
        foreach ($dokumenSpecs as $spec) {
            $field = $spec['input'];
            $label = $spec['label'];

            $prefix = $spec['required'] ? "uploaded[{$field}]|" : '';
            $rules[$field] = "{$prefix}max_size[{$field},2048]|mime_in[{$field},{$spec['mimes']}]|ext_in[{$field},{$spec['exts']}]";
            $messages[$field] = [
                'uploaded' => "{$label} wajib diunggah.",
                'max_size' => "{$label} maksimal 2 MB.",
                'mime_in'  => "Format {$label} tidak didukung.",
                'ext_in'   => "Ekstensi {$label} tidak diizinkan.",
            ];
        }
    }

    private function validateWithInput(array $input, array $rules, array $messages): bool
    {
        $validation = \Config\Services::validation();
        $validation->setRules($rules, $messages);

        if (!$validation->run($input)) {
            session()->setFlashdata('errors', $validation->getErrors());
            return false;
        }

        return true;
    }

    private function persistPermohonan(array $permohonanData, array $dokumenSpecs, string $formPage, string $riwayatNote)
    {
        $skuModel     = new PermohonanSkuModel();
        $dokumenModel = new DokumenPersyaratanModel();
        $riwayatModel = new RiwayatStatusModel();

        $userId        = (int) session()->get('userId');
        $kodePengajuan = $skuModel->generateKodePengajuan();
        $uploadRelDir  = 'uploads/sku/' . $kodePengajuan;
        $uploadAbsDir  = WRITEPATH . $uploadRelDir;

        if (!is_dir($uploadAbsDir) && !mkdir($uploadAbsDir, 0755, true) && !is_dir($uploadAbsDir)) {
            return redirect()->to("/pemohon/sku/{$formPage}")
                ->withInput()
                ->with('error', 'Gagal menyiapkan folder penyimpanan dokumen.');
        }

        $movedPaths = [];
        $db         = \Config\Database::connect();
        $db->transBegin();

        try {
            $permohonanId = $skuModel->insert(array_merge([
                'kode_pengajuan' => $kodePengajuan,
                'user_id'        => $userId,
                'nama_pemohon'   => (string) session()->get('namaLengkap'),
                'nik_pemohon'    => (string) session()->get('nik'),
                'alamat_pemohon' => (string) (session()->get('alamat') ?: 'Kalurahan Condongcatur'),
                'no_hp'          => (string) (session()->get('noHp') ?: '-'),
                'status'         => 'diajukan',
            ], $permohonanData), true);

            if (!$permohonanId) {
                throw new \RuntimeException('Gagal menyimpan permohonan SKU.');
            }

            $now = date('Y-m-d H:i:s');

            foreach ($dokumenSpecs as $jenis => $spec) {
                $file = $this->request->getFile($spec['input']);
                if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
                    if ($spec['required']) {
                        throw new \RuntimeException('Berkas ' . $spec['label'] . ' wajib diunggah.');
                    }
                    continue;
                }

                $fileMeta = $this->moveUploadedDocument($file, $spec['label'], $uploadRelDir, $uploadAbsDir, $movedPaths);

                $dokumenModel->insert(array_merge($fileMeta, [
                    'permohonan_id'     => $permohonanId,
                    'jenis_dokumen'     => $jenis,
                    'status_verifikasi' => 'pending',
                    'created_at'        => $now,
                ]));
            }

            $riwayatModel->catat($permohonanId, null, 'diajukan', $userId, $riwayatNote);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi database gagal.');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            foreach ($movedPaths as $path) {
                if (is_file($path)) {
                    @unlink($path);
                }
            }

            return redirect()->to("/pemohon/sku/{$formPage}")
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan pengajuan: ' . $e->getMessage());
        }

        return redirect()->to('/pemohon/dashboard')
            ->with('success', "Permohonan {$kodePengajuan} berhasil dikirim dan menunggu verifikasi staf.");
    }

    private function extractBaruValidation(): array
    {
        $input = [
            'nama_usaha'          => trim((string) $this->request->getPost('nama_usaha')),
            'bidang_usaha'        => trim((string) $this->request->getPost('bidang_usaha')),
            'alamat_usaha'        => trim((string) $this->request->getPost('alamat_usaha')),
            'padukuhan_usaha'     => trim((string) $this->request->getPost('padukuhan_usaha')),
            'lama_usaha_tahun'    => trim((string) $this->request->getPost('lama_usaha_tahun')),
            'omset_per_bulan'     => preg_replace('/\D/', '', (string) $this->request->getPost('omset_per_bulan')),
            'status_tempat_usaha' => trim((string) $this->request->getPost('status_tempat_usaha')),
        ];

        $padukuhanCsv = implode(',', self::PADUKUHAN_LIST);

        $rules = [
            'nama_usaha'          => 'required|min_length[3]|max_length[150]',
            'bidang_usaha'        => 'required|min_length[3]|max_length[100]',
            'alamat_usaha'        => 'required|max_length[500]',
            'padukuhan_usaha'     => "required|in_list[{$padukuhanCsv}]",
            'lama_usaha_tahun'    => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[99]',
            'omset_per_bulan'     => 'required|integer|greater_than_equal_to[0]',
            'status_tempat_usaha' => 'required|in_list[milik_sendiri,sewa,menumpang,lainnya]',
        ];

        $messages = [
            'nama_usaha'          => ['required' => 'Nama usaha wajib diisi.'],
            'bidang_usaha'        => ['required' => 'Bidang usaha wajib diisi.'],
            'alamat_usaha'        => ['required' => 'Alamat usaha wajib diisi.'],
            'padukuhan_usaha'     => ['required' => 'Wilayah padukuhan wajib dipilih.'],
            'lama_usaha_tahun'    => ['required' => 'Lama usaha wajib diisi.'],
            'omset_per_bulan'     => ['required' => 'Perkiraan omset wajib diisi.'],
            'status_tempat_usaha' => ['required' => 'Status tempat usaha wajib dipilih.'],
        ];

        return [$input, $rules, $messages];
    }

    private function getDokumenSpecsForType(string $tipe, ?bool $requiredOverride = null): array
    {
        $specs = $tipe === 'perpanjangan' ? self::DOKUMEN_PERPANJANGAN : self::DOKUMEN_BARU;

        if ($requiredOverride === null) {
            return $specs;
        }

        return array_map(static function (array $spec) use ($requiredOverride): array {
            $spec['required'] = $requiredOverride;
            return $spec;
        }, $specs);
    }

    private function moveUploadedDocument(
        \CodeIgniter\HTTP\Files\UploadedFile $file,
        string $label,
        string $uploadRelDir,
        string $uploadAbsDir,
        array &$movedPaths
    ): array {
        if (!$file->isValid() || $file->hasMoved()) {
            throw new \RuntimeException('Berkas ' . $label . ' tidak valid.');
        }

        $newName  = $file->getRandomName();
        $fileSize = (int) $file->getSize();
        $mimeType = (string) $file->getMimeType();
        $origName = (string) $file->getClientName();

        $file->move($uploadAbsDir, $newName);
        $movedPaths[] = $uploadAbsDir . DIRECTORY_SEPARATOR . $newName;

        return [
            'nama_file'   => $origName,
            'file_path'   => $uploadRelDir . '/' . $newName,
            'ukuran_file' => $fileSize,
            'tipe_file'   => $mimeType,
        ];
    }
}
