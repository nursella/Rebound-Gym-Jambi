<?php
require_once '../../config/config.php';
checkLogin();
$page_title = 'Perpanjang Membership';

$id_member = $_SESSION['user_id'];

// Fungsi untuk menentukan fasilitas berdasarkan paket
function getFasilitasByPaket($nama_paket, $harga) {
    $fasilitas = [];
    
    // Deteksi tipe paket
    $is_basic = (stripos($nama_paket, 'basic') !== false || $harga <= 150000);
    $is_gold = (stripos($nama_paket, 'gold') !== false || ($harga > 150000 && $harga <= 350000));
    $is_premium = (stripos($nama_paket, 'premium') !== false || $harga > 350000);
    
    // Semua paket mendapat ini
    $fasilitas[] = ['text' => 'Akses penuh fasilitas gym', 'icon' => 'fa-dumbbell'];
    $fasilitas[] = ['text' => 'Free locker & shower', 'icon' => 'fa-lock'];
    $fasilitas[] = ['text' => 'Free parking', 'icon' => 'fa-parking'];
    
    // Gold dan Premium
    if ($is_gold || $is_premium) {
        $fasilitas[] = ['text' => 'Konsultasi dengan trainer', 'icon' => 'fa-users'];
        $fasilitas[] = ['text' => 'Akses kelas group fitness', 'icon' => 'fa-calendar-check'];
    }
    
    // Khusus Premium
    if ($is_premium) {
        $fasilitas[] = ['text' => 'Akses sauna & steam room', 'icon' => 'fa-spa'];
        $fasilitas[] = ['text' => 'Personal trainer (2x/bulan)', 'icon' => 'fa-user-ninja'];
        $fasilitas[] = ['text' => 'Free protein shake', 'icon' => 'fa-glass-water'];
    }
    
    return $fasilitas;
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['perpanjang'])) {
    $id_paket = (int)$_POST['id_paket'];
    $metode_pembayaran = escape($_POST['metode_pembayaran']);
    
    $paket = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM paket WHERE id_paket = '$id_paket'"));
    
    if ($paket) {
        $tanggal_mulai = date('Y-m-d');
        $tanggal_expired = date('Y-m-d', strtotime("+{$paket['durasi_hari']} days"));
        
        $query = "INSERT INTO transaksi (id_member, id_paket, tanggal_mulai, tanggal_expired, total_harga, metode_pembayaran, status_pembayaran, status_membership) 
                  VALUES ('$id_member', '$id_paket', '$tanggal_mulai', '$tanggal_expired', '{$paket['harga']}', '$metode_pembayaran', 'pending', 'pending')";
        
        if (mysqli_query($conn, $query)) {
            redirect('../pembayaran/', 'Permintaan perpanjangan berhasil! Silakan lakukan pembayaran.', 'success');
        } else {
            $error = "Terjadi kesalahan: " . mysqli_error($conn);
        }
    }
}

// Ambil paket tanpa duplikasi
$pakets = mysqli_query($conn, "SELECT * FROM paket GROUP BY id_paket ORDER BY durasi_hari ASC, harga ASC");

include '../../includes/header.php';
include '../../includes/sidebar-member.php';
?>

<div class="content-wrapper">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="font-size: 28px; color: #1e293b;">Perpanjang Membership</h3>
            <p class="text-muted mb-0" style="font-size: 14px;">Pilih paket membership yang sesuai dengan kebutuhan Anda</p>
        </div>
        <a href="index.php" class="btn btn-light">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <?php if(isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Info Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(99, 102, 241, 0.1));">
        <div class="card-body p-4">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px;">
                    <i class="fas fa-info-circle text-primary fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Cara Perpanjangan</h6>
                    <p class="mb-0 text-muted" style="font-size: 13px;">Pilih paket, lakukan pembayaran, dan admin akan mengkonfirmasi dalam 1x24 jam.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Package Selection -->
    <h5 class="fw-bold mb-3" style="color: #1e293b;">Pilih Paket Membership</h5>
    <div class="row g-4 mb-4">
        <?php while($paket = mysqli_fetch_assoc($pakets)): 
            $fasilitas = getFasilitasByPaket($paket['nama_paket'], $paket['harga']);
            $is_premium = ($paket['harga'] > 350000);
            $is_gold = ($paket['harga'] > 150000 && $paket['harga'] <= 350000);
            
            // Tentukan warna berdasarkan tipe paket
            if($is_premium) {
                $warna_gradient = '#fbbf24, #f59e0b';
                $warna_teks = '#f59e0b';
                $badge = 'BEST VALUE';
            } elseif($is_gold) {
                $warna_gradient = '#60a5fa, #3b82f6';
                $warna_teks = '#3b82f6';
                $badge = '';
            } else {
                $warna_gradient = '#94a3b8, #64748b';
                $warna_teks = '#64748b';
                $badge = '';
            }
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm position-relative" style="border-radius: 12px; transition: all 0.3s;">
                
                <?php if($badge): ?>
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 20px; font-size: 11px;">
                        <i class="fas fa-star me-1"></i><?php echo $badge; ?>
                    </span>
                </div>
                <?php endif; ?>

                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 70px; height: 70px; background: linear-gradient(135deg, <?php echo $warna_gradient; ?>);">
                            <i class="fas fa-crown text-white fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #1e293b;"><?php echo htmlspecialchars($paket['nama_paket']); ?></h5>
                        <div class="mb-3">
                            <span class="display-6 fw-bold" style="color: <?php echo $warna_teks; ?>">
                                <?php echo formatRupiah($paket['harga']); ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-center align-items-center gap-2 text-muted">
                            <i class="far fa-calendar-alt"></i>
                            <span><?php echo $paket['durasi_hari']; ?> Hari</span>
                            <span class="text-muted">|</span>
                            <span><?php echo round($paket['durasi_hari']/30); ?> Bulan</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <ul class="list-unstyled mb-4">
                        <?php foreach($fasilitas as $fas): ?>
                        <li class="mb-3 d-flex align-items-start">
                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                 style="width: 24px; height: 24px;">
                                <i class="fas fa-check text-success" style="font-size: 11px;"></i>
                            </div>
                            <span class="text-muted" style="font-size: 13px; line-height: 1.4;"><?php echo htmlspecialchars($fas['text']); ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <button type="button" class="btn btn-primary w-100 py-2 fw-bold" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalPaket<?php echo $paket['id_paket']; ?>"
                            style="background: linear-gradient(135deg, <?php echo $warna_gradient; ?>); border: none;">
                        Pilih Paket Ini
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi -->
        <div class="modal fade" id="modalPaket<?php echo $paket['id_paket']; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $paket['id_paket']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="modalLabel<?php echo $paket['id_paket']; ?>">Konfirmasi Perpanjangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="perpanjang" value="1">
                        <input type="hidden" name="id_paket" value="<?php echo $paket['id_paket']; ?>">
                        <div class="modal-body">
                            <div class="alert bg-light mb-3">
                                <h6 class="fw-bold mb-2"><?php echo htmlspecialchars($paket['nama_paket']); ?></h6>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Durasi:</span>
                                    <span class="fw-bold"><?php echo $paket['durasi_hari']; ?> hari</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Total Pembayaran:</span>
                                    <span class="fw-bold text-primary"><?php echo formatRupiah($paket['harga']); ?></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Metode Pembayaran</label>
                                <select name="metode_pembayaran" class="form-select" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="transfer_bank">Transfer Bank (BCA/Mandiri/BRI)</option>
                                    <option value="qris">QRIS (All Payment)</option>
                                    <option value="cash">Tunai (Bayar di Lokasi)</option>
                                </select>
                            </div>

                            <div class="alert alert-info mb-0" style="font-size: 13px;">
                                <i class="fas fa-info-circle me-2"></i>
                                Setelah submit, silakan lakukan pembayaran sesuai metode yang dipilih.
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4" style="background: linear-gradient(135deg, #8b5cf6, #6366f1); border: none;">
                                <i class="fas fa-check me-2"></i>Lanjutkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- FAQ Section -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="mb-0 fw-bold"><i class="fas fa-question-circle me-2 text-primary"></i>Pertanyaan Umum</h6>
        </div>
        <div class="card-body">
            <div class="accordion accordion-flush" id="accordionFAQ">
                <div class="accordion-item border-0 mb-2">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            Berapa lama proses aktivasi membership?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-muted">
                            Membership akan diaktifkan maksimal 1x24 jam setelah pembayaran dikonfirmasi oleh admin.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-2">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Apakah bisa perpanjang sebelum expired?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-muted">
                            Ya, Anda bisa perpanjang kapan saja. Masa aktif akan ditambahkan dari tanggal expired sebelumnya.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.accordion-button:not(.collapsed) {
    background-color: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
    box-shadow: none;
}
.accordion-button:focus {
    box-shadow: none;
    border-color: rgba(139, 92, 246, 0.1);
}
</style>

<?php include '../../includes/footer.php'; ?>