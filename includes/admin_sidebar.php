<?php
// File: includes/admin_sidebar.php
include '../config/database.php';

// Hitung statistik untuk badge
if (isset($pdo)) {
    $total_produk = $pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
    $total_kategori = $pdo->query("SELECT COUNT(*) FROM kategori")->fetchColumn();
    $pending_orders = $pdo->query("SELECT COUNT(*) FROM pesanan WHERE status = 'pending'")->fetchColumn();
    $total_customer = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
} else {
    $total_produk = $total_kategori = $pending_orders = $total_customer = 0;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar">
    <!-- Sidebar desktop content tetap sama -->
    <div class="sidebar-header">
        <div class="admin-info">
            <div class="admin-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="admin-details">
                <h3><?= $_SESSION['nama_lengkap'] ?></h3>
                <span class="admin-role">Administrator</span>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="sidebar-menu">
            <li class="menu-item <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <a href="index.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="menu-item <?php echo $current_page == 'produk.php' ? 'active' : ''; ?>">
                <a href="produk.php">
                    <i class="fas fa-box"></i>
                    <span>Kelola Produk</span>
                    <span class="menu-badge"><?= $total_produk ?></span>
                </a>
            </li>
            
            <li class="menu-item <?php echo $current_page == 'kategori.php' ? 'active' : ''; ?>">
                <a href="kategori.php">
                    <i class="fas fa-tags"></i>
                    <span>Kategori</span>
                    <span class="menu-badge"><?= $total_kategori ?></span>
                </a>
            </li>
            
            <li class="menu-item <?php echo $current_page == 'pesanan.php' ? 'active' : ''; ?>">
                <a href="pesanan.php">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Pesanan</span>
                    <?php if ($pending_orders > 0): ?>
                        <span class="menu-badge badge-warning"><?= $pending_orders ?></span>
                    <?php endif; ?>
                </a>
            </li>
            
            <li class="menu-section">
                <span>Laporan</span>
            </li>
            
            <li class="menu-item">
                <a href="#">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan Penjualan</span>
                </a>
            </li>
            
            <li class="menu-item">
                <a href="#">
                    <i class="fas fa-users"></i>
                    <span>Data Customer</span>
                    <span class="menu-badge"><?= $total_customer ?></span>
                </a>
            </li>
            
            <li class="menu-section">
                <span>Pengaturan</span>
            </li>
            
            <li class="menu-item <?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                <a href="profile.php">
                    <i class="fas fa-user-cog"></i>
                    <span>Profile Admin</span>
                </a>
            </li>
            
            <li class="menu-item">
                <a href="../index.php" target="_blank">
                    <i class="fas fa-store"></i>
                    <span>Lihat Toko</span>
                </a>
            </li>
            
            <li class="menu-item logout-item">
                <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <p>Siomay Online v1.0</p>
        <small>Admin Panel</small>
    </div>
</aside>

<!-- Mobile Bottom Navigation dengan Menu Tengah -->
<nav class="mobile-bottom-nav">
    <ul class="bottom-nav-items">
        <li class="bottom-nav-item">
            <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li class="bottom-nav-item">
            <a href="produk.php" class="<?php echo $current_page == 'produk.php' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i>
                <span>Produk</span>
                <?php if ($total_produk > 0): ?>
                    <span class="bottom-nav-badge"><?= $total_produk ?></span>
                <?php endif; ?>
            </a>
        </li>
        
        <!-- Menu Tengah - More Options -->
        <li class="bottom-nav-item center-menu">
            <a href="#" class="more-menu-trigger">
                <div class="center-menu-icon">
                    <i class="fas fa-ellipsis-h"></i>
                </div>
                <span>More</span>
            </a>
            <div class="more-menu-dropdown">
                <a href="kategori.php" class="more-menu-item <?php echo $current_page == 'kategori.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i>
                    <span>Kategori</span>
                </a>
                <a href="profile.php" class="more-menu-item <?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user-cog"></i>
                    <span>Profile</span>
                </a>
                <a href="../index.php" target="_blank" class="more-menu-item">
                    <i class="fas fa-store"></i>
                    <span>Lihat Toko</span>
                </a>
                <a href="logout.php" class="more-menu-item logout" onclick="return confirm('Yakin ingin logout?')">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </li>
        
        <li class="bottom-nav-item">
            <a href="pesanan.php" class="<?php echo $current_page == 'pesanan.php' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i>
                <span>Pesanan</span>
                <?php if ($pending_orders > 0): ?>
                    <span class="bottom-nav-badge"><?= $pending_orders ?></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="bottom-nav-item">
            <a href="lihat.php" class="<?php echo $current_page == 'lihat.php' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i>
                <span>Lihat Toko</span>
                <?php if ($pending_orders > 0): ?>
                    <span class="bottom-nav-badge"><?= $pending_orders ?></span>
                <?php endif; ?>
            </a>
        </li>
    </ul>
</nav>