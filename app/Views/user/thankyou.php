<?= $this->extend('templates/index_checkout'); ?>
<?= $this->section('page-content'); ?>

<style>
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
h5, h6 {
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
  background-image: linear-gradient(135deg, #fff 0.5rem, rgba(0,0,0,0) 0),
                    linear-gradient(-135deg, #fff 0.5rem, rgba(0,0,0,0) 0);
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

/* ===================== RESPONSIVE (FROM MAYAR) ===================== */

/* 575px ↓ : phone portrait umum */
@media screen and (max-width:575px){
  .ticket-system{
    max-width:95%;
    padding:0 10px;
    margin:40px auto 80px;
  }

  .ticket-system .top .printer{
    width:88%;
    height:18px;
    border-width:4px;
    border-radius:8px;
  }

  .ticket-system .receipts-wrapper{
    margin-top:-8px;
    padding-bottom:15px;
  }

  .ticket-system .receipts .receipt{
    width:96%;
    padding:18px 14px;
    font-size:.9rem;
    border-radius:0 0 14px 14px;
    box-shadow:1px 2px 6px 2px rgba(0,0,0,.15);
  }

  .ticket-system .receipts .receipt.qr-code{
    height:95px;
    border-radius:14px 14px 0 0;
  }

  .success-check{width:55px;height:55px;margin:6px auto;}
  h4{font-size:1.05rem;}
  h5,h6{font-size:.95rem;}
  .btn-whatsapp{padding:8px 0;font-size:.88rem;width:100%;}
  .qr-code img{width:42px;height:42px;}
  .qr-code .description h2{font-size:.9rem;}
}

/* 500px ↓ : layar kecil (iPhone SE, Android mini) */
@media screen and (max-width:500px){
  .ticket-system{max-width:92%;}
  .ticket-system .receipts .receipt{padding:16px 12px;font-size:.88rem;}
}

/* 396px ↓ : very small devices */
@media screen and (max-width:396px){
  .ticket-system{max-width:100%!important;padding:0 6px;}
  .ticket-system .receipts .receipt{padding:14px 10px;font-size:.85rem;}
  .btn-whatsapp{font-size:.8rem;}
  .qr-code img{width:38px;height:38px;}
}
</style>

<!-- ===================== HTML ===================== -->
<main class="ticket-system">
  <div class="top">
    <div class="printer"></div>
  </div>

  <div class="receipts-wrapper">
    <div class="receipts">
      <div class="receipt">
        <div class="success-check">
          <lottie-player src="https://assets1.lottiefiles.com/packages/lf20_jbrw3hcz.json"
            background="transparent" speed="1" loop="false" autoplay></lottie-player>
        </div>

        <h4>Pembayaran Berhasil!</h4>
        <p class="text-center mb-2">Terima kasih sudah melakukan langganan YEN Survival.</p>
        <p class="text-center">Silakan selesaikan pembayaran sebelum waktu berikut:</p>
        <h5 id="countdown" class="text-danger font-weight-bold mb-4 text-center"></h5>

        <div class="text-left">
          <strong>Detail Pembayaran:</strong>
          <p><strong>Plan:</strong> <?= ucfirst($checkout['plan_type']) ?></p>
          <p><strong>Total:</strong> <?= $checkout['country'] === 'japan' ? '¥' .$checkout['price'] : 'Rp' .$checkout['priceIDR'] ?></p>
          <p><strong>Nomor Rekening:</strong>
            <?= $checkout['country'] === 'japan' ? 'Mitsui Bank: 123-456-789' : 'BCA: 123-456-7890' ?>
          </p>
        </div>

        <a href="<?= $waUrl ?>" target="_blank" class="btn btn-whatsapp">
          <i class="fab fa-whatsapp"></i> Konfirmasi Pembayaran
        </a>
      </div>

      <!-- ======== BAGIAN TERIMA KASIH (STRUK BAWAH) ======== -->
      <div class="receipt qr-code">
        <img src="http://yensurvival.my.id/assets/img/logo.png" alt="YEN Survival">
        <div class="description">
          <h2>Terima Kasih</h2>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    const countdownEl = document.getElementById("countdown");
    if (!countdownEl) return;

    let target = new Date();
    target.setHours(target.getHours() + 24);

    function updateCountdown() {
      let now = new Date().getTime();
      let dist = target - now;
      if (dist <= 0) {
        countdownEl.innerHTML = "Waktu habis";
        countdownEl.style.color = "#dc3545";
        clearInterval(x);
      } else {
        let h = Math.floor((dist % (1000*60*60*24)) / (1000*60*60));
        let m = Math.floor((dist % (1000*60*60)) / (1000*60));
        let s = Math.floor((dist % (1000*60)) / 1000);
        countdownEl.innerHTML = ${h}j ${m}m ${s}d;
        countdownEl.style.color = "#e63946";
      }
    }

    updateCountdown();
    let x = setInterval(updateCountdown, 1000);
  }, 3000); // nunggu animasi print 2.5 detik + buffer
});
</script>
<?= $this->endSection(); ?>