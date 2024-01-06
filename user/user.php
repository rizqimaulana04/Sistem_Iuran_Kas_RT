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

// Pagination settings
$resultsPerPage = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $resultsPerPage;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$whereClause = empty($search) ? '' : "WHERE 
    nama LIKE '%$search%' OR 
    nik LIKE '%$search%' OR 
    no_rumah LIKE '%$search%'";

$dataWargaQuery = "SELECT * FROM warga $whereClause ORDER BY nama ASC LIMIT $offset, $resultsPerPage";
$dataWargaResult = mysqli_query($koneksi, $dataWargaQuery);

// Count total records for pagination
$countQuery = "SELECT COUNT(*) AS total FROM warga $whereClause";
$countResult = mysqli_query($koneksi, $countQuery);
$totalRecords = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRecords / $resultsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Iuran KAS RT</title>
    <link rel="stylesheet" type="text/css" href="css/user.css">
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
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="user.php">Data Warga</a></li>
                        <li><a href="module/transaksi_iuran_user.php">Iuran KAS</a></li>
                        <li><a href="module/belum_bayar_user.php">Belum Bayar Iuran</a></li>
                        <li><a href="module/jumlah_kas_user.php">Jumlah KAS</a></li>
                        <li><a href="class/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>

            <section id="data-warga" class="col-9">
                <form method="get">
                    <input type="text" name="search" placeholder="Cari..." value="<?php echo $search; ?>">
                    <button type="submit">Cari</button>
                </form>

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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nomorBaris = ($page - 1) * $resultsPerPage + 1;

                        while ($row = mysqli_fetch_assoc($dataWargaResult)) {
                            echo "<tr>";
                            echo "<td>" . $nomorBaris . "</td>";
                            echo "<td>" . $row["nik"] . "</td>";
                            echo "<td>" . $row["nama"] . "</td>";
                            echo "<td>" . $row["jenis_kelamin"] . "</td>";
                            echo "<td>" . $row["no_hp"] . "</td>";
                            echo "<td>" . $row["alamat"] . "</td>";
                            echo "<td>" . $row["no_rumah"] . "</td>";
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
                    // Menampilkan maksimal 3 kotak halaman
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
