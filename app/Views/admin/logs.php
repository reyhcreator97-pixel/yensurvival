<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">

  <!-- Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800"><i class="fas fa-clipboard-list"></i> Log Aktivitas</h1>
  </div>

  <!-- Card -->
  <div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
      <strong><i class="fas fa-history"></i> Riwayat Aktivitas Sistem</strong>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover" width="100%">
          <thead class="thead-light">
            <tr class="text-center">
              <th width="130">Tanggal</th>
              <th>User ID</th>
              <th>Role</th>
              <th>Aksi</th>
              <th>Deskripsi</th>
              <th>IP</th>
              <th>Browser</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($logs)): ?>
              <tr>
                <td colspan="7" class="text-center text-muted py-3">
                  Belum ada log aktivitas.
                </td>
              </tr>
            <?php else: foreach ($logs as $log): ?>
              <tr>
                <td class="text-center"><?= date('Y-m-d H:i', strtotime($log['created_at'])) ?></td>
                <td class="text-center"><?= esc($log['user_id']) ?></td>
                <td class="text-center"><span class="badge badge-info"><?= esc($log['role'] ?? '-') ?></span></td>
                <td><?= esc($log['action']) ?></td>
                <td><?= esc($log['description'] ?? '-') ?></td>
                <td class="text-center"><?= esc($log['ip_address'] ?? '-') ?></td>
                <td><?= esc($log['user_agent'] ?? '-') ?></td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-3">
        <?= $pager->links('logs', 'bootstrap_full'); ?>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>