<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">
    <h3 class="mb-3 text-gray-800">Kategori Transaksi</h3>

    <!-- Button Tambah -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalAdd">
        <i class="fas fa-plus-circle"></i> Tambah Kategori
    </button>

    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr class="text-center">
                        <th>Icon</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $c): ?>
                        <tr class="text-center">
                            <td><i class="<?= esc($c['icon']) ?>"></i></td>
                            <td><?= esc($c['name']) ?></td>
                            <td>
                                <?= $c['type'] === 'in' ? '<span class="badge badge-success">Pemasukan</span>' :
                                    '<span class="badge badge-danger">Pengeluaran</span>' ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-edit"
                                    data-id="<?= $c['id'] ?>"
                                    data-name="<?= esc($c['name']) ?>"
                                    data-icon="<?= esc($c['icon']) ?>"
                                    data-type="<?= esc($c['type']) ?>">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- <a href="<?= site_url('admin/kategori-transaksi/delete/' . $c['id']) ?>"
                                    class="btn btn-sm btn-danger btn-delete">
                                    <i class="fas fa-trash"></i>
                                </a> -->
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= site_url('admin/kategori-transaksi/delete/' . $c['id']) ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalAdd">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('admin/kategori-transaksi/save') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" required>

                    <label class="mt-3">Icon (FontAwesome)</label>
                    <input type="text" name="icon" class="form-control" placeholder="fas fa-utensils" required>

                    <label class="mt-3">Jenis</label>
                    <select class="form-control" name="type">
                        <option value="in">Pemasukan</option>
                        <option value="out">Pengeluaran</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEdit" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Nama</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>

                    <label class="mt-3">Icon</label>
                    <input type="text" name="icon" id="edit_icon" class="form-control" required>

                    <label class="mt-3">Jenis</label>
                    <select class="form-control" name="type" id="edit_type">
                        <option value="in">Pemasukan</option>
                        <option value="out">Pengeluaran</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning text-white">Update</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->Section('scripts'); ?>
<script>
    $(".btn-edit").click(function() {
        $("#edit_name").val($(this).data("name"));
        $("#edit_icon").val($(this).data("icon"));
        $("#edit_type").val($(this).data("type"));

        let id = $(this).data("id");
        $("#formEdit").attr("action", "<?= site_url('admin/kategori-transaksi/update') ?>/" + id);

        $("#modalEdit").modal("show");
    });
</script>

<script>
    function confirmDelete(url) {
        Swal.fire({
            title: 'Yakin hapus kategori?',
            text: "Tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>

<?= $this->endSection(); ?>
<?= $this->endSection(); ?>