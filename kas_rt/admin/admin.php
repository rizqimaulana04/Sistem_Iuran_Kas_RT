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
                <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
                <nav>
                    <ul>
                        <li><a href="#data-warga">Data Warga</a></li>
                        <li><a href="module/transaksi_iuran.php">Iuran KAS</a></li>
                        <li><a href="module/laporan_transaksi.php">Laporan Transaksi</a></li>
                        <li><a href="module/belum_bayar.php">Belum Bayar Iuran</a></li>
                        <li><a href="module/jumlah_kas.php">Jumlah KAS</a></li>
                        <li><a href="class/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>

            <section id="data-warga" class="col-9">
                
                <a id="tambah-warga-link" href="module/tambah_warga.php">Tambah Warga</a>
                <h2>Data Warga</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>No HP</th>
                            <th>Alamat</th>
                            <th>No Rumah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $dataWargaQuery = "SELECT * FROM warga ORDER BY nama ASC";
                        $dataWargaResult = mysqli_query($koneksi, $dataWargaQuery);

                        $nomorBaris = 1;

                        while ($row = mysqli_fetch_assoc($dataWargaResult)) {
                            echo "<tr>";
                            echo "<td>" . $nomorBaris . "</td>";
                            echo "<td>" . $row["nik"] . "</td>";
                            echo "<td>" . $row["nama"] . "</td>";
                            echo "<td>" . $row["jenis_kelamin"] . "</td>";
                            echo "<td>" . $row["no_hp"] . "</td>";
                            echo "<td>" . $row["alamat"] . "</td>";
                            echo "<td>" . $row["no_rumah"] . "</td>";
                            echo "<td class='action-buttons'>";
                            echo "<a class='edit-button' href='ubah_warga.php?id=" . $row['id'] . "'>Ubah</a>";
                            echo "<a class='delete-button' href='hapus_warga.php?id=" . $row['id'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data?\")'>Hapus</a>";
                            echo "</td>";
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
