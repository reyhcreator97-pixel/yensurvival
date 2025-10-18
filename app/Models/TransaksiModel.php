<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Models\KekayaanItemModel;

class TransaksiModel extends Model
{
    protected $table         = 'transaksi';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'user_id','tanggal','jenis',
        'sumber_id','tujuan_id',
        'kategori','deskripsi','jumlah'
    ];
    protected $useTimestamps = false;

    // Hook untuk nge-apply pengaruh ke saldo_terkini akun
    protected $afterInsert = ['_afterInsert'];
    // (opsional) kalau kamu nanti pakai edit transaksi, bisa aktifkan:
    // protected $afterUpdate = ['_afterUpdate'];
    // protected $beforeDelete = ['_beforeDelete'];

    protected function _afterInsert(array $data)
    {
        $id = $data['id'] ?? null;
        if (is_array($id)) $id = $id[0] ?? null;
        if ($id) $this->updateSaldo((int)$id);
        return $data;
    }

    /**
     * Catatan: desain sederhana — kita cuma apply delta saat insert transaksi.
     * Kalau mau full-akurat (edit/hapus transaksi), tambahin rollback di beforeDelete/afterUpdate.
     */

    public function updateSaldo(int $trxId): void
    {
        $trx = $this->find($trxId);
        if (!$trx) return;

        $items = new KekayaanItemModel();

        // ambil akun sumber (wajib utk in/out; untuk transfer kamu sudah bikin dua baris, jadi masing2 akan jalan sendiri)
        if (!empty($trx['sumber_id'])) {
            $akun = $items->find($trx['sumber_id']);
            if ($akun) {
                // base saldo SEKARANG (bukan dari 'jumlah' awal)
                $base = isset($akun['saldo_terkini']) && $akun['saldo_terkini'] !== null
                      ? (float)$akun['saldo_terkini']
                      : 0.0;

                if ($trx['jenis'] === 'in' || $trx['jenis'] === 'pemasukan') {
                    $baru = $base + (float)$trx['jumlah'];
                } elseif ($trx['jenis'] === 'out' || $trx['jenis'] === 'pengeluaran') {
                    $baru = $base - (float)$trx['jumlah'];
                } else {
                    // tipe lain (kalau ada), abaikan
                    $baru = $base;
                }

                $items->update($akun['id'], ['saldo_terkini' => $baru]);
            }
        }
    }

}
