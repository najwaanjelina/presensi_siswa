
<?php
require_once 'cek_session.php';
require_once 'config/Database.php';

$database = new Database();
$pdo = $database->getConnection();

$pesan = '';
$jenisPesan = '';

$cariPresensi = trim($_GET['cari'] ?? $_POST['cari'] ?? '');

$tanggalFilter = $_GET['tanggal'] ?? $_POST['tanggal'] ?? date('Y-m-d');
$kelasFilter = $_GET['kelas'] ?? $_POST['kelas'] ?? '';

if ($tanggalFilter === '') {
    $tanggalFilter = date('Y-m-d');
}

// HAPUS LOG PRESENSI
if (isset($_POST['hapus'])) {
    $id = $_POST['id'] ?? '';

    if (ctype_digit((string) $id) && (int) $id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM presensi WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                $pesan = "Log presensi berhasil dihapus!";
                $jenisPesan = 'success';
            } else {
                $pesan = "Log presensi tidak ditemukan.";
                $jenisPesan = 'error';
            }
        } catch (PDOException $e) {
            $pesan = "Log presensi gagal dihapus.";
            $jenisPesan = 'error';
        }
    } else {
        $pesan = "Data presensi tidak valid.";
        $jenisPesan = 'error';
    }
}

// AMBIL DATA KELAS
$stmt = $pdo->query("SELECT * FROM kelas ORDER BY nama_kelas ASC");
$dataKelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// REKAP PRESENSI
$sql = "SELECT
            p.id,
            p.nisn,
            s.nama,
            k.nama_kelas,
            p.tanggal_waktu
        FROM presensi p
        INNER JOIN siswa s ON p.nisn = s.nisn
        INNER JOIN kelas k ON s.id_kelas = k.id_kelas
        WHERE DATE(p.tanggal_waktu) = :tanggal";

$params = [':tanggal' => $tanggalFilter];

// PENCARIAN NAMA ATAU NISN
if ($cariPresensi !== '') {
    $sql .= " AND (s.nama LIKE :nama OR s.nisn LIKE :nisn)";
    $keyword = "%$cariPresensi%";
    $params[':nama'] = $keyword;
    $params[':nisn'] = $keyword;
}

// FILTER KELAS
if ($kelasFilter !== '') {
    $sql .= " AND k.id_kelas = :id_kelas";
    $params[':id_kelas'] = $kelasFilter;
}

$sql .= " ORDER BY p.tanggal_waktu DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$dataPresensi = $stmt->fetchAll(PDO::FETCH_ASSOC);

// LAYOUT
$judul = 'Rekap Presensi';
require_once 'layouts/header.php';
require_once 'layouts/navbar.php';
?>

<div class="container">

    <h2>Rekap Presensi Siswa</h2>

    <p>
        Selamat datang,
        <?= htmlspecialchars($_SESSION['username']) ?>!
    </p>

    <?php if ($pesan !== ''): ?>
        <p class="pesan <?= $jenisPesan === 'success' ? 'success' : 'error' ?>">
            <?= htmlspecialchars($pesan) ?>
        </p>
    <?php endif; ?>

    <h3>Filter dan Pencarian Presensi</h3>

    <form method="GET">

        <label>Tanggal:</label>
        <input
            type="date"
            name="tanggal"
            value="<?= htmlspecialchars($tanggalFilter) ?>"
        >

        <label>Kelas:</label>
        <select name="kelas">
            <option value="">Semua Kelas</option>

            <?php foreach ($dataKelas as $kelas): ?>
                <option
                    value="<?= htmlspecialchars($kelas['id_kelas']) ?>"
                    <?= (string)$kelasFilter === (string)$kelas['id_kelas'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($kelas['nama_kelas']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <label>Cari Siswa:</label>
        <input
            type="text"
            name="cari"
            placeholder="Nama atau NISN..."
            value="<?= htmlspecialchars($cariPresensi) ?>"
        >

        <button type="submit">Filter</button>
        <a href="rekap.php">Reset</a>

    </form>

    <h3>Daftar Presensi</h3>

    <table>
        <tr>
            <th>No</th>
            <th>NISN</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Tanggal dan Waktu</th>
            <th>Aksi</th>
        </tr>

        <?php if (count($dataPresensi) > 0): ?>

            <?php $no = 1; ?>

            <?php foreach ($dataPresensi as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nisn']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_waktu']) ?></td>

                <td>
                    <form method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus log presensi ini?')">

                        <input type="hidden" name="id"
                            value="<?= htmlspecialchars($row['id']) ?>">

                        <input type="hidden" name="tanggal"
                            value="<?= htmlspecialchars($tanggalFilter) ?>">

                        <input type="hidden" name="kelas"
                            value="<?= htmlspecialchars($kelasFilter) ?>">

                        <input type="hidden" name="cari"
                            value="<?= htmlspecialchars($cariPresensi) ?>">

                        <button type="submit" name="hapus">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="6">Belum ada data presensi pada tanggal ini.</td>
            </tr>

        <?php endif; ?>
    </table>

</div>

<?php require_once 'layouts/footer.php'; ?>
