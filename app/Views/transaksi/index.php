<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800">Transaksi</h1>

    <div>
      <button class="btn btn-success mr-2" data-toggle="modal" data-target="#modalTrx">
        <i class="fas fa-plus-circle fa-sm text-white-50"></i> Tambah Transaksi
      </button>
      <button class="btn btn-primary" data-toggle="modal" data-target="#modalTransfer">
        <i class="fas fa-exchange-alt fa-sm text-white-50"></i> Pindah Dana
      </button>
      <button class="btn btn-info" data-toggle="modal" data-target="#modalDetailAkun">
  <i class="fas fa-wallet fa-sm text-white-50"></i> Detail Akun
</button>

    </div>
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

  <!-- Filter -->
  <div class="card shadow mb-3">
    <div class="card-body">
      <form class="form-inline">
        <label class="mr-2">Filter:</label>
        <select name="mode" class="form-control mr-2" onchange="toggleFilter(this.value)">
          <option value="daily"   <?= $mode==='daily'?'selected':''; ?>>Harian</option>
          <option value="monthly" <?= $mode==='monthly'?'selected':''; ?>>Bulanan</option>
          <option value="yearly"  <?= $mode==='yearly'?'selected':''; ?>>Tahunan</option>
        </select>

        <input type="date" name="date"  id="flt-date"  class="form-control mr-2" value="<?= esc($date) ?>"  <?= $mode!=='daily'?'style="display:none"':''; ?>>
        <input type="month" name="month" id="flt-month" class="form-control mr-2" value="<?= esc($month) ?>" <?= $mode!=='monthly'?'style="display:none"':''; ?>>
        <input type="number" min="2000" max="2100" name="year" id="flt-year" class="form-control mr-2" value="<?= esc($year) ?>" placeholder="YYYY" <?= $mode!=='yearly'?'style="display:none"':''; ?>>

        <button class="btn btn-secondary">Terapkan</button>
      </form>
    </div>
  </div>

  <!-- Ringkasan -->
  <div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pemasukan</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalIn,0) ?></div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalOut,0) ?></div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Saldo (In - Out)</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($saldo,0) ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel -->
  <div class="card shadow">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Detail Keuangan</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm">
          <thead class="thead-light">
            <tr>
              <th>Tanggal</th>
              <th>Jenis</th>
              <th>Akun</th>
              <th>Kategori</th>
              <th>Deskripsi</th>
              <th class="text-right">Jumlah (¥)</th>
              <th class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // helper nama akun
            $akunById = [];
            foreach ($akun as $a) { $akunById[$a['id']] = $a['deskripsi']; }
            ?>
            <?php if(empty($list)): ?>
              <tr><td colspan="7" class="text-center text-muted">Belum ada transaksi.</td></tr>
            <?php else: foreach ($list as $r): ?>
              <tr>
                <td><?= esc($r['tanggal']) ?></td>
                <td>
                  <?php if($r['jenis']==='in'): ?>
                    <span class="badge badge-success">Masuk</span>
                  <?php elseif($r['jenis']==='out'): ?>
                    <span class="badge badge-danger">Keluar</span>
                  <?php else: ?>
                    <span class="badge badge-primary">Transfer</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?= esc($akunById[$r['sumber_id']] ?? '-') ?>
                  <?php if($r['tujuan_id']): ?>
                    <i class="fas fa-arrow-right mx-1"></i>
                    <?= esc($akunById[$r['tujuan_id']] ?? '-') ?>
                  <?php endif; ?>
                </td>
                <td><?= esc($r['kategori'] ?? '-') ?></td>
                <td><?= esc($r['deskripsi'] ?? '-') ?></td>
                <td class="text-right">¥<?= number_format($r['jumlah'],0) ?></td>
                <td class="text-right">
                  <form action="<?= site_url('transaksi/delete/'.$r['id']) ?>" method="post" onsubmit="return confirm('Hapus transaksi ini?')">
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

<!-- MODAL: Tambah Transaksi -->
<div class="modal fade" id="modalTrx" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <form action="<?= site_url('transaksi/store') ?>" method="post" class="modal-content">
      <?= csrf_field() ?>
      <div class="modal-header bg-success text-white py-2">
        <h5 class="modal-title">Tambah Transaksi</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Tanggal</label>
          <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
          <label>Jenis</label>
          <select name="jenis" class="form-control" required>
            <option value="in">Pemasukan</option>
            <option value="out">Pengeluaran</option>
          </select>
        </div>
        <div class="form-group">
          <label>Sumber Dana</label>
          <select name="sumber_id" class="form-control" required>
            <option value="">- pilih -</option>
            <?php foreach($akun as $a): ?>
              <option value="<?= $a['id'] ?>"><?= esc($a['deskripsi']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Kategori</label>
          <input type="text" name="kategori" class="form-control" placeholder="Gaji / Makan / Transport ...">
        </div>
        <div class="form-group">
          <label>Deskripsi</label>
          <input type="text" name="deskripsi" class="form-control" placeholder="keterangan (opsional)">
        </div>
        <div class="form-group">
          <label>Jumlah (¥)</label>
          <input type="number" step="0.01" min="0" name="jumlah" class="form-control" placeholder="0" required>
        </div>
      </div>
      <div class="modal-footer py-2">
        <button class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Pindah Dana -->
<div class="modal fade" id="modalTransfer" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <form action="<?= site_url('transaksi/transfer') ?>" method="post" class="modal-content">
      <?= csrf_field() ?>
      <div class="modal-header bg-primary text-white py-2">
        <h5 class="modal-title">Pindah Dana</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Tanggal</label>
          <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
          <label>Dari Akun</label>
          <select name="from_id" class="form-control" required>
            <option value="">- pilih -</option>
            <?php foreach($akun as $a): ?>
              <option value="<?= $a['id'] ?>"><?= esc($a['deskripsi']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Ke Akun</label>
          <select name="to_id" class="form-control" required>
            <option value="">- pilih -</option>
            <?php foreach($akun as $a): ?>
              <option value="<?= $a['id'] ?>"><?= esc($a['deskripsi']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Jumlah (¥)</label>
          <input type="number" step="0.01" min="0" name="jumlah" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Kategori/Tag (opsional)</label>
          <input type="text" name="cat" class="form-control" placeholder="Transfer / Topup / Tarik">
        </div>
      </div>
      <div class="modal-footer py-2">
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Detail Akun -->
<div class="modal fade" id="modalDetailAkun" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info text-white py-2">
        <h5 class="modal-title">Detail Akun</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <table class="table table-sm table-hover">
          <thead class="thead-light">
            <tr>
              <th>Nama Akun</th>
              <th class="text-right">Saldo (¥)</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $akunList = array_filter($akun, fn($a) => $a['kategori'] === 'uang');
            foreach ($akunList as $a):
              $saldo = $a['saldo_terkini'] ?? $a['jumlah'];
            ?>
            <tr>
              <td><?= esc($a['deskripsi']) ?></td>
              <td class="text-right font-weight-bold">¥<?= number_format($saldo, 0) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <hr>
        <button class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#modalAddAkun" data-dismiss="modal">
          <i class="fas fa-plus"></i> Tambah Akun Baru
        </button>
      </div>
    </div>
  </div>
</div>
<!-- MODAL: Tambah Akun Baru -->
<div class="modal fade" id="modalAddAkun" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <form action="<?= site_url('transaksi/addAkun') ?>" method="post" class="modal-content">
      <?= csrf_field() ?>
      <div class="modal-header bg-success text-white py-2">
        <h5 class="modal-title">Tambah Akun Baru</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Nama Akun</label>
          <input type="text" name="deskripsi" class="form-control" placeholder="Contoh: Bank BCA, GoPay, Cash" required>
        </div>
        <div class="form-group">
          <label>Saldo Awal (¥)</label>
          <input type="number" step="0.01" name="jumlah" class="form-control" placeholder="0" required>
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
function toggleFilter(mode){
  document.getElementById('flt-date').style.display  = (mode==='daily')   ? '' : 'none';
  document.getElementById('flt-month').style.display = (mode==='monthly') ? '' : 'none';
  document.getElementById('flt-year').style.display  = (mode==='yearly')  ? '' : 'none';
}
</script>
<?= $this->endSection(); ?>
