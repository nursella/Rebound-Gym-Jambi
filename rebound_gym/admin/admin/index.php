<?php
require_once '../../config/config.php';
checkAdmin();
$page_title = "Kelola Admin";

// Handle Tambah Admin
if(isset($_POST['tambah_admin'])){
    $username = escape($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = escape($_POST['role']);
    mysqli_query($conn, "INSERT INTO owner (username, password, role) VALUES ('$username', '$password', '$role')");
    redirect('admin/', 'Admin berhasil ditambahkan!', 'success');
}

$admins = mysqli_query($conn, "SELECT * FROM owner ORDER BY created_at DESC");
include '../../includes/header.php';
include '../../includes/sidebar-admin.php';
?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Kelola Admin</h3>
            <p class="text-muted">Manajemen akun administrator sistem.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
            <i class="fas fa-plus me-2"></i>Tambah Admin
        </button>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Username</th>
                            <th>Role</th>
                            <th>Dibuat</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($a = mysqli_fetch_assoc($admins)): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?php echo $a['username']; ?></td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary"><?php echo ucfirst($a['role']); ?></span></td>
                            <td class="text-muted"><?php echo date('d M Y', strtotime($a['created_at'])); ?></td>
                            <td class="pe-4 text-end">
                                <?php if($a['role'] !== 'super_admin'): ?>
                                <button class="btn btn-sm btn-light text-danger" onclick="confirmDelete('hapus_admin.php?id=<?php echo $a['id_owner']; ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="addAdminModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Admin Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="tambah_admin" value="1">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <select name="role" class="form-select">
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>