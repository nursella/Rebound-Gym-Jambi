<?php
require_once '../../config/config.php';
checkAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_paket = mysqli_real_escape_string($conn, $_POST['nama_paket']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $durasi_hari = mysqli_real_escape_string($conn, $_POST['durasi_hari']);
    
    $query = "INSERT INTO paket (nama_paket, harga, durasi_hari) VALUES ('$nama_paket', '$harga', '$durasi_hari')";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Paket berhasil ditambahkan!";
        header("Location: index.php");
        exit();
    } else {
        $error = "Terjadi kesalahan!";
    }
}

include '../../includes/header.php';
include '../../includes/sidebar-admin.php';
?>

<div class="content-wrapper" style="margin-left: 250px;">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold"><i class="fas fa-plus me-2"></i>Tambah Paket</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Paket</label>
                        <input type="text" name="nama_paket" class="form-control" required 
                               placeholder="Contoh: Premium 1 Bulan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" required 
                               placeholder="Contoh: 350000">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durasi (Hari)</label>
                        <input type="number" name="durasi_hari" class="form-control" required 
                               placeholder="Contoh: 30">
                        <small class="text-muted">30 hari = 1 bulan, 90 hari = 3 bulan, dst</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>