<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Kelola Video Tutorial</h1>

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus-circle"></i> Tambah Video
    </button>

    <div class="card shadow mb-4">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>URL Video</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($videos as $v): ?>
                        <tr>
                            <td><?= esc($v['title']) ?></td>
                            <td><?= esc($v['category']) ?></td>
                            <td><?= esc($v['video_url']) ?></td>
                            <td><?= date('d M Y', strtotime($v['created_at'])) ?></td>
                            <td class="text-right">
                                <a href="<?= site_url('admin/tutorials/delete/' . $v['id']) ?>"
                                    class="btn btn-sm btn-danger btn-delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= site_url('admin/tutorials/store') ?>" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Video Tutorial</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>URL Video (Embed YouTube)</label>
                    <input type="url" name="video_url" class="form-control" placeholder="https://www.youtube.com/embed/xxxxx" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category" class="form-control">
                        <option>Dashboard</option>
                        <option>User Panel</option>
                        <option>Kekayaan Awal</option>
                        <option>Investasi</option>
                        <option>Aset</option>
                        <option>Utang</option>
                        <option>Piutang</option>
                        <option>Subscription</option>
                        <option>Lainnya</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        Swal.fire({
            title: 'Yakin hapus video ini?',
            text: "Tindakan ini tidak bisa dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
</script>

<?= $this->endSection(); ?>