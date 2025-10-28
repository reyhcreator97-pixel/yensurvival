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
          <div class="form-group">
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

<script>
document.addEventListener("DOMContentLoaded", function() {
  const select = document.getElementById('countrySelect');
  const info = document.getElementById('paymentInfo');
  const japan = document.getElementById('japanAccount');
  const indo = document.getElementById('indoAccount');
  const buyBtn = document.getElementById('buyBtn');

  select.addEventListener('change', function() {
    info.style.display = 'block';
    japan.classList.add('d-none');
    indo.classList.add('d-none');

    if (this.value === 'japan') {
      japan.classList.remove('d-none');
      buyBtn.classList.remove('disabled');
      buyBtn.href = "<?= site_url('user/subscription/buy/' . strtolower($plan)) ?>?country=japan";
    } else if (this.value === 'indonesia') {
      indo.classList.remove('d-none');
      buyBtn.classList.remove('disabled');
      buyBtn.href = "<?= site_url('user/subscription/buy/' . strtolower($plan)) ?>?country=indonesia";
    } else {
      buyBtn.classList.add('disabled');
      buyBtn.href = "#";
    }
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const countrySelect = document.getElementById('countrySelect');
    const paymentInfo = document.getElementById('paymentInfo');
    const japanAccount = document.getElementById('japanAccount');
    const indoAccount = document.getElementById('indoAccount');
    const priceElement = document.getElementById('priceValue');
    const currencyElement = document.getElementById('currencySymbol');

    // ambil variabel kurs dan harga asli dari PHP
    const kursJPYtoIDR = <?= $kurs ?>;
    const originalPrice = <?= $price ?>;

    // fungsi helper buat format angka
    const formatIDR = (num) => new Intl.NumberFormat('id-ID').format(num);
    const formatJPY = (num) => new Intl.NumberFormat('ja-JP').format(num);

    countrySelect.addEventListener('change', function() {
        const selected = this.value;
        paymentInfo.style.display = 'block';

        if (selected === 'indonesia') {
            japanAccount.classList.add('d-none');
            indoAccount.classList.remove('d-none');

            // konversi harga ke IDR
            const converted = Math.round(originalPrice * kursJPYtoIDR);
            priceElement.textContent = formatIDR(converted);
            currencyElement.textContent = 'Rp';
        } else if (selected === 'japan') {
            indoAccount.classList.add('d-none');
            japanAccount.classList.remove('d-none');

            // kembali ke yen
            priceElement.textContent = formatJPY(originalPrice);
            currencyElement.textContent = '¥';
        } else {
            paymentInfo.style.display = 'none';
        }
    });
});
</script>

<?= $this->endSection(); ?>

