<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Daftar Subscription</h4>
  </div>

  <div class="card shadow mb-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
      <h6 class="m-0 font-weight-bold text-primary">Details Users</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover">
          <thead class="thead-light">
            <tr>
              <th class="text-center">User</th>
              <th class="text-center">Plan</th>
              <th class="text-center">Status</th>
              <th class="text-center">Start</th>
              <th class="text-center">End</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($subs)): ?>
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada data subscription.</td>
              </tr>
              <?php else: foreach ($subs as $r): ?>
                <tr class="text-center">
                  <td><?= esc($r['username']) ?> <br><small><?= esc($r['email']) ?></small></td>
                  <td><?= ucfirst($r['plan_type']) ?></td>
                  <td>
                    <?php if ($r['status'] == 'active'): ?>
                      <span class="badge badge-success">Active</span>
                    <?php elseif ($r['status'] == 'expired'): ?>
                      <span class="badge badge-danger">Expired</span>
                    <?php elseif ($r['status'] == 'pending'): ?>
                      <span class="badge badge-warning">Pending</span>
                    <?php else: ?>
                      <span class="badge badge-danger">Canceled</span>
                    <?php endif; ?>
                  </td>
                  <td><?= esc($r['start_date']) ?></td>
                  <td><?= esc($r['end_date']) ?></td>
                  <td>
                    <a href="<?= site_url('admin/subscription/edit/' . $r['id']) ?>" class="btn btn-sm btn-primary">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <?php if ($r['status'] != 'active'): ?>
                      <a href="javascript:void(0)" onclick="confirmActivate('<?= site_url('admin/subscription/activate/' . $r['id']) ?>')"
                        class="btn btn-success btn-sm">
                        <i class="fas fa-user-check"></i> Aktifkan
                      </a>
                    <?php endif; ?>
                    <?php if ($r['status'] == 'active'): ?>
                      <a href="javascript:void(0)" onclick="confirmCancelSub('<?= site_url('admin/subscription/cancel/' . $r['id']) ?>')"
                        class="btn btn-danger btn-sm">
                        <i class="fas fa-times-circle"></i> Batalkan
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
            <?php endforeach;
            endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection(); ?>