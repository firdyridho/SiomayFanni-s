<?php
include 'includes/auth.php';
include 'config/database.php';
redirectIfNotLoggedIn();

// Handle actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $produk_id = $_GET['id'] ?? 0;
    
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }
    
    switch ($action) {
        case 'add':
            if (isset($_SESSION['keranjang'][$produk_id])) {
                $_SESSION['keranjang'][$produk_id]++;
            } else {
                $_SESSION['keranjang'][$produk_id] = 1;
            }
            break;
        case 'remove':
            unset($_SESSION['keranjang'][$produk_id]);
            break;
        case 'update':
            if (isset($_POST['jumlah'])) {
                foreach ($_POST['jumlah'] as $produk_id => $jumlah) {
                    if ($jumlah > 0) {
                        $_SESSION['keranjang'][$produk_id] = $jumlah;
                    } else {
                        unset($_SESSION['keranjang'][$produk_id]);
                    }
                }
            }
            break;
    }
    
    header('Location: keranjang.php');
    exit();
}

// Calculate total
$total = 0;
if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
    $placeholders = str_repeat('?,', count($_SESSION['keranjang']) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM produk WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($_SESSION['keranjang']));
    $produk_keranjang = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($produk_keranjang as $produk) {
        $total += $produk['harga'] * $_SESSION['keranjang'][$produk['id']];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Siomay Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>Keranjang Belanja</h1>
        
        <?php if (empty($_SESSION['keranjang'])): ?>
            <p>Keranjang belanja Anda kosong.</p>
        <?php else: ?>
            <form method="POST" action="keranjang.php?action=update">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produk_keranjang as $produk): ?>
                            <tr>
                                <td><?= $produk['nama_produk'] ?></td>
                                <td>Rp <?= number_format($produk['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <input type="number" name="jumlah[<?= $produk['id'] ?>]" 
                                           value="<?= $_SESSION['keranjang'][$produk['id']] ?>" min="1" max="<?= $produk['stok'] ?>">
                                </td>
                                <td>Rp <?= number_format($produk['harga'] * $_SESSION['keranjang'][$produk['id']], 0, ',', '.') ?></td>
                                <td>
                                    <a href="keranjang.php?action=remove&id=<?= $produk['id'] ?>" class="btn-danger">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="cart-total">
                    <h3>Total: Rp <?= number_format($total, 0, ',', '.') ?></h3>
                </div>
                
                <div class="cart-actions">
                    <button type="submit" class="btn">Update Keranjang</button>
                    <a href="checkout.php" class="btn-primary">Checkout</a>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>