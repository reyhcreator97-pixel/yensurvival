<?= $this->extend('templates/index_checkout'); ?>
<?= $this->section('page-content'); ?>

<style>
body { background: #f4f5f7; }
.thankyou-card {
  background: #fff; border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  padding: 30px; max-width: 600px;
  margin: 60px auto; text-align: center;
}
.btn-whatsapp {
  background: #25D366; color: #fff; border: none;
  border-radius: 6px; padding: 10px 18px;
}
</style>

<div class="thankyou-card">
  <h4 class="font-weight-bold mb-3">Terima Kasih!</h4>
  <p>Silakan transfer pembayaran kamu sebelum:</p>
  <h5 id="countdown" class="text-danger font-weight-bold mb-4"></h5>

  <div class="text-left">
    <h6>Detail Pembayaran:</h6>
    <p><strong>Plan:</strong> <?= ucfirst($checkout['plan_type']) ?></p>
    <p><strong>Total:</strong> <?= $checkout['country'] === 'japan' ? '¥'.$checkout['price'] : 'Rp'.$checkout['priceIDR'] ?></p>
    <p><strong>Nomor Rekening:</strong>
      <?= $checkout['country'] === 'japan' ? 'Mitsui Bank: 123-456-789' : 'BCA: 123-456-7890' ?>
    </p>
  </div>

  <a href="<?= $waUrl ?>" target="_blank" class="btn btn-whatsapp mt-3">
    <i class="fab fa-whatsapp"></i> Konfirmasi Pembayaran
  </a>
</div>

<script>
  // Countdown 24 jam
  let target = new Date();
  target.setHours(target.getHours() + 24);
  let x = setInterval(() => {
    let now = new Date().getTime();
    let dist = target - now;
    if (dist <= 0) {
      clearInterval(x);
      document.getElementById("countdown").innerHTML = "Waktu habis";
    } else {
      let h = Math.floor((dist % (1000*60*60*24)) / (1000*60*60));
      let m = Math.floor((dist % (1000*60*60)) / (1000*60));
      let s = Math.floor((dist % (1000*60)) / 1000);
      document.getElementById("countdown").innerHTML = ${h}j ${m}m ${s}d;
    }
  }, 1000);
</script>

<?= $this->endSection(); ?>