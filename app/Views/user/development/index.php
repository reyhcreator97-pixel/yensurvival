<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Development Updates</h1>

    <?php foreach ($versions as $ver => $v): ?>
        <?php
        // Tentukan warna berdasarkan status
        $color = [
            'On Progress' => 'warning',
            'On Hold'     => 'danger',
            'Finalisasi'  => 'info',
            'Release'     => 'success'
        ][$v['status']] ?? 'secondary';
        ?>
        <div class="card shadow-sm mb-4 border-left-<?= $color ?>">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="font-weight-bold mb-0"><?= esc($v['version_label']) ?></h5>
                    <small class="text-muted"><?= esc(date('d M Y', strtotime($v['date']))) ?></small>
                </div>
                <div>
                    <span class="badge badge-<?= $color ?>"><?= esc($v['status']) ?></span>
                </div>
            </div>

            <div class="card-body">
                <?php
                $sections = ['Added', 'Updated', 'Removed', 'Fixed'];
                foreach ($sections as $section):
                    $filtered = array_filter($v['items'], fn($i) => $i['section'] === $section);
                    if (empty($filtered)) continue;
                ?>
                    <h6 class="text-secondary font-weight-bold mt-3"><?= $section ?></h6>
                    <ul class="mb-2">
                        <?php foreach ($filtered as $item): ?>
                            <li>
                                <strong><?= esc($item['title']) ?></strong>
                                <?php if ($item['description']): ?>
                                    — <?= esc($item['description']) ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>


    <?php if (empty($versions)): ?>
        <div class="alert alert-info">Belum ada catatan pengembangan.</div>
    <?php endif; ?>
</div>
<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    <?= $pager->links('default', 'bootstrap_full') ?>
</div>


<?= $this->endSection(); ?>