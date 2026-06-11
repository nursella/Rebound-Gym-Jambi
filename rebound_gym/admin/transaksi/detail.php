<?php
require_once '../../config/config.php';
checkAdmin();

$id = $_GET['id'];
$transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT t.*, m.nama as nama_member, m.email, m.nomor_whatsapp, 
                                                    m.alamat, p.nama_paket, p.harga, p.durasi_hari
                                                    FROM transaksi t
                                                    JOIN member m ON t.id_member = m.id_member
                                                    JOIN paket p ON t.id_paket = p.id_paket
                                                    WHERE t.id_transaksi = '$id'"));

$notifikasis = mysqli_query($conn, "SELECT * FROM notifikasi WHERE id_transaksi = '$id' ORDER BY created_at DESC");

include '../../includes/header.php';
include '../../includes/sidebar-admin.php';
?>

<div class="content-wrapper" style="margin-left: 250px;">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold"><i class="fas fa-receipt me-2"></i>Detail Transaksi</h2>
                <a href="index.php" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Informasi Transaksi</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted">ID Transaksi</td>
                                <td class="fw-bold">#<?php echo $transaksi['id_transaksi']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Mulai</td>
                                <td><?php echo formatTanggal($transaksi['tanggal_mulai']); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Expired</td>
                                <td><?php echo formatTanggal($transaksi['tanggal_expired']); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Harga</td>
                                <td class="fw-bold text-primary"><?php echo formatRupiah($transaksi['total_harga']); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Bayar</td>
                                <td><?php echo $transaksi['tanggal_bayar'] ? formatTanggal($transaksi['tanggal_bayar']) : 'Belum bayar'; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Metode Pembayaran</td>
                                <td><?php echo ucfirst(str_replace('_', ' ', $transaksi['metode_pembayaran'])); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status Pembayaran</td>
                                <td>
                                    <span class="badge bg-<?php echo $transaksi['status_pembayaran'] == 'lunas' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($transaksi['status_pembayaran']); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status Membership</td>
                                <td>
                                    <span class="badge bg-<?php echo $transaksi['status_membership'] == 'aktif' ? 'success' : 'danger'; ?>">
                                        <?php echo ucfirst($transaksi['status_membership']); ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Informasi Member</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted">Nama</td>
                                <td class="fw-bold"><?php echo $transaksi['nama_member']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td><?php echo $transaksi['email']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">No. WhatsApp</td>
                                <td><?php echo $transaksi['nomor_whatsapp']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Alamat</td>
                                <td><?php echo $transaksi['alamat']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Informasi Paket</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted">Nama Paket</td>
                                <td class="fw-bold"><?php echo $transaksi['nama_paket']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga Normal</td>
                                <td><?php echo formatRupiah($transaksi['harga']); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Durasi</td>
                                <td><?php echo $transaksi['durasi_hari']; ?> hari</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Riwayat Notifikasi</h5>
            </div>
            <div class="card-body">
                <?php while ($notif = mysqli_fetch_assoc($notifikasis)): ?>
                <div class="alert alert-<?php echo $notif['jenis'] == 'expired' ? 'danger' : ($notif['jenis'] == 'followup' ? 'warning' : 'info'); ?>">
                    <small class="text-muted"><?php echo $notif['tanggal_kirim']; ?></small>
                    <p class="mb-0 mt-2"><?php echo nl2br($notif['pesan']); ?></p>
                </div>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($notifikasis) == 0): ?>
                <p class="text-muted mb-0">Belum ada notifikasi terkirim</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>