<?php
include 'config/database.php';
include 'includes/auth.php';

// Handle pesan logout
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'admin_logout':
            $success = "Anda telah logout dari panel admin!";
            break;
        case 'logout':
            $success = "Anda telah berhasil logout!";
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        
        if ($user['role'] == 'admin') {
            header('Location: admin/index.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Siomay Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <form method="POST" class="login-form">
            <h2>Login</h2>
            
            <?php if (isset($success)): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit" class="btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
            
            <div class="login-links">
                <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
                <p><a href="index.php">Kembali ke Beranda</a></p>
            </div>
            
            <div class="demo-accounts">
                <h4>Akun Demo:</h4>
                <p><strong>Admin:</strong> admin / password</p>
                <p><strong>Customer:</strong> customer1 / password</p>
            </div>
        </form>
    </div>
</body>
</html>