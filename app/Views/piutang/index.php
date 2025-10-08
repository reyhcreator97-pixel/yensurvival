<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800">Catatan Piutang</h1>
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahPiutang">
      <i class="fas fa-plus-circle fa-sm text-white-50"></i> Tambah Piutang
    </button>
  </div>

  <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?= esc(session('message')) ?>
      <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
      <?= esc(session('error')) ?>
      <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
    </div>
  <?php endif; ?>

  <?php
    if (!function_exists('yen2')) {
      function yen2($v){ return '¥'.number_format((float)$v, 0, ',', '.'); }
    }
  ?>

  <!-- Ringkasan -->
  <div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Piutang</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800"><?= yen2($totalPiutang ?? 0) ?></div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Diterima</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800"><?= yen2($totalDibayar ?? 0) ?></div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sisa Piutang</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800"><?= yen2($sisaPiutang ?? 0) ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Piutang -->
  <div class="card shadow">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Daftar Piutang</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm">
          <thead class="thead-light">
            <tr>
              <th>Nama Penerima</th>
              <th class="text-right">Jumlah</th>
              <th class="text-right">Diterima</th>
              <th class="text-right">Sisa</th>
              <th>Akun</th>
              <th class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($list)): ?>
              <tr><td colspan="6" class="text-center text-muted">Belum ada data piutang.</td></tr>
            <?php else: foreach ($list as $r):
                $dibayar = (float)($r['dibayar'] ?? 0);
                $jumlah  = (float)($r['jumlah'] ?? 0);
                $sisa    = max(0, $jumlah - $dibayar);
                $akunNama = '-';
                if (!empty($akun)) {
                  foreach ($akun as $a) {
                    if ((int)$a['id'] === (int)($r['akun_id'] ?? 0)) { $akunNama = $a['deskripsi']; break; }
                  }
                }
            ?>
              <tr>
                <td>
                  <div class="font-weight-bold"><?= esc($r['nama']) ?></div>
                  <div class="small text-muted"><?= esc($r['keterangan'] ?? '') ?></div>
                </td>
                <td class="text-right"><?= yen2($jumlah) ?></td>
                <td class="text-right"><?= yen2($dibayar) ?></td>
                <td class="text-right"><?= yen2($sisa) ?></td>
                <td><?= esc($akunNama) ?></td>
                <td class="text-right">
                <button onclick="openBayarPiutang(<?= $r['id'] ?>)" class="btn btn-primary btn-sm">
                 Terima
                </button>
                </td>
              </tr>

              <!-- Modal Terima -->
              <div class="modal fade" id="modalBayarPiutang" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Terima Pembayaran Piutang</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="<?= base_url('piutang/storePembayaran') ?>" method="post">
        <div class="modal-body">
          <input type="hidden" name="piutang_id" id="piutang_id">

          <div class="form-group">
            <label>Jumlah Diterima (¥)</label>
            <input type="number" class="form-control" name="jumlah" min="0" required>
          </div>

          <div class="form-group">
            <label>Diterima ke Akun</label>
            <select name="akun_id" class="form-control" required>
              <option value="">-- Pilih Akun --</option>
              <?php foreach($akun as $a): ?>
                <option value="<?= esc($a['id']) ?>">
                  <?= esc($a['deskripsi']) ?> (Saldo: ¥<?= number_format($a['saldo_terkini'] ?? 0,0) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  function openBayarPiutang(id) {
    document.getElementById('piutang_id').value = id;
    $('#modalBayarPiutang').modal('show');
  }
</script>

              <!-- /Modal Terima -->
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Piutang -->
<div class="modal fade" id="modalTambahPiutang" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <form action="<?= site_url('piutang/store') ?>" method="post" class="modal-content">
      <?= csrf_field() ?>
      <div class="modal-header bg-primary text-white py-2">
        <h5 class="modal-title">Tambah Piutang</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Nama Penerima</label>
          <input type="text" name="nama" class="form-control" placeholder="Contoh: Teman / Saudara" required>
        </div>
        <div class="form-group">
          <label>Jumlah (¥)</label>
          <input type="number" step="0.01" min="0" name="jumlah" class="form-control" placeholder="0" required>
        </div>
        <div class="form-group">
          <label>Diambil dari Akun</label>
          <select name="akun_id" class="form-control" required>
            <option value="">- pilih -</option>
            <?php foreach ($akun as $a): ?>
              <option value="<?= $a['id'] ?>"><?= esc($a['deskripsi']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Keterangan (opsional)</label>
          <input type="text" name="keterangan" class="form-control" placeholder="Catatan singkat">
        </div>
      </div>
      <div class="modal-footer py-2">
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection(); ?>
