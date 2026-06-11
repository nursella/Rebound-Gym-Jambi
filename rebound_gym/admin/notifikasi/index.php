<?php
require_once '../../config/config.php';
checkAdmin();

$page_title = "Notifikasi";

$notifikasis = mysqli_query($conn, "SELECT n.*, m.nama as nama_member, t.status_membership 
                                    FROM notifikasi n
                                    JOIN transaksi t ON n.id_transaksi = t.id_transaksi
                                    JOIN member m ON t.id_member = m.id_member
                                    ORDER BY n.created_at DESC
                                    LIMIT 50");

include '../../includes/header.php';
include '../../includes/sidebar-admin.php';
?>

<div class="content-wrapper" style="margin-left: 250px;">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold"><i class="fas fa-bell me-2"></i>Riwayat Notifikasi</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal Kirim</th>
                                <th>Member</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Pesan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($notif = mysqli_fetch_assoc($notifikasis)): ?>
                            <tr>
                                <td><?php echo $notif['tanggal_kirim']; ?></td>
                                <td><?php echo $notif['nama_member']; ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $notif['jenis'] == 'h-7' ? 'info' : 
                                            ($notif['jenis'] == 'h-3' ? 'warning' : 
                                            ($notif['jenis'] == 'expired' ? 'danger' : 'secondary'));
                                    ?>">
                                        <?php 
                                        echo $notif['jenis'] == 'h-7' ? 'Pengingat H-7' : 
                                            ($notif['jenis'] == 'h-3' ? 'Pengingat H-3' : 
                                            ($notif['jenis'] == 'expired' ? 'Expired' : 'Follow Up'));
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $notif['status'] == 'terkirim' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($notif['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo substr($notif['pesan'], 0, 100); ?>...</td>
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