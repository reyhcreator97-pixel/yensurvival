<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Models\KekayaanItemModel;

class TransaksiModel extends Model
{
    protected $table         = 'transaksi';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'user_id', 'tanggal', 'jenis', 'sumber_id', 'tujuan_id',
        'kategori', 'deskripsi', 'jumlah'
    ];
    protected $useTimestamps = false;

    // Otomatis jalankan setelah insert
    protected $afterInsert = ['_afterInsert'];

    protected function _afterInsert(array $data)
    {
        $ids = $data['id'] ?? null;

        // Handle batch insert
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $this->updateSaldo((int)$id);
            }
        } else {
            $this->updateSaldo((int)$ids);
        }
    }

    private function updateSaldo(int $trxId): void
    {
        $trx = $this->find($trxId);
        if (!$trx || ($trx['is_initial'] ?? 0) == 1) return; // ✅ SKIP modal awal
    
        $item = new KekayaanItemModel();
    
        $getSaldo = function(array $akun): float {
            $base = isset($akun['saldo_terkini']) && $akun['saldo_terkini'] !== null
                  ? (float)$akun['saldo_terkini']
                  : (float)$akun['jumlah'];
            return $base;
        };
    
        if ($trx['jenis'] === 'in' && !empty($trx['sumber_id'])) {
            $akun = $item->find($trx['sumber_id']);
            if ($akun) {
                $baru = $getSaldo($akun) + (float)$trx['jumlah'];
                $item->update($akun['id'], ['saldo_terkini' => $baru]);
            }
        }
    
        if ($trx['jenis'] === 'out' && !empty($trx['sumber_id'])) {
            $akun = $item->find($trx['sumber_id']);
            if ($akun) {
                $baru = $getSaldo($akun) - (float)$trx['jumlah'];
                $item->update($akun['id'], ['saldo_terkini' => $baru]);
            }
        }
    }
    

    // Tambahan helper untuk total pemasukan
    public function getTotalPemasukan($user_id): float
    {
        return (float)($this->where('user_id', $user_id)
            ->groupStart()
                ->where('jenis', 'in')
                ->orWhere('jenis', 'pemasukan')
            ->groupEnd()
            ->selectSum('jumlah')
            ->get()->getRow('jumlah') ?? 0);
    }

    // Tambahan helper untuk total pengeluaran
    public function getTotalPengeluaran($user_id): float
    {
        return (float)($this->where('user_id', $user_id)
            ->groupStart()
                ->where('jenis', 'out')
                ->orWhere('jenis', 'pengeluaran')
            ->groupEnd()
            ->selectSum('jumlah')
            ->get()->getRow('jumlah') ?? 0);
    }
}
