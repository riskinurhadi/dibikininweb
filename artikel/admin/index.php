<?php
/**
 * Halaman Login Admin
 * Modern & Professional Design
 */

session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        // Kredensial default
        $valid_username = 'admin';
        $valid_password = 'admin123';
        
        if ($username === $valid_username && $password === $valid_password) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_login_time'] = time();
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | dibikininweb</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #18A7D2;
            --primary-dark: #0d6efd;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: move 20s linear infinite;
        }
        
        @keyframes move {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
        
        .login-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            position: relative;
            z-index: 1;
        }
        
        .login-left {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 50px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: move 20s linear infinite;
        }
        
        .login-left > * {
            position: relative;
            z-index: 1;
        }
        
        .login-left .icon-wrapper {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
            font-size: 42px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-left h2 {
            font-weight: 700;
            margin-bottom: 16px;
            font-size: 28px;
            letter-spacing: -1px;
        }
        
        .login-left p {
            opacity: 0.95;
            line-height: 1.8;
            font-size: 14px;
        }
        
        .login-right {
            padding: 40px 35px;
        }
        
        .login-form-header {
            text-align: center;
            margin-bottom: 28px;
        }
        
        .login-form-header h3 {
            font-weight: 700;
            color: #1a1d29;
            margin-bottom: 8px;
            font-size: 22px;
            letter-spacing: -0.5px;
        }
        
        .login-form-header p {
            color: #6c757d;
            font-size: 13px;
        }
        
        .form-group {
            margin-bottom: 18px;
        }
        
        .form-group label {
            font-weight: 600;
            color: #1a1d29;
            margin-bottom: 8px;
            display: block;
            font-size: 13px;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #6c757d;
            padding: 0 16px;
        }
        
        .form-control {
            height: 46px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 11px 16px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-control.input-with-icon {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(24, 167, 210, 0.1);
            outline: none;
        }
        
        .btn-login {
            width: 100%;
            height: 46px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            margin-top: 8px;
            box-shadow: 0 4px 12px rgba(24, 167, 210, 0.3);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(24, 167, 210, 0.4);
            color: white;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
        }
        
        .default-credentials {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 20px;
            margin-top: 24px;
            font-size: 13px;
            color: #495057;
            border: 1px solid #e9ecef;
        }
        
        .default-credentials strong {
            color: var(--primary-color);
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .default-credentials code {
            background: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .back-link {
            text-align: center;
            margin-top: 24px;
        }
        
        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .back-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .login-left {
                display: none;
            }
            
            .login-right {
                padding: 40px 30px;
            }
            
            .login-form-header h3 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="row g-0 h-100">
            <!-- Left Side - Branding -->
            <div class="col-lg-5 login-left d-none d-lg-flex">
                <div>
                    <div class="icon-wrapper">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h2>Admin Panel</h2>
                    <p>Kelola artikel, berita, dan konten website Anda dengan mudah melalui dashboard admin yang modern dan profesional.</p>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div class="col-lg-7 login-right">
                <div class="login-form-header">
                    <h3><i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Admin</h3>
                    <p>Silakan masukkan kredensial Anda untuk mengakses panel admin</p>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" 
                                   class="form-control input-with-icon" 
                                   id="username" 
                                   name="username" 
                                   placeholder="Masukkan username" 
                                   required 
                                   autofocus
                                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" 
                                   class="form-control input-with-icon" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Masukkan password" 
                                   required>
                        </div>
                    </div>
                    
                    <button type="submit" name="login" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Dashboard
                    </button>
                </form>
                
                <div class="default-credentials">
                    <strong>Kredensial Default:</strong>
                    <div class="mt-2">
                        Username: <code>admin</code><br>
                        Password: <code>admin123</code>
                    </div>
                </div>
                
                <div class="back-link">
                    <a href="../index.php">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Halaman Artikel
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
