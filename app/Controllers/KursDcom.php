<?php
namespace App\Controllers;

class KursDcom extends BaseController
{
    private string $url = 'https://sendmoney.co.jp/id/fx-rate';

    public function index()
    {
        // Ambil halaman HTML
        $ch = curl_init($this->url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            CURLOPT_TIMEOUT        => 15,
        ]);
        $html = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if (!$html) {
            return "<div class='alert alert-danger'>Gagal ambil kurs DCOM: {$err}</div>";
        }

        // Cari kurs IDR (pattern: IDR 112.6000)
        if (preg_match('/IDR\s*([\d,.]+)/i', $html, $m)) {
            $kurs = str_replace(',', '', $m[1]); // hapus koma ribuan
            $kursFormatted = "Rp " . number_format($kurs, 2, ',', '.');
        } else {
            $kursFormatted = "Kurs tidak ditemukan";
        }

        // Cari tanggal update (pattern: YYYY-MM-DD HH:MM)
        if (preg_match('/\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}/', $html, $t)) {
            $updated = $t[0];
        } else {
            $updated = date('d-m-Y H:i');
        }

        return view('dcom/index', [
            'kurs'    => $kursFormatted,
            'updated' => $updated
        ]);
    }

public function getKurs(): float
{
    $url = 'https://sendmoney.co.jp/id/fx-rate';

    // lebih stabil pakai cURL + UA
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        CURLOPT_TIMEOUT        => 15,
    ]);
    $html = curl_exec($ch);
    curl_close($ch);

    if (!$html) return 0.0;

    // contoh baris: "IDR 112.6000   JPY 0.008881"
    if (preg_match('/IDR\s*([\d.,]+)/i', $html, $m)) {
        // di situs ini desimal pakai titik, ribuan jarang dipakai → hapus koma kalau ada
        $num = str_replace(',', '', $m[1]);   // "112.6000" -> "112.6000"
        return (float)$num;                   // 112.6
    }

    return 0.0;
}

}
