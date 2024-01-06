<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include '../../class/koneksi.php';

$userQuery = "SELECT * FROM users WHERE id=" . $_SESSION["user_id"];
$userResult = mysqli_query($koneksi, $userQuery);
$user = mysqli_fetch_assoc($userResult);

// Ambil ID transaksi dari parameter URL
if (!isset($_GET["id"])) {
    header("Location: transaksi_iuran.php");
    exit();
}

$transaksiId = $_GET["id"];

// Query untuk mendapatkan data transaksi berdasarkan ID
$transaksiQuery = "SELECT * FROM iuran WHERE id = $transaksiId";
$transaksiResult = mysqli_query($koneksi, $transaksiQuery);
$transaksiData = mysqli_fetch_assoc($transaksiResult);

// Ambil data warga untuk dropdown
$wargaQuery = "SELECT id, nama FROM warga";
$wargaResult = mysqli_query($koneksi, $wargaQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Transaksi - Sistem Iuran KAS RT</title>
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
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
                        <li><a href="../admin.php#data-warga">Data Warga</a></li>
                        <li><a href="transaksi_iuran.php">Iuran KAS</a></li>
                        <li><a href="laporan_transaksi.php">Laporan Transaksi</a></li>
                        <li><a href="belum_bayar.php">Belum Bayar Iuran</a></li>
                        <li><a href="jumlah_kas.php">Jumlah KAS</a></li>
                        <li><a href="../class/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>

            <section id="ubah-transaksi" class="col-9">
                <h2>Ubah Transaksi</h2>
                <form method="post" action="../class/proses_ubah_transaksi.php?id=<?php echo $transaksiId; ?>">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" value="<?php echo $transaksiData['tanggal']; ?>" required>

                    <label for="warga_id">Warga:</label>
                    <select id="warga_id" name="warga_id" required>
                        <?php
                        while ($row = mysqli_fetch_assoc($wargaResult)) {
                            $selected = ($row['id'] == $transaksiData['warga_id']) ? 'selected' : '';
                            echo "<option value='{$row['id']}' {$selected}>{$row['nama']}</option>";
                        }
                        ?>
                    </select>

                    <label for="nominal">Nominal:</label>
                    <input type="text" id="nominal" name="nominal" value="<?php echo $transaksiData['nominal']; ?>" required>

                    <label for="keterangan">Keterangan:</label>
                    <textarea id="keterangan" name="keterangan" required><?php echo $transaksiData['keterangan']; ?></textarea>

                    <label for="jenis_iuran">Jenis Iuran:</label>
                    <div class="jenis-iuran-checkboxes">
                        <input type="checkbox" id="iuran_bulanan" name="jenis_iuran[]" value="1" <?php echo (strpos($transaksiData['jenis_iuran'], '1') !== false) ? 'checked' : ''; ?>>
                        <label for="iuran_bulanan">Iuran Bulanan</label>

                        <input type="checkbox" id="iuran_keamanan" name="jenis_iuran[]" value="2" <?php echo (strpos($transaksiData['jenis_iuran'], '2') !== false) ? 'checked' : ''; ?>>
                        <label for="iuran_keamanan">Iuran Keamanan</label>

                        <input type="checkbox" id="iuran_kebersihan" name="jenis_iuran[]" value="3" <?php echo (strpos($transaksiData['jenis_iuran'], '3') !== false) ? 'checked' : ''; ?>>
                        <label for="iuran_kebersihan">Iuran Kebersihan</label>
                    </div>

                    <button type="submit">Simpan Perubahan</button>
                </form>
            </section>
        </div>

        <footer>
            <p>&copy; 2024, Teknik Informatika, Universitas Pelita Bangsa, Sistem Iuran Kas RT</p>
        </footer>
    </div>
</body>
</html>
