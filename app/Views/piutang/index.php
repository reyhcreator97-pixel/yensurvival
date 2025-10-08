<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800">Catatan Piutang</h1>
    <button class="btn btn-success" data-toggle="modal" data-target="#modalPiutang">
      <i class="fas fa-plus-circle fa-sm text-white-50"></i> Tambah Piutang
    </button>
  </div>

  <?php if(session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?= esc(session('message')) ?>
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-md-4 mb-3">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Piutang</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($total['piutang'],0) ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Dibayar</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($total['lunas'],0) ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sisa Piutang</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($sisa,0) ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow">
    <div class="card-header">
      <h6 class="m-0 font-weight-bold text-primary">Daftar Piutang</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-striped">
          <thead class="thead-light">
            <tr>
              <th>Tanggal</th>
              <th>Nama Peminjam</th>
              <th>Keterangan</th>
              <th class="text-right">Jumlah (¥)</th>
              <th>Status</th>
              <th class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($list)): ?>
              <tr><td colspan="6" class="text-center text-muted">Belum ada data piutang.</td></tr>
            <?php else: foreach($list as $r): ?>
              <tr>
                <td><?= esc($r['tanggal']) ?></td>
                <td>
                    <?= esc($r['nama'] ?? $r['deskripsi'] ?? '-') ?>
                </td>

                <td>
                    <?php 
                    if (($r['asal'] ?? '') === 'awal'){
                        echo 'Kekayaan Awal';
                    } else {
                    echo esc($r['keterangan'] ?? $r['deskripsi'] ?? '-');
                    }
                    ?>
                    </td>
                <td class="text-right">¥<?= number_format($r['jumlah'],0) ?></td>
                <td><?= $r['status']==='lunas' ? '<span class="badge badge-success">Lunas</span>' : '<span class="badge badge-warning">Belum Dibayar</span>' ?></td>
                <td class="text-right">
                  <?php if($r['status']==='belum'): ?>
                    <a href="<?= site_url('piutang/lunas/'.$r['id']) ?>" class="btn btn-xs btn-outline-success">Tandai Lunas</a>
                  <?php endif; ?>
                  <form action="<?= site_url('piutang/delete/'.$r['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus data ini beserta transaksi terkait?')">
                    <?= csrf_field() ?>
                    <button class="btn btn-xs btn-outline-danger"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- MODAL TAMBAH PIUTANG -->
<div class="modal fade" id="modalPiutang" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <form action="<?= site_url('piutang/store') ?>" method="post" class="modal-content">
      <?= csrf_field() ?>
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Tambah Piutang</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
        <div class="form-group"><label>Nama Peminjam</label><input type="text" name="nama" class="form-control" required></div>
        <div class="form-group"><label>Jumlah (¥)</label><input type="number" step="0.01" min="0" name="jumlah" class="form-control" required></div>
        <div class="form-group"><label>Keterangan</label><input type="text" name="keterangan" class="form-control"></div>
      </div>
      <div class="modal-footer"><button class="btn btn-success">Simpan</button></div>
    </form>
  </div>
</div>

<?= $this->endSection(); ?>
