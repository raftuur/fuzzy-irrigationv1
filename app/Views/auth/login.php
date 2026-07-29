<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Penyiram Tanaman</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }
        /* Leaf Decorations */
        .leaf {
            position: absolute;
            opacity: 0.15;
            z-index: 0;
        }
        .leaf-1 { top: -80px; left: -80px; width: 400px; transform: rotate(45deg); }
        .leaf-2 { bottom: -100px; right: -50px; width: 450px; transform: rotate(-30deg); }
        
        .login-wrapper {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px 35px;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .logo-container {
            margin-bottom: 25px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background: #ffffff;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.15);
        }
        .logo-container img {
            width: 55px;
            height: auto;
        }
        h2 {
            color: #2e7d32;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 24px;
        }
        p.subtitle {
            color: #757575;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #424242;
            font-size: 14px;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            background: #f9f9f9;
        }
        .form-control:focus {
            border-color: #4caf50;
            background: #ffffff;
            outline: none;
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.1);
        }
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            text-align: center;
            text-decoration: none;
        }
        .btn-primary {
            background: #4caf50;
            color: white;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }
        .btn-primary:hover {
            background: #43a047;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .btn-danger {
            background: transparent;
            color: #757575;
            border: 2px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-danger:hover {
            background: #f5f5f5;
            color: #d32f2f;
            border-color: #d32f2f;
        }
    </style>
</head>
<body>
    <!-- Abstract Leaf Shapes -->
    <svg class="leaf leaf-1" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
        <path fill="#4CAF50" d="M44.7,-76.4C58.3,-69.2,70.1,-58.1,79.5,-45.1C88.9,-32.1,95.9,-16,95.2,-0.4C94.5,15.2,86.1,30.4,75.4,43.2C64.7,56,51.8,66.4,37.3,73.5C22.8,80.6,6.8,84.4,-8.6,82.8C-24,81.2,-38.7,74.2,-51.7,64.8C-64.7,55.4,-75.9,43.6,-82.7,29.8C-89.5,16,-91.9,0.2,-88.7,-14.3C-85.5,-28.8,-76.7,-42,-64.8,-52.1C-52.9,-62.2,-37.9,-69.2,-23.5,-73.9C-9.1,-78.6,4.7,-81,19.3,-78.9C33.9,-76.8,44.7,-76.4,44.7,-76.4Z" transform="translate(100 100)" />
    </svg>
    <svg class="leaf leaf-2" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
        <path fill="#81C784" d="M38.1,-60.6C51.6,-53.4,66.4,-47.5,75.9,-36.5C85.4,-25.5,89.6,-9.4,87.6,6C85.6,21.4,77.4,36.1,66.1,47.4C54.8,58.7,40.4,66.6,25.2,71.2C10,75.8,-6,77.1,-21.5,73.7C-37,70.3,-52.1,62.2,-63.3,50.1C-74.5,38,-81.8,21.9,-83.4,5.4C-85,-11.1,-80.9,-28,-71.4,-41.3C-61.9,-54.6,-47,-64.3,-32.2,-70C-17.4,-75.7,-2.7,-77.4,11.2,-73.6C25.1,-69.8,38.1,-60.6,38.1,-60.6Z" transform="translate(100 100)" />
    </svg>

    <div class="login-wrapper">
        <form id="userlogin" method="post" action="<?= site_url('login') ?>" autocomplete="off">
            <?= csrf_field() ?>
            
            <div class="logo-container">
                <img src="<?= base_url('images/logo/logo.png') ?>" alt="Logo">
            </div>
            <h2>Selamat Datang</h2>
            <p class="subtitle">Admin Dashboard Penyiram Tanaman</p>
            
            <?php if (session()->getFlashdata('error')) : ?>
            <div style="background:#ffebee;padding:12px;margin-bottom:20px;border-radius:10px;color:#c62828;">
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input class="form-control" id="username" type="text" name="username" placeholder="Masukkan username" required autocomplete="off">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" id="password" type="password" name="password" placeholder="Masukkan password" required autocomplete="off">
            </div>
            
            <div class="btn-container">
                <a href="<?= base_url() ?>" class="btn btn-danger">Batal</a>
                <button type="submit" name="login" class="btn btn-primary">Masuk</button>
            </div>
        </form>
    </div>
</body>
</html>