
<?php
$halamanAktif = basename($_SERVER['PHP_SELF']);
?>

<style>
    .navbar {
        background: #263b63;
        padding: 15px 5%;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
    }

    .navbar a {
        color: #e8eef8;
        text-decoration: none;
        padding: 10px 16px;
        border-radius: 7px;
        font-size: 14px;
        font-weight: 500;
        transition: 0.2s;
        position: relative;
    }

    .navbar a:hover {
        background: #3b527e;
        color: white;
    }

    .navbar a.active {
        background: #ffffff;
        color: #263b63;
        font-weight: bold;
    }

    .navbar a.active::after {
        content: "";
        position: absolute;
        bottom: -5px;
        left: 20%;
        width: 60%;
        height: 3px;
        background: #9bb9e8;
        border-radius: 10px;
    }

    .navbar .logout {
        margin-left: auto;
    }

    @media (max-width: 600px) {
        .navbar {
            gap: 5px;
            padding: 12px;
        }

        .navbar a {
            padding: 9px 11px;
            font-size: 12px;
        }

        .navbar .logout {
            margin-left: 0;
        }
    }
</style>

<nav class="navbar">
    <a href="rekap.php"
       class="<?= $halamanAktif === 'rekap.php' ? 'active' : '' ?>">
        Rekap Presensi
    </a>

    <a href="kelas.php"
       class="<?= $halamanAktif === 'kelas.php' ? 'active' : '' ?>">
        Kelola Kelas
    </a>

    <a href="siswa.php"
       class="<?= $halamanAktif === 'siswa.php' ? 'active' : '' ?>">
        Kelola Siswa
    </a>

    <a href="tap-kartu.php"
       class="<?= $halamanAktif === 'tap-kartu.php' ? 'active' : '' ?>">
        Tap Kartu
    </a>

    <a href="logout.php" class="logout">
        Logout
    </a>
</nav>
