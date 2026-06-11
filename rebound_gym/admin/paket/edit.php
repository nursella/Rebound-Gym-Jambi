<?php
require_once '../../config/config.php';
checkAdmin();

$id = $_GET['id'];
$paket = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM paket WHERE id_paket = '$id'"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_paket = mysqli_real_escape_string($conn, $_POST['nama_paket']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $durasi_hari = mysqli_real_escape_string($conn, $_POST['durasi_hari']);
    
    $query = "UPDATE paket SET nama_paket = '$nama_paket', harga = '$harga', durasi_hari = '$durasi_hari' 
              WHERE id_paket = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Paket berhasil diupdate!";
        header("Location: index.php");
        exit();
    }
}

include '../../includes/header.php';
include '../../includes/sidebar-admin.php';
?>

<div class="content-wrapper" style="margin-left: 250px;">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold"><i class="fas fa-edit me-2"></i>Edit Paket</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Paket</label>
                        <input type="text" name="nama_paket" class="form-control" required 
                               value="<?php echo $paket['nama_paket']; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" required 
                               value="<?php echo $paket['harga']; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durasi (Hari)</label>
                        <input type="number" name="durasi_hari" class="form-control" required 
                               value="<?php echo $paket['durasi_hari']; ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update
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