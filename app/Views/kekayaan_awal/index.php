<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 text-gray-800">Kekayaan Awal</h1>
        <button class="btn btn-primary" data-toggle="modal" data-target="#wizardModal">
            <i class="fas fa-plus-circle fa-sm text-white-50"></i> Setup Kekayaan Awal
        </button>
    </div>

    <!-- <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc(session('message')) ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= esc(session('error')) ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?> -->

<!-- RINGKASAN TOTAL (3 atas, 2 bawah, spacing rapi) -->
<?php
function yen($v){ return '¥'.number_format((float)$v,0,',','.'); }
$cards = [
    'uang'      => ['title' => 'Total Uang',      'border'=>'primary', 'icon'=>'wallet'],
    'utang'     => ['title' => 'Total Utang',     'border'=>'danger',  'icon'=>'file-invoice-dollar'],
    'piutang'   => ['title' => 'Total Piutang',   'border'=>'info',    'icon'=>'hand-holding-usd'],
    'aset'      => ['title' => 'Total Aset',      'border'=>'success', 'icon'=>'boxes'],
    'investasi' => ['title' => 'Total Investasi', 'border'=>'warning', 'icon'=>'chart-line'],
];
?>

<style>
.card-summary {
    transition: all .2s ease;
    border-radius: 0.5rem;
}
.card-summary:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08);
}
.row-gap {
    margin-bottom: 1.25rem;
}
</style>

<!-- BARIS 1: 3 CARD -->
<div class="row row-gap">
    <?php foreach (array_slice($cards, 0, 3, true) as $k=>$cfg): ?>
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card card-summary border-left-<?= $cfg['border'] ?> shadow h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-<?= $cfg['border'] ?> text-uppercase mb-1">
                                <?= $cfg['title'] ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= yen($totals[$k] ?? 0) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-<?= $cfg['icon'] ?> fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- BARIS 2: 2 CARD -->
<div class="row">
    <?php foreach (array_slice($cards, 3, 2, true) as $k=>$cfg): ?>
        <div class="col-xl-6 col-md-6 mb-3">
            <div class="card card-summary border-left-<?= $cfg['border'] ?> shadow h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-<?= $cfg['border'] ?> text-uppercase mb-1">
                                <?= $cfg['title'] ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= yen($totals[$k] ?? 0) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-<?= $cfg['icon'] ?> fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


    <!-- DETAIL SETIAP KATEGORI -->
    <?php 
      $labels = ['uang'=>'Uang','utang'=>'Utang','piutang'=>'Piutang','aset'=>'Aset','investasi'=>'Investasi'];
      foreach ($labels as $key=>$label): 
          $rows = $items[$key] ?? [];
    ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><?= $label ?></h6>
            <?php if (!empty($rows)): ?>
                <span class="text-xs text-muted">Total: <b><?= yen($totals[$key] ?? 0) ?></b></span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (empty($rows)): ?>
                <p class="text-muted mb-0">Belum ada data <?= strtolower($label) ?>.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Deskripsi</th>
                                <th class="text-right">Jumlah (¥)</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= esc($r['deskripsi']) ?></td>
                                <td class="text-right"><?= yen($r['jumlah']) ?></td>
                                <td class="text-right">
                                    <button class="btn btn-xs btn-outline-secondary editBtn"
                                            data-id="<?= $r['id'] ?>"
                                            data-desc="<?= esc($r['deskripsi']) ?>"
                                            data-amount="<?= (float)$r['jumlah'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" 
                                      class="btn btn-xs btn-outline-danger btn-delete"
                                      data-url="<?= site_url('kekayaan-awal/delete/' . $r['id']) ?>">
                                      <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

</div>

<!-- MODAL WIZARD -->
<div class="modal fade" id="wizardModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content shadow-lg border-0">
      <form action="<?= site_url('kekayaan-awal/store') ?>" method="post" id="wizardForm">
        <?= csrf_field() ?>

        <div class="modal-header bg-primary text-white py-2 px-3">
          <h5 class="modal-title font-weight-bold">Setup Kekayaan Awal</h5>
          <button type="button" class="close text-white" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body p-3">
          <ul class="nav nav-pills mb-3 small" id="pillTabs" role="tablist">
            <?php 
              $steps = ['uang'=>'Uang','utang'=>'Utang','piutang'=>'Piutang','aset'=>'Aset','investasi'=>'Investasi'];
              $i=0; foreach($steps as $key=>$label): $i++; ?>
              <li class="nav-item">
                <a class="nav-link <?= $i==1?'active':'' ?>" id="pill-<?= $key ?>-tab" data-toggle="pill" href="#pill-<?= $key ?>" role="tab">
                  <?= $label ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>

          <div class="tab-content" id="pillContent">
            <?php $i=0; foreach($steps as $key=>$label): $i++; ?>
            <div class="tab-pane fade <?= $i==1?'show active':'' ?>" id="pill-<?= $key ?>" role="tabpanel">

              <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="font-weight-bold mb-0"><?= $label ?></h6>
                <button type="button" class="btn btn-sm btn-outline-primary addRowBtn" data-target="<?= $key ?>">
                  <i class="fas fa-plus"></i> Tambah Item
                </button>
              </div>

              <div class="table-responsive">
                <table class="table table-sm mb-1" id="tbl-<?= $key ?>">
                  <thead class="thead-light">
                    <tr>
                      <th>Deskripsi</th>
                      <th>Jumlah (¥)</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                  <tfoot>
                    <tr>
                      <th class="text-right">Total <?= $label ?></th>
                      <th id="tot-<?= $key ?>">¥0</th>
                      <th></th>
                    </tr>
                  </tfoot>
                </table>
              </div>

            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="modal-footer py-2 px-3">
          <button type="button" class="btn btn-light btn-sm" id="prevStep">Kembali</button>
          <button type="button" class="btn btn-primary btn-sm" id="nextStep">Selanjutnya</button>
          <button type="submit" class="btn btn-success btn-sm d-none" id="submitWizard">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Tambahkan CSS untuk tampilan clean -->
<style>
  #wizardModal .modal-content {
    border-radius: 12px;
    overflow: hidden;
  }
  #wizardModal .nav-pills .nav-link {
    border-radius: 20px;
    margin-right: 4px;
    padding: 4px 10px;
  }
  #wizardModal .table th,
  #wizardModal .table td {
    padding: 6px 8px !important;
    vertical-align: middle;
  }
</style>


<!-- MODAL: Edit Item -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <form action="<?= site_url('kekayaan-awal/update') ?>" method="post" class="modal-content shadow-lg border-0">
      <?= csrf_field() ?>

      <div class="modal-header bg-primary text-white py-2 px-3">
        <h5 class="modal-title font-weight-bold mb-0">
          <i class="fas fa-edit mr-2"></i> Edit Item
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body p-3">
        <input type="hidden" name="id" id="edit-id">

        <div class="form-group mb-2">
          <label class="small font-weight-bold mb-1">Deskripsi</label>
          <input type="text" class="form-control form-control-sm rounded" name="deskripsi" id="edit-desc" placeholder="Masukkan deskripsi" required>
        </div>

        <div class="form-group mb-2">
          <label class="small font-weight-bold mb-1">Jumlah (¥)</label>
          <input type="number" step="0.01" class="form-control form-control-sm rounded" name="jumlah" id="edit-amount" placeholder="Masukkan jumlah" required>
        </div>
      </div>

      <div class="modal-footer py-2 px-3">
        <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Batal
        </button>
        <button type="submit" class="btn btn-success btn-sm">
          <i class="fas fa-save mr-1"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ===== Tambahan CSS biar match sama wizard ===== -->
<style>
  #editModal .modal-content {
    border-radius: 12px;
    overflow: hidden;
  }
  #editModal .modal-header {
    border-bottom: none;
  }
  #editModal .modal-footer {
    border-top: none;
  }
  #editModal .form-control {
    border-radius: 8px !important;
  }
</style>





<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
function fmtYen(n){
    n = parseFloat(n || 0);
    return '¥' + (isNaN(n) ? 0 : n).toLocaleString('id-ID', { maximumFractionDigits: 0 });
}

function sumTable(key){
    let s = 0;
    $('#tbl-' + key + ' tbody .amount').each(function(){
        s += parseFloat($(this).val()) || 0;
    });
    $('#tot-' + key).text(fmtYen(s));
}

function addRow(key, desc = '', amount = ''){
    const row = `
        <tr>
            <td><input type="text" class="form-control" name="${key}_desc[]" value="${desc}" placeholder="Deskripsi"></td>
            <td><input type="number" class="form-control amount" name="${key}_amount[]" value="${amount}" placeholder="0"></td>
            <td class="text-right">
                <button type="button" class="btn btn-sm btn-outline-danger delRow">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    $('#tbl-' + key + ' tbody').append(row);
    sumTable(key);
}

// ===== Dynamic Buttons =====
$(document).on('click', '.addRowBtn', function(){
    const key = $(this).data('target');
    addRow(key);
});

$(document).on('click', '.delRow', function(){
    const tbl = $(this).closest('table').attr('id').replace('tbl-', '');
    $(this).closest('tr').remove();
    sumTable(tbl);
});

$(document).on('input', '.amount', function(){
    const tbl = $(this).closest('table').attr('id').replace('tbl-', '');
    sumTable(tbl);
});

// ===== Wizard Navigation =====
const order = ['uang', 'utang', 'piutang', 'aset', 'investasi'];
let idx = 0;

function setStep(i){
    idx = Math.max(0, Math.min(i, order.length - 1));
    $('#pill-' + order[idx] + '-tab').tab('show');
    $('#prevStep').prop('disabled', idx === 0);
    $('#nextStep').toggleClass('d-none', idx === order.length - 1);
    $('#submitWizard').toggleClass('d-none', idx !== order.length - 1);
}

$('#prevStep').on('click', () => setStep(idx - 1));
$('#nextStep').on('click', () => setStep(idx + 1));
$('#wizardModal').on('shown.bs.modal', () => setStep(0));

// ===== Default 1 Row Each Tab =====
order.forEach(k => addRow(k));

// ===== Edit Modal =====
$(document).on('click', '.editBtn', function(){
    const id     = $(this).data('id');
    const desc   = $(this).data('desc');
    const amount = $(this).data('amount');

    $('#edit-id').val(id);
    $('#edit-desc').val(desc);
    $('#edit-amount').val(amount);
    $('#editModal').modal('show');
});
</script>
<?= $this->endSection(); ?>

