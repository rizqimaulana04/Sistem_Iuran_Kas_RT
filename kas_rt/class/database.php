<?php
// Include file koneksi
include 'koneksi.php';

// Fungsi untuk mendapatkan data warga dari database
function getWargaData() {
    global $koneksi;
    $query = "SELECT * FROM warga";
    $result = mysqli_query($koneksi, $query);
    return $result;
}

// Fungsi untuk mendapatkan data transaksi iuran dari database
function getIuranData() {
    global $koneksi;
    $query = "SELECT * FROM iuran";
    $result = mysqli_query($koneksi, $query);
    return $result;
}

// Fungsi untuk menambahkan data warga ke database
function tambahWarga($nik, $nama, $jenis_kelamin, $no_hp, $alamat, $no_rumah, $status, $users_id) {
    global $koneksi;
    $query = "INSERT INTO warga (nik, nama, jenis_kelamin, no_hp, alamat, no_rumah, status, users_id) VALUES ('$nik', '$nama', '$jenis_kelamin', '$no_hp', '$alamat', '$no_rumah', '$status', '$users_id')";
    $result = mysqli_query($koneksi, $query);
    return $result;
}

// Fungsi untuk menambahkan data transaksi iuran ke database
function tambahIuran($tanggal, $warga_id, $nominal, $keterangan, $jenis_iuran) {
    global $koneksi;
    $query = "INSERT INTO iuran (tanggal, warga_id, nominal, keterangan, jenis_iuran) VALUES ('$tanggal', '$warga_id', '$nominal', '$keterangan', '$jenis_iuran')";
    $result = mysqli_query($koneksi, $query);
    return $result;
}
?>
