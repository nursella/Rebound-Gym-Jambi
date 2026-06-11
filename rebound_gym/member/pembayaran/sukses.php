<?php
session_start();
$nomor_pesanan = $_GET['order'] ?? '';
$member_name = $_SESSION['member_name'] ?? 'Member';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Rebound Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px;
            text-align: center;
            max-width: 600px;
            width: 90%;
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: scaleIn 0.5s ease-out 0.2s both;
        }
        @keyframes scaleIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .success-icon i {
            font-size: 70px;
            color: white;
        }
        .btn-dashboard {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 15px 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            margin-top: 25px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="success-icon">
            <i class="bi bi-check-lg"></i>
        </div>
        <h2 class="mb-3">Pembayaran Berhasil!</h2>
        <p class="lead text-muted mb-4">
            Selamat <strong><?= htmlspecialchars($member_name) ?></strong>!<br>
            Membership Anda sudah aktif dan siap digunakan.
        </p>
        
        <div class="alert alert-success mb-4">
            <h5 class="mb-2"><i class="bi bi-receipt"></i> Detail Transaksi</h5>
            <p class="mb-1"><strong>Nomor Pesanan:</strong> #<?= strtoupper(htmlspecialchars($nomor_pesanan)) ?></p>
            <p class="mb-0"><strong>Status:</strong> <span class="badge bg-success">Aktif</span></p>
        </div>

        <p class="text-muted">
            Nikmati fasilitas Rebound Gym Jambi dan tetap konsisten dalam berlatih! 💪
        </p>

        <a href="../" class="btn-dashboard">
            <i class="bi bi-speedometer2"></i> Ke Dashboard
        </a>
    </div>
</body>
</html>