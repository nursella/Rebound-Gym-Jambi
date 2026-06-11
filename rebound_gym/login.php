<?php
require_once 'config/config.php';
// Cek jika sudah login, redirect ke dashboard masing-masing
if(isset($_SESSION['user_id'])) {
    if($_SESSION['role'] == 'admin') header("Location: admin/dashboard.php");
    else header("Location: member/dashboard.php");
    exit();
}

$error = '';
// Default tab adalah member
$active_tab = isset($_GET['mode']) ? $_GET['mode'] : 'member';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = escape($_POST['email']);
    $pass = $_POST['password'];
    $role = escape($_POST['login_as']);
    
    if($role == 'admin') {
        // Login Admin
        $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM owner WHERE username='$email'"));
        if($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id_owner'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = 'admin';
            redirect('admin/dashboard.php');
        }
    } else {
        // Login Member
        $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM member WHERE email='$email'"));
        if($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id_member'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'member';
            redirect('member/dashboard.php');
        }
    }
    $error = "Email/Username atau Password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rebound Gym Jambi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
    body {
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.4s ease;
    }

    /* Tampilan Member (Light Mode) */
    body.light-mode { background-color: #f1f5f9; }
    body.light-mode .login-card { background: white; box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
    body.light-mode .form-control { background: #f8fafc; border: 1px solid #e2e8f0; color: #1e293b; }
    body.light-mode .form-label { color: #475569; }
    body.light-mode h2 { color: #1e293b; }

    /* Tampilan Admin (Dark Mode) */
    body.dark-mode { background-color: #0f172a; }
    body.dark-mode .login-card {
        background: #1e293b;
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        border: 1px solid #334155;
    }
    body.dark-mode .form-control {
        background: #0f172a;
        border: 1px solid #334155;
        color: white;
    }
    body.dark-mode .form-control::placeholder { color: #64748b; }
    body.dark-mode .form-label { color: #cbd5e1; }
    body.dark-mode h2 { color: #ffffff; }
    body.dark-mode .text-muted { color: #94a3b8 !important; }
    body.dark-mode .badge-system { color: #4ade80; }
    body.dark-mode .nav-link { color: #94a3b8; }
    body.dark-mode .nav-link.active { background-color: #16a34a; color: white; }
    body.dark-mode .bg-light { background-color: #0f172a !important; }

    /* General Styles */
    .login-card {
        width: 450px;
        max-width: 95%;
        padding: 40px;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    /* Logo Container - UKURAN SAMA */
    .logo-container {
        text-align: center;
        margin-bottom: 20px;
    }
    
    .logo-img {
        max-width: 140px;  /* UKURAN SAMA untuk Admin & Member */
        height: auto;
        transition: all 0.3s ease;
    }

    /* Title Section - DI ATAS */
    .title-section {
        text-align: center;
        margin-bottom: 25px;
    }
    
    .title-section h2 {
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .title-section p {
        font-size: 14px;
        margin: 0;
    }

    .system-badge {
        background: rgba(22, 163, 74, 0.15);
        color: #16a34a;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-bottom: 15px;
    }

    /* Tabs */
    .nav-pills .nav-link {
        border-radius: 8px;
        color: #64748b;
        font-weight: 600;
        font-size: 14px;
        padding: 12px;
    }
    .nav-pills .nav-link.active {
        background-color: #16a34a;
        color: white;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
    }

    /* Form */
    .form-section {
        margin-top: 25px;
    }
    
    .input-group-text {
        background-color: transparent;
        border: 1px solid #e2e8f0;
        border-right: none;
        color: #94a3b8;
        border-radius: 8px 0 0 8px;
    }
    .form-control {
        border-left: none;
        border-radius: 0 8px 8px 0;
        padding: 10px 15px;
    }
    .form-control:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15);
    }
    body.dark-mode .input-group-text {
        border-color: #334155;
    }

    /* Button */
    .btn-login {
        background-color: #16a34a;
        border: none;
        font-weight: 600;
        padding: 12px;
        font-size: 15px;
        border-radius: 8px;
        transition: all 0.2s;
        margin-top: 10px;
    }
    .btn-login:hover {
        background-color: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(22, 163, 74, 0.4);
    }
    
    /* Alert */
    .alert {
        border-radius: 10px;
        font-size: 14px;
    }
</style>
</head>
<body class="<?php echo ($active_tab == 'admin') ? 'dark-mode' : 'light-mode'; ?>" id="bodyTag">
    
    <div class="login-card">
    <!-- Logo Section -->
    <div class="logo-container">
        <img id="dynamicLogo" src="assets/images/logo_rebound_gym.png" alt="Logo" class="logo-img">
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger py-3 mb-3 text-center">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="title-section">
        <!-- Admin Badge (hanya muncul di tab admin) -->
        <div id="adminBadge" class="<?php echo ($active_tab == 'admin') ? '' : 'd-none'; ?>">
            <span class="system-badge">SYSTEM ADMIN ACCESS</span>
        </div>
        
        <!-- Member Title -->
        <div id="memberTitle" class="<?php echo ($active_tab == 'member') ? '' : 'd-none'; ?>">
            <h2 class="fw-bold mb-1">Login Member</h2>
            <p class="text-muted">Masuk ke akun member Anda</p>
        </div>
        
        <!-- Admin Title -->
        <div id="adminTitle" class="<?php echo ($active_tab == 'admin') ? '' : 'd-none'; ?>">
            <h2 class="fw-bold mb-1">Login Admin</h2>
            <p class="text-muted">Masuk ke akun administrator</p>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-pills nav-fill mb-4 bg-light p-1 rounded-3" id="loginTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link <?php echo ($active_tab == 'member') ? 'active' : ''; ?>" 
                    id="member-tab" data-bs-toggle="pill" data-bs-target="#memberTab" type="button" 
                    onclick="switchTheme('member')">
                <i class="fas fa-user me-2"></i>Member
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?php echo ($active_tab == 'admin') ? 'active' : ''; ?>" 
                    id="admin-tab" data-bs-toggle="pill" data-bs-target="#adminTab" type="button" 
                    onclick="switchTheme('admin')">
                <i class="fas fa-shield-alt me-2"></i>Admin
            </button>
        </li>
    </ul>

    <div class="tab-content" id="loginTabContent">
        <!-- FORM MEMBER -->
        <div class="tab-pane fade <?php echo ($active_tab == 'member') ? 'show active' : ''; ?>" id="memberTab" role="tabpanel">
            <div class="form-section">
                <form method="POST">
                    <input type="hidden" name="login_as" value="member">
                    <div class="mb-3">
                        <label class="form-label small">Email Member</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="far fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-login btn-success w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk
                    </button>
                </form>
                <div class="text-center mt-4">
                    <small class="text-muted">Belum punya akun? <a href="register_member.php" class="text-success fw-bold">Daftar sekarang</a></small>
                </div>
            </div>
        </div>

        <!-- FORM ADMIN -->
        <div class="tab-pane fade <?php echo ($active_tab == 'admin') ? 'show active' : ''; ?>" id="adminTab" role="tabpanel">
            <div class="form-section">
                <form method="POST">
                    <input type="hidden" name="login_as" value="admin">
                    <div class="mb-3">
                        <label class="form-label small">Username Admin</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                            <input type="text" name="email" class="form-control" placeholder="Masukkan username" value="admin" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-login btn-success w-100">
                        <i class="fas fa-shield-alt me-2"></i>Masuk Dashboard
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Fungsi Ganti Tema & Logo
    function switchTheme(mode) {
        const body = document.getElementById('bodyTag');
        const logo = document.getElementById('dynamicLogo');
        const memberTitle = document.getElementById('memberTitle');
        const adminTitle = document.getElementById('adminTitle');
        const adminBadge = document.getElementById('adminBadge');
        
        if (mode === 'admin') {
            body.classList.remove('light-mode');
            body.classList.add('dark-mode');
            logo.src = 'assets/images/logo_rebound_gym2.png';
            
            // Show/hide titles
            memberTitle.classList.add('d-none');
            adminTitle.classList.remove('d-none');
            adminBadge.classList.remove('d-none');
        } else {
            body.classList.remove('dark-mode');
            body.classList.add('light-mode');
            logo.src = 'assets/images/logo_rebound_gym.png';
            
            // Show/hide titles
            adminTitle.classList.add('d-none');
            memberTitle.classList.remove('d-none');
            adminBadge.classList.add('d-none');
        }
    }
    
    // Set logo dan title awal saat halaman pertama kali load
    document.addEventListener('DOMContentLoaded', function() {
        const body = document.getElementById('bodyTag');
        const logo = document.getElementById('dynamicLogo');
        const memberTitle = document.getElementById('memberTitle');
        const adminTitle = document.getElementById('adminTitle');
        const adminBadge = document.getElementById('adminBadge');
        
        if (body.classList.contains('dark-mode')) {
            logo.src = 'assets/images/logo_rebound_gym2.png';
            memberTitle.classList.add('d-none');
            adminTitle.classList.remove('d-none');
            adminBadge.classList.remove('d-none');
        } else {
            logo.src = 'assets/images/logo_rebound_gym.png';
            adminTitle.classList.add('d-none');
            memberTitle.classList.remove('d-none');
            adminBadge.classList.add('d-none');
        }
    });
</script>
</body>
</html>