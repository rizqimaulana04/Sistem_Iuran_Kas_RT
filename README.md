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

