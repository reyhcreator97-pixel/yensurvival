<?= $this->extend('templates/index_checkout'); ?>
<?= $this->section('page-content'); ?>

<style>
body { background: #f4f5f7; }
.checkout-card {
  background: #fff; border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  padding: 30px; max-width: 600px;
  margin: 40px auto;
}
.btn-primary {
  background: #007bff; border: none; border-radius: 6px;
}
.form-control { border-radius: 6px; }
</style>

<div class="checkout-card">
  <h4 class="mb-3 text-center font-weight-bold">Checkout Subscription</h4>
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5><?= esc($plan_type) ?> Plan</h5>
    <h5 id="priceDisplay" class="text-primary">
  <?= ($country === 'japan') ? '¥'.number_format($priceYen) : 'Rp'.number_format($priceIDR) ?>
</h5>
  </div>
  <small class="text-muted d-block mb-4">
    Kurs DCOM (<?= date('d M Y') ?>): <?= $kursText ?>
  </small>

  <form action="<?= site_url('checkout-form/process') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="plan_type" value="<?= esc($plan_type) ?>">
    <input type="hidden" name="country" value="<?= esc($country) ?>">
    <input type="hidden" name="price" value="<?= $priceYen ?>">
    <input type="hidden" name="priceIDR" value="<?= $priceIDR ?>">

    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Metode Pembayaran</label>
      <select name="country" class="form-control" required>
        <option value="japan" <?= $country=='japan'?'selected':'' ?>>Bank Jepang</option>
        <option value="indonesia" <?= $country=='indonesia'?'selected':'' ?>>Bank Indonesia</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary btn-block py-2">BAYAR SEKARANG</button>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const selectCountry = document.querySelector('select[name="country"]');
  const priceDisplay = document.querySelector('#priceDisplay');
  const priceYen = <?= $priceYen ?>;
  const kurs = <?= $kurs ?>;
  const priceIDR = Math.round(priceYen * kurs);

  selectCountry.addEventListener('change', function() {
    if (this.value === 'indonesia') {
      priceDisplay.innerHTML = 'Rp ' + priceIDR.toLocaleString('id-ID');
    } else {
      priceDisplay.innerHTML = '¥' + priceYen.toLocaleString('ja-JP');
    }
  });
});
</script>
<?= $this->endSection(); ?>