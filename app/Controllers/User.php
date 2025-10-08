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
    
        // --- Ambil model utama ---
        $items    = new \App\Models\KekayaanItemModel();
        $utangM   = new \App\Models\UtangModel();
        $piutangM = new \App\Models\PiutangModel();
    
        // --- Ambil total uang dari semua akun aktif ---
        $uangRows = $items->where(['user_id' => $uid, 'kategori' => 'uang'])->findAll();
        $totalUang = 0;
        foreach ($uangRows as $u) {
            $totalUang += (float)($u['saldo_terkini'] ?? $u['jumlah']);
        }
    
        // --- Total utang dan piutang (real-time, pakai sisa) ---
        $utangRows = $utangM->where('user_id', $uid)->findAll();
        $piutangRows = $piutangM->where('user_id', $uid)->findAll();
    
        $totalUtang = 0;
        foreach ($utangRows as $r) {
            $sisa = (float)$r['jumlah'] - (float)($r['dibayar'] ?? 0);
            if ($sisa > 0) $totalUtang += $sisa;
        }
    
        $totalPiutang = 0;
        foreach ($piutangRows as $r) {
            $sisa = (float)$r['jumlah'] - (float)($r['dibayar'] ?? 0);
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
