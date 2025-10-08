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
        // --- Ambil kurs DCOM ---
        $kursCtrl  = new \App\Controllers\KursDcom();
        $kursView  = $kursCtrl->index();
        $kurs      = $kursCtrl->getKurs();
    
        // --- Ambil ID user ---
        $uid = user_id();
    
        // --- Model utama ---
        $items        = new \App\Models\KekayaanItemModel();
        $utangModel   = new \App\Models\UtangModel();
        $piutangModel = new \App\Models\PiutangModel();
    
        // --- Total dari kekayaan awal ---
        $uangAwal      = $items->where(['user_id'=>$uid,'kategori'=>'uang'])->selectSum('jumlah')->first()['jumlah'] ?? 0;
        $utangAwal     = $items->where(['user_id'=>$uid,'kategori'=>'utang'])->selectSum('jumlah')->first()['jumlah'] ?? 0;
        $piutangAwal   = $items->where(['user_id'=>$uid,'kategori'=>'piutang'])->selectSum('jumlah')->first()['jumlah'] ?? 0;
        $asetAwal      = $items->where(['user_id'=>$uid,'kategori'=>'aset'])->selectSum('jumlah')->first()['jumlah'] ?? 0;
        $investAwal    = $items->where(['user_id'=>$uid,'kategori'=>'investasi'])->selectSum('jumlah')->first()['jumlah'] ?? 0;
    
        // --- Total dari tabel utang/piutang aktif (belum lunas aja) ---
        $utangBaru = $utangModel
            ->where(['user_id'=>$uid, 'status'=>'belum'])
            ->selectSum('jumlah')
            ->first()['jumlah'] ?? 0;
    
        $piutangBaru = $piutangModel
            ->where(['user_id'=>$uid, 'status'=>'belum'])
            ->selectSum('jumlah')
            ->first()['jumlah'] ?? 0;
    
        // --- Gabungkan kekayaan awal + baru ---
        $totals = [
            'uang'      => $uangAwal,
            'utang'     => $utangAwal + $utangBaru,
            'piutang'   => $piutangAwal + $piutangBaru,
            'aset'      => $asetAwal,
            'investasi' => $investAwal,
        ];
    
        // --- Konversi ke IDR ---
        $totalUangIdr      = $kurs > 0 ? $totals['uang'] * $kurs : 0;
        $totalUtangIdr     = $kurs > 0 ? $totals['utang'] * $kurs : 0;
        $totalPiutangIdr   = $kurs > 0 ? $totals['piutang'] * $kurs : 0;
        $totalAsetIdr      = $kurs > 0 ? $totals['aset'] * $kurs : 0;
        $totalInvestasiIdr = $kurs > 0 ? $totals['investasi'] * $kurs : 0;

         // --- Status Utang Berdasarkan Total Uang ---
         if ($totals['utang'] <= 0) {
            $statusUtang = ' ( Bebas Utang )';
            } elseif ($totals['uang'] > $totals['utang']) {
            $statusUtang = ' ( Bisa Lunas )';
            } else {
            $statusUtang = ' ( Belum Bisa Lunas )';
            }

    
        // --- Harga emas IndoGold ---
        $indogold = new \App\Controllers\EmasIndogold();
        $json     = $indogold->getHarga1Gram();
    
        // --- Data ke view ---
        $data = [
            'title'             => 'Dashboard',
            'hargalog'          => $json,
            'kursDcom'          => $kursView,
            'kurs'              => $kurs,
    
            // Total per kategori (YEN)
            'totalUang'         => $totals['uang'],
            'totalUtang'        => $totals['utang'],
            'totalPiutang'      => $totals['piutang'],
            'totalAset'         => $totals['aset'],
            'totalInvestasi'    => $totals['investasi'],
    
            // Total dalam IDR
            'totalUangIdr'      => $totalUangIdr,
            'totalUtangIdr'     => $totalUtangIdr,
            'totalPiutangIdr'   => $totalPiutangIdr,
            'totalAsetIdr'      => $totalAsetIdr,
            'totalInvestasiIdr' => $totalInvestasiIdr,   
            'statusUtang' => $statusUtang,
        ];
    
        return view('user/dashboard', $data);
    }
    
    

    
    
}
