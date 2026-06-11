<?php
require_once '../../config/config.php';
checkLogin();
$page_title = 'Profil Saya';

$id = $_SESSION['user_id'];
$member = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM member WHERE id_member = '$id'"));

include '../../includes/header.php';
include '../../includes/sidebar-member.php';
?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="font-size: 28px;">Profil Saya</h3>
            <p class="text-muted mb-0" style="font-size: 14px;">Kelola informasi pribadi Anda</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center p-4" style="border-radius: 12px;">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 36px; font-weight: bold; background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white;">
                    <?php echo strtoupper(substr($member['nama'], 0, 1)); ?>
                </div>
                <h4 class="fw-bold mb-1"><?php echo $member['nama']; ?></h4>
                <p class="text-muted mb-3"><?php echo $member['email']; ?></p>
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2" style="border-radius: 20px; font-size: 12px;">Member Aktif</span>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 py-3" style="border-radius: 12px 12px 0 0;">
                    <h6 class="mb-0 fw-bold">Informasi Pribadi</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted py-3" style="width: 35%;">Nama Lengkap</td>
                            <td class="py-3 fw-bold"><?php echo $member['nama']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Email</td>
                            <td class="py-2"><?php echo $member['email']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">No. WhatsApp</td>
                            <td class="py-2"><?php echo $member['nomor_whatsapp']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Alamat</td>
                            <td class="py-2"><?php echo $member['alamat']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Tanggal Bergabung</td>
                            <td class="py-2"><?php echo date('d M Y', strtotime($member['tanggal_daftar'])); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>