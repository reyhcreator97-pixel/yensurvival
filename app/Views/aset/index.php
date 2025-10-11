<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

  <!-- HEADER -->
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800">Aset</h1>
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAdd">
      <i class="fas fa-plus-circle fa-sm text-white-50"></i> Tambah Aset
    </button>
  </div>

  <!-- ALERT -->
  <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?= esc(session('message')) ?>
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
      <?= esc(session('error')) ?>
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
  <?php endif; ?>

  <!-- CARD TOTAL -->
  <div class="row mb-3">
    <div class="col-md-4 mb-3">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Nilai Sekarang</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalAset ?? 0, 0) ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- TABEL -->
  <div class="card shadow">
    <div class="card-header">
      <h6 class="m-0 font-weight-bold text-primary">Daftar Aset</h6>
    </div>
    <div class="card-body">

      <?php
      $akunById = [];
      foreach ($akun ?? [] as $a) {
        $akunById[$a['id']] = $a['deskripsi'];
      }

      $modals = []; // simpen semua modal di luar tabel
      ?>

      <div class="table-responsive">
        <table class="table table-sm table-hover">
          <thead class="thead-light">
            <tr>
              <th>Tanggal</th>
              <th>Nama Aset</th>
              <th>Akun</th>
              <th class="text-right">Jumlah (¥)</th>
              <th class="text-right">Nilai Sekarang (¥)</th>
              <th class="text-right">Keuntungan / Rugi</th>
              <th>Status</th>
              <th class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php if (empty($list)): ?>
            <tr><td colspan="8" class="text-center text-muted">Belum ada data aset.</td></tr>
          <?php else: foreach ($list as $r): 
              $tgl    = $r['tanggal'] ?? '-';
              $nama   = !empty($r['nama']) ? $r['nama'] : (!empty($r['deskripsi']) ? $r['deskripsi'] : '-');
              $akunNm = isset($r['akun_id']) ? ($akunById[$r['akun_id']] ?? '-') : '-';
              $jumlah = (float)($r['jumlah'] ?? 0);
              $nilai  = (float)($r['nilai_sekarang'] ?? 0);
              $profit = $nilai - $jumlah;
              $aktif  = ($r['status'] ?? 'aktif') !== 'selesai';
          ?>
            <tr>
              <td><?= esc($tgl) ?></td>
              <td><?= esc($nama) ?></td>
              <td><?= esc($akunNm) ?></td>
              <td class="text-right">¥<?= number_format($jumlah, 0) ?></td>
              <td class="text-right">¥<?= number_format($nilai, 0) ?></td>
              <td class="text-right <?= $profit >= 0 ? 'text-success' : 'text-danger' ?>">¥<?= number_format($profit, 0) ?></td>
              <td>
                <?php if ($aktif): ?>
                  <span class="badge badge-primary">Aktif</span>
                <?php else: ?>
                  <span class="badge badge-success">Terjual</span>
                <?php endif; ?>
              </td>
              <td class="text-right">
                <?php if ($aktif): ?>
                  <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalUpdate<?= $r['id'] ?>">Update</button>
                  <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#modalJual<?= $r['id'] ?>">Jual</button>
                <?php else: ?>
                  <form action="<?= site_url('aset/delete/'.$r['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                    <?= csrf_field() ?>
                    <button class="btn btn-xs btn-outline-danger">Hapus</button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php 
          // buffer modal
          ob_start(); ?>

          <!-- MODAL UPDATE -->
          <div class="modal fade" id="modalUpdate<?= $r['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <form action="<?= site_url('aset/updateNilai') ?>" method="post" class="modal-content">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                <div class="modal-header bg-info text-white py-2">
                  <h5 class="modal-title">Update Nilai Sekarang</h5>
                  <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <label>Nilai Sekarang (¥)</label>
                    <input type="number" name="nilai_sekarang" class="form-control" min="0" step="0.01" value="<?= $nilai ?>" required>
                  </div>
                </div>
                <div class="modal-footer py-2">
                  <button class="btn btn-info">Simpan</button>
                </div>
              </form>
            </div>
          </div>

          <!-- MODAL JUAL -->
          <div class="modal fade" id="modalJual<?= $r['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <form action="<?= site_url('aset/jual') ?>" method="post" class="modal-content">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                <div class="modal-header bg-warning text-white py-2">
                  <h5 class="modal-title">Jual Aset</h5>
                  <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <label>Nilai Penjualan (¥)</label>
                    <input type="number" name="nilai_sekarang" class="form-control" min="0" step="0.01" value="<?= max($nilai, $jumlah) ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Pilih Akun Tujuan</label>
                    <select name="akun_id" class="form-control" required>
                      <option value="">- Pilih -</option>
                      <?php foreach ($akun ?? [] as $a): ?>
                        <option value="<?= $a['id'] ?>"><?= esc($a['deskripsi']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Deskripsi</label>
                    <input type="text" name="deskripsi" class="form-control" placeholder="Keterangan (opsional)">
                  </div>
                </div>
                <div class="modal-footer py-2">
                  <button class="btn btn-warning">Simpan</button>
                </div>
              </form>
            </div>
          </div>

          <?php
          $modals[] = ob_get_clean();
          endforeach; endif; ?>
          </tbody>
        </table>
      </div>
      <!-- RENDER SEMUA MODAL DI LUAR TABEL -->
      <?= implode("\n", $modals) ?>
    </div>
  </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form action="<?= site_url('aset/store') ?>" method="post" class="modal-content">
      <?= csrf_field() ?>
      <div class="modal-header bg-primary text-white py-2">
        <h5 class="modal-title">Tambah Aset</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Nama Aset</label>
          <input type="text" name="nama" class="form-control" placeholder="Tanah / Kendaraan / Barang" required>
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
          <label>Jumlah Beli (¥)</label>
          <input type="number" name="jumlah" class="form-control" min="0" step="0.01" placeholder="0" required>
        </div>
        <div class="form-group">
          <label>Catatan</label>
          <input type="text" name="deskripsi" class="form-control" placeholder="Keterangan (opsional)">
        </div>
      </div>
      <div class="modal-footer py-2">
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection(); ?>
