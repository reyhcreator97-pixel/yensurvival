<?php

namespace App\Controllers;
use App\Models\KekayaanItemModel;

helper('auth'); // <- tambahin ini di luar class

class User extends BaseController
{
    public function index(): string
    {
        $user = user(); // fungsi bawaan Myth/Auth

       $belumIsi = false;
       if($user && isset($user->is_setup)){
        // kalau ada kolom is_setup di tabel users
        $belumIsi = ($user->is_setup == 0);
       }
        

        $data = [
            'title'=>'Welcome',
            'user'  => $user,
            'belumIsi'  => $belumIsi,
        ];
        return view('user/index', $data);
    }
    public function dashboard(): string
    {
        $uid = user_id();
    
        // --- Ambil kurs DCOM ---
        $kursCtrl  = new \App\Controllers\KursDcom();
        $kursView  = $kursCtrl->index();
        $kurs      = $kursCtrl->getKurs();
    
        // === Totals (aman, tanpa ubah struktur lain) ===
$uid      = (int) user_id();
$items    = new \App\Models\KekayaanItemModel();
$utangM   = new \App\Models\UtangModel();
$piutangM = new \App\Models\PiutangModel();

// Total Uang: pakai saldo_terkini kalau ada, fallback ke jumlah
$rowSaldo = $items->selectSum('saldo_terkini', 's')
                  ->where(['user_id' => $uid, 'kategori' => 'uang'])
                  ->get()->getRow();
$totalUang = (float)($rowSaldo->s ?? 0);

if ($totalUang <= 0) {
    $rowJumlah = $items->selectSum('jumlah', 'j')
                       ->where(['user_id' => $uid, 'kategori' => 'uang'])
                       ->get()->getRow();
    $totalUang = (float)($rowJumlah->j ?? 0);
}

// // Total Aset & Investasi (kalau dipakai di card)
// $rowAset = $items->selectSum('jumlah', 'j')
//                  ->where(['user_id' => $uid, 'kategori' => 'aset'])
//                  ->get()->getRow();
// $totalAset = (float)($rowAset->j ?? 0);

// $rowInv = $items->selectSum('jumlah', 'j')
//                 ->where(['user_id' => $uid, 'kategori' => 'investasi'])
//                 ->get()->getRow();
// $totalInvestasi = (float)($rowInv->j ?? 0);

// Total Utang = sisa semua utang (max 0)


$totalUtang = 0.0;
foreach ($utangM->where('user_id', $uid)->findAll() as $u) {
    $jumlah  = (float)($u['jumlah']  ?? 0);
    $dibayar = (float)($u['dibayar'] ?? 0);
    $sisa    = $jumlah - $dibayar;
    if ($sisa > 0) $totalUtang += $sisa;
}
// ✅ Tambahan untuk ambil data utang dari kekayaan_awal
$utangAwal = $items->where([
    'user_id'  => $uid,
    'kategori' => 'utang'
])->findAll();

foreach ($utangAwal as $r) {
    $jumlah = (float)($r['jumlah'] ?? 0);
    $sisa   = $jumlah; // kekayaan awal belum punya kolom dibayar
    if ($sisa > 0) $totalUtang += $sisa;
}

// Total Piutang = sisa semua piutang (max 0)
$totalPiutang = 0.0;
foreach ($piutangM->where('user_id', $uid)->findAll() as $p) {
    $jumlah  = (float)($p['jumlah']  ?? 0);
    $dibayar = (float)($p['dibayar'] ?? 0);
    $sisa    = $jumlah - $dibayar;
    if ($sisa > 0) $totalPiutang += $sisa;
}
// ✅ Tambahan untuk ambil data piutang dari kekayaan_awal
$piutangAwal = $items->where([
    'user_id'  => $uid,
    'kategori' => 'piutang'
])->findAll();

foreach ($piutangAwal as $r) {
    $jumlah = (float)($r['jumlah'] ?? 0);
    $sisa   = $jumlah;
    if ($sisa > 0) $totalPiutang += $sisa;
} 

        // --- Aset & Investasi tetap dari kekayaan_awal ---
        $totalAset = (float)($items->where(['user_id' => $uid, 'kategori' => 'aset'])->selectSum('jumlah')->first()['jumlah'] ?? 0);
        $totalInvestasi = (float)($items->where(['user_id' => $uid, 'kategori' => 'investasi'])->selectSum('jumlah')->first()['jumlah'] ?? 0);
    
        // --- Konversi ke IDR ---
        $totalUangIdr      = $kurs > 0 ? $totalUang * $kurs : 0;
        $totalUtangIdr     = $kurs > 0 ? $totalUtang * $kurs : 0;
        $totalPiutangIdr   = $kurs > 0 ? $totalPiutang * $kurs : 0;
        $totalAsetIdr      = $kurs > 0 ? $totalAset * $kurs : 0;
        $totalInvestasiIdr = $kurs > 0 ? $totalInvestasi * $kurs : 0;
    
        // --- Ambil harga emas IndoGold ---
        $indogold = new \App\Controllers\EmasIndogold();
        $json     = $indogold->getHarga1Gram();
    
        // --- Logic tambahan: status utang ---
        if ($totalUtang <= 0) {
            $statusUtang = 'Bebas Utang 🎉';
        } elseif ($totalUang >= $totalUtang) {
            $statusUtang = 'Bisa Lunas 💪';
        } else {
            $statusUtang = 'Belum Bisa Lunas 😅';
        }
    
        // --- Kirim ke view ---
        $data = [
            'title' => 'Dashboard',
            'hargalog' => $json,
            'kursDcom' => $kursView,
            'kurs' => $kurs,
    
            // Total YEN
            'totalUang'      => $totalUang,
            'totalUtang'     => $totalUtang,
            'totalPiutang'   => $totalPiutang,
            'totalAset'      => $totalAset,
            'totalInvestasi' => $totalInvestasi,
    
            // Total IDR
            'totalUangIdr'      => $totalUangIdr,
            'totalUtangIdr'     => $totalUtangIdr,
            'totalPiutangIdr'   => $totalPiutangIdr,
            'totalAsetIdr'      => $totalAsetIdr,
            'totalInvestasiIdr' => $totalInvestasiIdr,
    
            // Status tambahan
            'statusUtang' => $statusUtang,
        ];
    
        return view('user/dashboard', $data);
    }
    
    
    

    
    
}
