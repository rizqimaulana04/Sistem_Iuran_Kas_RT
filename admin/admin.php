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
    <title>Sistem Iuran KAS RT</title>
    <link rel="stylesheet" type="text/css" href="css/admin.css">
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
                        <li><a href="#data-warga">Data Warga</a></li>
                        <li><a href="#transaksi_iuran">Iuran KAS</a></li>
                        <li><a href="#laporan_transaksi">Laporan Transaksi</a></li>
                        <li><a href="#belum_bayar">Belum Bayar Iuran</a></li>
                        <li><a href="#jumlah_kas">Jumlah KAS</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>

            
        </div>

        <footer>
            <p>&copy; 2024, Teknik Informatika, Universitas Pelita Bangsa, Sistem Iuran Kas RT</p>
        </footer>
    </div>


</body>
</html>
