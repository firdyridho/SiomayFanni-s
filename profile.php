<?php
include 'includes/auth.php';
include 'config/database.php';
redirectIfNotLoggedIn();

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
    
    $_SESSION['success'] = "Profile berhasil diperbarui!";
    header('Location: profile.php');
    exit();
}

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Siomay Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>Profile Saya</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"><?= $_SESSION['success'] ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <form method="POST" class="profile-form">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" value="<?= $user['username'] ?>" readonly>
                <small>Tidak dapat diubah</small>
            </div>
            
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap:</label>
                <input type="text" name="nama_lengkap" value="<?= $user['nama_lengkap'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?= $user['email'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <textarea name="alamat" required><?= $user['alamat'] ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="no_telepon">No Telepon:</label>
                <input type="text" name="no_telepon" value="<?= $user['no_telepon'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password Baru (kosongkan jika tidak ingin mengubah):</label>
                <input type="password" name="password">
            </div>
            
            <button type="submit" class="btn-primary">Update Profile</button>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>