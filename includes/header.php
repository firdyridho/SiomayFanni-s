<?php
// File: includes/header.php - Single header untuk semua role
?>
<header class="<?php 
    if (isAdmin()) echo 'admin-header';
    elseif (isLoggedIn()) echo 'customer-header'; 
    else echo 'guest-header';
?>">
    <nav class="container">
        <div class="logo">
            <a href="<?php echo isAdmin() ? 'admin/index.php' : 'index.php'; ?>">
                <?php if (isAdmin()): ?>
                    <i class="fas fa-utensils"></i> Siomay Admin
                <?php else: ?>
                    <i class="fas fa-utensils"></i> Siomay Online
                <?php endif; ?>
            </a>
        </div>
        
        <?php if (isAdmin()): ?>
            <!-- HEADER UNTUK ADMIN (Tetap sama) -->
            <ul class="nav-links">
                <li><a href="admin/index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="admin/produk.php"><i class="fas fa-box"></i> Produk</a></li>
                <li><a href="admin/kategori.php"><i class="fas fa-tags"></i> Kategori</a></li>
                <li><a href="admin/pesanan.php"><i class="fas fa-shopping-cart"></i> Pesanan</a></li>
                <li class="dropdown">
                    <a href="#" class="admin-badge">
                        <i class="fas fa-user-shield"></i> <?= $_SESSION['nama_lengkap'] ?> ▼
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="admin/profile.php"><i class="fas fa-user-cog"></i> Profile Admin</a></li>
                        <li><a href="index.php" target="_blank"><i class="fas fa-store"></i> Lihat Toko</a></li>
                        <li><a href="admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
            
        <?php elseif (isLoggedIn()): ?>
            <!-- HEADER UNTUK CUSTOMER (Sederhana) -->
            <ul class="nav-links desktop-only">
                <li><a href="index.php"><i class="fas fa-home"></i> Beranda</a></li>
                <li><a href="produk.php"><i class="fas fa-utensils"></i> Menu</a></li>
                <li><a href="keranjang.php">
                    <i class="fas fa-shopping-cart"></i> Keranjang
                    <?php
                    if (isset($_SESSION['keranjang']) && count($_SESSION['keranjang']) > 0) {
                        $total_items = array_sum($_SESSION['keranjang']);
                        echo '<span class="cart-badge">' . $total_items . '</span>';
                    }
                    ?>
                </a></li>
                <li><a href="pesanan.php"><i class="fas fa-history"></i> Pesanan</a></li>
                <li class="dropdown">
                    <a href="#">
                        <i class="fas fa-user"></i> <?= $_SESSION['nama_lengkap'] ?> ▼
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php"><i class="fas fa-user-edit"></i> Profile</a></li>
                        <li><a href="pesanan.php"><i class="fas fa-history"></i> Riwayat</a></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
            
            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
            
        <?php else: ?>
            <!-- HEADER UNTUK GUEST -->
            <ul class="nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> Beranda</a></li>
                <li><a href="produk.php"><i class="fas fa-utensils"></i> Menu</a></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> Daftar</a></li>
            </ul>
        <?php endif; ?>
    </nav>
</header>

<?php if (isLoggedIn() && !isAdmin()): ?>
    <!-- Include bottom nav untuk customer -->
    <?php include 'customer_bottom_nav.php'; ?>
<?php endif; ?>