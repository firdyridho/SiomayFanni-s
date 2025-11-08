<?php
include 'includes/auth.php';
include 'config/database.php';
redirectIfNotLoggedIn();

if (empty($_SESSION['keranjang'])) {
    header('Location: keranjang.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();
        
        // Hitung total harga
        $total_harga = 0;
        $placeholders = str_repeat('?,', count($_SESSION['keranjang']) - 1) . '?';
        $stmt = $pdo->prepare("SELECT * FROM produk WHERE id IN ($placeholders)");
        $stmt->execute(array_keys($_SESSION['keranjang']));
        $produk_keranjang = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($produk_keranjang as $produk) {
            $total_harga += $produk['harga'] * $_SESSION['keranjang'][$produk['id']];
        }
        
        // Buat pesanan
        $stmt = $pdo->prepare("INSERT INTO pesanan (user_id, total_harga, alamat_pengiriman) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $total_harga, $_POST['alamat_pengiriman']]);
        $pesanan_id = $pdo->lastInsertId();
        
        // Simpan detail pesanan dan update stok
        foreach ($produk_keranjang as $produk) {
            $jumlah = $_SESSION['keranjang'][$produk['id']];
            $subtotal = $produk['harga'] * $jumlah;
            
            $stmt = $pdo->prepare("INSERT INTO detail_pesanan (pesanan_id, produk_id, jumlah, harga) VALUES (?, ?, ?, ?)");
            $stmt->execute([$pesanan_id, $produk['id'], $jumlah, $produk['harga']]);
            
            // Update stok
            $stmt = $pdo->prepare("UPDATE produk SET stok = stok - ? WHERE id = ?");
            $stmt->execute([$jumlah, $produk['id']]);
        }
        
        $pdo->commit();
        
        // Kosongkan keranjang
        unset($_SESSION['keranjang']);
        
        $_SESSION['success'] = "Pesanan berhasil dibuat! No. Pesanan: #$pesanan_id";
        header('Location: pesanan.php');
        exit();
        
    } catch(Exception $e) {
        $pdo->rollBack();
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Ambil data user untuk alamat default
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Siomay Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>Checkout</h1>
        
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <div class="checkout-container">
            <div class="checkout-summary">
                <h3>Ringkasan Pesanan</h3>
                <?php
                $total = 0;
                $placeholders = str_repeat('?,', count($_SESSION['keranjang']) - 1) . '?';
                $stmt = $pdo->prepare("SELECT * FROM produk WHERE id IN ($placeholders)");
                $stmt->execute(array_keys($_SESSION['keranjang']));
                $produk_keranjang = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($produk_keranjang as $produk) {
                    $subtotal = $produk['harga'] * $_SESSION['keranjang'][$produk['id']];
                    $total += $subtotal;
                    echo "
                    <div class='checkout-item'>
                        <span>{$produk['nama_produk']} (x{$_SESSION['keranjang'][$produk['id']]})</span>
                        <span>Rp " . number_format($subtotal, 0, ',', '.') . "</span>
                    </div>";
                }
                ?>
                <div class="checkout-total">
                    <strong>Total: Rp <?= number_format($total, 0, ',', '.') ?></strong>
                </div>
            </div>
            
            <form method="POST" class="checkout-form">
                <h3>Informasi Pengiriman</h3>
                
                <div class="form-group">
                    <label>Nama Penerima:</label>
                    <input type="text" value="<?= $user['nama_lengkap'] ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>No Telepon:</label>
                    <input type="text" value="<?= $user['no_telepon'] ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label for="alamat_pengiriman">Alamat Pengiriman:</label>
                    <textarea name="alamat_pengiriman" required><?= $user['alamat'] ?></textarea>
                </div>
                
                <button type="submit" class="btn-primary">Buat Pesanan</button>
            </form>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>