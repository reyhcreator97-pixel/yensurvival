<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">
    <h4 class="mb-4 font-weight-bold text-primary">Subscription Plan</h4>

    <div class="row">
        <!-- Monthly Plan -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm <?= (!empty($subscription) && $subscription['plan_type'] == 'monthly') ? 'border-primary' : ''; ?>">
                <div class="card-body text-center">
                    <h5 class="font-weight-bold">Bulanan</h5>
                    <h3 class="text-dark">¥<?= number_format($priceMonthly) ?>/bulan</h3>
                    <p class="text-muted">30 Hari Akses</p>

                    <?php if (!$subscription): ?>
                        <!-- Belum punya -->
                        <a href="<?= site_url('user/subscription/checkout/monthly'); ?>" class="btn btn-primary btn-sm">Beli Subscription</a>

                    <?php elseif ($subscription['plan_type'] == 'monthly' && $subscription['status'] == 'pending'): ?>
                        <!-- Pending bulanan -->
                        <span class="badge badge-warning mb-2">Menunggu Konfirmasi</span><br>
                        <p class="text-muted small">Admin akan memverifikasi pembayaran kamu</p>

                    <?php elseif ($subscription['plan_type'] == 'yearly' && $subscription['status'] == 'pending'): ?>
                        <!-- Pending tapi yearly -->
                        <a href="<?= site_url('user/subscription/checkout/monthly'); ?>" class="btn btn-primary btn-sm">Beli Subscription</a>

                    <?php elseif ($subscription['plan_type'] == 'monthly' && $subscription['status'] == 'active'): ?>
                        <!-- Aktif bulanan -->
                        <span class="badge badge-success mb-2">Active Plan</span><br>
                        <p><?= ceil((strtotime($subscription['end_date']) - time()) / 86400) ?> hari tersisa</p>
                        <a href="<?= site_url('user/subscription/checkout/monthly'); ?>" class="btn btn-warning btn-sm">Perpanjang</a>
                        <a href="<?= site_url('user/subscription/checkout/yearly'); ?>" class="btn btn-outline-primary btn-sm">Upgrade Plan</a>

                    <?php elseif ($subscription['plan_type'] == 'yearly' && $subscription['status'] == 'active'): ?>
                        <!-- Sudah yearly, nonaktifkan beli monthly -->
                        <span class="badge badge-info">Active Yearly Plan</span>

                    <?php else: ?>
                        <!-- Expired atau tidak aktif -->
                        <a href="<?= site_url('user/subscription/checkout/monthly'); ?>" class="btn btn-primary btn-sm">Beli Subscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Yearly Plan -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm <?= (!empty($subscription) && $subscription['plan_type'] == 'yearly') ? 'border-success' : ''; ?>">
                <div class="card-body text-center">
                    <h5 class="font-weight-bold">Tahunan</h5>
                    <h3 class="text-dark">¥<?= number_format($priceYearly) ?>/tahun</h3>
                    <p class="text-muted">365 Hari Full Akses</p>

                    <?php if (!$subscription): ?>
                        <!-- Belum punya -->
                        <a href="<?= site_url('user/subscription/checkout/yearly'); ?>" class="btn btn-success btn-sm">Beli Subscription</a>

                    <?php elseif ($subscription['plan_type'] == 'yearly' && $subscription['status'] == 'pending'): ?>
                        <!-- Pending tahunan -->
                        <span class="badge badge-warning mb-2">Menunggu Konfirmasi</span><br>
                        <p class="text-muted small">Admin akan memverifikasi pembayaran kamu</p>

                    <?php elseif ($subscription['plan_type'] == 'monthly' && $subscription['status'] == 'pending'): ?>
                        <!-- Pending tapi monthly -->
                        <a href="<?= site_url('user/subscription/checkout/yearly'); ?>" class="btn btn-success btn-sm">Beli Subscription</a>

                    <?php elseif ($subscription['plan_type'] == 'yearly' && $subscription['status'] == 'active'): ?>
                        <!-- Aktif tahunan -->
                        <span class="badge badge-success mb-2">Active Plan</span><br>
                        <p><?= ceil((strtotime($subscription['end_date']) - time()) / 86400) ?> hari tersisa</p>
                        <a href="<?= site_url('user/subscription/checkout/yearly'); ?>" class="btn btn-warning btn-sm">Perpanjang</a>

                    <?php elseif ($subscription['plan_type'] == 'monthly' && $subscription['status'] == 'active'): ?>
                        <!-- Aktif bulanan bisa upgrade -->
                        <a href="<?= site_url('user/subscription/checkout/yearly'); ?>" class="btn btn-info btn-sm">Upgrade Plan</a>

                    <?php else: ?>
                        <a href="<?= site_url('user/subscription/checkout/yearly'); ?>" class="btn btn-success btn-sm">Beli Subscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mt-5 font-weight-bold text-secondary">Billing History</h5>
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center bg-light">
                        <th>Tanggal</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($billings)): ?>
                        <?php foreach ($billings as $bill): ?>
                            <tr class="text-center">
                                <td><?= esc(date('Y-m-d', strtotime($bill['created_at']))) ?></td>
                                <td><?= esc($bill['deskripsi']) ?></td>
                                <td>
                                    <?php
                                    // 🔍 Cek apakah langganan user sudah expired
                                    $isExpired = false;
                                    if (!empty($subscription) && strtotime($subscription['end_date']) < time()) {
                                        $isExpired = true;
                                    }

                                    if ($bill['status'] == 'pending') : ?>
                                        <span class="badge badge-warning">Pending</span>

                                    <?php elseif ($bill['status'] == 'active' && $isExpired) : ?>
                                        <span class="badge badge-danger">Expired</span>

                                    <?php elseif ($bill['status'] == 'active') : ?>
                                        <span class="badge badge-success">Aktif</span>

                                    <?php else : ?>
                                        <span class="badge badge-light"><?= ucfirst($bill['status']) ?></span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($bill['status'] == 'pending'): ?>
                                        <?php
                                        $planName = urlencode($bill['deskripsi']);
                                        $username = urlencode(user()->username ?? 'User');
                                        $msg = "Halo Admin,%0ASaya%20*$username*%20sudah%20melakukan%20pembayaran%20untuk%20$planName.%0AMohon%20dicek%20dan%20dikonfirmasi.%0ATerima%20kasih!";
                                        $waLink = "https://wa.me/" . $adminWa . "?text=" . $msg;
                                        ?>
                                        <a href="<?= $waLink; ?>" target="_blank" class="btn btn-success btn-sm">
                                            Konfirmasi WA
                                        </a>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr class="text-center">
                            <td colspan="4">Belum ada riwayat transaksi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-3">
    <?= $pager->links('transaksi', 'bootstrap_full') ?>
</div>

<?= $this->endSection(); ?>