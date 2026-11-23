<?php

require_once 'models/baseModel.php';

class Presensi extends BaseModel {

    private $id;
    private $nisn;
    private $tanggal_waktu;

    public function __construct($pdo) {
        parent::__construct($pdo);
    }

    public function getAll() {
        $sql = "SELECT * FROM presensi";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNisn() {
        return $this->nisn;
    }

    public function setNisn($nisn) {
        $this->nisn = $nisn;
    }

    public function getTanggalWaktu() {
        return $this->tanggal_waktu;
    }

    public function setTanggalWaktu($tanggal_waktu) {
        $this->tanggal_waktu = $tanggal_waktu;
    }
}