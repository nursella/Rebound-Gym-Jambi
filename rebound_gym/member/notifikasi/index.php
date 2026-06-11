<?php
require_once '../../config/config.php';
checkLogin();
$page_title = 'Notifikasi';
$id = $_SESSION['user_id'];

if(isset($_GET['mark_all'])){
    mysqli_query($conn, "UPDATE notifikasi n JOIN transaksi t ON n.id_transaksi=t.id_transaksi SET n.status='dibaca' WHERE t.id_member='$id'");
    redirect('index.php', 'Semua notifikasi ditandai sudah dibaca.', 'success');
}

$notifs = mysqli_query($conn, "SELECT n.*, t.status_membership FROM notifikasi n JOIN transaksi t ON n.id_transaksi=t.id_transaksi WHERE t.id_member='$id' ORDER BY n.created_at DESC");

include '../../includes/header.php';
include '../../includes/sidebar-member.php';
?>
<div class="content-wrapper">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div><h2>Notifikasi</h2><p>Informasi penting terkait membership Anda.</p></div>
        <a href="?mark_all=1" class="btn btn-sm btn-outline-primary">Tandai Semua Dibaca</a>
    </div>
    <div class="card">
        <div class="card-body">
            <?php while($n = mysqli_fetch_assoc($notifs)): ?>
            <div class="alert alert-<?php echo $n['jenis']=='expired'?'danger':($n['jenis']=='followup'?'warning':'info'); ?> d-flex justify-content-between align-items-start mb-3">
                <div>
                    <strong><?php echo ucfirst($n['jenis']); ?></strong>
                    <p class="mb-0 mt-1"><?php echo nl2br($n['pesan']); ?></p>
                    <small class="text-muted"><?php echo $n['tanggal_kirim']; ?></small>
                </div>
                <span class="badge bg-<?php echo $n['status']=='dibaca'?'secondary':'danger'; ?> ms-3"><?php echo ucfirst($n['status']); ?></span>
            </div>
            <?php endwhile; ?>
            <?php if(mysqli_num_rows($notifs)==0): ?>
            <div class="text-center text-muted py-5"><i class="fas fa-bell-slash fa-3x mb-3"></i><p>Belum ada notifikasi.</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>