
<?php
require_once 'cek_session.php';
require_once 'config/Database.php';

$database = new Database();
$pdo = $database->getConnection();

$pesan = '';
$jenisPesan = '';

// TAMBAH KELAS
if (isset($_POST['tambah'])) {
    $namaKelas = trim($_POST['nama_kelas'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    if ($namaKelas === '' || $jurusan === '') {
        $pesan = "Nama kelas dan jurusan wajib diisi!";
        $jenisPesan = 'error';
    } else {
        $cek = $pdo->prepare(
            "SELECT id_kelas FROM kelas WHERE nama_kelas = ?"
        );
        $cek->execute([$namaKelas]);

        if ($cek->fetch()) {
            $pesan = "Nama kelas sudah digunakan!";
            $jenisPesan = 'error';
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO kelas (nama_kelas, jurusan) VALUES (?, ?)"
            );
            $stmt->execute([$namaKelas, $jurusan]);

            $pesan = "Kelas berhasil ditambahkan!";
            $jenisPesan = 'success';
        }
    }
}

// UBAH KELAS
if (isset($_POST['ubah'])) {
    $idKelas = $_POST['id_kelas'] ?? '';
    $namaKelas = trim($_POST['nama_kelas'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    if ($namaKelas === '' || $jurusan === '') {
        $pesan = "Nama kelas dan jurusan wajib diisi!";
        $jenisPesan = 'error';
    } else {
        $cek = $pdo->prepare(
            "SELECT id_kelas FROM kelas
             WHERE nama_kelas = ? AND id_kelas != ?"
        );
        $cek->execute([$namaKelas, $idKelas]);

        if ($cek->fetch()) {
            $pesan = "Nama kelas sudah digunakan!";
            $jenisPesan = 'error';
        } else {
            $stmt = $pdo->prepare(
                "UPDATE kelas
                 SET nama_kelas = ?, jurusan = ?
                 WHERE id_kelas = ?"
            );
            $stmt->execute([$namaKelas, $jurusan, $idKelas]);

            $pesan = "Kelas berhasil diubah!";
            $jenisPesan = 'success';
        }
    }
}

// HAPUS KELAS
if (isset($_POST['hapus'])) {
    $idKelas = $_POST['id_kelas'] ?? '';

    $cek = $pdo->prepare(
        "SELECT COUNT(*) FROM siswa WHERE id_kelas = ?"
    );
    $cek->execute([$idKelas]);

    if ($cek->fetchColumn() > 0) {
        $pesan = "Kelas tidak bisa dihapus karena masih memiliki siswa!";
        $jenisPesan = 'error';
    } else {
        $stmt = $pdo->prepare(
            "DELETE FROM kelas WHERE id_kelas = ?"
        );
        $stmt->execute([$idKelas]);

        $pesan = "Kelas berhasil dihapus!";
        $jenisPesan = 'success';
    }
}

// AMBIL DATA KELAS
$stmt = $pdo->query("SELECT * FROM kelas ORDER BY id_kelas ASC");
$dataKelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DATA UNTUK EDIT
$editKelas = null;

if (isset($_GET['edit'])) {
    $idEdit = $_GET['edit'];

    if (ctype_digit((string)$idEdit)) {
        $stmt = $pdo->prepare(
            "SELECT * FROM kelas WHERE id_kelas = ?"
        );
        $stmt->execute([$idEdit]);
        $editKelas = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// LAYOUT
$judul = 'Kelola Kelas';
require_once __DIR__ . '/layouts/header.php';
require_once __DIR__ . '/layouts/navbar.php';
?>

<div class="container">

    <h2>Kelola Kelas</h2>
    <p>Tambah, ubah, dan kelola data kelas siswa.</p>

    <?php if ($pesan !== ''): ?>
        <p class="pesan <?= $jenisPesan === 'success' ? 'success' : 'error' ?>">
            <?= htmlspecialchars($pesan) ?>
        </p>
    <?php endif; ?>

    <hr>

    <h3><?= $editKelas ? 'Ubah Kelas' : 'Tambah Kelas' ?></h3>

    <form method="POST">

        <?php if ($editKelas): ?>
            <input type="hidden" name="id_kelas"
                value="<?= htmlspecialchars($editKelas['id_kelas']) ?>">
        <?php endif; ?>

        <label>Nama Kelas</label><br>
        <input type="text" name="nama_kelas"
            value="<?= htmlspecialchars($editKelas['nama_kelas'] ?? '') ?>"
            required>

        <br><br>

        <label>Jurusan</label><br>
        <input type="text" name="jurusan"
            value="<?= htmlspecialchars($editKelas['jurusan'] ?? '') ?>"
            required>

        <br><br>

        <?php if ($editKelas): ?>
            <button type="submit" name="ubah">Simpan Perubahan</button>
            <a href="kelas.php">Batal</a>
        <?php else: ?>
            <button type="submit" name="tambah">Tambah Kelas</button>
        <?php endif; ?>

    </form>

    <hr>

    <h3>Daftar Kelas</h3>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama Kelas</th>
            <th>Jurusan</th>
            <th>Aksi</th>
        </tr>

        <?php if (count($dataKelas) > 0): ?>

            <?php foreach ($dataKelas as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id_kelas']) ?></td>
                <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                <td><?= htmlspecialchars($row['jurusan']) ?></td>
                <td>
                    <a href="kelas.php?edit=<?= urlencode($row['id_kelas']) ?>">
                        Edit
                    </a>

                    <form method="POST" style="display:inline;"
                        onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">

                        <input type="hidden" name="id_kelas"
                            value="<?= htmlspecialchars($row['id_kelas']) ?>">

                        <button type="submit" name="hapus">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>

        <?php else: ?>
            <tr>
                <td colspan="4">Belum ada data kelas.</td>
            </tr>
        <?php endif; ?>

    </table>

</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
