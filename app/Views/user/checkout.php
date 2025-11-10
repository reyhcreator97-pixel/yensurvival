<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">
  <h4 class="mb-4 font-weight-bold text-primary">Checkout Subscription</h4>

  <div class="card shadow">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6 border-right">
          <h5 class="font-weight-bold mb-3">Rincian Langganan</h5>
          <p><strong>Plan:</strong> <?= esc($plan) ?></p>
          <p><strong>Durasi:</strong> <?= esc($duration) ?> hari</p>
          <p><strong>Harga:</strong>
            <span id="currencySymbol"><?= esc($currency) ?></span>
            <span id="priceValue"><?= number_format($price) ?></span>
          </p>
          <p class="text-muted small">
            (Kurs DCOM: 1 JPY = Rp <?= rtrim(rtrim(number_format($kurs, 2, '.', ''), '0'), '.') ?>)
          </p>

          <hr>

          <!-- ✅ Input Kupon Promo -->
          <div class="form-group mt-3">
            <label class="font-weight-bold">Kupon Promo (Opsional)</label>
            <div class="input-group">
              <input type="text" id="coupon_code" class="form-control" placeholder="Masukkan kode kupon">
              <div class="input-group-append">
                <button type="button" id="checkCoupon" class="btn btn-outline-primary">Cek Kupon</button>
              </div>
            </div>
            <small id="couponFeedback" class="form-text text-success d-none"></small>
          </div>

          <input type="hidden" id="applied_coupon" value="">

          <div class="form-group mt-4">
            <label class="font-weight-bold">Pilih Negara Pembayaran</label>
            <select class="form-control" id="countrySelect" required>
              <option value="">-- Pilih Negara --</option>
              <option value="japan">Jepang</option>
              <option value="indonesia">Indonesia</option>
            </select>
          </div>

          <div id="paymentInfo" class="mt-3" style="display:none;">
            <h6 class="font-weight-bold">Informasi Pembayaran</h6>
            <div id="japanAccount" class="d-none">
              <p>🏦 <strong>Bank:</strong> Mizuho Bank</p>
              <p><strong>Nama:</strong> Rey Creator</p>
              <p><strong>No. Rekening:</strong> 1234567890</p>
            </div>

            <div id="indoAccount" class="d-none">
              <p>🏦 <strong>Bank:</strong> BCA</p>
              <p><strong>Nama:</strong> Rey Creator</p>
              <p><strong>No. Rekening:</strong> 9876543210</p>
            </div>
          </div>
        </div>

        <div class="col-md-6 text-center d-flex flex-column justify-content-center">
          <h5 class="font-weight-bold mb-3">Proses Pembelian</h5>
          <p>Setelah memilih negara pembayaran, klik tombol di bawah untuk membuat pesanan.</p>

          <a href="#" id="buyBtn" class="btn btn-primary px-4 py-2 disabled">
            <i class="fas fa-shopping-cart"></i> Beli Sekarang
          </a>

          <a href="<?= site_url('user/subscription'); ?>" class="btn btn-secondary mt-2 px-4">Batal</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const select = document.getElementById('countrySelect');
    const info = document.getElementById('paymentInfo');
    const japan = document.getElementById('japanAccount');
    const indo = document.getElementById('indoAccount');
    const buyBtn = document.getElementById('buyBtn');
    const priceEl = document.getElementById('priceValue');
    const symbolEl = document.getElementById('currencySymbol');
    const appliedCoupon = document.getElementById('applied_coupon');

    const kurs = <?= $kurs ?>;
    const originalPrice = <?= $price ?>;
    let currentPrice = originalPrice;
    let lastDiskon = 0;
    let kuponValid = false;
    let currentCoupon = ''; // 🧩 simpan kupon aktif

    // format helper
    const formatYen = (n) => '¥ ' + parseInt(n).toLocaleString('ja-JP', {
      minimumFractionDigits: 0
    });
    const formatRp = (n) => 'Rp ' + parseInt(n).toLocaleString('id-ID', {
      minimumFractionDigits: 0
    });

    // update harga
    function updatePriceDisplay(country) {
      if (country === 'indonesia') {
        priceEl.textContent = parseInt(currentPrice * kurs).toLocaleString('id-ID', {
          minimumFractionDigits: 0
        });
        symbolEl.textContent = 'Rp';
      } else {
        priceEl.textContent = parseInt(currentPrice).toLocaleString('ja-JP', {
          minimumFractionDigits: 0
        });
        symbolEl.textContent = '¥';
      }

      // 🧩 update feedback kupon sesuai negara
      const fb = document.getElementById('couponFeedback');
      if (kuponValid && fb) {
        const labelDiskon = (country === 'indonesia') ?
          formatRp(lastDiskon * kurs) :
          formatYen(lastDiskon);
        fb.innerText = `Kupon valid - Diskon ${labelDiskon}`;
      }

      // 🧩 update link beli sesuai negara + kupon
      const baseHref = "<?= site_url('user/subscription/buy/' . strtolower($plan)) ?>";
      const couponParam = currentCoupon ? `?coupon=${encodeURIComponent(currentCoupon)}` : '';
      buyBtn.href = baseHref + couponParam;
    }

    // perubahan negara
    select.addEventListener('change', function() {
      info.style.display = 'block';
      japan.classList.add('d-none');
      indo.classList.add('d-none');

      if (this.value === 'japan') japan.classList.remove('d-none');
      if (this.value === 'indonesia') indo.classList.remove('d-none');

      buyBtn.classList.remove('disabled');
      updatePriceDisplay(this.value);
    });

    // cek kupon
    document.getElementById('checkCoupon').addEventListener('click', function() {
      const code = document.getElementById('coupon_code').value.trim();
      const fb = document.getElementById('couponFeedback');

      if (!code) {
        Swal.fire({
          icon: 'warning',
          title: 'Oops!',
          text: 'Masukkan kode kupon terlebih dahulu.',
          confirmButtonColor: '#007bff'
        });
        return;
      }

      fetch('<?= site_url("coupon/check") ?>?code=' + code)
        .then(r => r.json())
        .then(data => {
          fb.classList.remove('d-none', 'text-danger', 'text-success');

          if (data.status === 'success') {
            fb.classList.add('text-success');
            let diskon = (data.jenis === 'percent') ?
              originalPrice * data.nilai / 100 :
              data.nilai;

            lastDiskon = Math.round(diskon);
            kuponValid = true;
            currentPrice = Math.max(0, originalPrice - diskon);
            currentCoupon = code; // 🧩 simpan kupon aktif
            appliedCoupon.value = code;

            const labelDiskon = (select.value === 'indonesia') ?
              formatRp(diskon * kurs) :
              formatYen(diskon);

            fb.innerText = `Kupon valid - Diskon ${labelDiskon}`;
            updatePriceDisplay(select.value);

            Swal.fire({
              icon: 'success',
              title: 'Kupon Diterapkan!',
              text: `Selamat Kamu Dapat Diskon : ${labelDiskon}`,
              confirmButtonColor: '#28a745'
            });
          } else {
            fb.classList.add('text-danger');
            fb.innerText = data.message || 'Kupon tidak valid.';
            kuponValid = false;
            currentCoupon = '';
            appliedCoupon.value = '';
            currentPrice = originalPrice;
            updatePriceDisplay(select.value);

            Swal.fire({
              icon: 'error',
              title: 'Kupon Tidak Valid',
              text: data.message || 'Kode kupon tidak berlaku atau sudah kadaluarsa.',
              confirmButtonColor: '#d33'
            });
          }
        })
        .catch(() => {
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat memeriksa kupon, silakan coba lagi.',
            confirmButtonColor: '#d33'
          });
        });
    });
  });
</script>



<?= $this->endSection(); ?>