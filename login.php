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
