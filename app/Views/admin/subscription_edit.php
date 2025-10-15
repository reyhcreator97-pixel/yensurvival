<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">
  <h4 class="mb-4">Edit Subscription</h4>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="<?= site_url('admin/subscription/update/'.$sub['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
          <label>Plan Type</label>
          <select name="plan_type" class="form-control">
            <option value="monthly" <?= $sub['plan_type'] == 'monthly' ? 'selected' : '' ?>>Monthly</option>
            <option value="yearly" <?= $sub['plan_type'] == 'yearly' ? 'selected' : '' ?>>Yearly</option>
          </select>
        </div>

        <div class="form-group">
          <label>Status</label>
          <select name="status" class="form-control">
            <option value="active" <?= $sub['status'] == 'active' ? 'selected' : '' ?>>Active</option>
            <option value="expired" <?= $sub['status'] == 'expired' ? 'selected' : '' ?>>Expired</option>
            <option value="canceled" <?= $sub['status'] == 'canceled' ? 'selected' : '' ?>>Canceled</option>
          </select>
        </div>

        <div class="form-group">
          <label>End Date</label>
          <input type="date" name="end_date" class="form-control" value="<?= esc($sub['end_date']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="<?= site_url('admin/subscription') ?>" class="btn btn-secondary">Kembali</a>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>