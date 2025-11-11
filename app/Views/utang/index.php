<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>
<div class="container-fluid">

  <!-- Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800">Utang</h1>
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAdd">
      <i class="fas fa-plus-circle fa-sm text-white-50"></i> Tambah Utang
    </button>
  </div>

  <!-- CARD TOTAL UTANG -->
  <div class="row mb-3">
    <div class="col-md-4 mb-3">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Utang</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalUtang ?? 0, 0) ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- TABEL -->
  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Daftar Utang</h6>
    </div>
    <div class="card-body">

      <div class="table-responsive">
        <table class="table table-sm table-hover">
          <thead class="thead-light">
            <tr>
              <th>Tanggal</th>
              <th>Nama Utang</th>
              <th>Akun</th>
              <th class="text-right">Jumlah (¥)</th>
              <th class="text-right">Dibayar (¥)</th>
              <th class="text-right">Sisa (¥)</th>
              <th>Status</th>
              <th class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($list)): ?>
              <tr>
                <td colspan="8" class="text-center text-muted">Belum ada data utang.</td>
              </tr>
              <?php else: foreach ($list as $r):
                $tgl    = $r['tanggal'] ?? '-';
                $nama   = $r['nama'] ?: ($r['deskripsi'] ?? '-');
                $akunNm = isset($r['akun_id']) ? ($akunNama[$r['akun_id']] ?? '-') : '-';
                $jumlah = (float)($r['jumlah'] ?? 0);
                $dibayar = (float)($r['dibayar'] ?? 0);
                $sisa   = $jumlah - $dibayar;
                $aktif  = ($r['status'] ?? 'belum') !== 'lunas';
              ?>
                <tr>
                  <td><?= esc($tgl) ?></td>
                  <td><?= esc($nama) ?></td>
                  <td><?= esc($r['nama_akun'] ?? '-') ?></td>
                  <td class="text-right">¥<?= number_format($jumlah, 0) ?></td>
                  <td class="text-right">¥<?= number_format($dibayar, 0) ?></td>
                  <td class="text-right <?= $sisa > 0 ? 'text-danger' : 'text-success' ?>">¥<?= number_format($sisa, 0) ?></td>
                  <td>
                    <?php if ($aktif): ?>
                      <span class="badge badge-warning px-2 py-1">Belum</span>
                    <?php else: ?>
                      <span class="badge badge-success px-2 py-1">Lunas</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-right">
                    <?php if ($aktif): ?>
                      <button class="btn btn-sm btn-info"
                        onclick="openBayarModal('<?= $r['id'] ?>', '<?= esc($r['nama']) ?>', '<?= $sisa ?>')">
                        Bayar
                      </button>
                    <?php else: ?>
                      <button type="button"
                        class="btn btn-xs btn-outline-danger btn-delete"
                        data-url="<?= site_url('utang/delete/' . $r['id']) ?>">
                        <i class="fas fa-trash"></i>
                      </button>
                    <?php endif; ?>
                  </td>
                </tr>
            <?php endforeach;
            endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- MODAL BAYAR (satu saja, di luar tabel) -->
<div class="modal fade" id="modalBayar" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow-sm border-0">
      <form action="<?= site_url('utang/storePembayaran') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="utang_id" id="utang_id">
        <input type="hidden" name="nama" id="utang_nama">
        <div class="modal-header bg-info text-white py-2">
          <h5 class="modal-title"><i class="fas fa-hand-holding-usd"></i> Bayar Utang</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Utang</label>
            <!---untuk display--->
            <input type="text" id="utang_nama_display" class="form-control" readonly>
            <!--- untuk di kirim ke controller --->
            <input type="hidden" id="nama" id="utang_nama">
          </div>
          <div class="form-group">
            <label>Jumlah Bayar (¥)</label>
            <input type="number" name="jumlah" id="jumlah_bayar" class="form-control" min="1" step="0.01" required>
          </div>
          <div class="form-group">
            <label>Bayar dari Akun</label>
            <select name="akun_id" class="form-control" required>
              <option value="">-- Pilih Akun --</option>
              <?php foreach ($akun as $a): ?>
                <option value="<?= $a['id'] ?>"><?= esc($a['deskripsi']) ?> (Saldo: ¥<?= number_format($a['saldo_terkini'] ?? 0, 0) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer py-2">
          <button type="submit" class="btn btn-info px-4">Simpan</button>
          <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form action="<?= site_url('utang/store') ?>" method="post" class="modal-content">
      <?= csrf_field() ?>
      <input type="hidden" name="status" value="belum">
      <div class="modal-header bg-primary text-white py-2">
        <h5 class="modal-title">Tambah Utang</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Nama Utang</label>
          <input type="text" name="nama" class="form-control" placeholder="Contoh: Utang Bank / Orang Tua" required>
        </div>
        <div class="form-group">
          <label>Ambil dari Akun</label>
          <select name="akun_id" class="form-control" required>
            <option value="">- Pilih -</option>
            <?php foreach ($akun ?? [] as $a): ?>
              <option value="<?= $a['id'] ?>"><?= esc($a['deskripsi']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Jumlah (¥)</label>
          <input type="number" name="jumlah" class="form-control" min="0" step="0.01" placeholder="0" required>
        </div>
        <div class="form-group">
          <label>Catatan</label>
          <input type="text" name="deskripsi" class="form-control" placeholder="Keterangan (opsional)">
        </div>
      </div>
      <div class="modal-footer py-2">
        <button class="btn btn-primary px-4">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openBayarModal(id, nama, max) {
    document.getElementById('utang_id').value = id;
    document.getElementById('utang_nama').value = nama; // hidden untuk backend
    document.getElementById('utang_nama_display').value = nama; // tampil di modal
    document.getElementById('jumlah_bayar').setAttribute('max', max);
    $('#modalBayar').modal('show');
  }
</script>

<?= $this->endSection(); ?>