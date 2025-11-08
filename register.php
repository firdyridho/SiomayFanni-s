<?php
include 'config/database.php';
include 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, nama_lengkap, alamat, no_telepon) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $email, $nama_lengkap, $alamat, $no_telepon]);
        
        $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
        header('Location: login.php');
        exit();
    } catch(PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = "Username atau email sudah digunakan!";
        } else {
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Siomay Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <form method="POST" class="login-form">
            <h2>Register</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <?php if (isset($_SESSION['success'])) { echo "<p class='success'>".$_SESSION['success']."</p>"; unset($_SESSION['success']); } ?>
            
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
            <textarea name="alamat" placeholder="Alamat" required></textarea>
            <input type="text" name="no_telepon" placeholder="No Telepon" required>
            
            <button type="submit">Daftar</button>
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </form>
    </div>
</body>
</html>