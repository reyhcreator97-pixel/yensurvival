<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800">Aset</h1>

    <button class="btn btn-success" data-toggle="modal" data-target="#modalTambahAset">
      <i class="fas fa-plus-circle fa-sm text-white-50"></i> Tambah Aset
    </button>
  </div>

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

  <!-- Card Total Aset -->
  <div class="row mb-4">
    <div class="col-xl-4 col-md-6">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Aset</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalAset, 0) ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter Status -->
  <div class="card shadow mb-3">
    <div class="card-body py-2">
      <form class="form-inline" method="get">
        <label class="mr-2">Status:</label>
        <select name="status" class="form-control mr-2" onchange="this.form.submit()">
          <option value="">Semua</option>
          <option value="aktif" <?= ($status ?? '')==='aktif'?'selected':''; ?>>Aktif</option>
          <option value="terjual" <?= ($status ?? '')==='terjual'?'selected':''; ?>>Terjual</option>
        </select>
      </form>
    </div>
  </div>

  <!-- Tabel Aset -->
  <div class="card shadow">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Daftar Aset</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead class="thead-light">
            <tr>
              <th>Nama Aset</th>
              <th class="text-right">Nilai Awal (¥)</th>
              <th class="text-right">Nilai Sekarang (¥)</th>
              <th class="text-right">Penyusutan (¥)</th>
              <th>Status</th>
              <th class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($list)): ?>
              <tr><td colspan="6" class="text-center text-muted">Belum ada aset.</td></tr>
            <?php else: foreach($list as $row): 
              $nilaiSekarang = $row['saldo_terkini'] ?? $row['jumlah'];
              $statusText = $nilaiSekarang <= 0 ? 'Terjual' : 'Aktif';
              $badge = $nilaiSekarang <= 0 ? 'badge-secondary' : 'badge-success';
            ?>
              <tr>
                <td><?= esc($row['deskripsi'] ?: '-') ?></td>
                <td class="text-right"><?= number_format($row['jumlah'], 0) ?></td>
                <td class="text-right"><?= number_format($nilaiSekarang, 0) ?></td>
                <td class="text-right text-danger"><?= number_format($row['penyusutan'], 0) ?></td>
                <td><span class="badge <?= $badge ?>"><?= $statusText ?></span></td>
                <td class="text-right">
                  <?php if ($nilaiSekarang > 0): ?>
                    <button class="btn btn-xs btn-outline-primary" 
                            data-toggle="modal" 
                            data-target="#modalUpdateAset"
                            data-id="<?= $row['id'] ?>"
                            data-nama="<?= esc($row['deskripsi']) ?>"
                            data-nilai="<?= $nilaiSekarang ?>">
                      <i class="fas fa-edit"></i>
                    </button>
                  <?php endif; ?>
                  <form action="<?= site_url('aset/delete/'.$row['id']) ?>" method="post" style="display:inline;" 
                        onsubmit="return confirm('Hapus aset ini?')">
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

<!-- MODAL: Tambah Aset -->
<div class="modal fade" id="modalTambahAset" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <form action="<?= site_url('aset/store') ?>" method="post" class="modal-content">
      <?= csrf_field() ?>
      <div class="modal-header bg-success text-white py-2">
        <h5 class="modal-title">Tambah Aset Baru</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Nama Aset</label>
          <input type="text" name="nama" class="form-control" required placeholder="Contoh: Laptop / Kamera">
        </div>
        <div class="form-group">
          <label>Nilai Awal (¥)</label>
          <input type="number" step="0.01" name="jumlah" class="form-control" required placeholder="0">
        </div>
        <div class="form-group">
          <label>Ambil Dana Dari Akun</label>
          <select name="akun_id" class="form-control">
            <option value="">- Pilih Akun -</option>
            <?php
            $akun = model('App\Models\KekayaanItemModel')
                ->where(['user_id'=>user_id(),'kategori'=>'uang'])
                ->orderBy('id','ASC')->findAll();
            foreach ($akun as $a): ?>
              <option value="<?= $a['id'] ?>"><?= esc($a['deskripsi']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="modal-footer py-2">
        <button class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Update Nilai Aset -->
<div class="modal fade" id="modalUpdateAset" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <form action="#" method="post" id="formUpdateAset" class="modal-content">
      <?= csrf_field() ?>
      <div class="modal-header bg-primary text-white py-2">
        <h5 class="modal-title">Update Nilai Aset</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="aset_id">
        <div class="form-group">
          <label>Nama Aset</label>
          <input type="text" id="aset_nama" name="deskripsi" class="form-control" readonly>
        </div>
        <div class="form-group">
          <label>Nilai Sekarang (¥)</label>
          <input type="number" step="0.01" name="nilai_sekarang" id="aset_nilai" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Masukkan ke Akun</label>
          <select name="akun_id" class="form-control">
            <option value="">- Pilih Akun -</option>
            <?php foreach ($akun as $a): ?>
              <option value="<?= $a['id'] ?>"><?= esc($a['deskripsi']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="modal-footer py-2">
        <button class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$('#modalUpdateAset').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var id     = button.data('id');
  var nama   = button.data('nama');
  var nilai  = button.data('nilai');
  var modal  = $(this);

  modal.find('#formUpdateAset').attr('action', '<?= site_url('aset/update/') ?>' + id);
  modal.find('#aset_id').val(id);
  modal.find('#aset_nama').val(nama);
  modal.find('#aset_nilai').val(nilai);
});
</script>
<?= $this->endSection(); ?>