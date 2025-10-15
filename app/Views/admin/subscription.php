<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Daftar Subscription</h4>
  </div>

  <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success"><?= esc(session('message')) ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover">
          <thead class="thead-light">
            <tr>
              <th>User</th>
              <th>Plan</th>
              <th>Status</th>
              <th>Start</th>
              <th>End</th>
              <th class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($subs)): ?>
              <tr><td colspan="6" class="text-center text-muted">Belum ada data subscription.</td></tr>
            <?php else: foreach ($subs as $r): ?>
              <tr>
                <td><?= esc($r['username']) ?> <br><small><?= esc($r['email']) ?></small></td>
                <td><?= ucfirst($r['plan_type']) ?></td>
                <td>
                  <?php if ($r['status'] == 'active'): ?>
                    <span class="badge badge-success">Active</span>
                  <?php elseif ($r['status'] == 'expired'): ?>
                    <span class="badge badge-warning">Expired</span>
                  <?php else: ?>
                    <span class="badge badge-danger">Canceled</span>
                  <?php endif; ?>
                </td>
                <td><?= esc($r['start_date']) ?></td>
                <td><?= esc($r['end_date']) ?></td>
                <td class="text-right">
                  <a href="<?= site_url('admin/subscription/edit/'.$r['id']) ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <?php if ($r['status'] != 'active'): ?>
                    <a href="<?= site_url('admin/subscription/activate/'.$r['id']) ?>" class="btn btn-sm btn-success">Aktifkan</a>
                  <?php endif; ?>
                  <?php if ($r['status'] == 'active'): ?>
                    <a href="<?= site_url('admin/subscription/cancel/'.$r['id']) ?>" class="btn btn-sm btn-danger">Batalkan</a>
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