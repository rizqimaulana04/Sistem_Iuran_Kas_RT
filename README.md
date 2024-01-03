## Halaman user

|  |  |  |
|-----|------|-----|
|Nama|Muhhammad Riyadus Solihin|
|NIM|312210404|
|Kelas|TI.22.A.4|
|Mata Kuliah|Pemograman Web|

### Sintaks Halaman User

```php
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
```
Sintaks php diatas ditaruh disetiap beberapa file untuk memanggil database dari file koneksi dan query berada.
<br>
- Dashboard

    ```HTML
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
                            <li><a href="../user.php">Data Warga</a></li>
                            <li><a href="transaksi_iuran_user.php">Iuran KAS</a></li>
                            <li><a href="laporan_transaksi_user.php">Laporan Transaksi</a></li>
                            <li><a href="belum_bayar_user.php">Belum Bayar Iuran</a></li>
                            <li><a href="jumlah_kas_user.php">Jumlah KAS</a></li>
                            <li><a href="../class/logout.php">Logout</a></li>
                        </ul>
                    </nav>
                </div>

                <section id="dashboard" class="col-9">
                    <h2>Dashboard</h2>
                    <p>Selamat datang di Dashboard Iuran KAS RT, <?php echo $user["nama"]; ?>!</p>
                    <p>Di sini Anda dapat melihat ringkasan informasi mengenai iuran dan keuangan RT.</p>
                    <p>Periksa halaman Iuran KAS untuk melakukan pembayaran iuran dan Laporan Transaksi untuk melihat riwayat transaksi.</p>
                    <p>Jangan lupa untuk mengecek Belum Bayar Iuran jika ada iuran yang masih perlu dibayarkan.</p>
                    <p>Anda juga dapat melihat Jumlah KAS untuk mengetahui total saldo kas RT.</p>
                </section>
            </div>
    ```

- Data Warga

    ```HTML
        <div class="container">
            <header class="header">
                <h1>Sistem Iuran KAS RT</h1>
                <p>Selamat datang, <?php echo $user["nama"]; ?>!</p>
            </header>

            <div class="wrapper">
                <div class="side-bar">
                    <nav>
                        <ul>
                            <li><a href="module/dashboard.php">Dashboard</a></li>
                            <li><a href="user.php">Data Warga</a></li>
                            <li><a href="module/transaksi_iuran_user.php">Iuran KAS</a></li>
                            <li><a href="module/laporan_transaksi_user.php">Laporan Transaksi</a></li>
                            <li><a href="module/belum_bayar_user.php">Belum Bayar Iuran</a></li>
                            <li><a href="module/jumlah_kas_user.php">Jumlah KAS</a></li>
                            <li><a href="class/logout.php">Logout</a></li>
                        </ul>
                    </nav>
                </div>

                <section id="data-warga" class="col-9">
                    
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
                            $dataWargaQuery = "SELECT * FROM warga ORDER BY nama ASC"; // Menambahkan ORDER BY
                            $dataWargaResult = mysqli_query($koneksi, $dataWargaQuery);

                            $nomorBaris = 1; // Inisialisasi nomor baris

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

                                $nomorBaris++; // Inkremen nomor baris
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </div>
    ```

- Iuran Kas

    ```HTML
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

            <section id="transaksi-iuran" class="col-9">

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
                            echo "</tr>";

                            $nomorBaris++; // Inkremen nomor baris
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </div>
    ```

- Laporan Transaksi

    ```HTML
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
    ```

- Belum Bayar Iuran

    ```HTML
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
                            <li><a href="../user.php">Data Warga</a></li>
                            <li><a href="transaksi_iuran_user.php">Iuran KAS</a></li>
                            <li><a href="laporan_transaksi_user.php">Laporan Transaksi</a></li>
                            <li><a href="belum_bayar_user.php">Belum Bayar Iuran</a></li>
                            <li><a href="jumlah_kas_user.php">Jumlah KAS</a></li>
                            <li><a href="../class/logout.php">Logout</a></li>
                        </ul>
                    </nav>
                </div>

                <section id="belum-bayar" class="col-9">
                    <h2>Belum Bayar Iuran</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>No Rumah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $belumBayarQuery = "SELECT * FROM warga WHERE id NOT IN (SELECT DISTINCT warga_id FROM iuran)";
                            $belumBayarResult = mysqli_query($koneksi, $belumBayarQuery);

                            $nomorBaris = 1; // Inisialisasi nomor baris

                            while ($row = mysqli_fetch_assoc($belumBayarResult)) {
                                echo "<tr>";
                                echo "<td>" . $nomorBaris . "</td>";
                                echo "<td>" . $row["nama"] . "</td>";
                                echo "<td>" . $row["jenis_kelamin"] . "</td>";
                                echo "<td>" . $row["no_hp"] . "</td>";
                                echo "<td>" . $row["alamat"] . "</td>";
                                echo "<td>" . $row["no_rumah"] . "</td>";
                                echo "</tr>";

                                $nomorBaris++; // Inkremen nomor baris
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </div>
    ```

- Jumlah Kas

    ```HTML
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

                <section id="jumlah-kas" class="col-9">
                    <h2>Jumlah KAS</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Uang Masuk</td>
                                <td>Rp <?php echo number_format($totalMasuk, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td>Uang Keluar</td>
                                <td>Rp <?php echo number_format($totalKeluar, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>Rp <?php echo number_format($totalKas, 2, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </div>
    ```

### Tampilan Halaman User

https://github.com/rizqimaulana04/Sistem_Iuran_Kas_RT/assets/116700346/ee99dbb5-e696-4f65-b8bf-ba0d0e94de43

