<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">

  <!-- Header -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800 font-weight-bold">Pengaturan Sistem</h1>
  </div>

  <!-- Flash Message -->
  <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
      <i class="fas fa-check-circle mr-2"></i><?= esc(session('message')) ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php endif; ?>

  <!-- Card Settings -->
  <div class="card shadow mb-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
      <h6 class="m-0 font-weight-bold text-primary">Konfigurasi Global</h6>
    </div>

    <form action="<?= site_url('admin/settings/save') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $config['id'] ?? 0 ?>">

      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-3">
            <label class="font-weight-bold text-gray-700">Mata Uang</label>
            <input type="text" name="currency" value="<?= esc($config['currency']) ?>" class="form-control" maxlength="5" required>
          </div>

          <div class="form-group col-md-4">
            <label class="font-weight-bold text-gray-700">Harga Langganan Bulanan (<?= esc($config['currency']) ?>)</label>
            <input type="number" name="price_monthly" value="<?= esc($config['price_monthly']) ?>" class="form-control" required>
          </div>

          <div class="form-group col-md-4">
            <label class="font-weight-bold text-gray-700">Harga Langganan Tahunan (<?= esc($config['currency']) ?>)</label>
            <input type="number" name="price_yearly" value="<?= esc($config['price_yearly']) ?>" class="form-control" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label class="font-weight-bold text-gray-700">Jadwal Backup Otomatis</label>
            <select name="backup_schedule" class="form-control">
              <option value="daily" <?= $config['backup_schedule']=='daily'?'selected':'' ?>>Harian</option>
              <option value="weekly" <?= $config['backup_schedule']=='weekly'?'selected':'' ?>>Mingguan</option>
              <option value="monthly" <?= $config['backup_schedule']=='monthly'?'selected':'' ?>>Bulanan</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="font-weight-bold text-gray-700">Kontak Admin (WhatsApp)</label>
            <input type="text" name="contact_whatsapp" value="<?= esc($config['contact_whatsapp']) ?>" class="form-control" placeholder="contoh: +628123456789">
          </div>
        </div>
      </div>

      <div class="card-footer bg-light border-top d-flex justify-content-between">
        <button type="submit" class="btn btn-primary px-4">
          <i class="fas fa-save mr-1"></i> Simpan Pengaturan
        </button>
        <a href="<?= site_url('admin/settings/backup') ?>" class="btn btn-primary px-4">
          <i class="fas fa-hdd mr-1"></i> Backup Sekarang
        </a>
      </div>
    </form>
  </div>

</div>

<?= $this->endSection(); ?>