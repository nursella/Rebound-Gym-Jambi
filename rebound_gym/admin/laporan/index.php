<?php
require_once '../../config/config.php';
checkAdmin();

$page_title = "Laporan";

// Filter tanggal
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

// Total pendapatan
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(total_harga) as total 
    FROM transaksi 
    WHERE status_pembayaran = 'lunas' 
    AND tanggal_bayar BETWEEN '$start_date' AND '$end_date'
"))['total'];

// Total transaksi
$total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM transaksi 
    WHERE status_pembayaran = 'lunas' 
    AND tanggal_bayar BETWEEN '$start_date' AND '$end_date'
"))['total'];

// Total member baru
$total_member_baru = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM member 
    WHERE tanggal_daftar BETWEEN '$start_date' AND '$end_date'
"))['total'];

// Total member aktif
$total_member_aktif = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(DISTINCT id_member) as total 
    FROM transaksi 
    WHERE status_membership = 'aktif'
"))['total'];

include '../../includes/header.php';
include '../../includes/sidebar-admin.php';
?>

<div class="content-wrapper" style="margin-left: 250px;">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold"><i class="fas fa-chart-bar me-2"></i>Laporan</h2>
            </div>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <a href="cetak.php?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" 
                           class="btn btn-success" target="_blank">
                            <i class="fas fa-print me-2"></i>Cetak
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2">Total Pendapatan</h6>
                        <h2 class="mb-0 fw-bold"><?php echo formatRupiah($total_pendapatan ?: 0); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2">Total Transaksi</h6>
                        <h2 class="mb-0 fw-bold"><?php echo $total_transaksi; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2">Member Baru</h6>
                        <h2 class="mb-0 fw-bold"><?php echo $total_member_baru; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2">Member Aktif</h6>
                        <h2 class="mb-0 fw-bold"><?php echo $total_member_aktif; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Transaksi -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Detail Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Member</th>
                                <th>Paket</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $transaksis = mysqli_query($conn, "
                                SELECT t.*, m.nama as nama_member, p.nama_paket
                                FROM transaksi t
                                JOIN member m ON t.id_member = m.id_member
                                JOIN paket p ON t.id_paket = p.id_paket
                                WHERE t.tanggal_bayar BETWEEN '$start_date' AND '$end_date'
                                AND t.status_pembayaran = 'lunas'
                                ORDER BY t.tanggal_bayar DESC
                            ");
                            
                            while ($transaksi = mysqli_fetch_assoc($transaksis)):
                            ?>
                            <tr>
                                <td><?php echo formatTanggal($transaksi['tanggal_bayar']); ?></td>
                                <td><?php echo $transaksi['nama_member']; ?></td>
                                <td><?php echo $transaksi['nama_paket']; ?></td>
                                <td><?php echo formatRupiah($transaksi['total_harga']); ?></td>
                                <td><span class="badge bg-success">Lunas</span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>