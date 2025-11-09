<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">
    <h4 class="mb-4 font-weight-bold text-primary">Kelola Kupon Promo</h4>

    <a href="<?= site_url('admin/coupons/create'); ?>" class="btn btn-primary btn-sm mb-3">
        <i class="fas fa-plus"></i> Tambah Kupon
    </a>

    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="bg-light text-center">
                    <tr>
                        <th>Kode</th>
                        <th>Jenis</th>
                        <th>Nilai</th>
                        <th>Masa Berlaku</th>
                        <th>Usage</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($coupons as $c): ?>
                        <tr>
                            <td><strong><?= esc($c['kode']); ?></strong></td>
                            <td><?= esc(ucfirst($c['jenis'])); ?></td>
                            <td>
                                <?php if ($c['jenis'] == 'percent'): ?>
                                    <?= rtrim(rtrim(number_format($c['nilai'], 2), '0'), '.') ?>%
                                <?php else: ?>
                                    ¥ <?= number_format($c['nilai'], 0, ',', '.') ?>
                                <?php endif; ?>
                            </td>

                            <td><?= esc($c['berlaku_mulai']); ?> s/d <?= esc($c['berlaku_sampai']); ?></td>
                            <td><?= $c['used_count']; ?>/<?= $c['max_usage'] > 0 ? $c['max_usage'] : '∞'; ?></td>
                            <td>
                                <?php if ($c['status'] == 'active'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= site_url('admin/coupons/delete/' . $c['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus kupon ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($coupons)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data kupon.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>