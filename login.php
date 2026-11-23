
<?php
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/users.php';

// Kalau sudah login, langsung ke halaman rekap
if (isset($_SESSION['id_user'])) {
    header("Location: rekap.php");
    exit;
}

// Koneksi database
$database = new Database();
$pdo = $database->getConnection();

// Membuat objek User
$user = new User($pdo);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user->setUsername($username);
    $user->setPassword($password);

    $hasil = $user->login();

    if ($hasil) {
        session_regenerate_id(true);

        $_SESSION['id_user'] = $hasil['id_user'];
        $_SESSION['username'] = $hasil['username'];

        header("Location: rekap.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}

$judul = 'Login Administrator';
require_once __DIR__ . '/layouts/header.php';
?>

<style>
    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f4f6fb;
    }

    .login-container {
        width: 90%;
        max-width: 400px;
        background: white;
        padding: 35px;
        border-radius: 12px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    }

    .login-container h2 {
        text-align: center;
        margin-bottom: 10px;
    }

    .login-container p {
        text-align: center;
        color: #777;
        font-size: 14px;
    }

    .login-container label {
        font-weight: bold;
        font-size: 14px;
    }

    .login-container input {
        width: 100%;
        padding: 11px;
        margin-top: 7px;
        margin-bottom: 18px;
    }

    .login-container button {
        width: 100%;
        padding: 11px;
        font-size: 15px;
    }

    .login-container .error {
        background: #fde8e8;
        color: #a32929;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        margin-bottom: 18px;
    }
</style>

<div class="login-container">
    <h2>Login Administrator</h2>
    <p>Aplikasi Presensi Sekolah</p>

    <?php if ($error): ?>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
