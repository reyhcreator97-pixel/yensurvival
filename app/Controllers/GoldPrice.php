<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class GoldPrice extends Controller
{
    private string $url = 'https://r.jina.ai/https://www.logammulia.com/id/harga-emas-hari-ini';
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {
        $data = $this->getHarga1Gram();
        return $this->response->setJSON($data);
    }

    public function getHarga1Gram(): array
    {
        $builder = $this->db->table('gold_price');
        $today = date('Y-m-d');

        // 🔹 Cek apakah data hari ini sudah ada
        $existing = $builder->where('DATE(updated_at)', $today)->orderBy('id', 'DESC')->get()->getRowArray();
        if ($existing) {
            return [
                'status'       => 'success (cached)',
                'berat'        => $existing['berat'],
                'harga_dasar'  => $existing['harga_dasar'],
                'harga_pph'    => $existing['harga_pph'],
                'updated_at'   => date('d M Y H:i', strtotime($existing['updated_at']))
            ];
        }

        // 🔹 Ambil data baru dari LogamMulia (via r.jina.ai)
        $ch = curl_init($this->url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        ]);
        $result = curl_exec($ch);
        curl_close($ch);

        if (!$result) {
            return [
                'status' => 'error',
                'message' => 'Gagal ambil data dari LogamMulia'
            ];
        }

        // 🔹 Ambil harga 1 gram dari tabel markdown
        if (preg_match('/\|\s*1\s*gr\s*\|\s*([\d,.]+)\s*\|\s*([\d,.]+)\s*\|/i', $result, $match)) {
            $hargaDasar = trim($match[1]);
            $hargaPPH   = trim($match[2]);
        } else {
            return [
                'status' => 'error',
                'message' => 'Struktur halaman berubah, gagal parsing data.'
            ];
        }

        // 🔹 Ambil tanggal update
        preg_match('/Harga Emas Hari Ini,\s*([0-9]{2}\s+\w+\s+[0-9]{4})/iu', $result, $tgl);
        $tanggal = $tgl[1] ?? date('d M Y');

        // 🔹 Simpan ke database
        $builder->insert([
            'berat'        => '1 gr',
            'harga_dasar'  => $hargaDasar,
            'harga_pph'    => $hargaPPH,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        return [
            'status'       => 'success (fresh)',
            'berat'        => '1 gr',
            'harga_dasar'  => $hargaDasar,
            'harga_pph'    => $hargaPPH,
            'updated_at'   => $tanggal
        ];
    }
}
