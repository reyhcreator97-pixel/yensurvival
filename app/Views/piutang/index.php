<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>
<div class="container-fluid">

  <!-- Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800">Piutang</h1>
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAdd">
      <i class="fas fa-plus-circle fa-sm text-white-50"></i> Tambah Piutang
    </button>
  </div>

  <!-- Flash message -->
  <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
      <?= esc(session('message')) ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
      <?= esc(session('error')) ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php endif; ?>

  <!-- CARD TOTAL PIUTANG -->
  <div class="row mb-3">
    <div class="col-md-4 mb-3">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Piutang</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalPiutang ?? 0, 0) ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- TABEL -->
  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Daftar Piutang</h6>
    </div>
    <div class="card-body">

      <div class="table-responsive">
        <table class="table table-sm table-hover">
          <thead class="thead-light">
            <tr>
              <th>Tanggal</th>
              <th>Nama Piutang</th>
              <th>Akun</th>
              <th class="text-right">Jumlah (¥)</th>
              <th class="text-right">Diterima (¥)</th>
              <th class="text-right">Sisa (¥)</th>
              <th>Status</th>
              <th class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php if (empty($list)): ?>
            <tr><td colspan="8" class="text-center text-muted">Belum ada data piutang.</td></tr>
          <?php else: foreach ($list as $r): 
              $tgl    = $r['tanggal'] ?? '-';
              $nama   = $r['nama'] ?: ($r['deskripsi'] ?? '-');
              $akunNm = isset($r['akun_id']) ? ($akunNama[$r['akun_id']] ?? '-') : '-';
              $jumlah = (float)($r['jumlah'] ?? 0);
              $diterima = (float)($r['dibayar'] ?? 0);
              $sisa   = $jumlah - $diterima;
              $aktif  = ($r['status'] ?? 'belum') !== 'lunas';
          ?>
            <tr>
              <td><?= esc($tgl) ?></td>
              <td><?= esc($nama) ?></td>
              <td><?= esc($akunNm) ?></td>
              <td class="text-right">¥<?= number_format($jumlah, 0) ?></td>
              <td class="text-right">¥<?= number_format($diterima, 0) ?></td>
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
                  <button class="btn btn-sm btn-success"
                    onclick="openTerimaModal('<?= $r['id'] ?>', '<?= esc($r['nama']) ?>', '<?= $sisa ?>')">
                    Terima
                  </button>
                <?php else: ?>
                  <form action="<?= site_url('piutang/delete/'.$r['id']) ?>" method="post"
                    class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                    <?= csrf_field() ?>
                    <button class="btn btn-sm btn-outline-danger">
                      Hapus
                    </button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- MODAL TERIMA (satu saja, di luar tabel) -->
<div class="modal fade" id="modalTerima" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow-sm border-0">
      <form action="<?= site_url('piutang/storePembayaran') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="piutang_id" id="piutang_id">
        <input type="hidden" name="nama" id="piutang_nama">
        <div class="modal-header bg-success text-white py-2">
          <h5 class="modal-title"><i class="fas fa-hand-holding-usd"></i> Terima Piutang</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Piutang</label>
            <input type="text" id="piutang_nama_display" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Jumlah Terima (¥)</label>
            <input type="number" name="jumlah" id="jumlah_terima" class="form-control" min="1" step="0.01" required>
          </div>
          <div class="form-group">
            <label>Masuk ke Akun</label>
            <select name="akun_id" class="form-control" required>
              <option value="">-- Pilih Akun --</option>
              <?php foreach($akun as $a): ?>
                <option value="<?= $a['id'] ?>"><?= esc($a['deskripsi']) ?> (Saldo: ¥<?= number_format($a['saldo_terkini'] ?? 0, 0) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer py-2">
          <button type="submit" class="btn btn-success px-4">Simpan</button>
          <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form action="<?= site_url('piutang/store') ?>" method="post" class="modal-content">
      <?= csrf_field() ?>
      <input type="hidden" name="status" value="belum">
      <div class="modal-header bg-primary text-white py-2">
        <h5 class="modal-title">Tambah Piutang</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Nama Piutang</label>
          <input type="text" name="nama" class="form-control" placeholder="Contoh: Piutang Teman / Klien" required>
        </div>
        <div class="form-group">
          <label>Masuk ke Akun</label>
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
function openTerimaModal(id, nama, max) {
  document.getElementById('piutang_id').value = id;
  document.getElementById('piutang_nama').value = nama;
  document.getElementById('piutang_nama_display').value = nama;
  document.getElementById('jumlah_terima').setAttribute('max', max);
  $('#modalTerima').modal('show');
}
</script>

<?= $this->endSection(); ?>
