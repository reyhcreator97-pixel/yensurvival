<?= $this->extend('templates/admin/index'); ?>
<?= $this->section('page-content-admin'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Development Logs</h1>

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalAdd">
        <i class="fas fa-plus-circle"></i> Tambah Log
    </button>

    <div class="card shadow mb-4">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr class="text-center">
                        <th>Version</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Section</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $r): ?>
                        <tr>
                            <td><?= esc($r['version']) ?></td>
                            <td><?= esc($r['date']) ?></td>
                            <td><span class="badge 
                <?= $r['status'] == 'On Progress' ? 'badge-warning' : ($r['status'] == 'Finalisasi' ? 'badge-info' : ($r['status'] == 'Release' ? 'badge-success' : 'badge-danger')) ?>">
                                    <?= esc($r['status']) ?>
                                </span></td>
                            <td><?= esc($r['section']) ?></td>
                            <td><?= esc($r['title']) ?></td>
                            <td><?= esc($r['description']) ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning btn-edit"
                                    data-id="<?= $r['id'] ?>"
                                    data-version="<?= esc($r['version']) ?>"
                                    data-date="<?= esc($r['date']) ?>"
                                    data-status="<?= esc($r['status']) ?>"
                                    data-section="<?= esc($r['section']) ?>"
                                    data-title="<?= esc($r['title']) ?>"
                                    data-description="<?= esc($r['description']) ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <a href="<?= site_url('admin/development/delete/' . $r['id']) ?>"
                                    class="btn btn-sm btn-danger btn-delete">
                                    <i class="fas fa-trash"></i> Hapus
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
<div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?= site_url('admin/development/save') ?>">
                <?= csrf_field() ?>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Update</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Version</label>
                        <input type="text" name="version" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option>On Progress</option>
                            <option>On Hold</option>
                            <option>Finalisasi</option>
                            <option>Release</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Section</label>
                        <select name="section" class="form-control" required>
                            <option>Added</option>
                            <option>Updated</option>
                            <option>Removed</option>
                            <option>Fixed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Edit Development -->
<div class="modal fade" id="modalEditDev" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-sm border-0">
            <form id="formEditDev" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit_id">

                <div class="modal-header bg-warning text-white py-2">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Development Log</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Version</label>
                        <input type="text" class="form-control" name="version" id="edit_version" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="date" id="edit_date" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status" id="edit_status" required>
                            <option>On Progress</option>
                            <option>On Hold</option>
                            <option>Finalisasi</option>
                            <option>Release</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Section</label>
                        <select class="form-control" name="section" id="edit_section" required>
                            <option>Added</option>
                            <option>Updated</option>
                            <option>Removed</option>
                            <option>Fixed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" class="form-control" name="title" id="edit_title" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer py-2">
                    <button type="submit" class="btn btn-warning text-white px-4">Simpan</button>
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        console.log("✅ Script edit siap");
        $('.btn-edit').on('click', function() {
            console.log("🟡 Tombol edit diklik");

            const id = $(this).data('id');
            $('#edit_id').val(id);
            $('#edit_version').val($(this).data('version'));
            $('#edit_date').val($(this).data('date'));
            $('#edit_status').val($(this).data('status'));
            $('#edit_section').val($(this).data('section'));
            $('#edit_title').val($(this).data('title'));
            $('#edit_description').val($(this).data('description'));

            $('#formEditDev').attr('action', '<?= site_url('admin/development/update') ?>/' + id);

            $('#modalEditDev').modal('show');
        });
    });
</script>

<script>
    $(document).ready(function() {

        // === KONFIRMASI HAPUS DENGAN SWEETALERT2 ===
        $('.btn-delete').on('click', function(e) {
            e.preventDefault(); // cegah langsung redirect

            const url = $(this).attr('href');

            Swal.fire({
                title: 'Yakin mau hapus?',
                text: "Data yang dihapus tidak bisa dikembalikan.",
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

    });
</script>

<?= $this->endSection(); ?>


<?= $this->endSection(); ?>