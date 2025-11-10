<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
        </a> -->
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

    <!-- Row 3: Chart & Harga Emas -->
    <div class="row align-items-stretch">
        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7 d-flex">
            <div class="card shadow mb-4 flex-fill">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Pemasukan & Pengeluaran</h6>
                    <div>
                        <select id="monthSelect" class="custom-select custom-select-sm d-inline-block w-auto">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m == date('n') ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        <select id="yearSelect" class="custom-select custom-select-sm d-inline-block w-auto">
                            <?php for ($y = date('Y') - 2; $y <= date('Y'); $y++): ?>
                                <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="chart-area mb-3" style="min-height: 280px;">
                        <canvas id="financeChart"></canvas>
                    </div>

                    <!-- 🔹 Ringkasan total di bawah grafik -->
                    <div class="row text-center border-top pt-3">
                        <div class="col-6">
                            <h6 class="font-weight-bold text-success mb-0" id="totalIn">Rp 0</h6>
                            <small class="text-muted">Total Pemasukan</small>
                        </div>
                        <div class="col-6">
                            <h6 class="font-weight-bold text-danger mb-0" id="totalOut">Rp 0</h6>
                            <small class="text-muted">Total Pengeluaran</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const ctx = document.getElementById("financeChart").getContext("2d");
                const monthSelect = document.getElementById("monthSelect");
                const yearSelect = document.getElementById("yearSelect");
                const totalInEl = document.getElementById("totalIn");
                const totalOutEl = document.getElementById("totalOut");

                let chart;

                function loadChart() {
                    const month = monthSelect.value;
                    const year = yearSelect.value;

                    fetch(`<?= site_url('user/dashboard/chart') ?>?month=${month}&year=${year}`)
                        .then(res => res.json())
                        .then(data => {
                            if (chart) chart.destroy();

                            // Hitung total pemasukan & pengeluaran
                            const totalIn = data.pemasukan.reduce((a, b) => a + b, 0);
                            const totalOut = data.pengeluaran.reduce((a, b) => a + b, 0);

                            totalInEl.textContent = '¥ ' + totalIn.toLocaleString('ja-JP');
                            totalOutEl.textContent = '¥ ' + totalOut.toLocaleString('ja-JP');

                            // 🌈 Google Finance Style Line Chart
                            chart = new Chart(ctx, {
                                type: "line",
                                data: {
                                    labels: data.labels,
                                    datasets: [{
                                            label: "Pemasukan",
                                            data: data.pemasukan,
                                            borderColor: "#00c853",
                                            backgroundColor: "rgba(0, 200, 83, 0.15)",
                                            borderWidth: 3,
                                            fill: true,
                                            tension: 0.4,
                                            pointRadius: 0,
                                            pointHoverRadius: 4,
                                        },
                                        {
                                            label: "Pengeluaran",
                                            data: data.pengeluaran,
                                            borderColor: "#ff5252",
                                            backgroundColor: "rgba(255, 82, 82, 0.15)",
                                            borderWidth: 3,
                                            fill: true,
                                            tension: 0.4,
                                            pointRadius: 0,
                                            pointHoverRadius: 4,
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    interaction: {
                                        mode: "index",
                                        intersect: false
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            backgroundColor: "#fff",
                                            titleColor: "#333",
                                            bodyColor: "#555",
                                            borderColor: "#ddd",
                                            borderWidth: 1,
                                            titleFont: {
                                                weight: 'bold'
                                            },
                                            callbacks: {
                                                label: function(ctx) {
                                                    return `${ctx.dataset.label}: ¥ ${ctx.formattedValue}`;
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
                                            grid: {
                                                display: false
                                            },
                                            ticks: {
                                                color: "#6c757d"
                                            }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                color: "#6c757d",
                                                callback: function(value) {
                                                    return '¥ ' + value.toLocaleString('ja-JP');
                                                }
                                            },
                                            grid: {
                                                color: "rgba(0,0,0,0.05)"
                                            }
                                        }
                                    }
                                }
                            });
                        });
                }

                // Load pertama kali + event filter
                loadChart();
                monthSelect.addEventListener("change", loadChart);
                yearSelect.addEventListener("change", loadChart);
            });
        </script>



        <!-- Harga Emas -->
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
                        <div class="card bg-primary text-white shadow mb-3">
                            <div class="card-body">
                                <?= rupiah($gold['harga_dasar'] ?? null) ?>
                                <div class="text-white-50 small">Harga Dasar</div>
                            </div>
                        </div>

                        <!-- Harga PPh -->
                        <div class="card bg-success text-white shadow">
                            <div class="card-body">
                                <?= rupiah($gold['harga_pph'] ?? null) ?>
                                <div class="text-white-50 small">Harga + PPh 0.25%</div>
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