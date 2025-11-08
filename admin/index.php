<?php
$page_title = "Dashboard Admin";
include '../includes/admin_layout.php';

// Hitung statistik
$total_produk = $pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$total_pesanan = $pdo->query("SELECT COUNT(*) FROM pesanan")->fetchColumn();
$total_pendapatan = $pdo->query("SELECT SUM(total_harga) FROM pesanan WHERE status = 'selesai'")->fetchColumn();
$total_user = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$pesanan_hari_ini = $pdo->query("SELECT COUNT(*) FROM pesanan WHERE DATE(created_at) = CURDATE()")->fetchColumn();
$pesanan_pending = $pdo->query("SELECT COUNT(*) FROM pesanan WHERE status = 'pending'")->fetchColumn();
?>

<div class="dashboard-header">
    <h1>Dashboard Admin</h1>
    <p>Selamat datang di panel administrator Siomay Online</p>
</div>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-info">
            <h3>Total Produk</h3>
            <p class="stat-number"><?= $total_produk ?></p>
            <a href="produk.php" class="stat-link">Kelola Produk</a>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-info">
            <h3>Total Pesanan</h3>
            <p class="stat-number"><?= $total_pesanan ?></p>
            <a href="pesanan.php" class="stat-link">Lihat Pesanan</a>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-info">
            <h3>Total Pendapatan</h3>
            <p class="stat-number">Rp <?= number_format($total_pendapatan ?: 0, 0, ',', '.') ?></p>
            <span class="stat-desc">Dari pesanan selesai</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>Total Customer</h3>
            <p class="stat-number"><?= $total_user ?></p>
            <span class="stat-desc">Pelanggan terdaftar</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3>Pesanan Hari Ini</h3>
            <p class="stat-number"><?= $pesanan_hari_ini ?></p>
            <span class="stat-desc">Pesanan baru hari ini</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="stat-info">
            <h3>Pending</h3>
            <p class="stat-number"><?= $pesanan_pending ?></p>
            <a href="pesanan.php?filter=pending" class="stat-link">Lihat Pending</a>
        </div>
    </div>
</div>

<div class="dashboard-actions">
    <h2>Quick Actions</h2>
    <div class="action-grid">
        <a href="produk.php" class="action-card">
            <i class="fas fa-plus-circle"></i>
            <span>Tambah Produk</span>
        </a>
        <a href="pesanan.php" class="action-card">
            <i class="fas fa-list"></i>
            <span>Kelola Pesanan</span>
        </a>
        <a href="kategori.php" class="action-card">
            <i class="fas fa-tags"></i>
            <span>Kelola Kategori</span>
        </a>
        <a href="profile.php" class="action-card">
            <i class="fas fa-user-cog"></i>
            <span>Profile Admin</span>
        </a>
    </div>
</div>

<div class="recent-orders">
    <div class="section-header">
        <h2>Pesanan Terbaru</h2>
        <a href="pesanan.php" class="btn">Lihat Semua</a>
    </div>
    
    <?php
    $stmt = $pdo->query("SELECT p.*, u.nama_lengkap 
                        FROM pesanan p 
                        JOIN users u ON p.user_id = u.id 
                        ORDER BY p.created_at DESC 
                        LIMIT 5");
    $pesanan_terbaru = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    
    <?php if (empty($pesanan_terbaru)): ?>
        <div class="empty-state">
            <i class="fas fa-shopping-cart"></i>
            <p>Belum ada pesanan</p>
        </div>
    <?php else: ?>
        <div class="orders-table">
            <table>
                <thead>
                    <tr>
                        <th>No Pesanan</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pesanan_terbaru as $order): ?>
                        <tr>
                            <td><strong>#<?= $order['id'] ?></strong></td>
                            <td><?= $order['nama_lengkap'] ?></td>
                            <td>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <span class="status status-<?= $order['status'] ?>">
                                    <?= strtoupper($order['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td>
                                <a href="detail_pesanan.php?id=<?= $order['id'] ?>" class="btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    text-align: center;
}

.dashboard-header h1 {
    margin-bottom: 0.5rem;
    font-size: 2rem;
}

.dashboard-header p {
    opacity: 0.9;
    margin: 0;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-card .stat-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #3498db, #2980b9);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-info {
    flex: 1;
}

.stat-info h3 {
    margin: 0 0 0.5rem 0;
    color: #7f8c8d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #2c3e50;
    margin: 0 0 0.5rem 0;
}

.stat-link {
    display: inline-block;
    background: #3498db;
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.8rem;
    transition: background 0.3s ease;
}

.stat-link:hover {
    background: #2980b9;
}

.stat-desc {
    font-size: 0.8rem;
    color: #95a5a6;
    display: block;
}

.dashboard-actions {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.dashboard-actions h2 {
    margin-bottom: 1rem;
    color: #2c3e50;
}

.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.action-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    color: #2c3e50;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    text-align: center;
}

.action-card:hover {
    background: #3498db;
    color: white;
    transform: translateY(-2px);
    border-color: #3498db;
}

.action-card i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

.action-card span {
    font-weight: 600;
    display: block;
}

.recent-orders {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.section-header h2 {
    margin: 0;
    color: #2c3e50;
}

.orders-table {
    overflow-x: auto;
}

.orders-table table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
}

.orders-table th,
.orders-table td {
    padding: 0.8rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.orders-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #2c3e50;
}

.btn-sm {
    background: #3498db;
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 3px;
    text-decoration: none;
    font-size: 0.8rem;
    transition: background 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.btn-sm:hover {
    background: #2980b9;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #7f8c8d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state p {
    margin: 0;
    font-size: 1.1rem;
}

/* Status Badges */
.status {
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: bold;
    text-transform: uppercase;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-diproses {
    background: #cce7ff;
    color: #004085;
}

.status-dikirim {
    background: #d1ecf1;
    color: #0c5460;
}

.status-selesai {
    background: #d4edda;
    color: #155724;
}
</style>

<?php include '../includes/admin_footer.php'; ?>