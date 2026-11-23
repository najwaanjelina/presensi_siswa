
<?php

require_once __DIR__ . '/../config/database.php';

class User {

    private $pdo;
    private $username;
    private $password;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function login() {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->username]);

        $user = $stmt->fetch();

        if ($user && password_verify($this->password, $user['password'])) {
            return $user;
        }

        return false;
    }
}

// getter/setter = mengakses dan mengubah properti.