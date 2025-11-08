<?php
include '../includes/admin_layout.php';

if (!isset($_GET['id'])) {
    header('Location: pesanan.php');
    exit();
}

$pesanan_id = $_GET['id'];

// Ambil data pesanan
$stmt = $pdo->prepare("SELECT p.*, u.nama_lengkap, u.email, u.no_telepon 
                       FROM pesanan p 
                       JOIN users u ON p.user_id = u.id 
                       WHERE p.id = ?");
$stmt->execute([$pesanan_id]);
$pesanan = $stmt->fetch();

if (!$pesanan) {
    header('Location: pesanan.php');
    exit();
}

// Ambil detail pesanan
$stmt = $pdo->prepare("SELECT dp.*, pr.nama_produk, pr.gambar 
                       FROM detail_pesanan dp 
                       JOIN produk pr ON dp.produk_id = pr.id 
                       WHERE dp.pesanan_id = ?");
$stmt->execute([$pesanan_id]);
$detail_pesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Update status pesanan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE pesanan SET status = ? WHERE id = ?");
    $stmt->execute([$status, $pesanan_id]);
    
    $_SESSION['success'] = "Status pesanan berhasil diperbarui!";
    header("Location: detail_pesanan.php?id=$pesanan_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= $pesanan['id'] ?> - Siomay Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container">
        <div class="admin-section">
            <div class="order-header">
                <h1>Detail Pesanan #<?= $pesanan['id'] ?></h1>
                <a href="pesanan.php" class="btn">Kembali ke Daftar Pesanan</a>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <div class="order-detail-admin">
                <div class="customer-info">
                    <h3>Informasi Customer</h3>
                    <p><strong>Nama:</strong> <?= $pesanan['nama_lengkap'] ?></p>
                    <p><strong>Email:</strong> <?= $pesanan['email'] ?></p>
                    <p><strong>No Telepon:</strong> <?= $pesanan['no_telepon'] ?></p>
                    <p><strong>Alamat Pengiriman:</strong> <?= $pesanan['alamat_pengiriman'] ?></p>
                </div>
                
                <div class="order-status">
                    <h3>Status Pesanan</h3>
                    <form method="POST">
                        <div class="form-group">
                            <select name="status" class="status-select">
                                <option value="pending" <?= $pesanan['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="diproses" <?= $pesanan['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                <option value="dikirim" <?= $pesanan['status'] == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                <option value="selesai" <?= $pesanan['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                            <button type="submit" name="update_status" class="btn-primary">Update Status</button>
                        </div>
                    </form>
                    <p><strong>Tanggal Pesanan:</strong> <?= date('d F Y H:i', strtotime($pesanan['created_at'])) ?></p>
                </div>
            </div>
            
            <div class="order-items-admin">
                <h3>Item Pesanan</h3>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detail_pesanan as $item): ?>
                            <tr>
                                <td>
                                    <?php if ($item['gambar']): ?>
                                        <img src="../assets/images/<?= $item['gambar'] ?>" alt="<?= $item['nama_produk'] ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php else: ?>
                                        <div style="width: 50px; height: 50px; background: #eee; display: flex; align-items: center; justify-content: center;">
                                            <small>No Image</small>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= $item['nama_produk'] ?></td>
                                <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td><?= $item['jumlah'] ?></td>
                                <td>Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                            <td><strong>Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="order-actions-admin">
                <a href="pesanan.php" class="btn">Kembali ke Daftar Pesanan</a>
                <?php if ($pesanan['status'] == 'selesai'): ?>
                    <span class="status status-selesai">PESANAN SELESAI</span>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <style>
    .order-detail-admin {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin: 2rem 0;
    }
    
    .customer-info, .order-status {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .status-select {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-right: 1rem;
    }
    
    .order-actions-admin {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #eee;
    }
    
    @media (max-width: 768px) {
        .order-detail-admin {
            grid-template-columns: 1fr;
        }
        
        .order-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .order-actions-admin {
            flex-direction: column;
            gap: 1rem;
        }
    }
    </style>

    <?php include '../includes/admin_footer.php'; ?>
</body>
</html>