<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">
    <h4 class="mb-4 font-weight-bold text-primary">Income Management</h4>

    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('message'); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php elseif (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error'); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Filter -->
    <form method="get" class="form-inline mb-4">
        <label class="mr-2 font-weight-bold">Filter:</label>
        <select name="month" class="form-control mr-2">
            <option value="">Semua Bulan</option>
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>" <?= ($month == $m) ? 'selected' : '' ?>>
                    <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                </option>
            <?php endfor; ?>
        </select>

        <select name="year" class="form-control mr-2">
            <option value="">Semua Tahun</option>
            <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                <option value="<?= $y ?>" <?= ($year == $y) ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>

        <button type="submit" class="btn btn-primary btn-sm">Terapkan</button>
        <a href="<?= site_url('admin/income') ?>" class="btn btn-secondary btn-sm ml-2">Reset</a>
    </form>

    <!-- Total Income -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="font-weight-bold mb-0">
                Total Income:
                <span class="text-success">¥<?= number_format($totalIncome) ?></span>
            </h5>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="bg-light text-center">
                    <tr>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($incomes)) : ?>
                        <?php foreach ($incomes as $row) : ?>
                            <tr class="text-center">
                                <td><?= esc(date('Y-m-d', strtotime($row['created_at'] ?? 'now'))) ?></td>
                                <td><?= esc($row['username'] ?? '-') ?></td>
                                <td><?= esc($row['email'] ?? '-') ?></td>
                                <td><?= esc($row['deskripsi'] ?? '-') ?></td>
                                <td>¥<?= number_format($row['jumlah'] ?? 0) ?></td>
                                <td>
                                    <?php if (($row['status'] ?? '') === 'active') : ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php elseif (($row['status'] ?? '') === 'pending') : ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php else : ?>
                                        <span class="badge badge-secondary">Unknown</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (($row['kategori'] ?? '') === 'subscription' && ($row['status'] ?? '') === 'pending') : ?>
                                        <a href="<?= site_url('admin/income/approve/' . $row['id']); ?>"
                                           class="btn btn-success btn-sm btn-approve"
                                           data-id="<?= esc($row['id']) ?>">
                                           ACC
                                        </a>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr class="text-center">
                            <td colspan="7">Belum ada transaksi subscription.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="mt-3">
                <?= $pager->links('incomes', 'bootstrap_full'); ?>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const approveButtons = document.querySelectorAll('.btn-approve');

    approveButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');

            Swal.fire({
                title: 'ACC Transaksi?',
                text: "Kamu yakin ingin menyetujui transaksi ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ACC!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
});
</script>

<?= $this->endSection(); ?>