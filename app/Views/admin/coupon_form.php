<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">
    <h4 class="mb-4 font-weight-bold text-primary">Tambah Kupon Baru</h4>

    <form action="<?= site_url('admin/coupons/store'); ?>" method="post">
        <div class="form-group">
            <label>Kode Kupon</label>
            <input type="text" name="kode" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Jenis Diskon</label>
            <select name="jenis" class="form-control">
                <option value="percent">Persen (%)</option>
                <option value="fixed">Nominal (Rp)</option>
            </select>
        </div>

        <div class="form-group">
            <label>Nilai Diskon</label>
            <input type="number" step="0.01" name="nilai" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Masa Berlaku</label>
            <div class="row">
                <div class="col">
                    <input type="date" name="berlaku_mulai" class="form-control">
                </div>
                <div class="col">
                    <input type="date" name="berlaku_sampai" class="form-control">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Maksimum Penggunaan</label>
            <input type="number" name="max_usage" class="form-control" placeholder="0 = tanpa batas">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="<?= site_url('admin/coupons'); ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?= $this->endSection(); ?>