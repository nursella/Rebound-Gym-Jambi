<?php
require_once '../../config/config.php';
checkLogin();
$page_title = 'Pembayaran';
$id = $_SESSION['user_id'];
$pending = mysqli_query($conn, "SELECT t.*, p.nama_paket FROM transaksi t JOIN paket p ON t.id_paket=p.id_paket WHERE t.id_member='$id' AND t.status_pembayaran='pending' ORDER BY t.created_at DESC");
$lunas = mysqli_query($conn, "SELECT t.*, p.nama_paket FROM transaksi t JOIN paket p ON t.id_paket=p.id_paket WHERE t.id_member='$id' AND t.status_pembayaran='lunas' ORDER BY t.tanggal_bayar DESC LIMIT 5");

include '../../includes/header.php';
include '../../includes/sidebar-member.php';
?>
<div class="content-wrapper">
    <div class="page-header"><h2>Pembayaran</h2><p>Kelola status pembayaran membership Anda.</p></div>
    
    <h5 class="mb-3 text-danger"><i class="fas fa-clock me-2"></i>Pembayaran Pending</h5>
    <div class="card mb-4">
        <div class="card-body">
            <table class="table table-hover">
                <thead><tr><th>Paket</th><th>Tanggal Request</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php while($t = mysqli_fetch_assoc($pending)): ?>
                    <tr>
                        <td><?php echo $t['nama_paket']; ?></td>
                        <td><?php echo formatTanggal($t['created_at']); ?></td>
                        <td><?php echo formatRupiah($t['total_harga']); ?></td>
                        <td><span class="badge bg-warning">Menunggu Konfirmasi Admin</span></td>
                        <td><button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bayarModal<?php echo $t['id_transaksi']; ?>"><i class="fas fa-money-bill me-2"></i>Bayar</button></td>
                    </tr>
                    <div class="modal fade" id="bayarModal<?php echo $t['id_transaksi']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Konfirmasi Pembayaran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                <div class="modal-body">
                                    <p>Transfer sesuai nominal ke rekening berikut:</p>
                                    <div class="alert alert-light border">
                                        <strong>Bank BCA</strong><br>
                                        No. Rek: 123-456-7890<br>
                                        A/N: Rebound Gym Jambi<br>
                                        Nominal: <?php echo formatRupiah($t['total_harga']); ?>
                                    </div>
                                    <p class="text-muted small">Setelah transfer, tunggu konfirmasi dari admin (maks 1x24 jam).</p>
                                </div>
                                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($pending)==0): ?><tr><td colspan="5" class="text-center">Tidak ada pembayaran pending.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <h5 class="mb-3 text-success"><i class="fas fa-check-circle me-2"></i>Riwayat Lunas</h5>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead><tr><th>Tanggal</th><th>Paket</th><th>Jumlah</th><th>Metode</th><th>Status</th></tr></thead>
                <tbody>
                    <?php while($t = mysqli_fetch_assoc($lunas)): ?>
                    <tr>
                        <td><?php echo formatTanggal($t['tanggal_bayar']); ?></td>
                        <td><?php echo $t['nama_paket']; ?></td>
                        <td><?php echo formatRupiah($t['total_harga']); ?></td>
                        <td>Transfer Bank</td>
                        <td><span class="badge bg-success">Lunas</span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>