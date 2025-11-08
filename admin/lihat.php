<?php include '../includes/admin_layout.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siomay Online - Beranda</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    
    <main class="container">
        <section class="hero">
            <h1>Selamat Datang di Siomay Online</h1>
            <p>Nikmati siomay terlezat dengan mudah dari rumah Anda</p>
            <a href="produk.php" class="btn">Lihat Menu</a>
        </section>

        <section class="featured-products">
            <h2>Menu Populer</h2>
            <div class="products-grid">
                <?php
                include '../config/database.php';
                $stmt = $pdo->query("SELECT * FROM produk LIMIT 6");
                while ($produk = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "
                    <div class='product-card'>
                        <img src='../assets/images/{$produk['gambar']}' alt='{$produk['nama_produk']}'>
                        <h3>{$produk['nama_produk']}</h3>
                        <p>Rp " . number_format($produk['harga'], 0, ',', '.') . "</p>
                        <a href='keranjang.php?action=add&id={$produk['id']}' class='btn'>Tambah ke Keranjang</a>
                    </div>";
                }
                ?>
            </div>
        </section>
    </main>

    <?php include '../includes/admin_footer.php'; ?>
</body>
</html>