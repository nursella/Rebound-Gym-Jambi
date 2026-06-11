<?php
require_once '../../config/config.php';
checkAdmin();

$page_title = "Transaksi";

$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$where = "WHERE 1=1";
if ($status_filter) {
    $where .= " AND t.status_pembayaran = '$status_filter'";
}

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi t $where");
$total = mysqli_fetch_assoc($result)['total'];
$total_pages = ceil($total / $limit);

$query = "SELECT t.*, m.nama as nama_member, p.nama_paket 
          FROM transaksi t
          JOIN member m ON t.id_member = m.id_member
          JOIN paket p ON t.id_paket = p.id_paket
          $where
          ORDER BY t.created_at DESC
          LIMIT $start, $limit";
$transaksis = mysqli_query($conn, $query);

include '../../includes/header.php';
include '../../includes/sidebar-admin.php';
?>

<div class="content-wrapper" style="margin-left: 250px;">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold"><i class="fas fa-exchange-alt me-2"></i>Transaksi</h2>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="lunas" <?php echo $status_filter == 'lunas' ? 'selected' : ''; ?>>Lunas</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Member</th>
                                <th>Paket</th>
                                <th>Tanggal Bayar</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Status Membership</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = $start + 1;
                            while ($transaksi = mysqli_fetch_assoc($transaksis)): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $transaksi['nama_member']; ?></td>
                                <td><?php echo $transaksi['nama_paket']; ?></td>
                                <td><?php echo $transaksi['tanggal_bayar'] ? formatTanggal($transaksi['tanggal_bayar']) : '-'; ?></td>
                                <td><?php echo formatRupiah($transaksi['total_harga']); ?></td>
                                <td><?php echo ucfirst(str_replace('_', ' ', $transaksi['metode_pembayaran'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $transaksi['status_pembayaran'] == 'lunas' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($transaksi['status_pembayaran']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $transaksi['status_membership'] == 'aktif' ? 'success' : ($transaksi['status_membership'] == 'expired' ? 'danger' : 'secondary'); ?>">
                                        <?php echo ucfirst($transaksi['status_membership']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="detail.php?id=<?php echo $transaksi['id_transaksi']; ?>" 
                                       class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($transaksi['status_pembayaran'] == 'pending'): ?>
                                    <a href="konfirmasi.php?id=<?php echo $transaksi['id_transaksi']; ?>" 
                                       class="btn btn-sm btn-success" title="Konfirmasi"
                                       onclick="return confirm('Konfirmasi pembayaran ini?')">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
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