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

// Query untuk mengambil jumlah total nominal masuk dan keluar
$totalMasukQuery = "SELECT SUM(nominal) AS total_masuk FROM iuran";
$totalKeluarQuery = "SELECT SUM(nominal) AS total_keluar FROM pengeluaran";

$totalMasukResult = mysqli_query($koneksi, $totalMasukQuery);
$totalKeluarResult = mysqli_query($koneksi, $totalKeluarQuery);

$totalMasuk = mysqli_fetch_assoc($totalMasukResult)["total_masuk"];
$totalKeluar = mysqli_fetch_assoc($totalKeluarResult)["total_keluar"];
$totalKas = $totalMasuk - $totalKeluar;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jumlah KAS - Sistem Iuran KAS RT</title>
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

            <section id="jumlah-kas" class="col-9">
                <h2>Jumlah KAS</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Uang Masuk</td>
                            <td>Rp <?php echo number_format($totalMasuk, 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Uang Keluar</td>
                            <td>Rp <?php echo number_format($totalKeluar, 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>Rp <?php echo number_format($totalKas, 2, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>
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
