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

// Query untuk mengambil total nominal per bulan
$bulanQuery = "SELECT DISTINCT YEAR(tanggal) AS tahun, MONTHNAME(tanggal) AS nama_bulan, SUM(nominal) AS total_bulan FROM iuran GROUP BY YEAR(tanggal), MONTH(tanggal)";
$bulanResult = mysqli_query($koneksi, $bulanQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jumlah KAS - Sistem Iuran KAS RT</title>
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
    <style>
        /* Style untuk kolom total semua kas */
        .total-semua-kas {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: right;
            padding: 5px;
        }
    </style>
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
                <?php
                // Create an associative array to store data for each year
                $yearlyData = array();

                // Loop through the result to organize data by year
                while ($bulan = mysqli_fetch_assoc($bulanResult)) {
                    $tahun = $bulan['tahun'];
                    $namaBulan = $bulan['nama_bulan'];
                    $totalBulan = $bulan['total_bulan'];

                    if (!isset($yearlyData[$tahun])) {
                        $yearlyData[$tahun] = array();
                    }

                    $yearlyData[$tahun][$namaBulan] = $totalBulan;
                }

                // Create tabs for each year
                echo "<div class=\"tabs\">";
                foreach ($yearlyData as $tahun => $data) {
                    echo "<button class=\"tablinks\" onclick=\"openTable(event, '{$tahun}Table')\">{$tahun}</button>";
                }
                echo "</div>";

                // Create tables for each year
                foreach ($yearlyData as $tahun => $data) {
                    // Inisialisasi total kas sebelumnya
                    $totalKasSebelumnya = 0;

                    echo "<div id=\"{$tahun}Table\" class=\"tabcontent\">";
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Jenis</th>";

                    // Loop through the months to create headers
                    foreach ($data as $namaBulan => $totalBulan) {
                        echo "<th>{$namaBulan}</th>";
                    }

                    echo "<th>Total</th>";
                    echo "<th>Total Kas Sebelumnya</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    // Uang Masuk row
                    echo "<tr>";
                    echo "<td>Uang Masuk</td>";

                    // Loop through the months to create cells
                    foreach ($data as $totalBulan) {
                        echo "<td>Rp " . number_format($totalBulan, 2, ',', '.') . "</td>";

                        // Tambahkan total kas sebelumnya di tahun tersebut
                        $totalKasSebelumnya += $totalBulan;
                        echo "<td>Rp " . number_format($totalKasSebelumnya, 2, ',', '.') . "</td>";
                    }

                    echo "<td>Rp " . number_format($totalKasSebelumnya, 2, ',', '.') . "</td>";

                    echo "</tr>";

                    // Uang Keluar row (Assuming no expenses for simplicity)
                    echo "<tr>";
                    echo "<td>Uang Keluar</td>";

                    // Loop through the months to create cells
                    foreach ($data as $totalBulan) {
                        echo "<td>Rp 0.00</td>";
                    }

                    echo "<td>Rp 0.00</td>"; // Uang keluar tidak ada, jadi totalnya Rp 0.00
                    echo "<td>Rp 0.00</td>"; // Total kas sebelumnya juga Rp 0.00

                    echo "</tr>";

                    // Total row
                    echo "<tr>";
                    echo "<td>Total</td>";

                    // Loop through the months to create cells
                    $totalBulan = 0;
                    foreach ($data as $total) {
                        echo "<td>Rp " . number_format($total, 2, ',', '.') . "</td>";
                        $totalBulan += $total;
                    }

                    echo "<td>Rp " . number_format($totalBulan, 2, ',', '.') . "</td>";

                    // Total Kas Sebelumnya di akhir tabel
                    echo "<td>Rp " . number_format($totalKasSebelumnya, 2, ',', '.') . "</td>";

                    echo "</tr>";

                    echo "</tbody>";
                    echo "</table>";

                    echo "</div>";
                }

                // Tabel Total Semua Kas
                echo "<div id=\"totalSemuaKasTable\" class=\"tabcontent\">";
                echo "<table>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Tahun</th>";
                echo "<th>Total Kas</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                $totalSemuaKas = 0;

                // Loop through the yearly data to calculate total kas
                foreach ($yearlyData as $tahun => $data) {
                    $totalTahun = array_sum($data);
                    $totalSemuaKas += $totalTahun;

                    echo "<tr>";
                    echo "<td>{$tahun}</td>";
                    echo "<td>Rp " . number_format($totalTahun, 2, ',', '.') . "</td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";

                // Menampilkan total semua kas dengan warna latar belakang khusus
                echo "<div class=\"total-semua-kas\">";
                echo "<strong>Total Semua Kas:</strong>";
                echo "<span style=\"background-color: #aaffaa;\">Rp " . number_format($totalSemuaKas, 2, ',', '.') . "</span>";
                echo "</div>";

                echo "</div>";
                ?>
            </section>
        </div>

        <footer>
            <p>&copy; 2024, Teknik Informatika, Universitas Pelita Bangsa, Sistem Iuran Kas RT</p>
        </footer>
    </div>

    <script>
        function openTable(evt, tableName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tableName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
</body>
</html>
