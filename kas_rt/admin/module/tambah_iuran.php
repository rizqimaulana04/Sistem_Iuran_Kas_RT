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
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $warga_id = mysqli_real_escape_string($koneksi, $_POST['warga_id']);
    $nominal = mysqli_real_escape_string($koneksi, $_POST['nominal']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $jenis_iuran = implode(",", $_POST['jenis_iuran']);

    $tambahIuranQuery = "INSERT INTO iuran (tanggal, warga_id, nominal, keterangan, jenis_iuran) 
                        VALUES ('$tanggal', '$warga_id', '$nominal', '$keterangan', '$jenis_iuran')";

    if (mysqli_query($koneksi, $tambahIuranQuery)) {
        header("Location: transaksi_iuran.php");
    } else {
        echo "Error: " . $tambahIuranQuery . "<br>" . mysqli_error($koneksi);
    }
}

// Ambil data warga untuk dropdown
$wargaQuery = "SELECT id, nama FROM warga";
$wargaResult = mysqli_query($koneksi, $wargaQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Iuran - Sistem Iuran KAS RT</title>
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
    <style>
        /* Tambahkan CSS tambahan sesuai kebutuhan */
    </style>
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

            <section id="tambah-iuran" class="col-9">
                <h2>Tambah Iuran</h2>
                <form method="post" action="tambah_iuran.php">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" required>

                    <label for="warga_id">Warga:</label>
                    <select id="warga_id" name="warga_id" required>
                        <?php
                        while ($row = mysqli_fetch_assoc($wargaResult)) {
                            echo "<option value='{$row['id']}'>{$row['nama']}</option>";
                        }
                        ?>
                    </select>

                    <label for="nominal">Nominal:</label>
                    <input type="text" id="nominal" name="nominal" required>

                    <label for="keterangan">Keterangan:</label>
                    <textarea id="keterangan" name="keterangan" required></textarea>

                    <label for="jenis_iuran">Jenis Iuran:</label>
                    <div class="jenis-iuran-checkboxes">
                        <input type="checkbox" id="iuran_bulanan" name="jenis_iuran[]" value="1">
                        <label for="iuran_bulanan">Iuran Bulanan</label>

                        <input type="checkbox" id="iuran_keamanan" name="jenis_iuran[]" value="2">
                        <label for="iuran_keamanan">Iuran Keamanan</label>

                        <input type="checkbox" id="iuran_kebersihan" name="jenis_iuran[]" value="3">
                        <label for="iuran_kebersihan">Iuran Kebersihan</label>
                    </div>



                    <button type="submit">Simpan</button>
                </form>
            </section>
        </div>

        <footer>
            <p>&copy; 2024, Teknik Informatika, Universitas Pelita Bangsa, Sistem Iuran Kas RT</p>
        </footer>
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
</body>
</html>
