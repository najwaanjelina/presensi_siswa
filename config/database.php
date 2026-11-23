<?php

class Database {
    private $host = 'localhost';
    private $db = 'struktur';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';
    private $conn;

    public function __construct() { //dijalankan otomatis saat objek dibuat.
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";

        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass);

            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION //membuat PDO melempar exception jika terjadi error.
            );

        } catch (PDOException $e) {
            error_log($e->getMessage());
            die('Koneksi database gagal.');
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
