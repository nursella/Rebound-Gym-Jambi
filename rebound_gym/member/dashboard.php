<?php
require_once '../config/config.php';
checkLogin();
$page_title = 'Dashboard Member';

$id = $_SESSION['user_id'];

// Validasi session
if (!isset($id) || empty($id)) {
    session_destroy();
    header("Location: " . BASE_URL . "login.php");
    exit();
}

// Query 1: Data Member
$query_member = "SELECT * FROM member WHERE id_member = '$id'";
$result_member = mysqli_query($conn, $query_member);

if (!$result_member) {
    die("<div style='padding:20px;background:#fee2e2;color:#991b1b;border-radius:8px;font-family:Arial;'>
            <h3>❌ Database Error</h3>
            <p><strong>Error:</strong> " . mysqli_error($conn) . "</p>
            <p><strong>Query:</strong> " . $query_member . "</p>
            <p>Silakan periksa apakah tabel 'member' sudah ada di database.</p>
         </div>");
}

$member = mysqli_fetch_assoc($result_member);

if (!$member) {
    die("<div style='padding:20px;background:#fee2e2;color:#991b1b;border-radius:8px;font-family:Arial;'>
            <h3>❌ Data Tidak Ditemukan</h3>
            <p>Member dengan ID <strong>$id</strong> tidak ada di database.</p>
            <p><a href='logout.php'>Logout dan Login Ulang</a></p>
         </div>");
}

// Query 2: Transaksi Aktif
$query_transaksi = "SELECT t.*, p.nama_paket, p.durasi_hari, DATEDIFF(t.tanggal_expired, CURDATE()) as sisa_hari 
                    FROM transaksi t 
                    LEFT JOIN paket p ON t.id_paket = p.id_paket 
                    WHERE t.id_member = '$id' AND t.status_membership = 'aktif' 
                    ORDER BY t.tanggal_expired DESC LIMIT 1";

$result_transaksi = mysqli_query($conn, $query_transaksi);
$transaksi_aktif = ($result_transaksi && mysqli_num_rows($result_transaksi) > 0) ? mysqli_fetch_assoc($result_transaksi) : null;

// Query 3: Riwayat Check-in
$query_checkin = "SELECT * FROM checkin WHERE id_member = '$id' ORDER BY tanggal_checkin DESC LIMIT 5";
$result_checkin = mysqli_query($conn, $query_checkin);
$riwayat_checkin = $result_checkin ? $result_checkin : false;

// Query 4: Riwayat Pembayaran
$query_pembayaran = "SELECT t.*, p.nama_paket FROM transaksi t 
                     LEFT JOIN paket p ON t.id_paket = p.id_paket 
                     WHERE t.id_member = '$id' AND t.status_pembayaran = 'lunas' 
                     ORDER BY t.tanggal_bayar DESC LIMIT 3";
$result_pembayaran = mysqli_query($conn, $query_pembayaran);
$riwayat_pembayaran = $result_pembayaran ? $result_pembayaran : false;

// Query 5: Total Check-in Bulan Ini
$query_total = "SELECT COUNT(*) as total FROM checkin WHERE id_member = '$id' AND MONTH(tanggal_checkin) = MONTH(CURDATE())";
$result_total = mysqli_query($conn, $query_total);
$total_checkin_data = ($result_total) ? mysqli_fetch_assoc($result_total) : ['total' => 0];
$total_checkin = $total_checkin_data['total'];

include '../includes/header.php';
include '../includes/sidebar-member.php';
?>

<div class="content-wrapper">
    <!-- Top Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Member Rebound Gym</h4>
            <p class="text-muted mb-0" style="font-size: 13px;">Selamat datang kembali! Tetap semangat mencapai goal fitness Anda 💪</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center bg-white px-3 py-2 rounded border">
                <i class="far fa-calendar text-muted me-2"></i>
                <span class="small fw-bold"><?php echo date('d M Y'); ?></span>
                <i class="fas fa-chevron-down text-muted ms-2" style="font-size: 10px;"></i>
            </div>
            <div class="position-relative bg-white rounded-circle p-2 border" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                <i class="far fa-bell text-muted"></i>
            </div>
        </div>
    </div>

    <?php if($transaksi_aktif): ?>
    <!-- Status Cards Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-crown text-primary me-2"></i>
                            <span class="text-muted small">Status Membership</span>
                            <span class="badge bg-success bg-opacity-10 text-success ms-2" style="font-size: 10px;">AKTIF</span>
                        </div>
                        <h4 class="fw-bold mb-0"><?php echo $transaksi_aktif['nama_paket']; ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar-alt text-warning me-2"></i>
                            <span class="text-muted small">Berlaku Hingga</span>
                        </div>
                        <h4 class="fw-bold mb-0"><?php echo date('d M Y', strtotime($transaksi_aktif['tanggal_expired'])); ?></h4>
                        <small class="text-muted">Sisa <?php echo $transaksi_aktif['sisa_hari']; ?> hari</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-primary me-2"></i>
                            <span class="text-muted small">Check-in</span>
                        </div>
                        <h4 class="fw-bold mb-0"><?php echo $total_checkin; ?> Kali</h4>
                        <small class="text-muted">Total check-in bulan ini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Motivation Banner -->
    <div class="card border-0 mb-4 overflow-hidden" style="background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.9)), url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center; min-height: 160px;">
        <div class="card-body d-flex align-items-center justify-content-between text-white p-4">
            <div>
                <h4 class="fw-bold mb-2">Tetap Konsisten!</h4>
                <p class="mb-3 opacity-75" style="font-size: 14px;">Setiap latihan adalah investasi terbaik untuk diri Anda.</p>
                <button class="btn btn-light btn-sm fw-bold px-3">Lihat Jadwal Gym</button>
            </div>
            <i class="fas fa-dumbbell fa-4x opacity-25 d-none d-md-block"></i>
        </div>
    </div>

    <!-- Info & Check-in Row -->
    <div class="row g-3 mb-4">
        <div class="col-lg-5">
            <div class="card border-0 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">Informasi Membership</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted py-2" style="font-size: 13px; width: 40%;">Paket</td>
                            <td class="py-2 fw-bold text-end" style="font-size: 13px;"><?php echo $transaksi_aktif['nama_paket']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2" style="font-size: 13px;">Tanggal Mulai</td>
                            <td class="py-2 text-end" style="font-size: 13px;"><?php echo date('d M Y', strtotime($transaksi_aktif['tanggal_mulai'])); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2" style="font-size: 13px;">Tanggal Expired</td>
                            <td class="py-2 text-end" style="font-size: 13px;"><?php echo date('d M Y', strtotime($transaksi_aktif['tanggal_expired'])); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2" style="font-size: 13px;">Sisa Hari</td>
                            <td class="py-2 text-end fw-bold text-warning" style="font-size: 13px;"><?php echo $transaksi_aktif['sisa_hari']; ?> hari</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2" style="font-size: 13px;">Harga</td>
                            <td class="py-2 text-end fw-bold" style="font-size: 13px;"><?php echo formatRupiah($transaksi_aktif['total_harga']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2" style="font-size: 13px;">Status</td>
                            <td class="py-2 text-end"><span class="badge bg-success bg-opacity-10 text-success" style="font-size: 11px;">AKTIF</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold">Riwayat Check-in Terbaru</h6>
                    <a href="checkin/" class="text-primary small text-decoration-none fw-bold">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php if($riwayat_checkin && mysqli_num_rows($riwayat_checkin) > 0): ?>
                            <?php while($c = mysqli_fetch_assoc($riwayat_checkin)): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3 border-0">
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span style="font-size: 13px;"><?php echo date('d M Y', strtotime($c['tanggal_checkin'])); ?></span>
                                </span>
                                <span class="text-muted" style="font-size: 13px;"><?php echo date('H:i', strtotime($c['tanggal_checkin'])); ?> WIB</span>
                            </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li class="list-group-item text-center text-muted py-4">Belum ada check-in</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments & Reminder Row -->
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold">Riwayat Pembayaran Terakhir</h6>
                    <a href="pembayaran/" class="text-primary small text-decoration-none fw-bold">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-2 px-3" style="font-size: 12px;">Tanggal</th>
                                    <th class="border-0 py-2 px-3" style="font-size: 12px;">Deskripsi</th>
                                    <th class="border-0 py-2 px-3" style="font-size: 12px;">Jumlah</th>
                                    <th class="border-0 py-2 px-3" style="font-size: 12px;">Metode</th>
                                    <th class="border-0 py-2 px-3" style="font-size: 12px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($riwayat_pembayaran && mysqli_num_rows($riwayat_pembayaran) > 0): ?>
                                    <?php while($p = mysqli_fetch_assoc($riwayat_pembayaran)): ?>
                                    <tr>
                                        <td class="px-3 py-2" style="font-size: 13px;"><?php echo date('d M Y', strtotime($p['tanggal_bayar'])); ?></td>
                                        <td class="px-3 py-2 fw-bold" style="font-size: 13px;"><?php echo $p['nama_paket']; ?></td>
                                        <td class="px-3 py-2" style="font-size: 13px;"><?php echo formatRupiah($p['total_harga']); ?></td>
                                        <td class="px-3 py-2" style="font-size: 13px;">Transfer Bank</td>
                                        <td class="px-3 py-2"><span class="badge bg-success bg-opacity-10 text-success" style="font-size: 11px;">Lunas</span></td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pembayaran</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 h-100 text-center p-4">
                <h6 class="text-muted mb-3 fw-bold"><i class="fas fa-bell text-warning me-2"></i>Pengingat</h6>
                <p class="mb-2" style="font-size: 13px;">Membership Anda akan expired dalam</p>
                <h2 class="fw-bold text-primary mb-1"><?php echo $transaksi_aktif['sisa_hari']; ?> Hari</h2>
                <p class="text-muted small mb-4"><?php echo date('d M Y', strtotime($transaksi_aktif['tanggal_expired'])); ?></p>
                <a href="membership/perpanjang.php" class="btn btn-primary w-100 fw-bold">Perpanjang Sekarang</a>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Jika tidak ada membership aktif -->
    <div class="card border-0 text-center p-5">
        <i class="fas fa-id-card fa-4x text-muted mb-3"></i>
        <h3>Anda belum memiliki membership aktif</h3>
        <p class="text-muted mb-4">Silakan daftar untuk menikmati fasilitas Rebound Gym Jambi.</p>
        <a href="membership/perpanjang.php" class="btn btn-primary btn-lg px-5">Daftar Membership Sekarang</a>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>