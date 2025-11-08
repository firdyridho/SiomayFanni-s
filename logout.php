<?php
// File: logout.php - Logout untuk customer dan umum
session_start();

// Simpan pesan logout berdasarkan role
$message = 'logout';
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $message = 'admin_logout';
}

// Hancurkan semua data session
$_SESSION = array();

// Hapus cookie session jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// Redirect ke halaman login dengan pesan
header("Location: login.php?message=" . $message);
exit();
?>