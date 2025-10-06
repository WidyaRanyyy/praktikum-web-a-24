<?php
session_start();

// Jika user sudah login, redirect ke dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Proses login jika form disubmit
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    // Validasi sederhana (dalam praktik nyata, gunakan database dan password hashing)
    // Untuk demo: username = "admin", password = "admin123"
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['username'] = $username;
        $_SESSION['login_time'] = date('Y-m-d H:i:s');
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dari Bontang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 1200px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideIn 0.6s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-container {
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-circle {
            display: inline-block;
            margin-bottom: 20px;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .login-header h2 {
            color: #841751;
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .login-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .demo-info {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            color: #0d47a1;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid #2196f3;
            font-size: 0.9rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        .demo-info strong {
            display: block;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .demo-info code {
            background: rgba(255, 255, 255, 0.7);
            padding: 2px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        .error-message {
            background: linear-gradient(135deg, #ffebee, #ffcdd2);
            color: #c62828;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #f44336;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .error-message svg {
            flex-shrink: 0;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            color: #333;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .input-wrapper:focus-within {
            border-color: #841751;
            background: white;
            box-shadow: 0 0 0 3px rgba(132, 23, 81, 0.1);
        }

        .input-wrapper svg {
            color: #666;
            flex-shrink: 0;
        }

        .input-wrapper:focus-within svg {
            color: #841751;
        }

        .input-wrapper input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 1rem;
            color: #333;
            outline: none;
        }

        .input-wrapper input::placeholder {
            color: #999;
        }

        .btn-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px;
            background: linear-gradient(135deg, #841751, #db2796);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(132, 23, 81, 0.4);
            background: linear-gradient(135deg, #6b1242, #b92080);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 4px 10px rgba(132, 23, 81, 0.3);
        }

        .btn-login svg {
            transition: transform 0.3s ease;
        }

        .btn-login:hover svg {
            transform: translateX(5px);
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #841751;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .back-link a:hover {
            background: rgba(132, 23, 81, 0.1);
            transform: translateX(-5px);
        }

        .back-link a svg {
            transition: transform 0.3s ease;
        }

        .back-link a:hover svg {
            transform: translateX(-3px);
        }

        .login-illustration {
            background: linear-gradient(135deg, #841751, #db2796);
            padding: 60px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-illustration::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            animation: float 6s ease-in-out infinite;
        }

        .login-illustration::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
            animation: float 4s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .illustration-content {
            position: relative;
            z-index: 1;
            color: white;
            text-align: center;
        }

        .illustration-content h3 {
            font-size: 2rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .illustration-content p {
            font-size: 1.1rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .features {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(10px);
        }

        .feature-item svg {
            flex-shrink: 0;
        }

        .feature-item span {
            font-size: 1rem;
            font-weight: 600;
        }

        @media (max-width: 1024px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }
            
            .login-illustration {
                display: none;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .login-container {
                padding: 30px 20px;
            }
            
            .login-header h2 {
                font-size: 1.5rem;
            }
            
            .btn-login {
                font-size: 1rem;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header">
                <div class="logo-circle">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <circle cx="30" cy="30" r="28" fill="url(#gradient1)"/>
                        <path d="M30 15L35 25H25L30 15Z" fill="white"/>
                        <circle cx="30" cy="35" r="8" fill="white"/>
                        <defs>
                            <linearGradient id="gradient1" x1="0" y1="0" x2="60" y2="60">
                                <stop offset="0%" stop-color="#841751"/>
                                <stop offset="100%" stop-color="#db2796"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <h2>Login Dari Bontang</h2>
                <p>Masuk ke akun Anda untuk melanjutkan</p>
            </div>
            
            <div class="demo-info">
                <strong>Demo Account:</strong><br>
                Username: <code>admin</code><br>
                Password: <code>admin123</code>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm1 15H9v-2h2v2zm0-4H9V5h2v6z"/>
                    </svg>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 0C7.79 0 6 1.79 6 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z"/>
                        </svg>
                        <input type="text" id="username" name="username" required 
                               placeholder="Masukkan username" autocomplete="username">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M15 7h-1V5c0-2.76-2.24-5-5-5S4 2.24 4 5v2H3c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 16c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM6 7V5c0-2.21 1.79-4 4-4s4 1.79 4 4v2H6z"/>
                        </svg>
                        <input type="password" id="password" name="password" required 
                               placeholder="Masukkan password" autocomplete="current-password">
                    </div>
                </div>
                
                <button type="submit" class="btn-login">
                    <span>Login</span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 0L8.59 1.41 15.17 8H0v2h15.17l-6.58 6.59L10 18l8-8-8-8z"/>
                    </svg>
                </button>
            </form>
            
            <div class="back-link">
                <a href="index.php">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M16 7H3.83l5.59-5.59L8 0 0 8l8 8 1.41-1.41L3.83 9H16V7z"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
        
        <div class="login-illustration">
            <div class="illustration-content">
                <h3>Selamat Datang di Dari Bontang</h3>
                <p>Jelajahi keindahan dan keunikan Kota Bontang melalui platform kami</p>
                <div class="features">
                    <div class="feature-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                        </svg>
                        <span>Akses Aman</span>
                    </div>
                    <div class="feature-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <span>Informasi Terpercaya</span>
                    </div>
                    <div class="feature-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <span>Konten Lokal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>