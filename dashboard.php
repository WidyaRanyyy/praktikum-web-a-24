<?php
session_start();

// Jika belum login, redirect ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Gunakan $_GET untuk menerima query string
$page = $_GET['page'] ?? 'home';

// Data untuk statistik
$stats = [
    'total_products' => 6,
    'total_wisata' => 2,
    'total_makanan' => 1,
    'total_views' => 1234
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

        .activity-list {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .activity-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            flex-shrink: 0;
        }

        .activity-content strong {
            display: block;
            color: #333;
            margin-bottom: 5px;
        }

        .activity-content p {
            color: #666;
            font-size: 0.9rem;
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

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
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
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
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
        }

        .card-actions {
            display: flex;
            gap: 10px;
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
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(132, 23, 81, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-small {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
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

        .settings-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .setting-group {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .setting-group h3 {
            color: #841751;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .setting-item:last-child {
            border-bottom: none;
        }

        .setting-label strong {
            display: block;
            color: #333;
            margin-bottom: 5px;
        }

        .setting-label p {
            color: #666;
            font-size: 0.9rem;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        .toggle-switch input:checked + .toggle-slider {
            background-color: #841751;
        }

        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .info-box strong {
            display: block;
            color: #333;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .info-box p {
            color: #841751;
            font-size: 1.2rem;
            font-weight: 700;
        }

        @media (max-width: 1024px) {
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

            .sidebar-nav {
                padding: 10px 5px;
            }

            .nav-item {
                justify-content: center;
                padding: 12px;
            }

            .logout-btn span {
                display: none;
            }

            .logout-btn {
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .dashboard-header {
                padding: 15px 20px;
            }

            .header-left h1 {
                font-size: 1.3rem;
            }

            .content-area {
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .user-details {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .grid-cards {
                grid-template-columns: 1fr;
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
                    <a href="dashboard.php?page=produk" class="action-card">
                        <div class="action-icon">📦</div>
                        <h3>Tambah Produk</h3>
                        <p>Tambahkan produk atau oleh-oleh baru</p>
                    </a>
                    <a href="dashboard.php?page=wisata" class="action-card">
                        <div class="action-icon">🗺️</div>
                        <h3>Tambah Wisata</h3>
                        <p>Tambahkan destinasi wisata baru</p>
                    </a>
                    <a href="dashboard.php?page=makanan" class="action-card">
                        <div class="action-icon">🍜</div>
                        <h3>Tambah Makanan</h3>
                        <p>Tambahkan makanan khas baru</p>
                    </a>
                    <a href="index.php" class="action-card" target="_blank">
                        <div class="action-icon">🌐</div>
                        <h3>Lihat Website</h3>
                        <p>Buka website publik</p>
                    </a>
                </div>
                
                <div class="section-header">
                    <h2>Aktivitas Terbaru</h2>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon" style="background: #4caf50;">✓</div>
                        <div class="activity-content">
                            <strong>Login Berhasil</strong>
                            <p>Anda berhasil login ke sistem pada <?php echo isset($_SESSION['login_time']) ? htmlspecialchars($_SESSION['login_time']) : date('Y-m-d H:i:s'); ?></p>
                        </div>
                    </div>
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
                    <button class="btn-primary">+ Tambah Produk</button>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Udeng Batik Kuntul Perak</td>
                                <td>Souvenir</td>
                                <td><span class="badge badge-success">Tersedia</span></td>
                                <td>
                                    <button class="btn-small btn-edit">Edit</button>
                                    <button class="btn-small btn-delete">Hapus</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Keripik Bawis</td>
                                <td>Makanan</td>
                                <td><span class="badge badge-success">Tersedia</span></td>
                                <td>
                                    <button class="btn-small btn-edit">Edit</button>
                                    <button class="btn-small btn-delete">Hapus</button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Pakaian Batik Kuntul Perak</td>
                                <td>Pakaian</td>
                                <td><span class="badge badge-success">Tersedia</span></td>
                                <td>
                                    <button class="btn-small btn-edit">Edit</button>
                                    <button class="btn-small btn-delete">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            
            <?php elseif ($page === 'wisata'): ?>
                <div class="section-header">
                    <h2>Kelola Wisata</h2>
                    <button class="btn-primary">+ Tambah Wisata</button>
                </div>
                <div class="grid-cards">
                    <div class="content-card">
                        <div class="card-image" style="background: linear-gradient(135deg, #667eea, #764ba2);"></div>
                        <div class="card-body">
                            <h3>Pantai Beras Basah</h3>
                            <p>Pantai dengan pasir putih bersih dan pemandangan sunset yang memukau.</p>
                            <div class="card-actions">
                                <button class="btn-small btn-edit">Edit</button>
                                <button class="btn-small btn-delete">Hapus</button>
                            </div>
                        </div>
                    </div>
                    <div class="content-card">
                        <div class="card-image" style="background: linear-gradient(135deg, #f093fb, #f5576c);"></div>
                        <div class="card-body">
                            <h3>Hutan Mangrove</h3>
                            <p>Kawasan konservasi mangrove dengan jembatan kayu dan edukasi lingkungan.</p>
                            <div class="card-actions">
                                <button class="btn-small btn-edit">Edit</button>
                                <button class="btn-small btn-delete">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            
            <?php elseif ($page === 'makanan'): ?>
                <div class="section-header">
                    <h2>Kelola Makanan Khas</h2>
                    <button class="btn-primary">+ Tambah Makanan</button>
                </div>
                <div class="grid-cards">
                    <div class="content-card">
                        <div class="card-image" style="background: linear-gradient(135deg, #4facfe, #00f2fe);"></div>
                        <div class="card-body">
                            <h3>Gammi Bawis</h3>
                            <p>Mi kuning khas Bontang dengan topping ikan bawis dan kuah gurih yang autentik.</p>
                            <div class="card-actions">
                                <button class="btn-small btn-edit">Edit</button>
                                <button class="btn-small btn-delete">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            
            <?php elseif ($page === 'setting'): ?>
                <div class="section-header">
                    <h2>Pengaturan</h2>
                </div>
                <div class="settings-container">
                    <div class="setting-group">
                        <h3>Pengaturan Akun</h3>
                        <div class="setting-item">
                            <div class="setting-label">
                                <strong>Ubah Password</strong>
                                <p>Ubah password akun Anda</p>
                            </div>
                            <button class="btn-secondary">Ubah</button>
                        </div>
                        <div class="setting-item">
                            <div class="setting-label">
                                <strong>Ubah Email</strong>
                                <p>Ubah email untuk notifikasi</p>
                            </div>
                            <button class="btn-secondary">Ubah</button>
                        </div>
                    </div>
                    
                    <div class="setting-group">
                        <h3>Pengaturan Tampilan</h3>
                        <div class="setting-item">
                            <div class="setting-label">
                                <strong>Mode Gelap</strong>
                                <p>Aktifkan mode gelap untuk tampilan</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="setting-group">
                        <h3>Informasi Sistem</h3>
                        <div class="info-grid">
                            <div class="info-box">
                                <strong>Versi Sistem</strong>
                                <p>1.0.0</p>
                            </div>
                            <div class="info-box">
                                <strong>PHP Version</strong>
                                <p><?php echo phpversion(); ?></p>
                            </div>
                            <div class="info-box">
                                <strong>Server</strong>
                                <p><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            
            <?php endif; ?>
        </div>
    </main>
</body>
</html>