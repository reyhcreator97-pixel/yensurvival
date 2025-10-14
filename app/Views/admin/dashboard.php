<?= $this->extend('templates/Admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard Admin</h1>
  </div>

  <div class="row">

    <!-- Total User -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total Pengguna
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($totalUser ?? 0) ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Subscription -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Subscription Aktif
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($totalSub ?? 0) ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-crown fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Transaksi -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Total Transaksi
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($totalTrx ?? 0) ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-coins fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Ringkasan Aktivitas</h6>
    </div>
    <div class="card-body">
      <p>Selamat datang di panel admin. Gunakan menu di sebelah kiri untuk mengelola pengguna, langganan, transaksi, dan pengaturan sistem.</p>
    </div>
  </div>

</div>

<?= $this->endSection(); ?>
