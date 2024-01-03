<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include '../class/koneksi.php';

$userQuery = "SELECT * FROM users WHERE id=" . $_SESSION["user_id"];
$userResult = mysqli_query($koneksi, $userQuery);
$user = mysqli_fetch_assoc($userResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Iuran KAS RT - Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/user.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Sistem Iuran KAS RT</h1>
            <p>Selamat datang, <?php echo $user["nama"]; ?>!</p>
        </header>

        <div class="wrapper">
            <div class="side-bar">
                <nav>
                    <ul>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="../user.php">Data Warga</a></li>
                        <li><a href="transaksi_iuran_user.php">Iuran KAS</a></li>
                        <li><a href="laporan_transaksi_user.php">Laporan Transaksi</a></li>
                        <li><a href="belum_bayar_user.php">Belum Bayar Iuran</a></li>
                        <li><a href="jumlah_kas_user.php">Jumlah KAS</a></li>
                        <li><a href="../class/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>

            <section id="dashboard" class="col-9">
                <h2>Dashboard</h2>
                <p>Selamat datang di Dashboard Iuran KAS RT, <?php echo $user["nama"]; ?>!</p>
                <p>Di sini Anda dapat melihat ringkasan informasi mengenai iuran dan keuangan RT.</p>
                <p>Periksa halaman Iuran KAS untuk melakukan pembayaran iuran dan Laporan Transaksi untuk melihat riwayat transaksi.</p>
                <p>Jangan lupa untuk mengecek Belum Bayar Iuran jika ada iuran yang masih perlu dibayarkan.</p>
                <p>Anda juga dapat melihat Jumlah KAS untuk mengetahui total saldo kas RT.</p>
            </section>
        </div>

        <footer>
            <p>&copy; 2024, Teknik Informatika, Universitas Pelita Bangsa, Sistem Iuran Kas RT</p>
        </footer>
    </div>
</body>
</html>
