<?php // sudah bisa di hapus sebenarnya

require_once 'config/Database.php';

$database = new Database();
$pdo = $database->getConnection();

$username = 'admin';
$password = password_hash('admin2511', PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username, $password]);

echo "Akun Administrator berhasil dibuat!";