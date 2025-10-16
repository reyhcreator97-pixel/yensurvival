<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">

  <!-- Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800">Log Aktivitas</h1>
  </div>

  <div class="card shadow mb-4">
    <div class="card-header bg-dark text-white">
      <h6 class="m-0 font-weight-bold">Riwayat Aktivitas Sistem</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-hover" width="100%" cellspacing="0">
          <thead class="thead-light">
            <tr class="text-center">
              <th>Waktu</th>
              <th>User</th>
              <th>Role</th>
              <th>Aksi</th>
              <th>Deskripsi</th>
              <th>IP</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($logs)): ?>
              <?php foreach ($logs as $r): ?>
                <tr class="text-center">
                  <td><?= date('Y-m-d H:i', strtotime($r['created_at'])) ?></td>
                  <td><?= esc($r['user_id']) ?></td>
                  <td><?= esc($r['role'] ?? '-') ?></td>
                  <td><span class="badge badge-info"><?= esc($r['action']) ?></span></td>
                  <td><?= esc($r['description']) ?></td>
                  <td><?= esc($r['ip_address']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="6" class="text-center text-muted">Belum ada aktivitas tercatat.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<?= $this->endSection(); ?>