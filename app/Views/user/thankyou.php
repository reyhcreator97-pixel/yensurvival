<?= $this->extend('templates/index_checkout'); ?> <?= $this->section('page-content'); ?> <style>
  body {
    background: linear-gradient(180deg, #f9fafc 0%, #eef1f5 100%);
    font-family: 'Poppins', sans-serif;
  }

  /* ===================== STRUCTURE ===================== */
  .ticket-system {
    max-width: 450px;
    margin: 60px auto 100px;
  }

  .ticket-system .top {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .ticket-system .top .printer {
    width: 90%;
    height: 20px;
    background-color: #000;
    border: 5px solid #ccc;
    border-radius: 10px;
    box-shadow: 1px 3px 3px 0 rgba(0, 0, 0, 0.2);
  }

  .ticket-system .receipts-wrapper {
    overflow: hidden;
    margin-top: -10px;
    padding-bottom: 10px;
  }

  .ticket-system .receipts {
    width: 100%;
    padding: 0 10px;
    display: flex;
    align-items: center;
    flex-direction: column;
    transform: translateY(-510px);
    animation-duration: 2.5s;
    animation-delay: 0.5s;
    animation-name: print;
    animation-fill-mode: forwards;
  }

  @keyframes print {
    0% {
      transform: translateY(-510px);
      opacity: 0.3;
    }

    20% {
      opacity: 1;
    }

    100% {
      transform: translateY(0);
      opacity: 1;
    }
  }

  /* ===================== RECEIPT ===================== */
  .ticket-system .receipts .receipt {
    padding: 25px 30px;
    text-align: left;
    min-height: 200px;
    width: 88%;
    background-color: #fff;
    border-radius: 0 0 20px 20px;
    box-shadow: 1px 3px 8px 3px rgba(0, 0, 0, 0.2);
  }

  /* ===================== PAYMENT TEXT ===================== */
  .success-check {
    width: 80px;
    height: 80px;
    margin: 10px auto;
  }

  h4 {
    font-weight: 600;
    color: #1e1e2f;
    text-align: center;
  }

  h5,
  h6 {
    color: #1e3a8a;
  }

  /* ===================== BUTTON ===================== */
  .btn-whatsapp {
    background: #25D366;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 10px 20px;
    font-weight: 600;
    display: block;
    width: 100%;
    text-align: center;
    margin-top: 15px;
  }

  .btn-whatsapp:hover {
    background: #1ebe5b;
    transform: translateY(-2px);
  }

  /* ===================== BOTTOM RECEIPT ===================== */
  .ticket-system .receipts .receipt.qr-code {
    height: 110px;
    min-height: unset;
    position: relative;
    border-radius: 20px 20px 0 0;
    display: flex;
    align-items: center;
  }

  .ticket-system .receipts .receipt.qr-code:after {
    background-image: linear-gradient(135deg, #fff 0.5rem, rgba(0, 0, 0, 0) 0), linear-gradient(-135deg, #fff 0.5rem, rgba(0, 0, 0, 0) 0);
    background-position: left-bottom;
    background-repeat: repeat-x;
    background-size: 1rem;
    content: "";
    position: absolute;
    bottom: -1rem;
    left: 0;
    width: 100%;
    height: 1rem;
  }

  .ticket-system .receipts .receipt.qr-code:before {
    content: "";
    background: linear-gradient(90deg, #fff 50%, #ccc 0);
    background-size: 22px 4px, 100% 4px;
    height: 2px;
    width: 90%;
    display: block;
    position: absolute;
    top: -1px;
    left: 5%;
    margin: auto;
  }

  .qr-code img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin-left: 15px;
  }

  .qr-code .description {
    margin-left: 20px;
  }

  .qr-code .description h2 {
    margin: 0 0 5px;
    font-weight: 500;
    color: #333;
  }

  /* ===================== RESPONSIVE ===================== */
  @media (max-width: 576px) {
    .ticket-system {
      max-width: 90%;
    }

    .ticket-system .receipts .receipt {
      padding: 20px 18px;
      width: 95%;
    }
  }
</style> <!-- ===================== HTML ===================== -->
<main class="ticket-system">
  <div class="top">
    <div class="printer"></div>
  </div>
  <div class="receipts-wrapper">
    <div class="receipts">
      <div class="receipt">
        <div class="success-check"> <lottie-player src="https://assets1.lottiefiles.com/packages/lf20_jbrw3hcz.json" background="transparent" speed="1" loop="false" autoplay></lottie-player> </div>
        <h4>Pembayaran Berhasil!</h4>
        <p class="text-center mb-2">Terima kasih sudah melakukan langganan YEN Survival.</p>
        <p class="text-center">Silakan selesaikan pembayaran sebelum waktu berikut:</p>
        <h5 id="countdown" class="text-danger font-weight-bold mb-4 text-center"></h5>
        <div class="text-left">
          <h6>Detail Pembayaran:</h6>
          <p><strong>Plan:</strong> <?= ucfirst($checkout['plan_type']) ?></p>
          <p><strong>Total:</strong> <?= $checkout['country'] === 'japan' ? '¥' . $checkout['price'] : 'Rp' . $checkout['priceIDR'] ?></p>
          <p><strong>Nomor Rekening:</strong> <?= $checkout['country'] === 'japan' ? 'Mitsui Bank: 123-456-789' : 'BCA: 123-456-7890' ?> </p>
        </div> <a href="<?= $waUrl ?>" target="_blank" class="btn btn-whatsapp"> <i class="fab fa-whatsapp"></i> Konfirmasi Pembayaran </a>
      </div> <!-- ======== BAGIAN TERIMA KASIH (STRUK BAWAH) ======== -->
      <div class="receipt qr-code"> <img src="<?= base_url('assets/img/logo-ys.png') ?>" alt="YEN Survival">
        <div class="description">
          <h2>Terima Kasih</h2>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script>
  let target = new Date();
  target.setHours(target.getHours() + 24);
  let x = setInterval(() => {
    let now = new Date().getTime();
    let dist = target - now;
    if (dist <= 0) {
      clearInterval(x);
      document.getElementById("countdown").innerHTML = "Waktu habis";
    } else {
      let h = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      let m = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
      let s = Math.floor((dist % (1000 * 60)) / 1000);
      document.getElementById("countdown").innerHTML = `${h}j ${m}m ${s}d`;
    }
  }, 1000);
</script>

<?= $this->endSection(); ?>