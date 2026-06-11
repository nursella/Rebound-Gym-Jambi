<?php
require_once '../config/config.php';
checkAdmin();
$page_title = 'Dashboard';

// Statistik
$total_member = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM member"))['total'];
$member_aktif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM member WHERE status_aktif = 'aktif'"))['total'];
$pendapatan_hari = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_harga),0) as total FROM transaksi WHERE DATE(tanggal_bayar) = CURDATE() AND status_pembayaran = 'lunas'"))['total'];
$pendapatan_bulan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_harga),0) as total FROM transaksi WHERE MONTH(tanggal_bayar) = MONTH(CURDATE()) AND status_pembayaran = 'lunas'"))['total'];
$total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE status_pembayaran = 'lunas'"))['total'];
$rata_rata = $total_transaksi > 0 ? $pendapatan_bulan / $total_transaksi : 0;

$member_akan_expired = mysqli_query($conn, "SELECT m.nama, p.nama_paket, t.tanggal_expired, DATEDIFF(t.tanggal_expired, CURDATE()) as sisa_hari FROM transaksi t JOIN member m ON t.id_member = m.id_member JOIN paket p ON t.id_paket = p.id_paket WHERE t.status_membership = 'aktif' AND DATEDIFF(t.tanggal_expired, CURDATE()) BETWEEN 0 AND 7 ORDER BY t.tanggal_expired ASC LIMIT 5");

$member_expired = mysqli_query($conn, "SELECT m.nama, p.nama_paket, t.tanggal_expired FROM transaksi t JOIN member m ON t.id_member = m.id_member JOIN paket p ON t.id_paket = p.id_paket WHERE t.status_membership = 'expired' ORDER BY t.tanggal_expired DESC LIMIT 5");

$pakets = mysqli_query($conn, "SELECT p.nama_paket, p.harga, COUNT(t.id_transaksi) as total FROM transaksi t JOIN paket p ON t.id_paket=p.id_paket GROUP BY t.id_paket ORDER BY total DESC LIMIT 5");

include '../includes/header.php';
include '../includes/sidebar-admin.php';
?>

<div class="content-wrapper">
    <!-- Top Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Hai Admin!!</h2>
            <p class="text-muted mb-0" style="font-size: 13px;">Ringkasan informasi Rebound Gym</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center bg-white px-3 py-2 rounded border">
                <i class="far fa-calendar text-muted me-2"></i>
                <span class="small fw-bold"><?php echo date('d M Y'); ?></span>
                <i class="fas fa-chevron-down text-muted ms-2" style="font-size: 10px;"></i>
            </div>
            <div class="position-relative bg-white rounded-circle p-2 border" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                <i class="far fa-bell text-muted"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px; padding: 3px 5px;">3</span>
            </div>
        </div>
    </div>

    <!-- Stat Cards Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card h-100 border-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-circle d-flex align-items-center justify-content-center" style="background: #8b5cf6; width: 48px; height: 48px;">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1" style="font-size: 12px;">Total Member</small>
                        <h3 class="fw-bold mb-0" style="font-size: 24px;"><?php echo $total_member; ?></h3>
                        <small class="text-muted" style="font-size: 11px;">Semua member terdaftar</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100 border-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-circle d-flex align-items-center justify-content-center" style="background: #10b981; width: 48px; height: 48px;">
                        <i class="fas fa-user-check text-white"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1" style="font-size: 12px;">Member Aktif</small>
                        <h3 class="fw-bold mb-0" style="font-size: 24px;"><?php echo $member_aktif; ?></h3>
                        <small class="text-muted" style="font-size: 11px;">Member dengan status aktif</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100 border-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-circle d-flex align-items-center justify-content-center" style="background: #f59e0b; width: 48px; height: 48px;">
                        <i class="fas fa-wallet text-white"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1" style="font-size: 12px;">Pendapatan Hari Ini</small>
                        <h3 class="fw-bold mb-0" style="font-size: 24px;"><?php echo formatRupiah($pendapatan_hari); ?></h3>
                        <small class="text-muted" style="font-size: 11px;">Total pendapatan hari ini</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100 border-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-circle d-flex align-items-center justify-content-center" style="background: #8b5cf6; width: 48px; height: 48px;">
                        <i class="fas fa-calendar-check text-white"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1" style="font-size: 12px;">Pendapatan Bulan Ini</small>
                        <h3 class="fw-bold mb-0" style="font-size: 24px;"><?php echo formatRupiah($pendapatan_bulan); ?></h3>
                        <small class="text-muted" style="font-size: 11px;">Total pendapatan bulan ini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-clock text-warning me-2"></i>Member Akan Expired (7 Hari)</h6>
                    <a href="#" class="text-primary small text-decoration-none fw-bold">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-2 px-3" style="font-size: 12px;">Nama</th>
                                    <th class="border-0 py-2 px-3" style="font-size: 12px;">Paket</th>
                                    <th class="border-0 py-2 px-3" style="font-size: 12px;">Expired</th>
                                    <th class="border-0 py-2 px-3" style="font-size: 12px;">Sisa Hari</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($r = mysqli_fetch_assoc($member_akan_expired)): ?>
                                <tr>
                                    <td class="px-3 py-2 fw-bold" style="font-size: 13px;"><?php echo $r['nama']; ?></td>
                                    <td class="px-3 py-2" style="font-size: 13px;"><?php echo $r['nama_paket']; ?></td>
                                    <td class="px-3 py-2" style="font-size: 13px;"><?php echo date('d/m/Y', strtotime($r['tanggal_expired'])); ?></td>
                                    <td class="px-3 py-2"><span class="badge bg-warning bg-opacity-10 text-warning" style="font-size: 11px;"><?php echo $r['sisa_hari']; ?> hari</span></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php if(mysqli_num_rows($member_akan_expired)==0): ?>
                                <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada data</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-times-circle text-danger me-2"></i>Member Expired</h6>
                    <a href="#" class="text-primary small text-decoration-none fw-bold">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-2 px-3" style="font-size: 12px;">Nama</th>
                                    <th class="border-0 py-2 px-3" style="font-size: 12px;">Paket</th>
                                    <th class="border-0 py-2 px-3" style="font-size: 12px;">Expired</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($r = mysqli_fetch_assoc($member_expired)): ?>
                                <tr>
                                    <td class="px-3 py-2 fw-bold" style="font-size: 13px;"><?php echo $r['nama']; ?></td>
                                    <td class="px-3 py-2" style="font-size: 13px;"><?php echo $r['nama_paket']; ?></td>
                                    <td class="px-3 py-2 text-danger fw-bold" style="font-size: 13px;"><?php echo date('d/m/Y', strtotime($r['tanggal_expired'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php if(mysqli_num_rows($member_expired)==0): ?>
                                <tr><td colspan="3" class="text-center text-muted py-4">Tidak ada data</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paket Terpopuler -->
    <div class="card border-0 mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="mb-0 fw-bold"><i class="fas fa-crown text-primary me-2"></i>Paket Terpopuler</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php $no = 1; while($p = mysqli_fetch_assoc($pakets)): 
                    $colors = ['#f59e0b', '#94a3b8', '#f59e0b', '#8b5cf6', '#f59e0b'];
                    $bg = $colors[$no-1] ?? '#8b5cf6';
                ?>
                <div class="col-md-2">
                    <div class="p-3 rounded text-center" style="background: #fef3c7;">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 28px; height: 28px; background: <?php echo $bg; ?>; color: white; font-size: 12px; font-weight: bold;"><?php echo $no++; ?></div>
                        <h6 class="fw-bold mb-1" style="font-size: 13px;"><?php echo $p['nama_paket']; ?></h6>
                        <small class="text-muted d-block"><?php echo $p['total']; ?> transaksi</small>
                        <div class="fw-bold text-primary mt-1" style="font-size: 13px;"><?php echo formatRupiah($p['harga']); ?></div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Chart Row -->
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-chart-line text-primary me-2"></i>Grafik Pendapatan (6 Bulan Terakhir)</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 mb-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-receipt text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block" style="font-size: 12px;">Total Transaksi</small>
                        <h5 class="fw-bold mb-0"><?php echo $total_transaksi; ?></h5>
                        <small class="text-muted">Transaksi</small>
                    </div>
                </div>
            </div>
            <div class="card border-0 mb-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block" style="font-size: 12px;">Pendapatan Total</small>
                        <h5 class="fw-bold mb-0"><?php echo formatRupiah($pendapatan_bulan); ?></h5>
                        <small class="text-muted">Total Pendapatan</small>
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="fas fa-calculator text-info"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block" style="font-size: 12px;">Rata-rata Transaksi</small>
                        <h5 class="fw-bold mb-0"><?php echo formatRupiah($rata_rata); ?></h5>
                        <small class="text-muted">Per Transaksi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Des 2023', 'Jan 2024', 'Feb 2024', 'Mar 2024', 'Apr 2024', 'Mei 2024'],
            datasets: [{
                label: 'Pendapatan',
                data: [15000000, 20000000, 22000000, 35000000, 32000000, 48750000],
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#8b5cf6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { 
                y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#e2e8f0' } }, 
                x: { grid: { display: false } } 
            }
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>