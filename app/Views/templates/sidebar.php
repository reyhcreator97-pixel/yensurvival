        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-yen-sign"></i>
                </div>
                <div class="sidebar-brand-text mx-3">YEN SURVIVAL</div>
            </a>
 <?php if( in_groups('Admin')): ?>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin/dashboard'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
 
            <!-- Divider -->
            <hr class="sidebar-divider">

             <!-- Heading -->
             <div class="sidebar-heading">
                User Management
            </div>

            <!-- Nav Item - User List -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin'); ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>User List</span></a>
            </li>
<?php endif; ?>
  <!-- Divider -->
  <hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item">
    <a class="nav-link" href="<?= base_url('user/dashboard'); ?>">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
</li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Kekayaan Awal
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Starting</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Profil Kekayaan Awal:</h6>
                        <a class="collapse-item" href="buttons.html">Kas & Tabungan</a>
                        <a class="collapse-item" href="cards.html">Aset Setara Kas</a>
                        <a class="collapse-item" href="cards.html">Aset Barang</a>
                        <a class="collapse-item" href="cards.html">Piutang</a>
                        <a class="collapse-item" href="cards.html">Utang</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Keuangan
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKeuangan"
                    aria-expanded="true" aria-controls="collapseKeuangan">
                    <i class="fas fa-money-check"></i>
                    <span>Catatan Keuangan</span>
                </a>
                <div id="collapseKeuangan" class="collapse" aria-labelledby="headingKeuangan" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Catatan Keuangan Kamu:</h6>
                        <a class="collapse-item" href="buttons.html">Pendapatan</a>
                        <a class="collapse-item" href="cards.html">Pengeluaran</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
               Investasi
            </div>

            <!-- Nav Item - Catat Investasi -->
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('user/dashboard'); ?>">
              <i class="fas fa-university"></i>
             <span>Catatan Investasi</span></a>
            </li>

            <!-- Nav Item - Jual Beli Barang -->
            <li class="nav-item">
    <a class="nav-link" href="<?= base_url('user/dashboard'); ?>">
    <i class="fas fa-shopping-bag"></i>
        <span>Jual Beli Barang</span></a>
            </li>

            <!-- Nav Item - Dream Tracker -->
            <li class="nav-item">
    <a class="nav-link" href="<?= base_url('user/dashboard'); ?>">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dream Tracker</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
               Utang
            </div>

               <!-- Nav Item - Catat Utang -->
            <li class="nav-item">
            <a class="nav-link" href="<?= base_url('user/dashboard'); ?>">
            <i class="fas fa-book"></i>
             <span>Catatan Utang</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
               Piutang
            </div>

               <!-- Nav Item - Catat Utang -->
            <li class="nav-item">
            <a class="nav-link" href="<?= base_url('user/dashboard'); ?>">
            <i class="fas fa-book"></i>
             <span>Catatan Piutang</span></a>
            </li>

         
             <!-- Divider -->
             <hr class="sidebar-divider my-0">


             <!-- Nav Item - Logout -->
            <li class="nav-item">
             <a class="nav-link" href="<?= base_url('user/dashboard'); ?>">
             <i class="fas fa-question-circle"></i>
             <span>Help</span></a>
            </li>

             <!-- Divider -->
             <hr class="sidebar-divider my-0">


             <!-- Nav Item - Logout -->
<li class="nav-item">
    <a class="nav-link" href="<?= base_url('logout'); ?>">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span></a>
</li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->