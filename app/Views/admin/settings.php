<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800">Pengaturan Sistem</h1>
  </div>

  <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
      <?= esc(session('message')) ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php endif; ?>

  <div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
      <h6 class="m-0 font-weight-bold">Konfigurasi Global</h6>
    </div>
    <form action="<?= site_url('admin/settings/save') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $config['id'] ?? 0 ?>">

      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-3">
            <label>Mata Uang</label>
            <input type="text" name="currency" value="<?= esc($config['currency']) ?>" class="form-control" maxlength="5" required>
          </div>

          <div class="form-group col-md-4">
            <label>Harga Langganan Bulanan (<?= esc($config['currency']) ?>)</label>
            <input type="number" name="price_monthly" value="<?= esc($config['price_monthly']) ?>" class="form-control" required>
          </div>

          <div class="form-group col-md-4">
            <label>Harga Langganan Tahunan (<?= esc($config['currency']) ?>)</label>
            <input type="number" name="price_yearly" value="<?= esc($config['price_yearly']) ?>" class="form-control" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label>Jadwal Backup Otomatis</label>
            <select name="backup_schedule" class="form-control">
              <option value="daily" <?= $config['backup_schedule']=='daily'?'selected':'' ?>>Harian</option>
              <option value="weekly" <?= $config['backup_schedule']=='weekly'?'selected':'' ?>>Mingguan</option>
              <option value="monthly" <?= $config['backup_schedule']=='monthly'?'selected':'' ?>>Bulanan</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label>Kontak Admin (WhatsApp)</label>
            <input type="text" name="contact_whatsapp" value="<?= esc($config['contact_whatsapp']) ?>" class="form-control" placeholder="contoh: +628123456789">
          </div>
        </div>

      </div>
      <div class="card-footer d-flex justify-content-between">
        <button type="submit" class="btn btn-primary px-4">Simpan Pengaturan</button>
        <a href="<?= site_url('admin/settings/backup') ?>" class="btn btn-outline-secondary">Backup Database Sekarang</a>
      </div>
    </form>
  </div>

</div>

<?= $this->endSection(); ?>