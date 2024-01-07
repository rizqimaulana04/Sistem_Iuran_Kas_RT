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

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <section id="dashboard" class="col-9">
                <h2 class="dashboard-title">Selamat Datang di RT Kuadrat</h2>
                <canvas id="myChart" width="500" height="50" style="max-width: 50%; height: auto;"></canvas>
                <p class="greeting">Hai, <span class="user-name"><?php echo $user["nama"]; ?></span>! Selamat datang di <span class="highlight">Web Iuran KAS RT Kuadrat</span>.</p>
                <p class="dashboard-info">Lihat ringkasan informasi mengenai iuran dan keuangan RT di sini.</p>
                <p class="dashboard-info">Kunjungi halaman <span class="highlight">Iuran KAS</span> untuk membayar iuran.
                <p class="dashboard-info">Pastikan untuk memeriksa <span class="highlight">Belum Bayar Iuran</span> jika ada iuran yang belum dibayarkan.</p>
                <p class="dashboard-info">Juga, lihat <span class="highlight">Jumlah KAS</span> untuk mengetahui total saldo kas RT.</p>
            </section>
            <br>

        </div>
        <script>
            // Inisialisasi grafik dengan data dinamis
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Iuran Terbayar', 'Iuran Belum Bayar'],
                    datasets: [{
                        label: 'Status Iuran',
                        data: [9, 3],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    }]
                }
            });
        </script>

        <footer>
            <p>&copy; 2024, Teknik Informatika, Universitas Pelita Bangsa, Sistem Iuran Kas RT</p>
        </footer>
    </div>
</body>
</html>