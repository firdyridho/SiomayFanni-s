<?php
include '../includes/admin_layout.php';

// Update status pesanan
if (isset($_POST['update_status'])) {
    $pesanan_id = $_POST['pesanan_id'];
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE pesanan SET status = ? WHERE id = ?");
    $stmt->execute([$status, $pesanan_id]);
    
    $_SESSION['success'] = "Status pesanan berhasil diperbarui!";
    header('Location: pesanan.php');
    exit();
}

// Ambil data pesanan
$pesanan = $pdo->query("SELECT p.*, u.nama_lengkap, u.no_telepon 
                       FROM pesanan p 
                       JOIN users u ON p.user_id = u.id 
                       ORDER BY p.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Siomay Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container">
        <h1>Kelola Pesanan</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"><?= $_SESSION['success'] ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <div class="admin-section">
            <h2>Daftar Pesanan</h2>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>No Pesanan</th>
                        <th>Customer</th>
                        <th>Telepon</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pesanan as $order): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= $order['nama_lengkap'] ?></td>
                            <td><?= $order['no_telepon'] ?></td>
                            <td>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="pesanan_id" value="<?= $order['id'] ?>">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="diproses" <?= $order['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                        <option value="dikirim" <?= $order['status'] == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                        <option value="selesai" <?= $order['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td>
                                <a href="detail_pesanan.php?id=<?= $order['id'] ?>" class="btn">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include '../includes/admin_footer.php'; ?>
</body>
</html>