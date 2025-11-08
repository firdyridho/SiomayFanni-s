<?php
include '../includes/admin_layout.php';

// Handle actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'] ?? 0;
    
    switch ($action) {
        case 'delete':
            // Cek apakah kategori digunakan
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kategori_id = ?");
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                $_SESSION['error'] = "Kategori tidak dapat dihapus karena masih digunakan oleh produk!";
            } else {
                $stmt = $pdo->prepare("DELETE FROM kategori WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Kategori berhasil dihapus!";
            }
            break;
    }
    
    header('Location: kategori.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = $_POST['nama_kategori'];
    $deskripsi = $_POST['deskripsi'];
    
    if (isset($_POST['kategori_id'])) {
        // Update kategori
        $stmt = $pdo->prepare("UPDATE kategori SET nama_kategori = ?, deskripsi = ? WHERE id = ?");
        $stmt->execute([$nama_kategori, $deskripsi, $_POST['kategori_id']]);
        $_SESSION['success'] = "Kategori berhasil diperbarui!";
    } else {
        // Tambah kategori
        $stmt = $pdo->prepare("INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)");
        $stmt->execute([$nama_kategori, $deskripsi]);
        $_SESSION['success'] = "Kategori berhasil ditambahkan!";
    }
    
    header('Location: kategori.php');
    exit();
}

// Ambil data kategori
$kategori = $pdo->query("SELECT * FROM kategori")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Siomay Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container">
        <h1>Kelola Kategori</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"><?= $_SESSION['success'] ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="admin-section">
            <h2>Tambah/Edit Kategori</h2>
            <form method="POST" class="admin-form">
                <input type="hidden" name="kategori_id" id="kategori_id">
                
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori:</label>
                    <input type="text" name="nama_kategori" id="nama_kategori" required>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi:</label>
                    <textarea name="deskripsi" id="deskripsi"></textarea>
                </div>
                
                <button type="submit" class="btn-primary">Simpan Kategori</button>
                <button type="button" onclick="resetForm()" class="btn">Batal</button>
            </form>
        </div>
        
        <div class="admin-section">
            <h2>Daftar Kategori</h2>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kategori as $kat): ?>
                        <tr>
                            <td><?= $kat['nama_kategori'] ?></td>
                            <td><?= $kat['deskripsi'] ?></td>
                            <td>
                                <button onclick="editKategori(<?= htmlspecialchars(json_encode($kat)) ?>)" class="btn">Edit</button>
                                <a href="kategori.php?action=delete&id=<?= $kat['id'] ?>" class="btn-danger" onclick="return confirm('Yakin hapus kategori?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
    function editKategori(kategori) {
        document.getElementById('kategori_id').value = kategori.id;
        document.getElementById('nama_kategori').value = kategori.nama_kategori;
        document.getElementById('deskripsi').value = kategori.deskripsi;
    }
    
    function resetForm() {
        document.querySelector('form').reset();
        document.getElementById('kategori_id').value = '';
    }
    </script>

    <?php include '../includes/admin_footer.php'; ?>
</body>
</html>