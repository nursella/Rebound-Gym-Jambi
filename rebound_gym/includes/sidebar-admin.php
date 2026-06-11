<nav class="sidebar">
    <!-- BAGIAN LOGO (Hanya Gambar) -->
    <div class="sidebar-brand" style="justify-content: center; padding: 30px 20px; border-bottom: 1px solid rgba(255,255,255,0.05);">
        <img src="<?php echo BASE_URL; ?>assets/images/logo_rebound_gym2.png" alt="Logo" style="height: 70px; width: auto;">
    </div>
    
    <div class="sidebar-menu">
        <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> <span>Dashboard</span>
        </a>
        <a href="<?php echo BASE_URL; ?>admin/member/" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/member/') !== false ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> <span>Data Member</span>
        </a>
        <a href="<?php echo BASE_URL; ?>admin/paket/" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/paket/') !== false ? 'active' : ''; ?>">
            <i class="fas fa-box-open"></i> <span>Paket Membership</span>
        </a>
        <a href="<?php echo BASE_URL; ?>admin/transaksi/" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/transaksi/') !== false ? 'active' : ''; ?>">
            <i class="fas fa-exchange-alt"></i> <span>Transaksi</span>
        </a>
        <a href="<?php echo BASE_URL; ?>admin/laporan/" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/laporan/') !== false ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i> <span>Laporan</span>
        </a>
        <a href="<?php echo BASE_URL; ?>admin/notifikasi/" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/notifikasi/') !== false ? 'active' : ''; ?>">
            <i class="fas fa-bell"></i> <span>Notifikasi</span>
            <?php 
            $notif_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM notifikasi WHERE status='belum'"))['total'];
            if ($notif_count > 0): ?>
            <span class="badge"><?php echo $notif_count; ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo BASE_URL; ?>admin/admin/" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/admin/') !== false && strpos($_SERVER['PHP_SELF'], 'dashboard') === false ? 'active' : ''; ?>">
            <i class="fas fa-user-shield"></i> <span>Admin</span>
        </a>
        <a href="<?php echo BASE_URL; ?>admin/pengaturan/" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/pengaturan/') !== false ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> <span>Pengaturan</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="<?php echo BASE_URL; ?>logout.php" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </a>
        <div class="mt-3 d-flex align-items-center p-2 rounded" style="background: rgba(255,255,255,0.05);">
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width:40px;height:40px;font-size:14px;font-weight:bold;">A</div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-bold text-white text-truncate" style="font-size:13px;"><?php echo $_SESSION['username']; ?></div>
                <small class="text-white-50" style="font-size:11px;"><?php echo $_SESSION['username']; ?>@reboundgym.com</small>
            </div>
        </div>
    </div>
</nav>