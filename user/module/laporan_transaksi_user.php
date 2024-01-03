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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Sistem Iuran KAS RT</title>
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
                        <li><a href="../user.php#data-warga">Data Warga</a></li>
                        <li><a href="transaksi_iuran_user.php">Iuran KAS</a></li>
                        <li><a href="laporan_transaksi_user.php">Laporan Transaksi</a></li>
                        <li><a href="belum_bayar_user.php">Belum Bayar Iuran</a></li>
                        <li><a href="jumlah_kas_user.php">Jumlah KAS</a></li>
                        <li><a href="../class/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>

            <section id="laporan-transaksi" class="col-9">
                <h2>Laporan Transaksi</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Transaksi Masuk</th>
                            <th>Warga</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $laporanTransaksiQuery = "SELECT iuran.id, iuran.tanggal, warga.nama AS warga, iuran.nominal 
                                                FROM iuran
                                                JOIN warga ON iuran.warga_id = warga.id";
                        $laporanTransaksiResult = mysqli_query($koneksi, $laporanTransaksiQuery);

                        $nomorBaris = 1;

                        while ($row = mysqli_fetch_assoc($laporanTransaksiResult)) {
                            echo "<tr>";
                            echo "<td>" . $nomorBaris . "</td>";
                            echo "<td>" . $row["tanggal"] . "</td>";
                            echo "<td>" . $row["warga"] . "</td>";
                            echo "<td>" . $row["nominal"] . "</td>";
                            echo "</tr>";

                            $nomorBaris++;
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </div>

        <footer>
            <p>&copy; 2024, Teknik Informatika, Universitas Pelita Bangsa, Sistem Iuran Kas RT</p>
        </footer>
    </div>

</body>
</html>
