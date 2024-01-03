<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include '../../class/koneksi.php';

$userQuery = "SELECT * FROM users WHERE id=" . $_SESSION["user_id"];
$userResult = mysqli_query($koneksi, $userQuery);
$user = mysqli_fetch_assoc($userResult);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jenis_kelamin = implode(",", $_POST['jenis_kelamin']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_rumah = mysqli_real_escape_string($koneksi, $_POST['no_rumah']);

    $tambahWargaQuery = "INSERT INTO warga (nik, nama, jenis_kelamin, no_hp, alamat, no_rumah, status, users_id) VALUES ('$nik', '$nama', '$jenis_kelamin', '$no_hp', '$alamat', '$no_rumah', '$status', " . $_SESSION["user_id"] . ")";

    if (mysqli_query($koneksi, $tambahWargaQuery)) {
        header("Location: admin.php#data-warga");
    } else {
        echo "Error: " . $tambahWargaQuery . "<br>" . mysqli_error($koneksi);
    }
}

mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Warga - Sistem Iuran KAS RT</title>
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Sistem Iuran KAS RT</h1>
            <p>Selamat datang, <?php echo $user["nama"]; ?>!</p>
        </header>

        <div class="wrapper">
            <div class="side-bar">
                <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
                <nav>
                    <ul>
                        <li><a href="../admin.php#data-warga">Data Warga</a></li>
                        <li><a href="transaksi_iuran.php">Iuran KAS</a></li>
                        <li><a href="laporan_transaksi.php">Laporan Transaksi</a></li>
                        <li><a href="belum_bayar.php">Belum Bayar Iuran</a></li>
                        <li><a href="jumlah_kas.php">Jumlah KAS</a></li>
                        <li><a href="../class/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>

            <section id="tambah-warga" class="col-9">
                <h2>Tambah Warga</h2>
                <form method="post" action="tambah_warga.php">
                    <label for="nik">NIK:</label>
                    <input type="text" id="nik" name="nik" required>

                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" required>

                    <label>Jenis Kelamin:</label>
                    <div class="gender-checkboxes">
                        <input type="checkbox" id="laki_laki" name="jenis_kelamin[]" value="L">
                        <label for="laki_laki">Laki-laki</label>

                        <input type="checkbox" id="perempuan" name="jenis_kelamin[]" value="P">
                        <label for="perempuan">Perempuan</label>
                    </div>

                    <label for="no_hp">No HP:</label>
                    <input type="text" id="no_hp" name="no_hp" required>

                    <label for="alamat">Alamat:</label>
                    <textarea id="alamat" name="alamat" required></textarea>

                    <label for="no_rumah">No Rumah:</label>
                    <input type="text" id="no_rumah" name="no_rumah" required>

                    <button type="submit">Simpan</button>
                </form>
            </section>
        </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wrapper = document.querySelector('.wrapper');
            const sidebarToggleBtn = document.querySelector('.toggle-btn');

            sidebarToggleBtn.addEventListener('click', function () {
                wrapper.classList.toggle('closed');
                const isClosed = wrapper.classList.contains('closed');
                sidebarToggleBtn.textContent = isClosed ? '☰' : '✖';
            });
        });
    </script>

<footer>
            <p>&copy; 2024, Teknik Informatika, Universitas Pelita Bangsa, Sistem Iuran Kas RT</p>
        </footer>
</body>
</html>
