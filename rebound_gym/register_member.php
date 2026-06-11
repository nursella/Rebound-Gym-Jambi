<?php
require_once 'config/config.php';

// Debug mode (hapus/comment setelah website live)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Ambil & bersihkan data
    $nama           = escape($_POST['nama'] ?? '');
    $email          = escape($_POST['email'] ?? '');
    $password       = $_POST['password'] ?? '';
    $konfirmasi     = $_POST['konfirmasi_password'] ?? '';
    $alamat         = escape($_POST['alamat'] ?? '');
    $no_wa          = escape($_POST['nomor_whatsapp'] ?? '');
    $tanggal_daftar = date('Y-m-d');

    // 2. Validasi
    if (empty($nama) || empty($email) || empty($password) || empty($no_wa)) {
        $error = "Semua field wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif ($password !== $konfirmasi) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        // 3. Cek apakah email sudah ada
        $cek_email = mysqli_query($conn, "SELECT id_member FROM member WHERE email = '$email'");
        if (mysqli_num_rows($cek_email) > 0) {
            $error = "Email sudah terdaftar! Silakan login atau gunakan email lain.";
        } else {
            // 4. Hash password & Insert ke database
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO member (nama, email, password, alamat, nomor_whatsapp, tanggal_daftar, status_aktif) 
                      VALUES ('$nama', '$email', '$hash_password', '$alamat', '$no_wa', '$tanggal_daftar', 'nonaktif')";
            
            if (mysqli_query($conn, $query)) {
                $success = "✅ Pendaftaran berhasil! Silakan login dengan akun Anda.";
                // Auto redirect ke login setelah 2 detik
                echo '<script>setTimeout(() => window.location.href="login.php?mode=member", 2000);</script>';
            } else {
                // Tampilkan error SQL jika gagal
                $error = "❌ Gagal menyimpan data: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Member - Rebound Gym Jambi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .register-card {
            width: 480px;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .btn-register {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            font-weight: 600;
            padding: 12px;
            transition: transform 0.2s;
        }
        .btn-register:hover { transform: translateY(-2px); color: white; }
        .form-control:focus { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.15); }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold mb-1">Daftar Member Baru</h3>
            <p class="text-muted small">Bergabunglah dengan Rebound Gym Jambi</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger py-2 mb-3"><i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success py-2 mb-3"><i class="fas fa-check-circle me-2"></i><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label small fw-bold">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required value="<?php echo $_POST['nama'] ?? ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Email</label>
                <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required value="<?php echo $_POST['email'] ?? ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Nomor WhatsApp</label>
                <input type="text" name="nomor_whatsapp" class="form-control" placeholder="08xxxxxxxxxx" required value="<?php echo $_POST['nomor_whatsapp'] ?? ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap"><?php echo $_POST['alamat'] ?? ''; ?></textarea>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                </div>
                <div class="col-6">
                    <label class="form-label small fw-bold">Konfirmasi</label>
                    <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-register btn-success w-100 mb-3">
                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
            </button>
            <div class="text-center">
                <small class="text-muted">Sudah punya akun? <a href="login.php?mode=member" class="text-success fw-bold">Login di sini</a></small>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>