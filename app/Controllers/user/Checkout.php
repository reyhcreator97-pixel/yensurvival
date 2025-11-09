<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Controllers\KursDcom;
use App\Models\UsersModel;
use App\Models\SubscriptionModel;
use App\Models\CouponModel;
use Myth\Auth\Password;

class Checkout extends BaseController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->userModel = new UsersModel();
    }

    public function index()
    {
        $plan = $this->request->getGet('plan_type') ?? 'monthly';
        $country = $this->request->getGet('country') ?? 'japan';

        // Ambil harga dari settings
        $settings = $this->db->table('settings')->get()->getRow();
        $price = ($plan === 'monthly') ? $settings->price_monthly : $settings->price_yearly;
        $price = (float)$price;

        // Ambil kurs dari controller KursDcom
        $kursCtrl = new KursDcom();
        $kurs = $kursCtrl->getKurs();
        $kursText = "Rp " . number_format($kurs, 2, ',', '.');

        // Hitung harga dalam Rupiah jika pilih Indonesia
        $priceIDR = ($kurs > 0) ? $price * $kurs : 0;

        $data = [
            'title' => 'Checkout Subscription',
            'plan_type' => ucfirst($plan),
            'country' => $country,
            'priceYen' => $price,
            'priceIDR' => $priceIDR,
            'kursText' => $kursText,
            'kurs' => $kurs
        ];

        return view('user/checkout-form', $data);
    }

    public function process()
    {
        $post = $this->request->getPost();

        // Buat user baru dan hash password
        $passwordHash = Password::hash($post['password'] ?? '');
        $this->userModel->insert([
            'username'      => $post['username'] ?? '',
            'email'         => $post['email'] ?? '',
            'password_hash' => $passwordHash,
            'active'        => 1,
        ]);

        $userId = $this->userModel->getInsertID();

        // Masukkan user baru ke grup "User"
        $this->db->table('auth_groups_users')->insert([
            'user_id'  => $userId,
            'group_id' => 2
        ]);

        // Ambil data dari form
        $plan_type   = $post['plan_type'] ?? 'monthly';
        $country     = $post['country'] ?? 'japan';
        $kurs        = (float) ($post['kurs'] ?? 0);
        $priceYen    = (float) ($post['price'] ?? 0); // 💎 harga FINAL dari form
        $priceIDR    = ($kurs > 0) ? $priceYen * $kurs : 0;
        $couponCode  = $post['applied_coupon'] ?? null;
        $discount    = 0;
        $discountLabel = '';

        // 🔥 Validasi kupon (tanpa hitung ulang diskon)
        if ($couponCode) {
            $couponModel = new CouponModel();
            $coupon = $couponModel
                ->where('kode', $couponCode)
                ->where('status', 'active')
                ->first();

            if ($coupon) {
                if ($coupon['jenis'] === 'percent') {
                    $discountLabel = "{$coupon['nilai']}%";
                } else {
                    $discountLabel = '¥ ' . number_format($coupon['nilai'], 0, ',', '.');
                }

                // Update penggunaan kupon
                $couponModel->set('used_count', 'used_count + 1', false)
                    ->where('id', $coupon['id'])
                    ->update();
            }
        }

        // Simpan ke tabel subscriptions
        $this->db->table('subscriptions')->insert([
            'user_id'     => $userId,
            'plan_type'   => $plan_type,
            'start_date'  => date('Y-m-d'),
            'end_date'    => ($plan_type === 'monthly')
                ? date('Y-m-d', strtotime('+1 month'))
                : date('Y-m-d', strtotime('+1 year')),
            'status'      => 'pending',
        ]);

        // Simpan ke tabel transaksi (harga final Yen)
        $this->db->table('transaksi')->insert([
            'user_id'   => $userId,
            'tanggal'   => date('Y-m-d'),
            'jenis'     => 'out',
            'kategori'  => 'subscription',
            'deskripsi' => 'Pembelian paket ' . ucfirst($plan_type) . ' plan' . ($couponCode ? " (Kupon: {$couponCode})" : ''),
            'status'    => 'pending',
            'jumlah'    => $priceYen, // 💰 Harga final dari form
            'is_initial' => 0,
        ]);

        // Simpan ke session untuk thankyou page
        session()->setFlashdata('checkout-form', [
            'username'       => $post['username'] ?? '',
            'email'          => $post['email'] ?? '',
            'country'        => $country,
            'plan_type'      => $plan_type,
            'price'          => $priceYen,      // Harga akhir dalam Yen
            'priceIDR'       => $priceIDR,      // Harga akhir dalam Rupiah
            'kurs'           => $kurs,
            'coupon'         => $couponCode,
            'discountLabel'  => $discountLabel
        ]);

        return redirect()->to('checkout-form/thankyou');
    }

    public function thankyou()
    {
        $checkout = session()->getFlashdata('checkout-form');

        if (!$checkout || !is_array($checkout)) {
            return redirect()->to('checkout-form')
                ->with('error', 'Data checkout tidak ditemukan, silakan ulangi proses pembayaran.');
        }

        $settings = $this->db->table('settings')->get()->getRow();
        $adminWa  = $settings->contact_whatsapp ?? '';
        $waUrl    = "https://wa.me/{$adminWa}?text=Halo%20Admin,%20saya%20ingin%20konfirmasi%20pembayaran%20Yen%20Survival.";

        return view('user/thankyou', [
            'title'    => 'Terima Kasih',
            'checkout' => $checkout,
            'waUrl'    => $waUrl
        ]);
    }
}
