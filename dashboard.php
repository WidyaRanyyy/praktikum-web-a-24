<?php
session_start();
require_once 'koneksi.php';

// Jika belum login, redirect ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Gunakan $_GET untuk menerima query string
$page = $_GET['page'] ?? 'home';

// Ambil data statistik dari database
$query_produk = "SELECT COUNT(*) as total FROM produk";
$result_produk = mysqli_query($conn, $query_produk);
$total_products = mysqli_fetch_assoc($result_produk)['total'];

$query_wisata = "SELECT COUNT(*) as total FROM wisata";
$result_wisata = mysqli_query($conn, $query_wisata);
$total_wisata = mysqli_fetch_assoc($result_wisata)['total'];

$query_makanan = "SELECT COUNT(*) as total FROM makanan";
$result_makanan = mysqli_query($conn, $query_makanan);
$total_makanan = mysqli_fetch_assoc($result_makanan)['total'];

// Data untuk statistik
$stats = [
    'total_products' => $total_products,
    'total_wisata' => $total_wisata,
    'total_makanan' => $total_makanan,
    'total_views' => 1234 // Bisa diambil dari tracking jika ada
];

// Menu items
$menu_items = [
    'home' => ['icon' => '🏠', 'title' => 'Beranda'],
    'profil' => ['icon' => '👤', 'title' => 'Profil'],
    'produk' => ['icon' => '🛍️', 'title' => 'Produk'],
    'wisata' => ['icon' => '🗺️', 'title' => 'Wisata'],
    'makanan' => ['icon' => '🍜', 'title' => 'Makanan'],
    'setting' => ['icon' => '⚙️', 'title' => 'Pengaturan']
];

// Validasi page
if (!array_key_exists($page, $menu_items)) {
    $page = 'home';
}

// Ambil data untuk halaman produk
if ($page === 'produk') {
    $query_produk_list = "SELECT * FROM produk ORDER BY id DESC LIMIT 10";
    $result_produk_list = mysqli_query($conn, $query_produk_list);
}

// Ambil data untuk halaman wisata
if ($page === 'wisata') {
    $query_wisata_list = "SELECT * FROM wisata ORDER BY id DESC";
    $result_wisata_list = mysqli_query($conn, $query_wisata_list);
}

// Ambil data untuk halaman makanan
if ($page === 'makanan') {
    $query_makanan_list = "SELECT * FROM makanan ORDER BY id DESC";
    $result_makanan_list = mysqli_query($conn, $query_makanan_list);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dari Bontang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #841751, #db2796);
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .logo svg {
            flex-shrink: 0;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 10px;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: white;
            border-radius: 0 4px 4px 0;
        }

        .nav-icon {
            font-size: 1.2rem;
        }

        .sidebar-footer {
            padding: 20px 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .dashboard-header {
            background: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left h1 {
            font-size: 1.8rem;
            color: #841751;
            margin-bottom: 5px;
        }

        .header-left p {
            color: #666;
            font-size: 0.95rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #841751, #db2796);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-details strong {
            color: #333;
            font-size: 1rem;
        }

        .user-details span {
            color: #666;
            font-size: 0.85rem;
        }

        .content-area {
            flex: 1;
            padding: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .section-header h2 {
            font-size: 1.5rem;
            color: #333;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-info h3 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #666;
            font-size: 0.9rem;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .action-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            color: #333;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .action-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .action-card h3 {
            color: #841751;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .action-card p {
            color: #666;
            font-size: 0.9rem;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
            margin-bottom: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: #f8f9fa;
        }

        .data-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
        }

        .data-table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .data-table tbody tr:hover {
            background: #f8f9fa;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .btn-primary {
            background: linear-gradient(135deg, #841751, #db2796);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(132, 23, 81, 0.4);
        }

        .btn-small {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #ffc107;
            color: #333;
        }

        .btn-edit:hover {
            background: #e0a800;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            max-width: 600px;
            margin: 0 auto;
        }

        .profile-avatar-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #841751, #db2796);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 3rem;
            margin: 0 auto 20px;
        }

        .profile-card h2 {
            color: #333;
            margin-bottom: 5px;
        }

        .profile-role {
            color: #666;
            margin-bottom: 30px;
        }

        .profile-info {
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .info-item strong {
            color: #333;
        }

        .info-item span {
            color: #666;
        }

        .status-badge {
            background: #4caf50;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .grid-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .content-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .card-image {
            height: 180px;
            background-size: cover;
            background-position: center;
        }

        .card-body {
            padding: 20px;
        }

        .card-body h3 {
            color: #841751;
            margin-bottom: 10px;
        }

        .card-body p {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .card-actions {
            display: flex;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .logo span,
            .nav-text {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }

            .user-details {
                display: none;
            }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <circle cx="20" cy="20" r="18" fill="url(#gradient1)"/>
                    <path d="M20 10L23 17H17L20 10Z" fill="white"/>
                    <circle cx="20" cy="23" r="5" fill="white"/>
                    <defs>
                        <linearGradient id="gradient1" x1="0" y1="0" x2="40" y2="40">
                            <stop offset="0%" stop-color="#841751"/>
                            <stop offset="100%" stop-color="#db2796"/>
                        </linearGradient>
                    </defs>
                </svg>
                <span>Dari Bontang</span>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <?php foreach ($menu_items as $key => $item): ?>
            <a href="dashboard.php?page=<?php echo $key; ?>" 
               class="nav-item <?php echo $page === $key ? 'active' : ''; ?>">
                <span class="nav-icon"><?php echo $item['icon']; ?></span>
                <span class="nav-text"><?php echo $item['title']; ?></span>
            </a>
            <?php endforeach; ?>
        </nav>
        
        <div class="sidebar-footer">
            <a href="logout.php" class="logout-btn">
                <span class="nav-icon">🚪</span>
                <span>Logout</span>
            </a>
        </div>
    </aside>
    
    <main class="main-content">
        <header class="dashboard-header">
            <div class="header-left">
                <h1>Dashboard</h1>
                <p>Selamat datang kembali, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                    <div class="user-details">
                        <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                        <span>Administrator</span>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="content-area">
            <?php if ($page === 'home'): ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                                <path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['total_products']; ?></h3>
                            <p>Total Produk</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['total_wisata']; ?></h3>
                            <p>Destinasi Wisata</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                                <path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['total_makanan']; ?></h3>
                            <p>Makanan Khas</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['total_views']; ?></h3>
                            <p>Total Pengunjung</p>
                        </div>
                    </div>
                </div>
                
                <div class="section-header">
                    <h2>Aksi Cepat</h2>
                </div>
                <div class="quick-actions">
                    <a href="crud_produk.php" class="action-card">
                        <div class="action-icon">📦</div>
                        <h3>Kelola Produk</h3>
                        <p>Tambah, edit, atau hapus produk</p>
                    </a>
                    <a href="crud_wisata.php" class="action-card">
                        <div class="action-icon">🗺️</div>
                        <h3>Kelola Wisata</h3>
                        <p>Kelola destinasi wisata</p>
                    </a>
                    <a href="crud_makanan.php" class="action-card">
                        <div class="action-icon">🍜</div>
                        <h3>Kelola Makanan</h3>
                        <p>Kelola makanan khas</p>
                    </a>
                    <a href="index.php" class="action-card" target="_blank">
                        <div class="action-icon">🌐</div>
                        <h3>Lihat Website</h3>
                        <p>Buka website publik</p>
                    </a>
                </div>
            
            <?php elseif ($page === 'profil'): ?>
                <div class="section-header">
                    <h2>Profil Pengguna</h2>
                </div>
                <div class="profile-card">
                    <div class="profile-avatar-large">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                    <h2><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                    <p class="profile-role">Administrator</p>
                    
                    <div class="profile-info">
                        <div class="info-item">
                            <strong>Username:</strong>
                            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Role:</strong>
                            <span>Administrator</span>
                        </div>
                        <div class="info-item">
                            <strong>Login Terakhir:</strong>
                            <span><?php echo isset($_SESSION['login_time']) ? htmlspecialchars($_SESSION['login_time']) : date('Y-m-d H:i:s'); ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Status:</strong>
                            <span class="status-badge">Aktif</span>
                        </div>
                    </div>
                </div>
            
            <?php elseif ($page === 'produk'): ?>
                <div class="section-header">
                    <h2>Kelola Produk</h2>
                    <a href="crud_produk.php" class="btn-primary">+ Kelola Produk</a>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result_produk_list)): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                                <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                <td><?php echo htmlspecialchars($row['lokasi']); ?></td>
                                <td><span class="badge badge-success"><?php echo htmlspecialchars($row['status']); ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            
            <?php elseif ($page === 'wisata'): ?>
                <div class="section-header">
                    <h2>Kelola Wisata</h2>
                    <a href="crud_wisata.php" class="btn-primary">+ Kelola Wisata</a>
                </div>
                <div class="grid-cards">
                    <?php while ($row = mysqli_fetch_assoc($result_wisata_list)): ?>
                    <div class="content-card">
                        <div class="card-image" style="background-image: url('<?php echo htmlspecialchars($row['gambar']); ?>'); background-color: #667eea;"></div>
                        <div class="card-body">
                            <h3><?php echo htmlspecialchars($row['nama_wisata']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($row['deskripsi'], 0, 100)) . '...'; ?></p>
                            <span class="badge <?php 
                                if ($row['status'] == 'Buka') echo 'badge-success';
                                elseif ($row['status'] == 'Tutup') echo 'badge-danger';
                                else echo 'badge-warning';
                            ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            
            <?php elseif ($page === 'makanan'): ?>
                <div class="section-header">
                    <h2>Kelola Makanan Khas</h2>
                    <a href="crud_makanan.php" class="btn-primary">+ Kelola Makanan</a>
                </div>
                <div class="grid-cards">
                    <?php while ($row = mysqli_fetch_assoc($result_makanan_list)): ?>
                    <div class="content-card">
                        <div class="card-image" style="background-image: url('<?php echo htmlspecialchars($row['gambar']); ?>'); background-color: #4facfe;"></div>
                        <div class="card-body">
                            <h3><?php echo htmlspecialchars($row['nama_makanan']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($row['deskripsi'], 0, 100)) . '...'; ?></p>
                            <p><strong>Harga:</strong> <?php echo htmlspecialchars($row['harga_range']); ?></p>
                            <span class="badge <?php echo $row['status'] == 'Tersedia' ? 'badge-success' : 'badge-danger'; ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            
            <?php elseif ($page === 'setting'): ?>
                <div class="section-header">
                    <h2>Pengaturan</h2>
                </div>
                <div class="profile-card">
                    <h2>Pengaturan Sistem</h2>
                    <div class="profile-info">
                        <div class="info-item">
                            <strong>Versi Sistem</strong>
                            <span>1.0.0</span>
                        </div>
                        <div class="info-item">
                            <strong>PHP Version</strong>
                            <span><?php echo phpversion(); ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Database</strong>
                            <span>MySQL - dari_bontang</span>
                        </div>
                        <div class="info-item">
                            <strong>Server</strong>
                            <span><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></span>
                        </div>
                    </div>
                    <div style="margin-top: 20px;">
                        <a href="crud_produk.php" class="btn-primary">Kelola Produk</a>
                        <a href="crud_wisata.php" class="btn-primary" style="margin-left: 10px;">Kelola Wisata</a>
                        <a href="crud_makanan.php" class="btn-primary" style="margin-left: 10px;">Kelola Makanan</a>
                    </div>
                </div>
            
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
<?php close_connection(); ?>