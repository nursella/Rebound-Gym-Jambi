<?php
require_once '../../config/config.php';
checkLogin();
$page_title = 'Paket Membership';

// Ambil paket dengan GROUP BY untuk menghindari duplikasi
$pakets = mysqli_query($conn, "SELECT * FROM paket GROUP BY id_paket ORDER BY durasi_hari ASC, harga ASC");

include '../../includes/header.php';
include '../../includes/sidebar-member.php';
?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Pilih Paket Membership</h3>
            <p class="text-muted mb-0">Pilih paket yang sesuai dengan kebutuhan Anda</p>
        </div>
        <a href="index.php" class="btn btn-light">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row g-4">
        <?php 
        $no = 1;
        while($paket = mysqli_fetch_assoc($pakets)): 
            // Tentukan badge berdasarkan durasi
            $badge = '';
            $badge_class = '';
            
            if($paket['durasi_hari'] == 30) {
                $badge = '1 Bulan';
                $badge_class = 'primary';
            } elseif($paket['durasi_hari'] == 90) {
                $badge = '3 Bulan';
                $badge_class = 'warning';
            } else {
                $badge = '6 Bulan';
                $badge_class = 'success';
            }
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; transition: all 0.3s;">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #8b5cf6, #6366f1);">
                        <i class="fas fa-crown text-white fa-2x"></i>
                    </div>
                    
                    <h5 class="fw-bold mb-2"><?php echo $paket['nama_paket']; ?></h5>
                    
                    <div class="mb-3">
                        <span class="display-5 fw-bold" style="color: #8b5cf6;">
                            <?php echo formatRupiah($paket['harga']); ?>
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-center align-items-center gap-2 text-muted mb-4">
                        <i class="far fa-calendar-alt"></i>
                        <span><?php echo $paket['durasi_hari']; ?> Hari</span>
                        <span class="text-muted">|</span>
                        <span><?php echo round($paket['durasi_hari']/30); ?> Bulan</span>
                    </div>

                    <hr class="my-4">

                    <ul class="list-unstyled mb-4 text-start">
                        <li class="mb-2 d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="text-muted">Akses penuh fasilitas gym</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="text-muted">Free locker & shower</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="text-muted">Konsultasi gratis dengan trainer</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="text-muted">Akses kelas group fitness</span>
                        </li>
                    </ul>

                    <button type="button" class="btn btn-primary w-100" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalPaket<?php echo $paket['id_paket']; ?>"
                            style="background: linear-gradient(135deg, #8b5cf6, #6366f1); border: none;">
                        Pilih Paket
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi -->
        <div class="modal fade" id="modalPaket<?php echo $paket['id_paket']; ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Konfirmasi Perpanjangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="perpanjang.php">
                        <input type="hidden" name="id_paket" value="<?php echo $paket['id_paket']; ?>">
                        <div class="modal-body">
                            <div class="alert bg-light mb-3">
                                <h6 class="fw-bold mb-2"><?php echo $paket['nama_paket']; ?></h6>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Durasi:</span>
                                    <span class="fw-bold"><?php echo $paket['durasi_hari']; ?> hari</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Total:</span>
                                    <span class="fw-bold text-primary"><?php echo formatRupiah($paket['harga']); ?></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Metode Pembayaran</label>
                                <select name="metode_pembayaran" class="form-select" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="transfer_bank">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                    <option value="cash">Cash</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php 
        $no++;
        endwhile; 
        ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>