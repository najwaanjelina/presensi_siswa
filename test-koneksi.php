<?php

require_once 'config/Database.php';

$database = new Database();
$pdo = $database->getConnection();

echo "Koneksi database berhasil!";