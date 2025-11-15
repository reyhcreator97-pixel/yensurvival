<?php

namespace App\Controllers;

use App\Models\InvestasiModel;
use App\Models\KekayaanItemModel;
use App\Models\TransaksiModel;

class Investasi extends BaseController
{
    protected $investasi;
    protected $kekayaan;
    protected $trx;

    public function __construct()
    {
        $this->investasi = new InvestasiModel();
        $this->kekayaan  = new KekayaanItemModel();
        $this->trx       = new TransaksiModel();
    }

    private function uid()
    {
        return (int) user_id();
    }

    // ======================
    // 📊 HALAMAN UTAMA
    // ======================
    public function index()
    {
        $uid = $this->uid();

        // 1️⃣ Ambil semua data investasi yang disimpan manual
        $list = $this->investasi
            ->where('user_id', $uid)
            ->orderBy('id', 'DESC')
            ->findAll();

        // 2️⃣ Ambil juga data dari kekayaan_awal kategori investasi
        $dariKekayaan = $this->kekayaan
            ->where(['user_id' => $uid, 'kategori' => 'investasi'])
            ->findAll();

        // 3️⃣ Satukan hasil tanpa ubah struktur variabel
        foreach ($dariKekayaan as $r) {
            $list[] = [
                'id'              => $r['id'],
                'tanggal'         => $r['created_at'] ?? '',
                'nama'            => $r['deskripsi'],
                'akun_id'         => null,
                'jumlah'          => $r['jumlah'],
                'nilai_sekarang'  => $r['saldo_terkini'] ?? $r['jumlah'],
                'deskripsi'       => $r['deskripsi'],
                'status'          => (strpos(strtolower($r['deskripsi']), '(terjual)') !== false) ? 'selesai' : 'aktif',
            ];
        }

        // 4️⃣ Akun untuk dropdown
        $akun = $this->kekayaan
            ->where(['user_id' => $uid, 'kategori' => 'uang'])
            ->findAll();

        // 5️⃣ Hitung total nilai sekarang (❌ skip yang sudah terjual / selesai)
        $totalInvestasi = 0;
        foreach ($list as $r) {
            if (!isset($r['status']) || $r['status'] !== 'selesai') {
                $totalInvestasi += (float)($r['nilai_sekarang'] ?? 0);
            }
        }

        $data = [
            'title'          => 'Investasi',
            'list'           => $list,
            'akun'           => $akun,
            'totalInvestasi' => $totalInvestasi,
        ];

        return view('investasi/index', $data);
    }

    // ======================
    // ➕ TAMBAH INVESTASI
    // ======================
    public function store()
    {
        $uid     = $this->uid();
        $nama    = trim($this->request->getPost('nama'));
        $akun_id = $this->request->getPost('akun_id');
        $jumlah  = (float) $this->request->getPost('jumlah');
        $desc    = $this->request->getPost('deskripsi') ?? '';
        $tanggal = date('Y-m-d');

        if ($nama === '' || !$akun_id || $jumlah <= 0) {
            return redirect()->back()->with('error', 'Data belum lengkap.');
        }

        $this->investasi->insert([
            'user_id'        => $uid,
            'tanggal'        => $tanggal,
            'nama'           => $nama,
            'akun_id'        => $akun_id,
            'jumlah'         => $jumlah,
            'nilai_sekarang' => $jumlah,
            'deskripsi'      => $desc,
            'status'         => 'aktif',
        ]);

        // Catat ke transaksi (uang keluar)
        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => $tanggal,
            'jenis'     => 'out',
            'sumber_id' => $akun_id,
            'kategori'  => 'Investasi',
            'deskripsi' => 'Beli investasi: ' . $nama,
            'jumlah'    => $jumlah,
        ]);

        return redirect()->to('/investasi')->with('message', 'Investasi berhasil ditambahkan.');
    }

    // ======================
    // 🔁 UPDATE NILAI SEKARANG
    // ======================
    public function updateNilai()
    {
        $uid   = $this->uid();
        $id    = $this->request->getPost('id');
        $nilai = (float) $this->request->getPost('nilai_sekarang');

        // Coba cari dulu di tabel investasi
        $row = $this->investasi->where('id', $id)->first();

        if ($row) {
            // ✅ Update dari tabel investasi
            $this->investasi->update($id, ['nilai_sekarang' => $nilai]);
            return redirect()->to('/investasi')->with('message', 'Nilai investasi diperbarui.');
        }

        // Kalau gak ada di tabel investasi, cek di tabel kekayaan_awal
        $cekAwal = $this->kekayaan
            ->where('id', $id)
            ->where('kategori', 'investasi')
            ->first();

        if ($cekAwal) {
            // ✅ Update dari data kekayaan_awal kategori investasi
            $this->kekayaan->update($id, ['saldo_terkini' => $nilai]);
            return redirect()->to('/investasi')->with('message', 'Nilai investasi awal diperbarui.');
        }

        // Kalau gak ditemukan di dua-duanya
        return redirect()->back()->with('error', 'Investasi tidak ditemukan.');
    }


    // =======================
    // 💰 JUAL INVESTASI
    // =======================
    public function jual()
    {
        $uid      = $this->uid();
        $id       = (int)$this->request->getPost('id');
        $nilai    = (float)$this->request->getPost('nilai_sekarang');
        $akun_id  = $this->request->getPost('akun_id');
        $desc     = $this->request->getPost('deskripsi') ?? '';
        $tanggal  = date('Y-m-d');

        // Cari di tabel investasi dulu
        $row = $this->investasi->where(['user_id' => $uid, 'id' => $id])->first();

        // Kalau gak ketemu, cari di kekayaan awal (kategori investasi)
        if (!$row) {
            $row = $this->kekayaan
                ->where('user_id', $uid)
                ->where('kategori', 'investasi')
                ->groupStart()
                ->where('id', $id)
                ->orWhere('deskripsi', $desc)
                ->groupEnd()
                ->first();
        }

        // Kalau tetap gak ketemu
        if (!$row) {
            return redirect()->back()->with('error', 'Investasi tidak ditemukan.');
        }

        // Update status dan nilai
        if (isset($row['status'])) {
            // Dari tabel investasi
            $this->investasi->update($row['id'], [
                'status'         => 'selesai',
                'nilai_sekarang' => $nilai,
            ]);
        } else {
            // Dari kekayaan awal
            $this->kekayaan
                ->where('id', $row['id'])
                ->set([
                    'saldo_terkini' => $nilai,
                    'deskripsi'     => $row['deskripsi'] . ' (Terjual)',
                    'updated_at'    => date('Y-m-d H:i:s'),
                ])
                ->update();
        }

        // Catat transaksi hanya jika akun_id valid
        if (!empty($akun_id)) {
            $this->trx->insert([
                'user_id'   => $uid,
                'tanggal'   => $tanggal,
                'jenis'     => 'in',
                'sumber_id' => $akun_id,
                'kategori'  => 'investasi',
                'deskripsi' => 'Penjualan investasi: ' . ($row['nama'] ?? $row['deskripsi']),
                'jumlah'    => $nilai,
            ]);
        }

        return redirect()->to('/investasi')->with('message', 'Investasi berhasil dijual.');
    }


    // =======================
    // 🗑️ HAPUS INVESTASI
    // =======================
    public function delete($id)
    {
        $uid = $this->uid();

        // 1️⃣ Coba cari di tabel investasi dulu
        $row = $this->investasi
            ->where(['user_id' => $uid, 'id' => $id])
            ->first();

        // 2️⃣ Kalau tidak ketemu, coba cari di kekayaan awal (kategori investasi)
        if (!$row) {
            $row = $this->kekayaan
                ->where('user_id', $uid)
                ->where('kategori', 'investasi')
                ->groupStart()
                ->where('id', $id)
                ->orWhere('deskripsi LIKE', '%(Terjual)%')
                ->groupEnd()
                ->first();
        }

        // 3️⃣ Kalau tetap tidak ditemukan
        if (!$row) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // 4️⃣ Hapus berdasarkan sumber data
        if (isset($row['status'])) {
            // Dari tabel investasi
            $this->investasi->delete($row['id']);
        } else {
            // Dari kekayaan awal
            $this->kekayaan->delete($row['id']);
        }

        return redirect()->to('/investasi')->with('message', 'Data investasi berhasil dihapus.');
    }


    public function getTotalInvestasi()
    {
        $uid = $this->uid();

        // Ambil dari tabel investasi
        $total1 = $this->investasi
            ->where('user_id', $uid)
            ->selectSum('nilai_sekarang')
            ->first()['nilai_sekarang'] ?? 0;

        // Ambil juga dari kekayaan_awal kategori investasi
        $total2 = $this->kekayaan
            ->where(['user_id' => $uid, 'kategori' => 'investasi'])
            ->selectSum('saldo_terkini')
            ->first()['saldo_terkini'] ?? 0;

        // Gabung dua total
        $total = (float)$total1 + (float)$total2;

        // Return dalam format JSON biar bisa dipanggil dari Dashboard
        return $this->response->setJSON([
            'status' => 'success',
            'total_investasi' => $total
        ]);
    }
}
