<nav class="sidebar">
    <!-- BAGIAN LOGO (Hanya Gambar) -->
    <div class="sidebar-brand" style="justify-content: center; padding: 30px 20px; border-bottom: 1px solid rgba(255,255,255,0.05);">
        <img src="<?php echo BASE_URL; ?>assets/images/logo_rebound_gym2.png" alt="Logo" style="height: 70px; width: auto;">
    </div>
    
    <div class="sidebar-menu">
        <a href="<?php echo BASE_URL; ?>member/dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> <span>Dashboard Member</span>
        </a>
        <a href="<?php echo BASE_URL; ?>member/profil/" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/profil/') !== false ? 'active' : ''; ?>">
            <i class="fas fa-user"></i> <span>Profil Saya</span>
        </a>
        <a href="<?php echo BASE_URL; ?>member/membership/" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/membership/') !== false ? 'active' : ''; ?>">
            <i class="fas fa-crown"></i> <span>Riwayat Membership</span>
        </a>
        <a href="<?php echo BASE_URL; ?>member/pembayaran/" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/pembayaran/') !== false ? 'active' : ''; ?>">
            <i class="fas fa-credit-card"></i> <span>Pembayaran</span>
        </a>
        <a href="<?php echo BASE_URL; ?>member/notifikasi/" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/notifikasi/') !== false ? 'active' : ''; ?>">
            <i class="fas fa-bell"></i> <span>Notifikasi</span>
            <?php 
            $id_member = $_SESSION['user_id'];
            $notif_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM notifikasi n JOIN transaksi t ON n.id_transaksi=t.id_transaksi WHERE t.id_member='$id_member' AND n.status='belum'"))['total'];
            if ($notif_count > 0): ?>
            <span class="badge"><?php echo $notif_count; ?></span>
            <?php endif; ?>
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="<?php echo BASE_URL; ?>logout.php" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </a>
        <div class="mt-3 d-flex align-items-center p-2 rounded" style="background: rgba(255,255,255,0.05);">
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width:40px;height:40px;font-size:14px;font-weight:bold;">
                <?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-bold text-white text-truncate" style="font-size:13px;"><?php echo $_SESSION['nama']; ?></div>
                <small class="text-white-50" style="font-size:11px;"><?php echo $_SESSION['email'] ?? 'member@email.com'; ?></small>
            </div>
        </div>
    </div>
</nav>