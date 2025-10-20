<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

<!-- Begin Page Content -->
<div class="container-fluid text-center">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-center mb-4 mt-5">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Coming Soon</h1>
    </div>

    <!-- Coming Soon Content -->
    <div class="card shadow-lg border-0 py-5 px-4 mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <i class="fas fa-hourglass-half fa-5x text-warning mb-4"></i>
            <h3 class="text-gray-800 font-weight-bold mb-3">Halaman Sedang Dalam Pengembangan</h3>
            <p class="text-gray-600 mb-4">
                Kami sedang menyiapkan sesuatu yang keren buat kamu!  
                Nantikan update selanjutnya ya 😄
            </p>
            <a href="<?= base_url('user/dashboard'); ?>" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div> <!-- END container-fluid -->

<?= $this->endSection(); ?>