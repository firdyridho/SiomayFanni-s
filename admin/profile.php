<?php
include '../includes/admin_layout.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    
    // Jika ada password baru
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET nama_lengkap = ?, email = ?, alamat = ?, no_telepon = ?, password = ? WHERE id = ?");
        $stmt->execute([$nama_lengkap, $email, $alamat, $no_telepon, $password, $_SESSION['user_id']]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET nama_lengkap = ?, email = ?, alamat = ?, no_telepon = ? WHERE id = ?");
        $stmt->execute([$nama_lengkap, $email, $alamat, $no_telepon, $_SESSION['user_id']]);
    }
    
    // Update session
    $_SESSION['nama_lengkap'] = $nama_lengkap;
    
    $_SESSION['success'] = "Profile admin berhasil diperbarui!";
    header('Location: profile.php');
    exit();
}

// Ambil data admin
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$admin = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Admin - Siomay Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-profile {
            max-width: 800px;
            margin: 0 auto;
        }
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .profile-content {
            background: white;
            padding: 2rem;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .admin-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
        }
        .admin-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        .stat-item {
            text-align: center;
            padding: 1rem;
            background: white;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container">
        <div class="admin-profile">
            <div class="profile-header">
                <h1><i class="fas fa-user-cog"></i> Profile Admin</h1>
                <p>Kelola informasi akun administrator Anda</p>
            </div>
            
            <div class="profile-content">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success"><?= $_SESSION['success'] ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                
                <div class="admin-info">
                    <h3>Informasi Admin</h3>
                    <p><strong>Role:</strong> Administrator</p>
                    <p><strong>Terdaftar sejak:</strong> <?= date('d F Y', strtotime($admin['created_at'])) ?></p>
                </div>
                
                <form method="POST" class="profile-form">
                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" value="<?= $admin['username'] ?>" readonly>
                        <small>Tidak dapat diubah</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap:</label>
                        <input type="text" name="nama_lengkap" value="<?= $admin['nama_lengkap'] ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="<?= $admin['email'] ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <textarea name="alamat" required><?= $admin['alamat'] ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="no_telepon">No Telepon:</label>
                        <input type="text" name="no_telepon" value="<?= $admin['no_telepon'] ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password Baru (kosongkan jika tidak ingin mengubah):</label>
                        <input type="password" name="password" placeholder="Masukkan password baru">
                        <small>Minimal 6 karakter</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                        <a href="index.php" class="btn">
                            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </form>
                
                <?php
                // Ambil statistik admin
                $total_produk = $pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
                $total_pesanan_hari_ini = $pdo->query("SELECT COUNT(*) FROM pesanan WHERE DATE(created_at) = CURDATE()")->fetchColumn();
                $total_pendapatan_bulan_ini = $pdo->query("SELECT SUM(total_harga) FROM pesanan WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND status = 'selesai'")->fetchColumn();
                ?>
                
                <div class="admin-stats">
                    <div class="stat-item">
                        <h4>Total Produk</h4>
                        <p class="stat-number"><?= $total_produk ?></p>
                    </div>
                    <div class="stat-item">
                        <h4>Pesanan Hari Ini</h4>
                        <p class="stat-number"><?= $total_pesanan_hari_ini ?></p>
                    </div>
                    <div class="stat-item">
                        <h4>Pendapatan Bulan Ini</h4>
                        <p class="stat-number">Rp <?= number_format($total_pendapatan_bulan_ini ?: 0, 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include '../includes/admin_footer.php'; ?>
</body>
</html>