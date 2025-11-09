<?= $this->extend('templates/index_checkout'); ?>
<?= $this->section('page-content'); ?>

<style>
  body {
    background: linear-gradient(180deg, #f9fafc 0%, #eef1f5 100%);
    font-family: 'Poppins', sans-serif;
  }

  .checkout-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    padding: 40px 30px;
    max-width: 620px;
    margin: 60px auto;
    position: relative;
    overflow: hidden;
  }

  .checkout-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 6px;
    background: linear-gradient(90deg, #007bff, #00aaff);
  }

  h4 {
    font-weight: 600;
    color: #1e1e2f;
  }

  h5 {
    color: #1e3a8a;
  }

  small.text-muted {
    font-size: 13px;
  }

  .form-control {
    border-radius: 8px;
    border-color: #d0d7de;
    transition: 0.3s;
  }

  .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .15);
  }

  .btn-primary {
    background: linear-gradient(90deg, #007bff, #00aaff);
    border: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all .3s ease;
    letter-spacing: .5px;
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 123, 255, 0.3);
  }

  .price-box {
    background: #f1f5fa;
    border-radius: 8px;
    padding: 10px 15px;
    font-weight: 600;
  }

  @media (max-width: 576px) {
    .checkout-card {
      margin: 20px 15px;
      padding: 25px 20px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    h4 {
      font-size: 1.25rem;
    }

    .price-box {
      flex-direction: row !important;
      justify-content: space-between;
      align-items: center !important;
    }

    .price-box span {
      display: inline-block;
      font-size: 1rem;
    }

    #priceDisplay {
      font-size: 1.1rem;
      margin-top: 0 !important;
    }

    .btn-primary {
      font-size: 0.95rem;
      padding: 10px 0;
    }
  }

  .checkout-logo {
    width: 300px;
    height: auto;
    margin-top: -10px;
    margin-bottom: 10px;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.15));
    animation: fadeDown 0.6s ease-out;
  }

  @keyframes fadeDown {
    from {
      opacity: 0;
      transform: translateY(-15px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @media (max-width: 576px) {
    .checkout-logo {
      width: 150px;
      margin-bottom: 5px;
    }
  }
</style>

<div class="checkout-card">
  <div class="text-center mb-3">
    <img src="<?= base_url('img/yensurvival3.png') ?>"
      alt="YEN Survival Logo"
      class="checkout-logo">
  </div>
  <h4 class="mb-4 text-center">Checkout Subscription</h4>

  <div class="d-flex justify-content-between align-items-center mb-3 price-box">
    <span><?= esc($plan_type) ?> Plan</span>
    <span id="priceDisplay" class="text-primary">
      <?= ($country === 'japan') ? '¥' . number_format($priceYen) : 'Rp' . number_format($priceIDR) ?>
    </span>
  </div>

  <small class="text-muted d-block mb-4 text-center">
    Kurs DCOM (<?= date('d M Y') ?>): <?= $kursText ?>
  </small>

  <form action="<?= site_url('checkout-form/process') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="plan_type" value="<?= esc($plan_type) ?>">
    <input type="hidden" name="country" value="<?= esc($country) ?>">
    <input type="hidden" name="price" value="<?= $priceYen ?>">
    <input type="hidden" name="priceIDR" value="<?= $priceIDR ?>">

    <div class="form-group">
      <label class="font-weight-semibold">Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>

    <div class="form-group">
      <label class="font-weight-semibold">Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="form-group">
      <label class="font-weight-semibold">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="form-group">
      <label class="font-weight-semibold">Metode Pembayaran</label>
      <select name="country" class="form-control" required>
        <option value="japan" <?= $country == 'japan' ? 'selected' : '' ?>>Bank Jepang</option>
        <option value="indonesia" <?= $country == 'indonesia' ? 'selected' : '' ?>>Bank Indonesia</option>
      </select>
    </div>

    <!-- ✅ FIELD BARU: Kupon Promo -->
    <div class="form-group">
      <label class="font-weight-semibold">Kupon Promo (Opsional)</label>
      <div class="input-group">
        <input type="text" name="coupon_code" class="form-control" placeholder="Masukkan kode kupon">
        <div class="input-group-append">
          <button type="button" class="btn btn-outline-primary" id="checkCoupon">Cek Kupon</button>
        </div>
      </div>
      <small id="couponFeedback" class="form-text text-success d-none"></small>
    </div>

    <button type="submit" class="btn btn-primary btn-block py-2 mt-3">
      BAYAR SEKARANG
    </button>
  </form>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const selectCountry = document.querySelector('select[name="country"]');
    const priceDisplay = document.querySelector('#priceDisplay');
    const priceYen = <?= $priceYen ?>;
    const kurs = <?= $kurs ?>;
    const priceIDR = Math.round(priceYen * kurs);

    let currentPriceYen = priceYen;
    let currentPriceIDR = priceIDR;

    selectCountry.addEventListener('change', function() {
      updateDisplay();
    });

    function updateDisplay() {
      if (selectCountry.value === 'indonesia') {
        priceDisplay.innerHTML = 'Rp ' + currentPriceIDR.toLocaleString('id-ID');
        document.querySelector('input[name="price"]').value = currentPriceIDR;
      } else {
        priceDisplay.innerHTML = '¥' + currentPriceYen.toLocaleString('ja-JP');
        document.querySelector('input[name="price"]').value = currentPriceYen;
      }
    }

    // ✅ Script Kupon Promo (update harga langsung)
    document.getElementById('checkCoupon').addEventListener('click', function() {
      const code = document.querySelector('input[name="coupon_code"]').value.trim();
      if (!code) return alert('Masukkan kode kupon!');
      fetch('<?= site_url("coupon/check") ?>?code=' + code)
        .then(r => r.json())
        .then(data => {
          const fb = document.getElementById('couponFeedback');
          fb.classList.remove('d-none', 'text-danger', 'text-success');
          if (data.status === 'success') {
            fb.classList.add('text-success');
            fb.innerText = `Kupon valid (${data.used_count}/${data.max_usage || '∞'}) - Diskon ${data.label}`;

            // Hitung diskon langsung
            if (data.jenis === 'percent') {
              currentPriceYen = priceYen - (priceYen * data.nilai / 100);
              currentPriceIDR = priceIDR - (priceIDR * data.nilai / 100);
            } else {
              currentPriceYen = priceYen - data.nilai;
              currentPriceIDR = priceIDR - (data.nilai * kurs);
            }
            updateDisplay();

            // Simpan kupon aktif di hidden input
            if (!document.querySelector('input[name="applied_coupon"]')) {
              const hidden = document.createElement('input');
              hidden.type = 'hidden';
              hidden.name = 'applied_coupon';
              hidden.value = code;
              document.querySelector('form').appendChild(hidden);
            } else {
              document.querySelector('input[name="applied_coupon"]').value = code;
            }

          } else {
            fb.classList.add('text-danger');
            fb.innerText = data.message || 'Kupon tidak valid.';
          }
        })
        .catch(() => alert('Gagal memeriksa kupon, coba lagi.'));
    });
  });
</script>

<?= $this->endSection(); ?>