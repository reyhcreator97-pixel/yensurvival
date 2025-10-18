<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">

  <!-- Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 text-gray-800"><i class="fas fa-clipboard-list"></i> Log Aktivitas</h1>
  </div>
<!-- Filter -->
<form method="get" class="card shadow-sm border-0 mb-4 p-3 bg-light">
  <div class="form-row align-items-end">
    <div class="col-md-4 mb-2">
      <label><i class="fas fa-tasks"></i> Aksi</label>
      <select name="action" class="form-control">
        <option value="">-- Semua Aksi --</option>
        <?php foreach ($actions as $a): ?>
          <option value="<?= esc($a['action']) ?>" <?= ($action ?? '') == $a['action'] ? 'selected' : '' ?>>
            <?= esc(ucfirst($a['action'])) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3 mb-2">
      <label><i class="fas fa-calendar-alt"></i> Tanggal</label>
      <input type="date" name="tanggal" class="form-control" value="<?= esc($tanggal ?? '') ?>">
    </div>

    <div class="col-md-2 mb-2">
      <button type="submit" class="btn btn-primary btn-block">
        <i class="fas fa-search"></i> Filter
      </button>
    </div>

    <div class="col-md-2 mb-2">
      <a href="<?= site_url('admin/logs') ?>" class="btn btn-outline-secondary btn-block">
        <i class="fas fa-sync-alt"></i> Reset
      </a>
    </div>
  </div>
</form>
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