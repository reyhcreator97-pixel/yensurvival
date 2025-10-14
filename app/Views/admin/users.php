<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kelola Pengguna</h1>
  </div>

  <!-- Alert -->
  <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
      <?= session('message') ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php elseif (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
      <?= session('error') ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php endif; ?>

  <!-- Filter & Search -->
  <form method="get" class="mb-3">
    <div class="form-row align-items-end">
      <div class="col-md-3">
        <label>Status</label>
        <select name="status" class="form-control">
          <option value="">Semua</option>
          <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>Aktif</option>
          <option value="suspend" <?= $status == 'suspend' ? 'selected' : '' ?>>Suspend</option>
        </select>
      </div>
      <div class="col-md-4">
        <label>Cari</label>
        <input type="text" name="search" class="form-control"
               placeholder="Cari username atau email..."
               value="<?= esc($search) ?>">
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary btn-block">
          <i class="fas fa-search"></i> Filter
        </button>
      </div>
      <div class="col-md-2">
        <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary btn-block">
          <i class="fas fa-sync-alt"></i> Reset
        </a>
      </div>
    </div>
  </form>

  <!-- Table -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna Premium</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered">
          <thead class="thead-light">
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Email</th>
              <th>Status</th>
              <th>Dibuat</th>
              <th>Terakhir Update</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($list)): ?>
              <tr><td colspan="7" class="text-center text-muted">Tidak ada data pengguna ditemukan.</td></tr>
            <?php else: foreach ($list as $r): ?>
              <tr>
                <td><?= esc($r['id']) ?></td>
                <td><?= esc($r['username']) ?></td>
                <td><?= esc($r['email']) ?></td>
                <td>
                  <?php if ($r['active']): ?>
                    <span class="badge badge-success">Aktif</span>
                  <?php else: ?>
                    <span class="badge badge-danger">Suspend</span>
                  <?php endif; ?>
                </td>
                <td><?= esc(date('Y-m-d', strtotime($r['created_at']))) ?></td>
                <td><?= esc(date('Y-m-d', strtotime($r['updated_at']))) ?></td>
                <td class="text-center">
                  <?php if ($r['active']): ?>
                    <a href="<?= site_url('admin/users/suspend/'.$r['id']) ?>"
                       class="btn btn-sm btn-warning"
                       onclick="return confirm('Suspend user ini?')">Suspend</a>
                  <?php else: ?>
                    <a href="<?= site_url('admin/users/activate/'.$r['id']) ?>"
                       class="btn btn-sm btn-success"
                       onclick="return confirm('Aktifkan user ini?')">Aktifkan</a>
                  <?php endif; ?>

                  <a href="<?= site_url('admin/users/resetPassword/'.$r['id']) ?>"
                     class="btn btn-sm btn-info"
                     onclick="return confirm('Reset password user ini?')">Reset</a>

                  <a href="<?= site_url('admin/users/delete/'.$r['id']) ?>"
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('Hapus user ini?')">Hapus</a>
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
