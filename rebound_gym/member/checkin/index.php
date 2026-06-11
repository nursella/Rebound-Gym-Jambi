<?php
require_once '../../config/config.php';
checkMember();

$id_member = $_SESSION['user_id'];

// Logika Check-in
if (isset($_GET['action']) && $_GET['action'] == 'checkin') {
    // Cek apakah member aktif
    $transaksi_aktif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM transaksi WHERE id_member = '$id_member' AND status_membership = 'aktif' ORDER BY tanggal_expired DESC LIMIT 1"));
    
    if ($transaksi_aktif) {
        // Cek apakah hari ini sudah checkin
        $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM checkin WHERE id_member = '$id_member' AND DATE(tanggal_checkin) = CURDATE()"));
        
        if (!$cek) {
            mysqli_query($conn, "INSERT INTO checkin (id_member, tanggal_checkin) VALUES ('$id_member', NOW())");
            $_SESSION['success'] = "Check-in berhasil! Selamat berolahraga 💪";
        } else {
            $_SESSION['warning'] = "Anda sudah check-in hari ini.";
        }
    } else {
        $_SESSION['error'] = "Membership Anda tidak aktif atau expired!";
    }
    header("Location: index.php");
    exit();
}

$riwayat = mysqli_query($conn, "SELECT * FROM checkin WHERE id_member = '$id_member' ORDER BY tanggal_checkin DESC LIMIT 10");

include '../../includes/header.php';
include '../../includes/sidebar-member.php';
?>

<div class="content-wrapper" style="margin-left: 250px;">
    <div class="container-fluid">
        <h2 class="mb-4"><i class="fas fa-check-circle me-2"></i>Check-In</h2>

        <div class="row">
            <div class="col-md-6">
                <div class="card text-center p-5 mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <i class="fas fa-dumbbell fa-4x mb-4"></i>
                    <h3 class="fw-bold">Mulai Latihan Hari Ini?</h3>
                    <p class="mb-4">Klik tombol di bawah untuk mencatat kehadiran Anda.</p>
                    <a href="?action=checkin" class="btn btn-light btn-lg fw-bold text-primary">
                        <i class="fas fa-fingerprint me-2"></i>Check-In Sekarang
                    </a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Riwayat Check-In</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php while ($row = mysqli_fetch_assoc($riwayat)): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-check-circle text-success me-2"></i><?php echo date('l, d M Y', strtotime($row['tanggal_checkin'])); ?></span>
                                <span class="badge bg-light text-dark"><?php echo date('H:i', strtotime($row['tanggal_checkin'])); ?> WIB</span>
                            </li>
                            <?php endwhile; ?>
                            <?php if (mysqli_num_rows($riwayat) == 0): ?>
                                <li class="list-group-item text-muted text-center">Belum ada riwayat check-in.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>