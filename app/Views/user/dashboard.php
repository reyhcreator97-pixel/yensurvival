<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <style>
        .finance-card {
            margin-bottom: 10px !important;
            padding-top: 12px !important;
            padding-bottom: 12px !important;
        }
    </style>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

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
                        ¥<?= number_format($totalUtang, 0) ?>
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

    <!-- Row 3: Data Keuangan + Kategori + Harga Emas -->
    <div class="row align-items-stretch">

        <!-- Kolom 1: Data Keuangan (Total In/Out per bulan) -->
        <div class="col-xl-4 col-lg-5 d-flex">
            <div class="card shadow mb-4 flex-fill">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Data Keuangan</h6>
                    <div>
                        <select id="financeMonth" class="custom-select custom-select-sm d-inline-block w-auto">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m == date('n') ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        <select id="financeYear" class="custom-select custom-select-sm d-inline-block w-auto">
                            <?php for ($y = date('Y') - 2; $y <= date('Y'); $y++): ?>
                                <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="card-body d-flex flex-column justify-content-between">
                    <!-- Total pemasukan -->
                    <div class="mb-3">
                        <div class="card shadow-sm border-0 bg-success text-white finance-card">
                            <div class="card-body py-3">
                                <div class="small text-white-50 mb-1">Total Pemasukan</div>
                                <div class="h4 mb-0 font-weight-bold" id="sumIn">¥0</div>
                            </div>
                        </div>
                    </div>

                    <!-- Total pengeluaran -->
                    <div>
                        <div class="card shadow-sm border-0 bg-danger text-white finance-card">
                            <div class="card-body py-3">
                                <div class="small text-white-50 mb-1">Total Pengeluaran</div>
                                <div class="h4 mb-0 font-weight-bold" id="sumOut">¥0</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Kolom 2: Kategori Pengeluaran / Pemasukan -->
        <div class="col-xl-4 col-lg-7 d-flex">
            <div class="card shadow mb-4 flex-fill">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Kategori Keuangan</h6>
                    <!-- Tab simple -->
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary active" id="tabOutBtn">Pengeluaran</button>
                        <button type="button" class="btn btn-outline-primary" id="tabInBtn">Pemasukan</button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- List Pengeluaran -->
                    <div id="listOut" class="ys-cat-list">
                        <!-- Diisi via JS -->
                    </div>

                    <!-- List Pemasukan -->
                    <div id="listIn" class="ys-cat-list d-none">
                        <!-- Diisi via JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom 3: Harga Emas (tetap) -->
        <div class="col-xl-4 col-lg-5 d-flex">
            <div class="card shadow mb-4 flex-fill">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Harga Emas Hari Ini</h6>
                    <span class="small text-muted">
                        <?= esc($gold['updated_at'] ?? date('d-m-Y H:i')) ?>
                    </span>
                </div>

                <div class="card-body d-flex flex-column justify-content-between">

                    <div>
                        <h6 class="font-weight-bold text-primary mb-3">Emas Antam (1 Gram)</h6>

                        <?php
                        // Format angka jadi Rupiah
                        function rupiah($value)
                        {
                            if (!$value || $value === 'N/A') return '–';
                            $num = preg_replace('/[^\d]/', '', $value);
                            return 'Rp ' . number_format($num, 0, ',', '.');
                        }
                        ?>

                        <!-- Harga Dasar -->
                        <div class="card bg-primary text-white shadow mb-3 finance-card">
                            <div class="card-body">
                                <?= rupiah($gold['harga_dasar'] ?? null) ?>
                                <div class="text-white-50 small">Harga Dasar</div>
                            </div>
                        </div>

                        <!-- Harga PPh -->
                        <div class="card bg-success text-white shadow finance-card">
                            <div class="card-body">
                                <?= rupiah($gold['harga_pph'] ?? null) ?>
                                <div class="text-white-50 small">Harga + PPh 0.25%</div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div> <!-- end row 3 -->


</div> <!-- END container-fluid -->

<?= $this->endSection(); ?>



<!-- ====================== -->
<!--  🔥 CHART JS SCRIPT   -->
<!-- ====================== -->
<?= $this->section('scripts'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthSel = document.getElementById('financeMonth');
        const yearSel = document.getElementById('financeYear');

        const sumInEl = document.getElementById('sumIn');
        const sumOutEl = document.getElementById('sumOut');

        const listOut = document.getElementById('listOut');
        const listIn = document.getElementById('listIn');

        const tabOutBtn = document.getElementById('tabOutBtn');
        const tabInBtn = document.getElementById('tabInBtn');

        function formatYen(val) {
            return '¥' + Number(val || 0).toLocaleString('ja-JP');
        }

        function renderList(container, items) {
            container.innerHTML = '';

            if (!items || items.length === 0) {
                container.innerHTML = '<p class="text-muted small mb-0">Belum ada data untuk periode ini.</p>';
                return;
            }

            items.forEach(row => {
                const wrap = document.createElement('div');
                wrap.className = 'd-flex align-items-center justify-content-between mb-2 ys-cat-item';

                const left = document.createElement('div');
                left.className = 'd-flex align-items-center';

                const iconBox = document.createElement('div');
                iconBox.className = 'ys-cat-icon mr-2 d-flex align-items-center justify-content-center';

                const icon = document.createElement('i');
                icon.className = row.icon || 'fas fa-circle';
                iconBox.appendChild(icon);

                const label = document.createElement('div');
                label.className = 'ys-cat-label';
                label.textContent = row.kategori;

                left.appendChild(iconBox);
                left.appendChild(label);

                const right = document.createElement('div');
                right.className = 'font-weight-bold';
                right.textContent = formatYen(row.total);

                wrap.appendChild(left);
                wrap.appendChild(right);

                container.appendChild(wrap);
            });
        }

        function loadFinance() {
            const m = monthSel.value;
            const y = yearSel.value;

            fetch(`<?= site_url('user/dashboard/finance-data'); ?>?month=${m}&year=${y}`)
                .then(r => r.json())
                .then(data => {
                    sumInEl.textContent = formatYen(data.total_in);
                    sumOutEl.textContent = formatYen(data.total_out);

                    renderList(listOut, data.pengeluaran || []);
                    renderList(listIn, data.pemasukan || []);
                })
                .catch(err => {
                    console.error('Error load finance data', err);
                });
        }

        // Tab switch
        tabOutBtn.addEventListener('click', function() {
            tabOutBtn.classList.add('active');
            tabInBtn.classList.remove('active');
            listOut.classList.remove('d-none');
            listIn.classList.add('d-none');
        });

        tabInBtn.addEventListener('click', function() {
            tabInBtn.classList.add('active');
            tabOutBtn.classList.remove('active');
            listIn.classList.remove('d-none');
            listOut.classList.add('d-none');
        });

        // Event filter
        monthSel.addEventListener('change', loadFinance);
        yearSel.addEventListener('change', loadFinance);

        // First load
        loadFinance();
    });
</script>

<style>
    /* Panel kategori height = D: sedang, scroll lembut */
    .ys-cat-list {
        max-height: 260px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .ys-cat-item:last-child {
        margin-bottom: 0;
    }

    .ys-cat-icon {
        width: 32px;
        height: 32px;
        border-radius: 12px;
        background: #f1f5ff;
        font-size: 14px;
        color: #4e73df;
    }

    .ys-cat-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #4a4a4a;
    }

    /* Tab style 2: modern */
    #tabOutBtn.active,
    #tabInBtn.active {
        color: #fff;
        background-color: #4e73df;
        border-color: #4e73df;
    }

    #tabOutBtn,
    #tabInBtn {
        min-width: 110px;
    }
</style>




<?= $this->endSection(); ?>