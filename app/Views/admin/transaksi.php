<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">

  <!-- Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800">Daftar Transaksi</h1>
  </div>

  <!-- Filter Form -->
  <form method="get" class="card shadow-sm border-0 mb-4 p-3 bg-light">
    <div class="form-row align-items-end">
      <div class="col-md-3 mb-2">
        <label><i class="fas fa-search"></i> Cari (User / Email / Deskripsi)</label>
        <input type="text" name="keyword" class="form-control" value="<?= esc($keyword ?? '') ?>" placeholder="Masukkan kata kunci...">
      </div>

      <div class="col-md-3 mb-2">
        <label><i class="fas fa-tags"></i> Kategori</label>
        <select name="kategori" class="form-control">
          <option value="">-- Semua Kategori --</option>
          <?php foreach ($kategoriList as $k): ?>
            <option value="<?= esc($k['kategori']) ?>" <?= ($kategori ?? '') == $k['kategori'] ? 'selected' : '' ?>>
              <?= esc(ucfirst($k['kategori'])) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-2 mb-2">
        <label><i class="fas fa-calendar-alt"></i> Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="<?= esc($tanggal ?? '') ?>">
      </div>

      <div class="col-md-2 mb-2">
        <button type="submit" class="btn btn-primary btn-block">
          <i class="fas fa-filter"></i> Terapkan
        </button>
      </div>

      <div class="col-md-2 mb-2">
        <a href="<?= site_url('admin/transaksi') ?>" class="btn btn-outline-secondary btn-block">
          <i class="fas fa-sync-alt"></i> Reset
        </a>
      </div>
    </div>
  </form>

  <!-- Tabel -->
  <div class="card shadow border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h6 class="m-0 font-weight-bold"><i class="fas fa-list"></i> Transaksi Terbaru</h6>
      <a href="<?= site_url('admin/transaksi/export') ?>" class="btn btn-sm btn-light text-primary font-weight-bold">
        <i class="fas fa-file-export"></i> Export CSV
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover" width="100%">
          <thead class="thead-light">
            <tr class="text-center">
              <th width="120">Tanggal</th>
              <th>User</th>
              <th>Email</th>
              <th>Kategori</th>
              <th>Deskripsi</th>
              <th class="text-right" width="120">Jumlah (¥)</th>
              <th width="90">Jenis</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($list)): ?>
              <tr><td colspan="7" class="text-center text-muted py-3">Belum ada transaksi ditemukan.</td></tr>
            <?php else: foreach ($list as $r): ?>
              <tr>
                <td class="text-center"><?= esc($r['tanggal'] ?? '-') ?></td>
                <td><?= esc($r['username'] ?? '-') ?></td>
                <td><?= esc($r['email'] ?? '-') ?></td>
                <td><?= esc($r['kategori'] ?? '-') ?></td>
                <td><?= esc($r['deskripsi'] ?? '-') ?></td>
                <td class="text-right font-weight-bold">¥<?= number_format($r['jumlah'] ?? 0, 0) ?></td>
                <td class="text-center">
                  <?php if ($r['jenis'] == 'in'): ?>
                    <span class="badge badge-success px-2 py-1">Masuk</span>
                  <?php else: ?>
                    <span class="badge badge-danger px-2 py-1">Keluar</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-end mt-3">
        <?= $pager->links('transaksi', 'bootstrap_full'); ?>
      </div>

    </div>
  </div>

</div>

<?= $this->endSection(); ?>