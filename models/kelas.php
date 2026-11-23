
<?php

require_once 'models/BaseModel.php';

class Kelas extends BaseModel {

    private $idKelas;
    private $namaKelas;
    private $jurusan;

    public function __construct($pdo) { // memanggil constructor milik induknya.
        parent::__construct($pdo);
    }

    public function getAll() { // memenuhi abstract method dari BaseModel.
        $stmt = $this->pdo->query("SELECT * FROM kelas");
        return $stmt->fetchAll();
    }

    public function getIdKelas() {
        return $this->idKelas;
    }

    public function setIdKelas($idKelas) {
        $this->idKelas = $idKelas;
    }

    public function getNamaKelas() {
        return $this->namaKelas;
    }

    public function setNamaKelas($namaKelas) {
        $this->namaKelas = $namaKelas;
    }

    public function getJurusan() {
        return $this->jurusan;
    }

    public function setJurusan($jurusan) {
        $this->jurusan = $jurusan;
    }
}
