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
    iuran.tanggal LIKE '%$search%' OR 
    warga.nama LIKE '%$search%'";

$laporanTransaksiQuery = "SELECT iuran.id, iuran.tanggal, warga.nama AS warga, iuran.nominal 
                        FROM iuran
                        JOIN warga ON iuran.warga_id = warga.id
                        $whereClause
                        ORDER BY iuran.tanggal DESC, warga.nama
                        LIMIT $offset, $resultsPerPage";

$laporanTransaksiResult = mysqli_query($koneksi, $laporanTransaksiQuery);

// Count total records for pagination
$countQuery = "SELECT COUNT(*) AS total 
                FROM iuran
                JOIN warga ON iuran.warga_id = warga.id
                $whereClause";
$countResult = mysqli_query($koneksi, $countQuery);
$totalRecords = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRecords / $resultsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Sistem Iuran KAS RT</title>
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
                        <li><a href="../admin.php#data-warga">Data Warga</a></li>
                        <li><a href="transaksi_iuran.php">Iuran KAS</a></li>
                        <li><a href="laporan_transaksi.php">Laporan Transaksi</a></li>
                        <li><a href="belum_bayar.php">Belum Bayar Iuran</a></li>
                        <li><a href="jumlah_kas.php">Jumlah KAS</a></li>
                        <li><a href="../class/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>

            <section id="laporan-transaksi" class="col-9">
                <form method="get">
                    <input type="text" name="search" placeholder="Cari..." value="<?php echo $search; ?>">
                    <button type="submit">Cari</button>
                </form>

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
                        $nomorBaris = ($page - 1) * $resultsPerPage + 1;

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
