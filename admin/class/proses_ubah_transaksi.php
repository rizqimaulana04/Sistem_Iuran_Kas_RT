<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include '../../class/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transaksiId = $_GET["id"];

    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $warga_id = mysqli_real_escape_string($koneksi, $_POST['warga_id']);
    $nominal = mysqli_real_escape_string($koneksi, $_POST['nominal']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $jenis_iuran = implode(",", $_POST['jenis_iuran']);

    // Query untuk mengupdate data transaksi
    $updateTransaksiQuery = "UPDATE iuran SET tanggal='$tanggal', warga_id='$warga_id', nominal='$nominal', keterangan='$keterangan', jenis_iuran='$jenis_iuran' WHERE id = $transaksiId";

    if (mysqli_query($koneksi, $updateTransaksiQuery)) {
        header("Location: ../module/transaksi_iuran.php");
    } else {
        echo "Error: " . $updateTransaksiQuery . "<br>" . mysqli_error($koneksi);
    }
} else {
    header("Location: ../module/transaksi_iuran.php");
}
?>
