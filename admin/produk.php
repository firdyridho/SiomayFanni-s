<?php
include '../includes/admin_layout.php';

// Handle actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'] ?? 0;
    
    switch ($action) {
        case 'delete':
            $stmt = $pdo->prepare("DELETE FROM produk WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['success'] = "Produk berhasil dihapus!";
            break;
    }
    
    header('Location: produk.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori_id = $_POST['kategori_id'];
    
    // Handle gambar upload
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambar = uniqid() . '_' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], '../assets/images/' . $gambar);
    }
    
    if (isset($_POST['produk_id'])) {
        // Update produk
        if ($gambar) {
            $stmt = $pdo->prepare("UPDATE produk SET nama_produk = ?, deskripsi = ?, harga = ?, stok = ?, kategori_id = ?, gambar = ? WHERE id = ?");
            $stmt->execute([$nama_produk, $deskripsi, $harga, $stok, $kategori_id, $gambar, $_POST['produk_id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE produk SET nama_produk = ?, deskripsi = ?, harga = ?, stok = ?, kategori_id = ? WHERE id = ?");
            $stmt->execute([$nama_produk, $deskripsi, $harga, $stok, $kategori_id, $_POST['produk_id']]);
        }
        $_SESSION['success'] = "Produk berhasil diperbarui!";
    } else {
        // Tambah produk
        $stmt = $pdo->prepare("INSERT INTO produk (nama_produk, deskripsi, harga, stok, kategori_id, gambar) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nama_produk, $deskripsi, $harga, $stok, $kategori_id, $gambar]);
        $_SESSION['success'] = "Produk berhasil ditambahkan!";
    }
    
    header('Location: produk.php');
    exit();
}

// Ambil data produk
$produk = $pdo->query("SELECT p.*, k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.kategori_id = k.id")->fetchAll(PDO::FETCH_ASSOC);

// Ambil data kategori untuk dropdown
$kategori = $pdo->query("SELECT * FROM kategori")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Siomay Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    
    <main class="container">
        <h1>Kelola Produk</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"><?= $_SESSION['success'] ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <div class="admin-section">
            <h2>Tambah/Edit Produk</h2>
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <input type="hidden" name="produk_id" id="produk_id">
                
                <div class="form-group">
                    <label for="nama_produk">Nama Produk:</label>
                    <input type="text" name="nama_produk" id="nama_produk" required>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi:</label>
                    <textarea name="deskripsi" id="deskripsi" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="harga">Harga:</label>
                    <input type="number" name="harga" id="harga" required>
                </div>
                
                <div class="form-group">
                    <label for="stok">Stok:</label>
                    <input type="number" name="stok" id="stok" required>
                </div>
                
                <div class="form-group">
                    <label for="kategori_id">Kategori:</label>
                    <select name="kategori_id" id="kategori_id" required>
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($kategori as $kat): ?>
                            <option value="<?= $kat['id'] ?>"><?= $kat['nama_kategori'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="gambar">Gambar:</label>
                    <input type="file" name="gambar" id="gambar" accept="image/*">
                    <img id="preview_gambar" src="" alt="" style="max-width: 200px; display: none;">
                </div>
                
                <button type="submit" class="btn-primary">Simpan Produk</button>
                <button type="button" onclick="resetForm()" class="btn">Batal</button>
            </form>
        </div>
        
        <div class="admin-section">
            <h2>Daftar Produk</h2>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produk as $prod): ?>
                        <tr>
                            <td>
                                <?php if ($prod['gambar']): ?>
                                    <img src="../assets/images/<?= $prod['gambar'] ?>" alt="<?= $prod['nama_produk'] ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php endif; ?>
                            </td>
                            <td><?= $prod['nama_produk'] ?></td>
                            <td><?= $prod['nama_kategori'] ?></td>
                            <td>Rp <?= number_format($prod['harga'], 0, ',', '.') ?></td>
                            <td><?= $prod['stok'] ?></td>
                            <td>
                                <button onclick="editProduk(<?= htmlspecialchars(json_encode($prod)) ?>)" class="btn">Edit</button>
                                <a href="produk.php?action=delete&id=<?= $prod['id'] ?>" class="btn-danger" onclick="return confirm('Yakin hapus produk?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
    function editProduk(produk) {
        document.getElementById('produk_id').value = produk.id;
        document.getElementById('nama_produk').value = produk.nama_produk;
        document.getElementById('deskripsi').value = produk.deskripsi;
        document.getElementById('harga').value = produk.harga;
        document.getElementById('stok').value = produk.stok;
        document.getElementById('kategori_id').value = produk.kategori_id;
        
        if (produk.gambar) {
            document.getElementById('preview_gambar').src = '../assets/images/' + produk.gambar;
            document.getElementById('preview_gambar').style.display = 'block';
        }
    }
    
    function resetForm() {
        document.querySelector('form').reset();
        document.getElementById('produk_id').value = '';
        document.getElementById('preview_gambar').style.display = 'none';
    }
    </script>

    <?php include '../includes/admin_footer.php'; ?>
</body>
</html>