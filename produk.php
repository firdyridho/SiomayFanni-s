<?php include 'includes/auth.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Siomay Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>Daftar Produk</h1>
        
        <div class="filter-section">
            <form method="GET">
                <select name="kategori">
                    <option value="">Semua Kategori</option>
                    <?php
                    include 'config/database.php';
                    $stmt = $pdo->query("SELECT * FROM kategori");
                    while ($kategori = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($_GET['kategori'] ?? '') == $kategori['id'] ? 'selected' : '';
                        echo "<option value='{$kategori['id']}' $selected>{$kategori['nama_kategori']}</option>";
                    }
                    ?>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>

        <div class="products-grid">
            <?php
            $kategori_filter = $_GET['kategori'] ?? '';
            $sql = "SELECT p.*, k.nama_kategori FROM produk p 
                    LEFT JOIN kategori k ON p.kategori_id = k.id";
            
            if ($kategori_filter) {
                $sql .= " WHERE p.kategori_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$kategori_filter]);
            } else {
                $stmt = $pdo->query($sql);
            }
            
            while ($produk = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "
                <div class='product-card'>
                    <img src='assets/images/{$produk['gambar']}' alt='{$produk['nama_produk']}'>
                    <h3>{$produk['nama_produk']}</h3>
                    <p class='kategori'>{$produk['nama_kategori']}</p>
                    <p class='deskripsi'>{$produk['deskripsi']}</p>
                    <p class='harga'>Rp " . number_format($produk['harga'], 0, ',', '.') . "</p>
                    <p class='stok'>Stok: {$produk['stok']}</p>
                    <a href='keranjang.php?action=add&id={$produk['id']}' class='btn'>Tambah ke Keranjang</a>
                </div>";
            }
            ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>