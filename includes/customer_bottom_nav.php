<?php
// File: includes/customer_bottom_nav.php
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}
$total_keranjang = array_sum($_SESSION['keranjang']);
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Mobile Bottom Navigation untuk Customer -->
<nav class="customer-bottom-nav">
    <ul class="bottom-nav-items">
        <li class="bottom-nav-item">
            <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
        </li>
        
        <li class="bottom-nav-item">
            <a href="produk.php" class="<?php echo $current_page == 'produk.php' ? 'active' : ''; ?>">
                <i class="fas fa-utensils"></i>
                <span>Menu</span>
            </a>
        </li>
        
        <!-- Menu Tengah - Keranjang -->
        <li class="bottom-nav-item center-menu">
            <a href="keranjang.php" class="<?php echo $current_page == 'keranjang.php' ? 'active' : ''; ?>">
                <div class="center-menu-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <span>Keranjang</span>
                <?php if ($total_keranjang > 0): ?>
                    <span class="bottom-nav-badge"><?= $total_keranjang ?></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="bottom-nav-item">
            <a href="pesanan.php" class="<?php echo $current_page == 'pesanan.php' ? 'active' : ''; ?>">
                <i class="fas fa-history"></i>
                <span>Pesanan</span>
            </a>
        </li>
        
        <li class="bottom-nav-item">
            <a href="profile.php" class="<?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </li>
    </ul>
</nav>

<!-- Quick Actions untuk Customer -->
<div class="customer-quick-actions">
    <div class="quick-actions-content">
        <h3>Quick Menu</h3>
        <div class="quick-actions-grid">
            <a href="produk.php?kategori=1" class="quick-action-item">
                <i class="fas fa-drumstick-bite"></i>
                <span>Siomay</span>
            </a>
            <a href="produk.php?kategori=2" class="quick-action-item">
                <i class="fas fa-cube"></i>
                <span>Batagor</span>
            </a>
            <a href="pesanan.php" class="quick-action-item">
                <i class="fas fa-clock"></i>
                <span>Status Pesanan</span>
            </a>
            <a href="logout.php" class="quick-action-item logout" onclick="return confirm('Yakin ingin logout?')">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
        <button class="close-actions">Tutup</button>
    </div>
</div>