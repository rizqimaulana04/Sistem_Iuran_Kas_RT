<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include '../../class/koneksi.php';

if (isset($_GET["id"])) {
    $wargaId = $_GET["id"];

    // Gunakan prepared statement untuk menghindari SQL injection
    $deleteWargaQuery = "DELETE FROM warga WHERE id = ?";
    
    if ($stmt = mysqli_prepare($koneksi, $deleteWargaQuery)) {
        // Bind parameter ke statement
        mysqli_stmt_bind_param($stmt, "i", $wargaId);
        
        // Eksekusi statement
        mysqli_stmt_execute($stmt);
        
        // Tutup statement
        mysqli_stmt_close($stmt);
        
        // Redirect kembali ke halaman data-warga setelah menghapus
        header("Location: admin.php");
        exit();
    } else {
        echo "Error in prepared statement: " . mysqli_error($koneksi);
    }
}

mysqli_close($koneksi);
?>
