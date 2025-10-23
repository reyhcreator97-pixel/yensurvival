<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">

  <!-- Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Dashboard</h1>
  </div>

  <!-- Summary Cards -->
  <div class="row">

    <!-- Total Users -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_users ?? 0) ?></div>
        </div>
      </div>
    </div>

    <!-- Active Subs -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Subs</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($active_subscriptions ?? 0) ?></div>
        </div>
      </div>
    </div>

    <!-- Expired Subs -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Expired Subs</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($expired_subscriptions ?? 0) ?></div>
        </div>
      </div>
    </div>

    <!-- Total Income -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Income</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">
            ¥<?= number_format($total_income ?? 0, 0, ',', '.') ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Langganan Terbaru -->
  <div class="card shadow mb-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
      <h6 class="m-0 font-weight-bold text-primary">Langganan Terbaru</h6>
      <a href="<?= site_url('admin/subscription') ?>" class="btn btn-sm btn-primary">
        <i class="fas fa-eye"></i> Lihat Semua
      </a>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-sm align-middle mb-0">
          <thead class="thead-light">
            <tr class="text-center">
              <th>User</th>
              <th>Email</th>
              <th>Plan</th>
              <th>Status</th>
              <th>Mulai</th>
              <th>Berakhir</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($latest_subs)): ?>
              <?php foreach ($latest_subs as $r): ?>
                <tr class="text-center">
                  <td><?= esc($r['username'] ?? '-') ?></td>
                  <td><?= esc($r['email'] ?? '-') ?></td>
                  <td class="text-capitalize text-center"><?= esc($r['plan_type'] ?? '-') ?></td>
                  <td class="text-center">
                    <span class="badge badge-<?= $r['status']=='active' ? 'success' : ($r['status']=='expired' ? 'danger' : 'warning') ?>">
                      <?= esc($r['status'] ?? '-') ?>
                    </span>
                  </td>
                  <td class="text-center"><?= esc($r['start_date'] ?? '-') ?></td>
                  <td class="text-center"><?= esc($r['end_date'] ?? '-') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-muted py-3">Belum ada data langganan</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<?= $this->endSection(); ?>