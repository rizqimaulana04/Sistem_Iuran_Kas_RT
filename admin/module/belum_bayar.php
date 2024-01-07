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

// Tangkap data pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mengambil data belum bayar iuran dengan pencarian
$bulanIni = date('Y-m-d');
$belumBayarQuery = "SELECT warga.id, warga.nama, warga.jenis_kelamin, warga.no_hp, warga.alamat, warga.no_rumah,
    MONTH(iuran.tanggal) AS bulan,
    CASE WHEN iuran.keterangan = 'lunas' THEN 'Lunas' ELSE 'Belum Bayar' END AS status
    FROM warga
    LEFT JOIN iuran ON warga.id = iuran.warga_id AND MONTH(iuran.tanggal) = MONTH('$bulanIni')
    WHERE (iuran.keterangan IS NULL OR iuran.keterangan != 'lunas') AND warga.nama LIKE '%$search%'";
$belumBayarResult = mysqli_query($koneksi, $belumBayarQuery);

$nomorBaris = 1; // Inisialisasi nomor baris
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belum Bayar Iuran - Sistem Iuran KAS RT</title>
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Iuran KAS RT Kuadrat</h1>
            <p>Selamat datang, <?php echo $user["nama"]; ?>!</p>
        </header>

        <div class="wrapper">
            <div class="side-bar">
                <nav>
                    <ul>
                        <li><a href="../admin.php">Data Warga</a></li>
                        <li><a href="transaksi_iuran.php">Iuran KAS</a></li>
                        <li><a href="laporan_transaksi.php">Laporan Transaksi</a></li>
                        <li><a href="belum_bayar.php">Belum Bayar Iuran</a></li>
                        <li><a href="jumlah_kas.php">Jumlah KAS</a></li>
                        <li><a href="../class/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>

            <section id="belum-bayar" class="col-9">
                <h2>Belum Bayar Iuran</h2>
                <form method="get">
                    <input type="text" name="search" placeholder="Cari berdasarkan nama..." value="<?php echo $search; ?>">
                    <button type="submit">Cari</button>
                </form>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>No HP</th>
                            <th>Alamat</th>
                            <th>No Rumah</th>
                            <th>Bulan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($belumBayarResult)) {
                            echo "<tr>";
                            echo "<td>" . $nomorBaris . "</td>";
                            echo "<td>" . $row["nama"] . "</td>";
                            echo "<td>" . $row["jenis_kelamin"] . "</td>";
                            echo "<td>" . $row["no_hp"] . "</td>";
                            echo "<td>" . $row["alamat"] . "</td>";
                            echo "<td>" . $row["no_rumah"] . "</td>";
                            echo "<td>" . date('F', mktime(0, 0, 0, $row["bulan"], 1)) . "</td>";
                            echo "<td>" . $row["status"] . "</td>";
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
