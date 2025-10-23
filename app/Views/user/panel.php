<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">

  <!-- Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800 font-weight-bold">User Panel</h1>
  </div>

  <div class="row">
    <!-- Card: Data User -->
    <div class="col-lg-5">
      <div class="card shadow mb-4 border-left-primary">
        <div class="card-header bg-primary text-white">
          <h6 class="m-0 font-weight-bold">Data User</h6>
        </div>
        <div class="card-body">
          <p><strong>Username:</strong> <?= esc($user->username) ?></p>
          <p><strong>Email:</strong> <?= esc($user->email) ?></p>
          <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#changePasswordModal">
            <i class="fas fa-key"></i> Ganti Password
          </button>
        </div>
      </div>
    </div>

    <!-- Card: Subscription -->
    <div class="col-lg-7">
      <div class="card shadow mb-4 border-left-success">
        <div class="card-header bg-success text-white">
          <h6 class="m-0 font-weight-bold">Status Subscription</h6>
        </div>
        <div class="card-body">
          <?php if ($subscription): ?>
            <p><strong>Plan:</strong> <?= ucfirst($subscription['plan_type']) ?></p>
            <p><strong>Mulai:</strong> <?= esc($subscription['start_date']) ?></p>
            <p><strong>Berakhir:</strong> <?= esc($subscription['end_date']) ?></p>
            
            <p><strong>Status:</strong>
              <?php if ($subscription['status'] === 'active'): ?>
                <span class="badge badge-success">Aktif</span>
              <?php elseif ($subscription['status'] === 'pending'): ?>
                <span class="badge badge-warning">Menunggu Konfirmasi Admin</span>
              <?php else: ?>
                <span class="badge badge-secondary">Tidak Aktif</span>
              <?php endif; ?>
            </p>

            <?php if ($subscription['status'] === 'active'): ?>
              <div class="mt-3">
                <a href="<?= site_url('user/subscription') ?>" class="btn btn-primary btn-sm">
                  <i class="fas fa-sync-alt"></i> Perpanjang
                </a>
                <a href="<?= site_url('user/subscription') ?>" class="btn btn-warning btn-sm">
                  <i class="fas fa-level-up-alt"></i> Upgrade
                </a>
              </div>
            <?php elseif ($subscription['status'] === 'pending'): ?>
              <p class="text-muted mt-3 mb-0">
                Pembayaran kamu sedang diverifikasi oleh admin.<br>
                Tombol akan aktif setelah langganan disetujui.
              </p>
            <?php endif; ?>

          <?php else: ?>
            <div class="text-center py-3">
              <span class="text-muted d-block mb-2">Kamu belum berlangganan.</span>
              <a href="<?= site_url('user/subscription') ?>" class="btn btn-success btn-sm">
                <i class="fas fa-play"></i> Langganan Sekarang
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Modal: Ganti Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="changePasswordLabel"><i class="fas fa-key"></i> Ganti Password</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('user/panel/changePassword') ?>" method="post">
        <?= csrf_field() ?>
        <div class="modal-body">
          <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="new_password" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>