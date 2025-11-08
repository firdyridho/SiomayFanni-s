<?php
include 'includes/auth.php';
include 'config/database.php';
redirectIfNotLoggedIn();

if (!isset($_GET['id'])) {
    header('Location: pesanan.php');
    exit();
}

$pesanan_id = $_GET['id'];

// Ambil data pesanan
$stmt = $pdo->prepare("SELECT p.*, u.nama_lengkap 
                       FROM pesanan p 
                       JOIN users u ON p.user_id = u.id 
                       WHERE p.id = ? AND (p.user_id = ? OR ? = 'admin')");
$stmt->execute([$pesanan_id, $_SESSION['user_id'], $_SESSION['role']]);
$pesanan = $stmt->fetch();

if (!$pesanan) {
    header('Location: pesanan.php');
    exit();
}

// Ambil detail pesanan
$stmt = $pdo->prepare("SELECT dp.*, pr.nama_produk 
                       FROM detail_pesanan dp 
                       JOIN produk pr ON dp.produk_id = pr.id 
                       WHERE dp.pesanan_id = ?");
$stmt->execute([$pesanan_id]);
$detail_pesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Siomay Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>Detail Pesanan #<?= $pesanan['id'] ?></h1>
        
        <div class="order-detail">
            <div class="order-info">
                <h3>Informasi Pesanan</h3>
                <p><strong>Status:</strong> <span class="status status-<?= $pesanan['status'] ?>"><?= strtoupper($pesanan['status']) ?></span></p>
                <p><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($pesanan['created_at'])) ?></p>
                <p><strong>Nama Customer:</strong> <?= $pesanan['nama_lengkap'] ?></p>
                <p><strong>Alamat Pengiriman:</strong> <?= $pesanan['alamat_pengiriman'] ?></p>
            </div>
            
            <div class="order-items">
                <h3>Item Pesanan</h3>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detail_pesanan as $item): ?>
                            <tr>
                                <td><?= $item['nama_produk'] ?></td>
                                <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td><?= $item['jumlah'] ?></td>
                                <td>Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                            <td><strong>Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="order-actions">
            <a href="pesanan.php" class="btn">Kembali ke Daftar Pesanan</a>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>