<?php
require_once '../../config/config.php';
checkLogin();
$page_title = 'Riwayat Membership';
$id = $_SESSION['user_id'];
$riwayat = mysqli_query($conn, "SELECT t.*, p.nama_paket FROM transaksi t JOIN paket p ON t.id_paket=p.id_paket WHERE t.id_member='$id' ORDER BY t.created_at DESC");

include '../../includes/header.php';
include '../../includes/sidebar-member.php';
?>
<div class="content-wrapper">
    <div class="page-header"><h2>Riwayat Membership</h2><p>Histori paket dan status keanggotaan Anda.</p></div>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead><tr><th>Paket</th><th>Tanggal Mulai</th><th>Expired</th><th>Status</th><th>Total</th></tr></thead>
                <tbody>
                    <?php while($r = mysqli_fetch_assoc($riwayat)): ?>
                    <tr>
                        <td><strong><?php echo $r['nama_paket']; ?></strong></td>
                        <td><?php echo formatTanggal($r['tanggal_mulai']); ?></td>
                        <td><?php echo formatTanggal($r['tanggal_expired']); ?></td>
                        <td><span class="badge bg-<?php echo $r['status_membership']=='aktif'?'success':'danger'; ?>"><?php echo ucfirst($r['status_membership']); ?></span></td>
                        <td><?php echo formatRupiah($r['total_harga']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($riwayat)==0): ?><tr><td colspan="5" class="text-center text-muted">Belum ada riwayat membership.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>