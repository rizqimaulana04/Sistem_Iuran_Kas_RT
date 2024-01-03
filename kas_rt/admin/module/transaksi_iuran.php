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
    <title>Sistem Iuran KAS RT - Transaksi Iuran</title>
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

            <section id="transaksi-iuran" class="col-9">

                <a id="tambah-iuran-link" href="tambah_iuran.php">Tambah Iuran</a>
                <h2>Transaksi Iuran</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Warga</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                            <th>Jenis Iuran</th>
                            <th>Aksi</th>
                            <!-- Add more columns as needed -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $transaksiIuranQuery = "SELECT * FROM iuran";
                        $transaksiIuranResult = mysqli_query($koneksi, $transaksiIuranQuery);

                        $nomorBaris = 1; // Inisialisasi nomor baris

                        while ($row = mysqli_fetch_assoc($transaksiIuranResult)) {
                            echo "<tr>";
                            echo "<td>" . $nomorBaris . "</td>";
                            echo "<td>" . $row["tanggal"] . "</td>";

                            // Dapatkan nama warga berdasarkan warga_id
                            $wargaId = $row["warga_id"];
                            $namaWargaQuery = "SELECT nama FROM warga WHERE id = $wargaId";
                            $namaWargaResult = mysqli_query($koneksi, $namaWargaQuery);
                            $namaWarga = mysqli_fetch_assoc($namaWargaResult)["nama"];

                            echo "<td>" . $namaWarga . "</td>";

                            echo "<td>" . $row["nominal"] . "</td>";
                            echo "<td>" . $row["keterangan"] . "</td>";
                            echo "<td>" . $row["jenis_iuran"] . "</td>";
                            echo "<td class='action-buttons'>";
                            echo "<a class='edit-button' href='ubah_transaksi.php?id=" . $row['id'] . "'>Ubah</a>";
                            echo "<a class='delete-button' href='hapus_transaksi.php?id=" . $row['id'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data?\")'>Hapus</a>";
                            echo "</td>";
                            // Add more columns as needed
                            echo "</tr>";

                            $nomorBaris++; // Inkremen nomor baris
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