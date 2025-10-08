<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
        </a>
     </div>

    <!-- Row 1: Card keuangan -->
<!-- Row 1: Card Keuangan -->
<div class="row justify-content-center">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Uang</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalUang, 0) ?></div>
                        <div class="text-xs text-muted">Rp <?= number_format($totalUangIdr, 0) ?></div>
                
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Piutang</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalPiutang, 0) ?></div>
                <div class="text-xs text-muted">Rp <?= number_format($totalPiutangIdr, 0) ?></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kurs DCOM</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= esc($kurs) ?></div>
                <div class="text-muted small">Update: <?= esc($updated) ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Card Tambahan -->
<div class="row justify-content-center">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Utang</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                ¥<?= number_format($totalUtang,0) ?>
                <small class="ml-2"><?= esc($statusUtang) ?></small>
                </div>
                <div class="text-xs text-muted">Rp <?= number_format($totalUtangIdr, 0) ?></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Aset Barang</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalAset, 0) ?></div>
                <div class="text-xs text-muted">Rp <?= number_format($totalAsetIdr, 0) ?></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Total Investasi</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">¥<?= number_format($totalInvestasi, 0) ?></div>
                <div class="text-xs text-muted">Rp <?= number_format($totalInvestasiIdr, 0) ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Chart & Harga Emas -->
<div class="row align-items-stretch">
    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7 d-flex">
        <div class="card shadow mb-4 flex-fill">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="chart-area" style="min-height: 280px;">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Harga Emas -->
    <div class="col-xl-4 col-lg-5 d-flex">
        <div class="card shadow mb-4 flex-fill">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Harga Emas Hari Ini</h6>
                <span class="small text-muted">
                    <?= esc($hargalog['updated_at'] ?? date('d-m-Y H:i')) ?>
                </span>
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
                <?php 
                    $antam = $hargalog['Antam'] ?? null;
                    $ubs   = $hargalog['UBS'] ?? null;

                    if (!$antam && isset($harga['1.0']['Antam'])) {
                        $antam = $harga['1.0']['Antam'];
                    }
                    if (!$ubs && isset($harga['1.0']['UBS'])) {
                        $ubs = $harga['1.0']['UBS'];
                    }
                ?>

                <div>
                    <h6 class="font-weight-bold text-primary mb-3">Emas Antam (1 Gram)</h6>
                    <div class="card bg-primary text-white shadow mb-3">
                        <div class="card-body">
                            <?= esc($antam['harga'] ?? '–') ?>
                            <div class="text-white-50 small">Harga Beli</div>
                        </div>
                    </div>
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            <?= esc($antam['harga_buyback'] ?? '–') ?>
                            <div class="text-white-50 small">Harga Buyback</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahan CSS untuk tinggi sejajar -->
<style>
    .card-body {
        flex: 1 1 auto;
    }
    .chart-area {
        height: 100%;
    }
</style>


</div> <!-- END container-fluid -->

<?= $this->endSection(); ?>
