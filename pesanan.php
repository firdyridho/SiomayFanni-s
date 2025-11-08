<?php
include 'includes/auth.php';
include 'config/database.php';
redirectIfNotLoggedIn();

// Ambil data pesanan user
$stmt = $pdo->prepare("SELECT p.*, COUNT(dp.id) as jumlah_item 
                       FROM pesanan p 
                       LEFT JOIN detail_pesanan dp ON p.id = dp.pesanan_id 
                       WHERE p.user_id = ? 
                       GROUP BY p.id 
                       ORDER BY p.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$pesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Siomay Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>Pesanan Saya</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"><?= $_SESSION['success'] ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (empty($pesanan)): ?>
            <p>Belum ada pesanan.</p>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($pesanan as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <strong>No. Pesanan: #<?= $order['id'] ?></strong>
                                <span class="status status-<?= $order['status'] ?>"><?= strtoupper($order['status']) ?></span>
                            </div>
                            <div>
                                <small>Tanggal: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small>
                            </div>
                        </div>
                        
                        <div class="order-body">
                            <p><strong>Total: Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></strong></p>
                            <p><strong>Jumlah Item: <?= $order['jumlah_item'] ?></strong></p>
                            <p><strong>Alamat:</strong> <?= $order['alamat_pengiriman'] ?></p>
                        </div>
                        
                        <div class="order-actions">
                            <a href="detail_pesanan.php?id=<?= $order['id'] ?>" class="btn">Lihat Detail</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>