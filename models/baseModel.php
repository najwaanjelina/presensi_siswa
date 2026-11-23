<?php //Class induk yang menyimpan koneksi PDO

abstract class BaseModel {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    abstract public function getAll();
}

// abstract class itu kayak aturan ketua yang harus di ikuti bawahannya