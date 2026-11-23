
<?php
require_once 'cek_session.php';
require_once 'config/Database.php';

$database = new Database();
$pdo = $database->getConnection();

$pesan = '';
$jenisPesan = '';

// TAMBAH SISWA
if (isset($_POST['tambah'])) {
    $nisn = trim($_POST['nisn'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $idKelas = $_POST['id_kelas'] ?? '';

    if (!preg_match('/^[0-9]{10}$/', $nisn)) {
        $pesan = "NISN wajib terdiri dari 10 digit angka!";
        $jenisPesan = 'error';

    } elseif ($nama === '') {
        $pesan = "Nama siswa wajib diisi!";
        $jenisPesan = 'error';

    } else {
        $cek = $pdo->prepare(
            "SELECT nisn FROM siswa WHERE nisn = ?"
        );
        $cek->execute([$nisn]);

        if ($cek->fetch()) {
            $pesan = "NISN sudah terdaftar!";
            $jenisPesan = 'error';
        } else {
            $cekKelas = $pdo->prepare(
                "SELECT id_kelas FROM kelas WHERE id_kelas = ?"
            );
            $cekKelas->execute([$idKelas]);

            if (!$cekKelas->fetch()) {
                $pesan = "Silakan pilih kelas yang tersedia!";
                $jenisPesan = 'error';
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO siswa (nisn, nama, alamat, id_kelas)
                     VALUES (?, ?, ?, ?)"
                );

                $stmt->execute([
                    $nisn,
                    $nama,
                    $alamat,
                    $idKelas
                ]);

                $pesan = "Data siswa berhasil ditambahkan!";
                $jenisPesan = 'success';
            }
        }
    }
}

// HAPUS SISWA
if (isset($_POST['hapus'])) {
    $nisn = $_POST['nisn'] ?? '';

    try {
        $stmt = $pdo->prepare(
            "DELETE FROM siswa WHERE nisn = ?"
        );
        $stmt->execute([$nisn]);

        if ($stmt->rowCount() > 0) {
            $pesan = "Data siswa berhasil dihapus!";
            $jenisPesan = 'success';
        } else {
            $pesan = "Data siswa tidak ditemukan!";
            $jenisPesan = 'error';
        }

    } catch (PDOException $e) {
        $pesan = "Siswa tidak bisa dihapus karena masih memiliki riwayat presensi!";
        $jenisPesan = 'error';
    }
}

// AMBIL DAFTAR KELAS
$stmt = $pdo->query(
    "SELECT * FROM kelas ORDER BY nama_kelas ASC"
);
$dataKelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// PENCARIAN SISWA
$cariSiswa = trim($_GET['cari'] ?? '');

$sql = "SELECT siswa.*, kelas.nama_kelas
        FROM siswa
        INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas";

if ($cariSiswa !== '') {
    $sql .= " WHERE siswa.nama LIKE :nama
              OR siswa.nisn LIKE :nisn";
}

$sql .= " ORDER BY siswa.nama ASC";

$stmt = $pdo->prepare($sql);

if ($cariSiswa !== '') {
    $keyword = "%$cariSiswa%";
    $stmt->execute([
        ':nama' => $keyword,
        ':nisn' => $keyword
    ]);
} else {
    $stmt->execute();
}

$dataSiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);

// LAYOUT
$judul = 'Kelola Siswa';
require_once __DIR__ . '/layouts/header.php';
require_once __DIR__ . '/layouts/navbar.php';
?>

<div class="container">

    <h2>Kelola Siswa</h2>
    <p>Tambah dan kelola data siswa yang terdaftar di sekolah.</p>

    <?php if ($pesan !== ''): ?>
        <p class="pesan <?= $jenisPesan === 'success' ? 'success' : 'error' ?>">
            <?= htmlspecialchars($pesan) ?>
        </p>
    <?php endif; ?>

    <hr>

    <h3>Tambah Siswa</h3>

    <form method="POST">

        <label>NISN</label><br>
        <input
            type="text"
            name="nisn"
            maxlength="10"
            pattern="[0-9]{10}"
            inputmode="numeric"
            required
        >

        <br><br>

        <label>Nama Siswa</label><br>
        <input type="text" name="nama" required>

        <br><br>

        <label>Alamat</label><br>
        <textarea name="alamat"></textarea>

        <br><br>

        <label>Kelas</label><br>
        <select name="id_kelas" required>
            <option value="">-- Pilih Kelas --</option>

            <?php foreach ($dataKelas as $kelas): ?>
                <option value="<?= htmlspecialchars($kelas['id_kelas']) ?>">
                    <?= htmlspecialchars($kelas['nama_kelas']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <br><br>

        <button type="submit" name="tambah">Tambah Siswa</button>

    </form>

    <hr>

    <h3>Daftar Siswa</h3>

    <form method="GET">
        <input
            type="text"
            name="cari"
            placeholder="Cari nama atau NISN..."
            value="<?= htmlspecialchars($cariSiswa) ?>"
        >

        <button type="submit">Cari</button>
        <a href="siswa.php">Reset</a>
    </form>

    <table>
        <tr>
            <th>NISN</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Kelas</th>
            <th>Aksi</th>
        </tr>

        <?php if (count($dataSiswa) > 0): ?>

            <?php foreach ($dataSiswa as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['nisn']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['alamat'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                <td>
                    <form method="POST" style="display:inline;"
                        onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">

                        <input type="hidden" name="nisn"
                            value="<?= htmlspecialchars($row['nisn']) ?>">

                        <button type="submit" name="hapus">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>

        <?php else: ?>
            <tr>
                <td colspan="5">Data siswa tidak ditemukan.</td>
            </tr>
        <?php endif; ?>

    </table>

</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
