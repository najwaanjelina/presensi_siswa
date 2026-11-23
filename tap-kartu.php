
<?php
require_once 'cek_session.php';
require_once 'config/Database.php';

$database = new Database();
$pdo = $database->getConnection();

$pesan = '';
$jenisPesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = trim($_POST['nisn'] ?? '');

    if (!preg_match('/^[0-9]{10}$/', $nisn)) {
        $pesan = "NISN harus terdiri dari 10 digit angka!";
        $jenisPesan = 'error';
    } else {
        // CEK NISN SISWA
        $cek = $pdo->prepare(
            "SELECT nama FROM siswa WHERE nisn = ?"
        );
        $cek->execute([$nisn]);

        $siswa = $cek->fetch(PDO::FETCH_ASSOC);

        if (!$siswa) {
            $pesan = "NISN tidak terdaftar! Presensi ditolak.";
            $jenisPesan = 'error';
        } else {
            // SIMPAN PRESENSI
            $stmt = $pdo->prepare(
                "INSERT INTO presensi (nisn) VALUES (?)"
            );
            $stmt->execute([$nisn]);

            $pesan = "Presensi berhasil! Selamat datang, " . $siswa['nama'] . ".";
            $jenisPesan = 'success';
        }
    }
}

// LAYOUT
$judul = 'Simulasi Tap Kartu';
require_once __DIR__ . '/layouts/header.php';
require_once __DIR__ . '/layouts/navbar.php';
?>

<div class="container">

    <h2>Simulasi Tap Kartu RFID</h2>

    <p>
        Masukkan NISN siswa untuk melakukan simulasi presensi.
    </p>

    <?php if ($pesan !== ''): ?>
        <p class="pesan <?= $jenisPesan === 'success' ? 'success' : 'error' ?>">
            <?= htmlspecialchars($pesan) ?>
        </p>
    <?php endif; ?>

    <hr>

    <h3>Form Tap Kartu</h3>

    <form method="POST">

        <label>Masukkan NISN</label><br>

        <input
            type="text"
            name="nisn"
            maxlength="10"
            pattern="[0-9]{10}"
            inputmode="numeric"
            placeholder="Masukkan 10 digit NISN"
            required
        >

        <br><br>

        <button type="submit">Tap Kartu</button>

    </form>

</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

