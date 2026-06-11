<?php
require_once '../../config/config.php';
checkAdmin();
$page_title = 'Paket Membership';

// Gunakan GROUP BY untuk menghindari duplikasi
$pakets = mysqli_query($conn, "SELECT * FROM paket GROUP BY id_paket ORDER BY created_at DESC");

include '../../includes/header.php';
include '../../includes/sidebar-admin.php';
?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Paket Membership</h3>
            <p class="text-muted mb-0">Kelola paket membership yang tersedia</p>
        </div>
        <a href="tambah.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Paket
        </a>
    </div>

    <div class="row g-4">
        <?php while($paket = mysqli_fetch_assoc($pakets)): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px; background: linear-gradient(135deg, #8b5cf6, #6366f1);">
                            <i class="fas fa-box text-white"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="edit.php?id=<?php echo $paket['id_paket']; ?>">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="hapus.php?id=<?php echo $paket['id_paket']; ?>" 
                                     onclick="return confirm('Hapus paket ini?')">
                                    <i class="fas fa-trash me-2"></i>Hapus
                                </a></li>
                            </ul>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-2"><?php echo $paket['nama_paket']; ?></h5>
                    
                    <div class="mb-3">
                        <span class="display-6 fw-bold" style="color: #8b5cf6;">
                            <?php echo formatRupiah($paket['harga']); ?>
                        </span>
                    </div>

                    <div class="d-flex gap-2 mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                            <i class="far fa-calendar me-1"></i><?php echo $paket['durasi_hari']; ?> Hari
                        </span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                            <i class="far fa-clock me-1"></i><?php echo round($paket['durasi_hari']/30); ?> Bulan
                        </span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Dibuat: <?php echo date('d/m/Y', strtotime($paket['created_at'])); ?></small>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>