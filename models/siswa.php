<?php

require_once 'models/baseModel.php';

class Siswa extends BaseModel {

    private $nisn;
    private $nama;
    private $alamat;
    private $id_kelas;

    public function __construct($pdo) {
        parent::__construct($pdo);
    }

    public function getAll() {
        $sql = "SELECT * FROM siswa";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getNisn() {
        return $this->nisn;
    }

    public function setNisn($nisn) {
        $this->nisn = $nisn;
    }

    public function getNama() {
        return $this->nama;
    }

    public function setNama($nama) {
        $this->nama = $nama;
    }

    public function getAlamat() {
        return $this->alamat;
    }

    public function setAlamat($alamat) {
        $this->alamat = $alamat;
    }

    public function getIdKelas() {
        return $this->id_kelas;
    }

    public function setIdKelas($id_kelas) {
        $this->id_kelas = $id_kelas;
    }
}