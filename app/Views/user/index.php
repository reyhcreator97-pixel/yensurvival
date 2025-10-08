<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

    <!-- Page Heading -->
    <!-- <h1 class="h3 mb-4 text-gray-800">Welcome, <?= esc($user->username ?? $user->email) ?> 🎉</h1> -->
    <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh; background-color: #f8f9fc;">
    <div class="row w-100">
        <div class="col-md-6 d-flex flex-column justify-content-center">
             <h2 class="mb-2">
            Halo <?= esc(user()->username ?? 'User'); ?>
            </h2>
            <h1 class="display-4 font-weight-bold text-primary mb-3">
            Selamat Datang di Yen Survival
            </h1>
            <p class="lead text-muted mb-4">
            Partner finansialmu di Jepang.<br/>
            Atur, catat, dan kelola setiap keuanganmu dengan mudah untuk masa depan yang lebih pasti.
            </p>
            <a href="<?= base_url('user/dashboard'); ?>" 
               class="btn btn-primary btn-lg shadow">
                Go To Dashboard
            </a>
        </div>

        <div class="col-md-6 text-center">
            <img src="<?= base_url('img/yensurvival3.png'); ?>" 
                 alt="Welcome Image" 
                 class="img-fluid" 
                 style="max-height:400px;">
        </div>
    </div>
</div>



    <?php if (isset($belumIsi) && $belumIsi): ?>
    <!-- Modal Setup Kekayaan Awal -->
    <div class="modal fade" id="setupModal" tabindex="-1" role="dialog" aria-labelledby="setupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setupModalLabel">Setup Kekayaan Awal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Selamat datang, <?= esc($user->username ?? 'User') ?>! 🎉 <br><br>
                    Sebelum menggunakan sistem ini, silakan isi data kekayaan awal kamu terlebih dahulu.
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url('kekayaan-awal') ?>" class="btn btn-primary">Isi Sekarang</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
<?= $this->endSection(); ?>

<?php if (isset($belumIsi) && $belumIsi): ?>
<script>
    $(document).ready(function() {
        if ($('#setupModal').length) {
            $('#setupModal').modal('show');
        }
    });
</script>
<?php endif; ?>
