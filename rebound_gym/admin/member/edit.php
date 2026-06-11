<?php
require_once '../../config/config.php';
checkAdmin();
$page_title = 'Edit Member';

$id = (int)$_GET['id'];
$m = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM member WHERE id_member='$id'"));
if(!$m) redirect('member/', 'Member tidak ditemukan!', 'error');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $nama = escape($_POST['nama']);
    $email = escape($_POST['email']);
    $alamat = escape($_POST['alamat']);
    $no_wa = escape($_POST['nomor_whatsapp']);
    $status = escape($_POST['status_aktif']);
    
    $query = "UPDATE member SET nama='$nama', email='$email', alamat='$alamat', nomor_whatsapp='$no_wa', status_aktif='$status' WHERE id_member='$id'";
    
    if(!empty($_POST['password'])){
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "UPDATE member SET nama='$nama', email='$email', password='$pass', alamat='$alamat', nomor_whatsapp='$no_wa', status_aktif='$status' WHERE id_member='$id'";
    }
    
    if(mysqli_query($conn, $query)){
        redirect('member/', 'Member berhasil diupdate!', 'success');
    }
}

include '../../includes/header.php';
include '../../includes/sidebar-admin.php';
?>

<div class="content-wrapper">
    <div class="page-header">
        <h2>Edit Member</h2>
        <p>Update data member: <?php echo $m['nama']; ?></p>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?php echo $m['nama']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $m['email']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control" minlength="6">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor WhatsApp</label>
                    <input type="text" name="nomor_whatsapp" class="form-control" value="<?php echo $m['nomor_whatsapp']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status Aktif</label>
                    <select name="status_aktif" class="form-select">
                        <option value="aktif" <?php echo $m['status_aktif']=='aktif'?'selected':''; ?>>Aktif</option>
                        <option value="nonaktif" <?php echo $m['status_aktif']=='nonaktif'?'selected':''; ?>>Nonaktif</option>
                        <option value="expired" <?php echo $m['status_aktif']=='expired'?'selected':''; ?>>Expired</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" required><?php echo $m['alamat']; ?></textarea>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Member</button>
                    <a href="index.php" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>