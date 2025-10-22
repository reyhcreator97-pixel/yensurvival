        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index">
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
                    <span>Admin Pannel</span></a>
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
<li class="nav-item">
    <a class="nav-link" href="<?= base_url('user/panel'); ?>">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>User Panel</span></a>
</li>

<li class="nav-item">
    <a class="nav-link" href="<?= base_url('user/subscription'); ?>">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Subscription</span></a>
</li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Kekayaan Awal
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                 <a class="nav-link" href="<?= base_url('kekayaan-awal'); ?>">
                 <i class="fas fa-fw fa-tachometer-alt"></i>
                 <span>Setting Kekayaan Awal</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Transaksi
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                 <a class="nav-link" href="<?= base_url('transaksi'); ?>">
                 <i class="fas fa-money-check"></i>
                 <span>Catatan Keuangan</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
               Investasi
            </div>

            <!-- Nav Item - Catat Investasi -->
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('investasi'); ?>">
              <i class="fas fa-university"></i>
             <span>Catatan Investasi</span></a>
            </li>

            <!-- Nav Item - Jual Beli Barang -->
            <li class="nav-item">
    <a class="nav-link" href="<?= base_url('aset'); ?>">
    <i class="fas fa-shopping-bag"></i>
        <span>Catatan Aset</span></a>
            </li>

            <!-- Nav Item - Dream Tracker -->
            <li class="nav-item">
    <a class="nav-link" href="<?= base_url('dream'); ?>">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dream Tracker</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
               Utang - Piutang
            </div>

               <!-- Nav Item - Catat Utang -->
            <li class="nav-item">
            <a class="nav-link" href="<?= base_url('utang'); ?>">
            <i class="fas fa-book"></i>
             <span>Catatan Utang</span></a>
            </li>

               <!-- Nav Item - Catat Utang -->
            <li class="nav-item">
            <a class="nav-link" href="<?= base_url('piutang'); ?>">
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