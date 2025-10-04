<?php
session_start();

// Jika belum login, redirect ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Gunakan $_GET untuk menerima query string (contoh: dashboard.php?page=profil)
$page = $_GET['page'] ?? 'home';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>Ini adalah halaman dashboard.</p>

    <p>Halaman aktif: <b><?php echo htmlspecialchars($page); ?></b></p>
    <a href="dashboard.php?page=profil">Profil</a> | 
    <a href="dashboard.php?page=setting">Setting</a> | 
    <a href="logout.php">Logout</a>
</body>
</html>
