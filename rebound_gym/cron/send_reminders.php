<?php
require_once '../config/config.php';
$today = date('Y-m-d');
echo "=== CRON JOB STARTED: ".date('Y-m-d H:i:s')." ===\n";

// H-7
$res = mysqli_query($conn, "SELECT t.*, m.nama, p.nama_paket FROM transaksi t JOIN member m ON t.id_member=m.id_member JOIN paket p ON t.id_paket=p.id_paket WHERE t.status_membership='aktif' AND DATEDIFF(t.tanggal_expired, '$today')=7");
while($r = mysqli_fetch_assoc($res)){
    $cek = mysqli_query($conn, "SELECT id_notifikasi FROM notifikasi WHERE id_transaksi='{$r['id_transaksi']}' AND jenis='h-7'");
    if(mysqli_num_rows($cek)==0){
        $msg = "Halo {$r['nama']}! 🏋️‍♂️\n\nMembership {$r['nama_paket']} Anda berakhir pada ".formatTanggal($r['tanggal_expired'])." (7 hari lagi).\n\nCara Perpanjang:\n1. Login ke website\n2. Klik 'Perpanjang Sekarang'\n3. Pilih paket & bayar\n\nTerima kasih! 💪";
        mysqli_query($conn, "INSERT INTO notifikasi (id_transaksi, jenis, pesan, tanggal_kirim, status) VALUES ('{$r['id_transaksi']}', 'h-7', '".escape($msg)."', NOW(), 'terkirim')");
        echo "- H-7 sent to: {$r['nama']}\n";
    }
}

// H-3
$res = mysqli_query($conn, "SELECT t.*, m.nama, p.nama_paket FROM transaksi t JOIN member m ON t.id_member=m.id_member JOIN paket p ON t.id_paket=p.id_paket WHERE t.status_membership='aktif' AND DATEDIFF(t.tanggal_expired, '$today')=3");
while($r = mysqli_fetch_assoc($res)){
    $cek = mysqli_query($conn, "SELECT id_notifikasi FROM notifikasi WHERE id_transaksi='{$r['id_transaksi']}' AND jenis='h-3'");
    if(mysqli_num_rows($cek)==0){
        $msg = "URGENT! ⚠️ {$r['nama']}\n\nMembership {$r['nama_paket']} berakhir dalam 3 hari!\nExpired: ".formatTanggal($r['tanggal_expired'])."\n\nSegera perpanjang agar akses tidak terputus.\nLogin -> Riwayat Membership -> Perpanjang.\n\nJangan sampai terlewat! 🔥";
        mysqli_query($conn, "INSERT INTO notifikasi (id_transaksi, jenis, pesan, tanggal_kirim, status) VALUES ('{$r['id_transaksi']}', 'h-3', '".escape($msg)."', NOW(), 'terkirim')");
        echo "- H-3 sent to: {$r['nama']}\n";
    }
}

// Auto Expire & Follow Up
mysqli_query($conn, "UPDATE transaksi SET status_membership='expired' WHERE status_membership='aktif' AND tanggal_expired < '$today'");
mysqli_query($conn, "UPDATE member m JOIN transaksi t ON m.id_member=t.id_member SET m.status_aktif='expired' WHERE t.status_membership='expired' AND m.status_aktif='aktif'");
echo "- Auto expired updated.\n";

$res = mysqli_query($conn, "SELECT t.*, m.nama, m.nomor_whatsapp, p.nama_paket FROM transaksi t JOIN member m ON t.id_member=m.id_member JOIN paket p ON t.id_paket=p.id_paket WHERE t.status_membership='expired' AND DATEDIFF('$today', t.tanggal_expired)=4");
while($r = mysqli_fetch_assoc($res)){
    $cek = mysqli_query($conn, "SELECT id_notifikasi FROM notifikasi WHERE id_transaksi='{$r['id_transaksi']}' AND jenis='followup'");
    if(mysqli_num_rows($cek)==0){
        $msg = "ALERT FOLLOW UP! \nMember: {$r['nama']}\nPaket: {$r['nama_paket']}\nExpired: ".formatTanggal($r['tanggal_expired'])."\nSudah 4 hari belum perpanjang.\nNo. WA: {$r['nomor_whatsapp']}\n\nSegera hubungi member!";
        mysqli_query($conn, "INSERT INTO notifikasi (id_transaksi, jenis, pesan, tanggal_kirim, status) VALUES ('{$r['id_transaksi']}', 'followup', '".escape($msg)."', NOW(), 'terkirim')");
        echo "- Follow up alert for: {$r['nama']}\n";
    }
}

echo "=== CRON JOB FINISHED ===\n";
?>