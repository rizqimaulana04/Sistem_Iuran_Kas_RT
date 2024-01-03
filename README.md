## Halaman Login dan Registrasi

|  |  |  |
|-----|------|-----|
|Nama|Sandy Ramadhan|
|NIM|312210633|
|Kelas|TI.22.A.4|
|Mata Kuliah|Pemograman Web|
|  |  |  |

### Database **users. Warga, Iuran**

- Users
    ```sql
    CREATE TABLE IF NOT EXISTS `kas_rt`.`users` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `username` VARCHAR(100) NULL,
        `password` VARCHAR(100) NULL,
        `nama` VARCHAR(200) NULL,
        `email` VARCHAR(200) NULL,
        `status` TINYINT(1) NULL,
        `role` TINYINT(1) NULL DEFAULT 2 COMMENT '1:Admin, 2:User',
        PRIMARY KEY (`id`),
        UNIQUE INDEX `username_UNIQUE` (`username` ASC),
        UNIQUE INDEX `email_UNIQUE` (`email` ASC)
    ) ENGINE = InnoDB;
    ```

- Warga
    ```sql
    CREATE TABLE IF NOT EXISTS `kas_rt`.`warga` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `nik` VARCHAR(50) NULL,
        `nama` VARCHAR(200) NULL,
        `jenis_kelamin` ENUM('L', 'P') NULL,
        `no_hp` VARCHAR(20) NULL,
        `alamat` TINYTEXT NULL,
        `no_rumah` VARCHAR(10) NULL,
        `status` TINYINT(1) NULL,
        `users_id` INT NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE INDEX `nik_UNIQUE` (`nik` ASC),
        INDEX `fk_warga_users1_idx` (`users_id` ASC),
        CONSTRAINT `fk_warga_users1`
            FOREIGN KEY (`users_id`)
            REFERENCES `db_kas_rt`.`users` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
    ) ENGINE = InnoDB;
    ```

- Iuran
    ```sql
    CREATE TABLE IF NOT EXISTS `kas_rt`.`iuran` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `tanggal` DATE NULL,
        `warga_id` INT NOT NULL,
        `nominal` DECIMAL(10,2) NULL,
        `keterangan` TINYTEXT NULL,
        `jenis_iuran` TINYINT(1) NULL,
        PRIMARY KEY (`id`),
        INDEX `fk_iuran_warga1_idx` (`warga_id` ASC),
        CONSTRAINT `fk_iuran_warga1`
            FOREIGN KEY (`warga_id`)
            REFERENCES `db_kas_rt`.`warga` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
    ) ENGINE = InnoDB;

    ```
### Koneksi

```php
<?php
$host = "localhost"; 
$user = "username"; 
$password = "password"; 
$database = "kas_rt"; 

$koneksi = mysqli_connect($host, $user, $password, $database);

// Periksa koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
```

### Login

```php
<?php
// File login.php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include file koneksi
    include 'class/koneksi.php';

    // Ambil data dari formulir
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Cek keberadaan username dalam database
    $checkQuery = "SELECT * FROM users WHERE username='$username'";
    $checkResult = mysqli_query($koneksi, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $row = mysqli_fetch_assoc($checkResult);

        // Verifikasi password
        if (password_verify($password, $row["password"])) {
            // Password benar, set session
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = $row["role"];

            // Redirect ke halaman yang sesuai dengan role
            $redirectPage = ($_SESSION["role"] == 1) ? 'admin/admin.php' : 'user/user.php';
            header("Location: $redirectPage");
            exit();
        } else {
            $errorMessage = "Password salah. Silakan coba lagi.";
        }
    } else {
        $errorMessage = "Username tidak terdaftar. Silakan registrasi <a href='admin/module/register.php'>di sini</a>.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body class="login-body">
    <div class="header">
        <h1>Sistem Iuran Kas RT</h1>
    </div>
    
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <?php
            if (isset($errorMessage)) {
                echo '<p class="error-message">' . $errorMessage . '</p>';
            }
            ?>
            <label for="username">Username:</label>
            <input class="text-input" type="text" name="username" required><br>

            <label for="password">Password:</label>
            <input class="text-input" type="password" name="password" required><br>

            <input type="submit" value="Login">
        </form>
        
        <div class="register-link">
            Belum punya akun? <a href="register.php">Registrasi di sini</a>.
        </div>
    </div>
</body>
</html>
```

### Registrasi

```php
<?php
// File register.php

// Cek apakah formulir telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include file koneksi
    include 'class/koneksi.php';

    // Ambil data dari formulir
    $username = mysqli_real_escape_string($koneksi, $_POST["username"]);
    $password = password_hash(mysqli_real_escape_string($koneksi, $_POST["password"]), PASSWORD_DEFAULT);
    $nama = mysqli_real_escape_string($koneksi, $_POST["nama"]);
    $email = mysqli_real_escape_string($koneksi, $_POST["email"]);
    $status = 1; // Misalnya, set status menjadi aktif

    // Ambil role dari formulir
    $role = mysqli_real_escape_string($koneksi, $_POST["role"]);

    // Contoh query SQL untuk memasukkan data ke dalam tabel users
    $insertQuery = "INSERT INTO users (username, password, nama, email, status, role) VALUES ('$username', '$password', '$nama', '$email', '$status', '$role')";

    // Eksekusi query
    $insertResult = mysqli_query($koneksi, $insertQuery);

    // Periksa apakah query berhasil dieksekusi
    if ($insertResult) {
        // Tentukan halaman tujuan berdasarkan role
        $redirectPage = ($role == 1) ? 'admin/admin.php' : 'user/user.php';

        // Redirect ke halaman yang sesuai dengan role
        header("Location: $redirectPage");
        exit();
    } else {
        $errorMessage = "Registrasi gagal. Silakan coba lagi. Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
    <div class="header-2">
        <h1>Sistem Iuran Kas RT</h1>
    </div>
    <div class="register-container">
        <h2>Registrasi Akun</h2>
        <?php
        if (isset($errorMessage)) {
            echo '<p class="error-message">' . $errorMessage . '</p>';
        }
        ?>
        <form action="register.php" method="post">
            <div class="text-input">
                <label for="username">Username:</label>
                <input class="text-input" type="text" name="username" required><br>

                <label for="password">Password:</label>
                <input class="text-input" type="password" name="password" required><br>

                <label for="nama">Nama:</label>
                <input class="text-input" type="text" name="nama" required><br>

                <label for="email">Email:</label>
                <input class="text-input" type="email" name="email" required><br>

                <!-- Tambahkan field untuk memilih role -->
                <label for="role">Role:</label>
                <select name="role" required>
                    <option value="1">Admin</option>
                    <option value="2">User</option>
                </select><br>
            </div><br>

            <input type="submit" value="Register">
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="login.php">Login di sini</a>.
        </div>
    </div>
</body>
</html>
```

### Tampilan form Login dan Registrasi
https://github.com/rizqimaulana04/Sistem_Iuran_Kas_RT/assets/115614173/2d19fe4a-0288-4010-8fcc-26342f785001


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