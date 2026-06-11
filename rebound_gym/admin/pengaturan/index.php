<?php
require_once '../../config/config.php';
checkAdmin();
$page_title = "Pengaturan Sistem";

// Buat tabel settings jika belum ada
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS settings (setting_key VARCHAR(50) PRIMARY KEY, setting_value TEXT)");

// Handle Simpan
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan_settings'])){
    $data = [
        'nama_gym' => escape($_POST['nama_gym']),
        'alamat' => escape($_POST['alamat']),
        'no_telp' => escape($_POST['no_telp']),
        'email' => escape($_POST['email'])
    ];
    
    foreach($data as $key => $val){
        mysqli_query($conn, "INSERT INTO settings (setting_key, setting_value) VALUES ('$key', '$val') ON DUPLICATE KEY UPDATE setting_value='$val'");
    }
    redirect('pengaturan/', 'Pengaturan berhasil disimpan!', 'success');
}

// Ambil data
$current = [];
$res = mysqli_query($conn, "SELECT * FROM settings");
while($row = mysqli_fetch_assoc($res)) $current[$row['setting_key']] = $row['setting_value'];

include '../../includes/header.php';
include '../../includes/sidebar-admin.php';
?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Pengaturan Sistem</h3>
            <p class="text-muted">Konfigurasi informasi gym dan sistem.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">Informasi Gym</h6>
                </div>
                <div class="card-body">
                    <form method="POST" id="formSettings">
                        <input type="hidden" name="simpan_settings" value="1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Gym</label>
                                <input type="text" name="nama_gym" class="form-control" value="<?php echo $current['nama_gym'] ?? 'Rebound Gym Jambi'; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">No. Telepon / WhatsApp</label>
                                <input type="text" name="no_telp" id="inputNoTelp" class="form-control" value="<?php echo $current['no_telp'] ?? ''; ?>" placeholder="Contoh: 081234567890">
                                <!-- Pesan Error -->
                                <div id="errorNoTelp" class="invalid-feedback d-none" style="font-size: 12px; color: #ef4444; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle me-1"></i>Hanya angka yang diperbolehkan!
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="3"><?php echo $current['alamat'] ?? 'Jl. Jendral Sudirman No. 123, Jambi'; ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Email Resmi</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $current['email'] ?? 'admin@reboundgym.com'; ?>">
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" id="btnSimpan" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">Info Server</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-4 py-3 d-flex justify-content-between">
                            <span class="text-muted">Versi PHP</span>
                            <span class="fw-bold"><?php echo phpversion(); ?></span>
                        </li>
                        <li class="list-group-item px-4 py-3 d-flex justify-content-between">
                            <span class="text-muted">Database</span>
                            <span class="fw-bold">MariaDB / MySQL</span>
                        </li>
                        <li class="list-group-item px-4 py-3 d-flex justify-content-between">
                            <span class="text-muted">Timezone</span>
                            <span class="fw-bold">Asia/Jakarta</span>
                        </li>
                        <li class="list-group-item px-4 py-3 d-flex justify-content-between">
                            <span class="text-muted">Status Cron</span>
                            <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Validasi No. Telepon -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputNoTelp = document.getElementById('inputNoTelp');
    const errorNoTelp = document.getElementById('errorNoTelp');
    const formSettings = document.getElementById('formSettings');
    const btnSimpan = document.getElementById('btnSimpan');
    
    // Fungsi untuk cek apakah input hanya angka
    function validatePhoneNumber(value) {
        // Hapus semua karakter non-angka (spasi, dash, dll)
        const cleaned = value.replace(/[^0-9]/g, '');
        return cleaned === value || value === '';
    }
    
    // Event listener saat user mengetik
    inputNoTelp.addEventListener('input', function() {
        const value = this.value;
        
        if (!validatePhoneNumber(value)) {
            // Tampilkan error
            this.classList.add('is-invalid');
            errorNoTelp.classList.remove('d-none');
            errorNoTelp.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Hanya angka yang diperbolehkan!';
        } else {
            // Sembunyikan error
            this.classList.remove('is-invalid');
            errorNoTelp.classList.add('d-none');
        }
    });
    
    // Event listener saat form di-submit
    formSettings.addEventListener('submit', function(e) {
        const value = inputNoTelp.value;
        
        if (!validatePhoneNumber(value) && value !== '') {
            e.preventDefault(); // Cegah submit
            inputNoTelp.classList.add('is-invalid');
            errorNoTelp.classList.remove('d-none');
            inputNoTelp.focus();
            
            // Animasi shake pada input
            inputNoTelp.style.animation = 'shake 0.5s';
            setTimeout(() => {
                inputNoTelp.style.animation = '';
            }, 500);
        }
    });
    
    // Optional: Auto-format saat blur (fokus keluar dari input)
    inputNoTelp.addEventListener('blur', function() {
        // Hapus karakter non-angka otomatis
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>

<!-- CSS untuk animasi shake -->
<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}
</style>

<?php include '../../includes/footer.php'; ?>