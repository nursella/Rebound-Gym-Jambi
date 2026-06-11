<?php
require_once '../../config/config.php';
checkAdmin();
$page_title = 'Tambah Member';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $nama = escape($_POST['nama']);
    $email = escape($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $alamat = escape($_POST['alamat']);
    $no_wa = escape($_POST['nomor_whatsapp']);
    $tanggal_daftar = date('Y-m-d');
    
    $cek = mysqli_query($conn, "SELECT id_member FROM member WHERE email='$email'");
    if(mysqli_num_rows($cek)>0){
        redirect('member/tambah.php', 'Email sudah terdaftar!', 'error');
    }
    
    if(mysqli_query($conn, "INSERT INTO member (nama, email, password, alamat, nomor_whatsapp, tanggal_daftar, status_aktif) VALUES ('$nama', '$email', '$password', '$alamat', '$no_wa', '$tanggal_daftar', 'nonaktif')")){
        redirect('member/', 'Member berhasil ditambahkan!', 'success');
    } else {
        redirect('member/tambah.php', 'Gagal menambah member!', 'error');
    }
}

include '../../includes/header.php';
include '../../includes/sidebar-admin.php';
?>

<div class="content-wrapper">
    <div class="page-header">
        <h2>Tambah Member Baru</h2>
        <p>Isi data member dengan lengkap dan benar.</p>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor WhatsApp</label>
                    <input type="text" name="nomor_whatsapp" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" required></textarea>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Member</button>
                    <a href="index.php" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>