<?php
namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Controllers\KursDcom;
use App\Models\UsersModel;
use App\Models\SubscriptionModel;
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

        // Pakai password hashing dari Myth\Auth (biar bisa login normal)
        $passwordHash = Password::hash($post['password'] ?? '');

        // Simpan user baru dan langsung aktif
        $this->userModel->insert([
            'username'      => $post['username'] ?? '',
            'email'         => $post['email'] ?? '',
            'password_hash' => $passwordHash,
            'active'        => 1,
        ]);

        // Ambil ID user baru
        $userId = $this->userModel->getInsertID();

        // Masukkan user baru ke grup "User" (group_id = 2)
        $this->db->table('auth_groups_users')->insert([
            'user_id'  => $userId,
            'group_id' => 2
        ]);

        // Ambil data plan, negara, dan harga
        $plan_type = $post['plan_type'] ?? 'monthly';
        $country   = $post['country'] ?? 'japan';
        $price     = $post['price'] ?? 0;
        $priceIDR  = $post['priceIDR'] ?? 0;

        // === Tambahkan ke tabel subscription ===
        $subscriptionData = [
            'user_id'     => $userId,
            'plan_type'   => $plan_type,
            'start_date'  => date('Y-m-d'),
            'end_date'    => ($plan_type === 'monthly')
                                ? date('Y-m-d', strtotime('+1 month'))
                                : date('Y-m-d', strtotime('+1 year')),
            'status'      => 'pending',
        ];
        $this->db->table('subscriptions')->insert($subscriptionData);

       // Tambah ke tabel transaksi (pakai harga yen)
        $this->db->table('transaksi')->insert([
            'user_id'   => $userId,
            'tanggal'   => date('Y-m-d'),
            'jenis'     => 'out',
            'kategori'  => 'subscription',
            'deskripsi' => 'Pembelian paket ' . ucfirst($plan_type) . ' plan',
            'status'    => 'pending',
            'jumlah'    => $price,
            'is_initial'=> 0,
        ]);

        // === Simpan data ke session sementara ===
        session()->setFlashdata('checkout-form', [
            'username'   => $post['username'] ?? '',
            'email'      => $post['email'] ?? '',
            'country'    => $country,
            'plan_type'  => $plan_type,
            'price'      => $price,
            'priceIDR'   => $priceIDR,
        ]);

        return redirect()->to('checkout-form/thankyou');
    }

    public function thankyou()
    {
        $checkout = session()->getFlashdata('checkout-form');

        if (!$checkout || !is_array($checkout)) {
            return redirect()->to('checkout-form')->with('error', 'Data checkout tidak ditemukan, silakan ulangi proses pembayaran.');
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