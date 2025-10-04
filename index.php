<?php
// index.php (converted from index.html with PHP extension)
// No major PHP changes needed for the main page as it's public, but added session start for potential future use
// Using $_GET to handle a simple query string example, e.g., ?search=term to simulate search handling
session_start();

$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dari Bontang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <video autoplay muted loop id="myVideo">
        <source src="path/to/background-video.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

    <header>
        <div class="header-top">
            <div class="logo-judul">
                <img src="path/to/logo.png" alt="Dari Bontang" class="logo-kanan">
                <h1>Dari Bontang</h1>
            </div>
            <div class="header-form">
                <div class="input-wrapper">
                    <input type="text" placeholder="Cari produk atau wisata..." value="<?php echo $searchTerm; ?>">
                    <button type="submit">&#x1F50D;</button>
                </div>
            </div>
        </div>
        <nav class="nav-links">
            <a href="#beranda">Beranda</a>
            <a href="#produk">Produk</a>
            <a href="#wisata">Wisata</a>
            <a href="#makanan">Makanan</a>
            <a href="#kontak">Kontak</a>
            <!-- Added link to login for authentication feature -->
            <a href="login.php">Login</a>
        </nav>
    </header>

    <section id="beranda" class="section">
        <h2>Selamat Datang di 'Dari Bontang'</h2>
        <p>Temukan berbagai oleh-oleh khas Bontang, selain makanan dan cindera mata. Pengalaman merupakan oleh-oleh terbaik!</p>
        <video class="responsive-img" controls>
            <source src="path/to/video.mp4" type="video/mp4">
            Browser Anda tidak mendukung video.
        </video>
    </section>

    <section id="produk" class="section">
        <h2>Produk Rekomendasi</h2>
        <div class="grid">
            <div class="card">
                <img src="path/to/udeng.jpg" alt="Udeng Batik Kuntul Perak">
                <h3>Udeng Batik Kuntul Perak</h3>
                <p>Udeng tradisional dengan motif khas Bontang yang elegan dan bersejarah.</p>
                <a href="#" class="btn btn-primary">Lihat Info Lengkapnya</a>
            </div>
            <div class="card">
                <img src="path/to/keripik.jpg" alt="Keripik Bawis">
                <h3>Keripik Bawis</h3>
                <p>Keripik ikan bawis khas Bontang yang gurih dan renyah, olahan tradisional.</p>
                <a href="#" class="btn btn-primary">Lihat Info Selengkapnya</a>
            </div>
            <div class="card">
                <img src="path/to/pakaian.jpg" alt="Pakaian Batik Kuntul Perak">
                <h3>Pakaian Batik Kuntul Perak</h3>
                <p>Pakaian batik dengan motif Kuntul Perak untuk berbagai acara formal.</p>
                <a href="#" class="btn btn-primary">Lihat Info Selengkapnya</a>
            </div>
        </div>
    </section>

    <section id="wisata" class="section">
        <h2>Wisata Kota Bontang</h2>
        <div class="grid">
            <div class="card">
                <img src="path/to/pantai.jpg" alt="Pantai Beras Basah">
                <h3>Pantai Beras Basah</h3>
                <p>Pantai dengan pasir putih bersih dan pemandangan sunset yang memukau.</p>
                <a href="#" class="btn btn-primary">Lihat Info Lengkapnya</a>
            </div>
            <div class="card">
                <img src="path/to/mangrove.jpg" alt="Hutan Mangrove">
                <h3>Hutan Mangrove</h3>
                <p>Kawasan konservasi mangrove dengan jembatan kayu dan edukasi lingkungan.</p>
                <a href="#" class="btn btn-primary">Lihat Info Selengkapnya</a>
            </div>
        </div>
    </section>

    <section id="makanan" class="section">
        <h2>Makanan Khas</h2>
        <div class="grid">
            <div class="card">
                <img src="path/to/gammi.jpg" alt="Gammi Bawis">
                <h3>Gammi Bawis</h3>
                <p>Mi kuning khas Bontang dengan topping ikan bawis dan kuah gurih yang autentik.</p>
                <a href="#" class="btn btn-primary">Lihat Info Lengkapnya</a>
            </div>
        </div>
    </section>

    <section id="kontak" class="section">
        <h2>Kontak Kami</h2>
        <p>Hubungi kami untuk informasi lebih lanjut atau pemesanan produk.</p>
        <ul>
            <li>📧 Email: daribontang@gmail.com</li>
            <li>📞 Telepon: +62 821-5687-8954</li>
            <li>📍 Alamat: Jl. Brigjend Katamso, Belimbing, Bontang Barat, Kota Bontang, Kalimantan Timur</li>
            <li>🕒 Jam Operasional: Senin - Sabtu, 08:00 - 17:00 WITA</li>
        </ul>
    </section>

    <footer>
        <p>© 2025 Dari Bontang. All rights reserved.</p>
        <a href="#beranda">Kembali ke Atas</a> | <a href="mailto:daribontang@gmail.com">Email Kami</a>
    </footer>

    <script src="script.js"></script>
</body>
</html>