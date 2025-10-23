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
                <a class="nav-link" href="<?= base_url('admin/users'); ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span></a>
            </li>
            <!-- Nav Item - User List -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin/subscription'); ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Subscription</span></a>
            </li>
            <!-- Nav Item - User List -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin/income'); ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Income</span></a>
            </li>

             <!-- Divider -->
             <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
             <a class="nav-link" href="<?= base_url('admin/transaksi'); ?>">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Transaksi</span></a>
            </li>

             <!-- Divider -->
             <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
             <a class="nav-link" href="<?= base_url('admin/settings'); ?>">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Settings</span></a>
            </li>

             <!-- Divider -->
             <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
             <a class="nav-link" href="<?= base_url('admin/logs'); ?>">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Log</span></a>
            </li>
            
            <?php endif; ?>
            
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