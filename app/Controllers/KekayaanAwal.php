<?php

namespace App\Controllers;

use App\Models\KekayaanItemModel;
use App\Models\TransaksiModel; // ✅ tambahkan ini
use CodeIgniter\HTTP\RedirectResponse;

class KekayaanAwal extends BaseController
{
    protected KekayaanItemModel $items;
    protected TransaksiModel $trx; // ✅ model transaksi

    public function __construct()
    {
        $this->items = new KekayaanItemModel();
        $this->trx   = new TransaksiModel(); // ✅ inisialisasi
        helper(['form', 'text']);
    }

    private function currentUserId(): int
    {
        if (function_exists('user_id') && user_id()) return (int) user_id();
        $auth = service('authentication');
        return (int) ($auth->user()->id ?? 0);
    }

    private function cleanNumber($val): float
    {
        $val = preg_replace('/[^\d\.\-]/', '', (string)$val);
        return (float)$val;
    }
    
    public function index(): string
    {
        $uid = $this->currentUserId();
        $grouped = $this->items->byUserGrouped($uid);
        $totals  = $this->items->totalByUser($uid);

        $data = [
            'title'   => 'Kekayaan Awal',
            'items'   => $grouped,
            'totals'  => $totals,
            'isEmpty' => array_sum($totals) == 0,
        ];

        return view('kekayaan_awal/index', $data);
    }

    public function store(): RedirectResponse
    {
        $uid = $this->currentUserId();
        if (!$uid) return redirect()->back()->with('error', 'User tidak dikenali.');
    
        $categories = ['uang','utang','piutang','aset','investasi'];
        $inserted   = 0;
    
        foreach ($categories as $cat) {
            $descKey   = $cat . '_desc';
            $amountKey = $cat . '_amount';
    
            $descs   = (array) $this->request->getPost($descKey);
            $amounts = (array) $this->request->getPost($amountKey);
    
            foreach ($descs as $i => $desc) {
                $desc = trim((string)$desc);
                $amt  = $this->cleanNumber($amounts[$i] ?? 0);
                if ($desc === '' || $amt == 0) continue;
    
                // simpan ke tabel kekayaan_awal
                $this->items->insert([
                    'user_id'   => $uid,
                    'kategori'  => $cat,
                    'deskripsi' => $desc,
                    'jumlah'    => $amt,
                ]);
    
                $itemId = $this->items->getInsertID();
                $inserted++;
    
                // tentukan jenis transaksi
                $jenis = in_array($cat, ['utang']) ? 'out' : 'in';
    
                // simpan otomatis ke transaksi
                $this->trx->insert([
                    'user_id'   => $uid,
                    'tanggal'   => date('Y-m-d'),
                    'jenis'     => $jenis,
                    'sumber_id' => $itemId, // ✅ relasional
                    'kategori'  => ucfirst($cat),
                    'deskripsi' => 'Modal awal dari ' . ucfirst($cat),
                    'jumlah'    => $amt,
                ]);
            }
        }
    
        // update status setup user
        if ($inserted > 0) {
            try {
                $db = \Config\Database::connect();
                if ($db->fieldExists('is_setup', 'users')) {
                    $db->table('users')->where('id', $uid)->update(['is_setup' => 1]);
                }
            } catch (\Throwable $th) {}
        }
    
        return redirect()->to('/kekayaan-awal')->with('message', 'Data kekayaan awal tersimpan dan otomatis ditambahkan ke transaksi.');
    }
    

    public function update(): RedirectResponse
    {
        $uid  = $this->currentUserId();
        $id   = (int) $this->request->getPost('id');
        $desc = trim((string)$this->request->getPost('deskripsi'));
        $amt  = $this->cleanNumber($this->request->getPost('jumlah'));

        $row = $this->items->find($id);
        if (!$row || $row['user_id'] != $uid) {
            return redirect()->back()->with('error', 'Item tidak ditemukan.');
        }

        $this->items->update($id, [
            'deskripsi' => $desc,
            'jumlah'    => $amt,
        ]);

        return redirect()->to('/kekayaan-awal')->with('message', 'Item diperbarui.');
    }

    public function delete($id): RedirectResponse
    {
        $uid = $this->currentUserId();
        $row = $this->items->find((int)$id);
        if ($row && $row['user_id'] == $uid) {
            $this->items->delete((int)$id);
            return redirect()->to('/kekayaan-awal')->with('message', 'Item dihapus.');
        }
        return redirect()->back()->with('error', 'Item tidak ditemukan.');
    }
}
