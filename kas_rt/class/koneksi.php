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