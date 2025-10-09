<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">

  <!-- Judul dan Tombol -->
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800">Investasi</h1>
    <button class="btn btn-success" data-toggle="modal" data-target="#modalInvestasi">
      <i class="fas fa-plus-circle"></i> Tambah Investasi
    </button>
  </div>

  <!-- Alert -->
  <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= esc(session('message')) ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= esc(session('error')) ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <!-- Card Ringkasan -->
  <div class="row mb-4">
    <div class="col-md-4 mb-3">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Investasi</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalInvestasi,0) ?></div>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Nilai Sekarang</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalSekarang,0) ?></div>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Keuntungan / Rugi</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">
            <?php 
              $selisih = $totalSekarang - $totalInvestasi;
              $warna = $selisih >= 0 ? 'text-success' : 'text-danger';
            ?>
            <span class="<?= $warna ?>">¥<?= number_format($selisih,0) ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Investasi -->
  <div class="card shadow">
    <div class="card-header">
      <h6 class="m-0 font-weight-bold text-primary">Daftar Investasi</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead class="thead-light">
            <tr>
              <th>Tanggal</th>
              <th>Nama Investasi</th>
              <th>Akun</th>
              <th>Jumlah (¥)</th>
              <th>Nilai Sekarang (¥)</th>
              <th>Keuntungan/Rugi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($list)): ?>
              <tr><td colspan="8" class="text-center text-muted">Belum ada data investasi.</td></tr>
            <?php else: foreach ($list as $r): 
              $profit = ($r['nilai_sekarang'] ?? 0) - $r['jumlah'];
            ?>
              <tr>
                <td><?= isset($r['tanggal']) ? esc ($r['tanggal']) : '-' ?></td>
                <td><?= esc($r['nama'] ?? $r['deskripsi']) ?? '-' ?></td>
                <td><?= esc($r['akun_nama'] ?? '-') ?></td>
                <td class="text-right">¥<?= number_format($r['jumlah'],0) ?></td>
                <td class="text-right">¥<?= number_format($r['nilai_sekarang'] ?? 0,0) ?></td>
                <td class="text-right <?= $profit>=0?'text-success':'text-danger' ?>">
                  ¥<?= number_format($profit,0) ?>
                </td>
                <td>
                <?php
                $status = isset($r['status']) ? $r['status'] : 'aktif';
                ?>
                <?php if ($status === 'selesai'): ?>
                  <span class="badge badge-success">Terjual</span>
                <?php else: ?>
                  <span class="badge badge-primary">Aktif</span>
                <?php endif; ?>

                </td>
                <td>
                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalUpdate<?= $r['id'] ?>">
                Update Nilai
                </button>
                <?php if ($status !== 'selesai'): ?>
                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalJual<?= $r['id'] ?>">Jual</button>
                <?php endif; ?>
                </td>
              </tr>

<!-- Modal Jual -->
<div class="modal fade" id="modalJual<?= $r['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title">Jual Investasi - <?= isset($r['nama']) ? esc ($r['nama']) : '-' ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>

      <form action="<?= base_url('investasi/jual') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $r['id'] ?>">

        <div class="modal-body">
          <div class="form-group">
            <label>Nilai Penjualan (¥)</label>
            <input type="number" name="nilai_jual" class="form-control" min="0" step="0.01" required>
          </div>

          <div class="form-group">
            <label>Pilih Akun Tujuan</label>
            <select name="akun_id" class="form-control" required>
              <option value="">Pilih Akun</option>
              <?php foreach ($akun as $a): ?>
                <option value="<?= $a['id'] ?>"><?= esc($a['deskripsi']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="modal-footer py-2">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Simpan</button>
        </div>
      </form>

    </div>
  </div>
</div>

            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<!-- Modal Update Nilai -->
<div class="modal fade" id="modalUpdate<?= $r['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Update Nilai Investasi - <?= esc($r['deskripsi']) ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>

      <form action="<?= base_url('investasi/updateNilai') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $r['id'] ?>">

        <div class="modal-body">
          <div class="form-group">
            <label>Nilai Sekarang (¥)</label>
            <input type="number" name="nilai_sekarang" class="form-control" min="0" step="0.01"
              value="<?= esc($r['saldo_terkini'] ?? $r['jumlah']) ?>" required>
          </div>
        </div>

        <div class="modal-footer py-2">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-info">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Tambah Investasi -->
<div class="modal fade" id="modalInvestasi" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <form action="<?= site_url('investasi/store') ?>" method="post" class="modal-content">
      <?= csrf_field() ?>
      <div class="modal-header bg-success text-white py-2">
        <h5 class="modal-title">Tambah Investasi</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Tanggal</label>
          <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
          <label>Nama Investasi</label>
          <input type="text" name="nama" class="form-control" placeholder="Contoh: Saham Toyota" required>
        </div>
        <div class="form-group">
          <label>Akun Sumber Dana</label>
          <select name="akun_id" class="form-control" required>
            <option value="">- pilih -</option>
            <?php foreach($akun as $a): ?>
              <option value="<?= $a['id'] ?>"><?= esc($a['deskripsi']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Jumlah Investasi (¥)</label>
          <input type="number" name="jumlah" class="form-control" placeholder="0" min="0" step="0.01" required>
        </div>
      </div>
      <div class="modal-footer py-2">
        <button class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
  // Auto close alert
  setTimeout(() => {
    $('.alert').alert('close');
  }, 3000);
</script>
<?= $this->endSection(); ?>
