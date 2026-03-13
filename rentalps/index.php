<?php
session_start();

if (isset($_SESSION['username'])) {
    header("location:dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Rental PS</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body class="login-page">

    <div class="login-shell">

        <div class="login-side">
            <div class="login-side-badge">🎮 Rental PS Terbaik</div>
            <h1>Tempat nyaman untuk bermain PS dengan suasana seru dan menyenangkan.</h1>
            <p>
                Nikmati pengalaman bermain PlayStation dengan unit yang terawat, tempat yang nyaman,
                dan pelayanan yang ramah untuk menemani waktu bermain Anda.
            </p>

            <div class="login-feature-list">
                <div class="login-feature-item">
                    <span>✅</span>
                    <p>Tempat nyaman dan cocok untuk main santai</p>
                </div>
                <div class="login-feature-item">
                    <span>✅</span>
                    <p>Unit PS terawat dan siap dimainkan</p>
                </div>
                <div class="login-feature-item">
                    <span>✅</span>
                    <p>Cocok untuk bermain sendiri maupun bersama teman</p>
                </div>
            </div>
        </div>

        <div class="login-card-modern">
            <div class="login-logo-wrap">
                <img src="controller.png" alt="Controller">
            </div>

            <h2>Selamat Datang</h2>
            <p class="login-subtitle">Silakan login untuk masuk ke sistem Rental PS</p>

            <form method="POST" action="cek_login.php" class="login-form-modern">
                <div class="input-modern-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Masukkan username" required>
                </div>

                <div class="input-modern-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn-login-modern">Login Sekarang</button>
            </form>
        </div>

    </div>

</body>

</html>