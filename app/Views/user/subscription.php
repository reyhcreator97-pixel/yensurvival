<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">
    <h4 class="mb-4 font-weight-bold text-primary">Subscription Plan</h4>

    <div class="row">
        <!-- Monthly Plan -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm <?= (!empty($subscription) && isset($subscription['plan']) && $subscription['plan'] == 'monthly') ? 'border-primary' : ''; ?>">
                <div class="card-body">
                    <h5 class="font-weight-bold">Bulanan</h5>
                    <h3 class="text-dark">¥<?= number_format($priceMonthly) ?>/bulan</h3>

                    <?php if (!empty($subscription) && isset($subscription['plan']) && $subscription['plan'] == 'monthly' && isset($subscription['status']) && $subscription['status'] == 'active') : ?>
                        <p><?= ceil((strtotime($subscription['end_date']) - time()) / 86400) ?> days remaining</p>
                        <a href="<?= site_url('user/subscription/buy/monthly'); ?>" class="btn btn-primary btn-sm">Perpanjang</a>
                        <a href="<?= site_url('user/subscription/buy/yearly'); ?>" class="btn btn-outline-primary btn-sm">Upgrade</a>
                    <?php elseif (!empty($subscription) && isset($subscription['plan']) && $subscription['plan'] == 'yearly') : ?>
                        <p><span class="badge badge-success">Active Yearly Plan</span></p>
                    <?php else : ?>
                        <a href="<?= site_url('user/subscription/buy/monthly'); ?>" class="btn btn-primary btn-sm">Beli Subscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Yearly Plan -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm <?= (!empty($subscription) && isset($subscription['plan']) && $subscription['plan'] == 'yearly') ? 'border-primary' : ''; ?>">
                <div class="card-body">
                    <h5 class="font-weight-bold">Tahunan</h5>
                    <h3 class="text-dark">¥<?= number_format($priceYearly) ?>/tahun</h3>
                    <p>365 Hari Full Akses</p>

                    <?php if (!empty($subscription) && isset($subscription['plan']) && $subscription['plan'] == 'yearly' && isset($subscription['status']) && $subscription['status'] == 'active') : ?>
                        <p><?= ceil((strtotime($subscription['end_date']) - time()) / 86400) ?> days remaining</p>
                        <a href="<?= site_url('user/subscription/buy/yearly'); ?>" class="btn btn-primary btn-sm">Perpanjang</a>
                    <?php elseif (!empty($subscription) && isset($subscription['plan']) && $subscription['plan'] == 'monthly') : ?>
                        <a href="<?= site_url('user/subscription/buy/yearly'); ?>" class="btn btn-outline-primary btn-sm">Upgrade</a>
                    <?php else : ?>
                        <a href="<?= site_url('user/subscription/buy/yearly'); ?>" class="btn btn-primary btn-sm">Beli Subscription</a>
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
                    <?php if (!empty($billings)) : ?>
                        <?php foreach ($billings as $bill) : ?>
                            <tr class="text-center">
                                <td><?= esc(date('Y-m-d', strtotime($bill['created_at']))) ?></td>
                                <td><?= esc($bill['deskripsi']) ?></td>
                                <td>
                                    <?php if ($bill['status'] == 'active') : ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else : ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($bill['status'] == 'pending') : ?>
                                        <?php
                                        $planName = urlencode($bill['deskripsi']);
                                        $username = urlencode(user()->username ?? 'User');
                                        $msg = "Halo Admin,%0ASaya%20*$username*%20sudah%20melakukan%20pembayaran%20untuk%20$planName.%0AMohon%20dicek%20dan%20dikonfirmasi.%0ATerima%20kasih!";
                                        $waLink = "https://wa.me/" . $adminWa . "?text=" . $msg;
                                        ?>
                                        <a href="<?= $waLink; ?>" target="_blank" class="btn btn-success btn-sm">
                                            Konfirmasi WA
                                        </a>
                                    <?php else : ?>
                                        —
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
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