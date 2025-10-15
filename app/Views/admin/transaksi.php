<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Transaksi Global</h1>
  </div>

  <!-- Filter -->
  <form method="get" class="form-inline mb-3">
    <select name="mode" class="form-control mr-2" onchange="this.form.submit()">
      <option value="daily" <?= $mode == 'daily' ? 'selected' : '' ?>>Harian</option>
      <option value="monthly" <?= $mode == 'monthly' ? 'selected' : '' ?>>Bulanan</option>
      <option value="yearly" <?= $mode == 'yearly' ? 'selected' : '' ?>>Tahunan</option>
    </select>

    <?php if ($mode == 'daily'): ?>
      <input type="date" name="date" value="<?= $date ?>" class="form-control mr-2" onchange="this.form.submit()">
    <?php elseif ($mode == 'monthly'): ?>
      <input type="month" name="month" value="<?= $month ?>" class="form-control mr-2" onchange="this.form.submit()">
    <?php else: ?>
      <input type="number" name="year" value="<?= $year ?>" min="2020" max="<?= date('Y') ?>" class="form-control mr-2" onchange="this.form.submit()">
    <?php endif; ?>
  </form>

  <!-- Summary Cards -->
  <div class="row mb-4">
    <div class="col-md-4 mb-3">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pemasukan</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalIn, 0) ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalOut, 0) ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Saldo (In - Out)</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($saldo, 0) ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div class="card shadow">
    <div class="card-header">
      <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-hover">
          <thead class="thead-light">
            <tr class="text-center">
              <th>Tanggal</th>
              <th>User</th>
              <th>Email</th>
              <th>Kategori</th>
              <th>Deskripsi</th>
              <th class="text-right">Jumlah (¥)</th>
              <th>Jenis</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($list)): ?>
              <tr><td colspan="7" class="text-center text-muted">Belum ada transaksi</td></tr>
            <?php else: foreach ($list as $r): ?>
              <tr class="text-center">
                <td><?= esc($r['tanggal']) ?></td>
                <td><?= esc($r['username']) ?></td>
                <td><?= esc($r['email']) ?></td>
                <td><?= esc($r['kategori']) ?></td>
                <td><?= esc($r['deskripsi']) ?></td>
                <td class="text-right">¥<?= number_format($r['jumlah'], 0) ?></td>
                <td>
                  <?php if ($r['jenis'] === 'in'): ?>
                    <span class="badge badge-success">Masuk</span>
                  <?php else: ?>
                    <span class="badge badge-danger">Keluar</span>
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

<?= $this->endSection(); ?>