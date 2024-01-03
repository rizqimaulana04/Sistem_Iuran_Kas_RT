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
