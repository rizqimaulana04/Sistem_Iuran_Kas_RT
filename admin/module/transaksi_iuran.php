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

// Pagination settings
$resultsPerPage = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $resultsPerPage;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$whereClause = empty($search) ? '' : "WHERE 
    warga.nama LIKE '%$search%' OR 
    iuran.nominal LIKE '%$search%' OR 
    iuran.keterangan LIKE '%$search%' OR 
    iuran.jenis_iuran LIKE '%$search%'";

$transaksiIuranQuery = "SELECT iuran.*, warga.nama AS nama_warga
                        FROM iuran
                        JOIN warga ON iuran.warga_id = warga.id
                        $whereClause
                        ORDER BY iuran.tanggal DESC
                        LIMIT $offset, $resultsPerPage";
$transaksiIuranResult = mysqli_query($koneksi, $transaksiIuranQuery);

// Count total records for pagination
$countQuery = "SELECT COUNT(*) AS total FROM iuran
               JOIN warga ON iuran.warga_id = warga.id
               $whereClause";
$countResult = mysqli_query($koneksi, $countQuery);
$totalRecords = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRecords / $resultsPerPage);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $warga_id = mysqli_real_escape_string($koneksi, $_POST['warga_id']);
    $nominal = mysqli_real_escape_string($koneksi, $_POST['nominal']);
    
    // Mendapatkan total iuran pada bulan ini untuk warga tertentu
    $bulanIni = date("Y-m-01");
    $totalIuranQuery = "SELECT SUM(nominal) AS total_iuran FROM iuran WHERE warga_id = '$warga_id' AND MONTH(tanggal) = MONTH('$bulanIni')";
    $totalIuranResult = mysqli_query($koneksi, $totalIuranQuery);
    $totalIuran = mysqli_fetch_assoc($totalIuranResult)['total_iuran'];

    // Mengatur keterangan berdasarkan total iuran
    $keterangan = ($totalIuran + $nominal >= 50000) ? 'Lunas' : 'Belum Lunas';

    $tambahIuranQuery = "INSERT INTO iuran (warga_id, tanggal, nominal, keterangan, jenis_iuran) VALUES ('$warga_id', NOW(), '$nominal', '$keterangan', 'Iuran Bulanan')";

    if (mysqli_query($koneksi, $tambahIuranQuery)) {
        header("Location: transaksi_iuran.php");
    } else {
        echo "Error: " . $tambahIuranQuery . "<br>" . mysqli_error($koneksi);
    }
}
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

            <section id="transaksi-iuran" class="col-9">
                <form method="get">
                    <input type="text" name="search" placeholder="Cari..." value="<?php echo $search; ?>">
                    <button type="submit">Cari</button>
                </form>

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
                        $nomorBaris = ($page - 1) * $resultsPerPage + 1;

                        while ($row = mysqli_fetch_assoc($transaksiIuranResult)) {
                            echo "<tr>";
                            echo "<td>" . $nomorBaris . "</td>";
                            echo "<td>" . $row["tanggal"] . "</td>";
                            echo "<td>" . $row["nama_warga"] . "</td>";
                            echo "<td>" . $row["nominal"] . "</td>";
                            echo "<td>" . $row["keterangan"] . "</td>";
                            echo "<td>" . $row["jenis_iuran"] . "</td>";
                            echo "<td class='action-buttons'>";
                            echo "<a class='edit-button' href='ubah_transaksi.php?id=" . $row['id'] . "'>Ubah</a>";
                            echo "<a class='delete-button' href='hapus_transaksi.php?id=" . $row['id'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data?\")'>Hapus</a>";
                            echo "</td>";
                            // Add more columns as needed
                            echo "</tr>";

                            $nomorBaris++; // Increment the row number
                        }
                        ?>
                    </tbody>
                </table>

                 <!-- Pagination links -->
                 <div class="pagination">
                    <?php if ($page > 1) : ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>" class="prev">&laquo; Previous</a>
                    <?php endif; ?>

                    <?php
                    $startPage = max(1, $page - 1);
                    $endPage = min($totalPages, $page + 1);

                    for ($i = $startPage; $i <= $endPage; $i++) {
                        $activeClass = ($i == $page) ? 'active' : '';
                        echo "<a class='$activeClass' href='?page=$i&search=$search'>$i</a>";
                    }
                    ?>

                    <?php if ($page < $totalPages) : ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>" class="next">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <footer>
            <p>&copy; 2024, Teknik Informatika, Universitas Pelita Bangsa, Sistem Iuran Kas RT</p>
        </footer>
    </div>
</body>
</html>
