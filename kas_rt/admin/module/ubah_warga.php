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

// Jika data warga yang akan diubah belum dipilih
if (!isset($_GET["id"])) {
    header("Location: admin.php#data-warga");
    exit();
}

$wargaId = $_GET["id"];

// Ambil data warga berdasarkan ID
$dataWargaQuery = "SELECT * FROM warga WHERE id = $wargaId";
$dataWargaResult = mysqli_query($koneksi, $dataWargaQuery);
$dataWarga = mysqli_fetch_assoc($dataWargaResult);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jenis_kelamin = implode(",", $_POST['jenis_kelamin']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_rumah = mysqli_real_escape_string($koneksi, $_POST['no_rumah']);

    // Query untuk mengupdate data warga
    $updateWargaQuery = "UPDATE warga SET nik='$nik', nama='$nama', jenis_kelamin='$jenis_kelamin', no_hp='$no_hp', alamat='$alamat', no_rumah='$no_rumah' WHERE id = $wargaId";

    if (mysqli_query($koneksi, $updateWargaQuery)) {
        header("Location: admin.php#data-warga");
    } else {
        echo "Error: " . $updateWargaQuery . "<br>" . mysqli_error($koneksi);
    }
}

mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Warga - Sistem Iuran KAS RT</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Sistem Iuran KAS RT</h1>
            <p>Selamat datang, <?php echo $user["nama"]; ?>!</p>
        </header>

        <div class="wrapper">
            <div class="side-bar">
                <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
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

            <section id="ubah-warga" class="col-9">
                <h2>Ubah Warga</h2>
                <form method="post" action="ubah_warga.php?id=<?php echo $wargaId; ?>">
                    <label for="nik">NIK:</label>
                    <input type="text" id="nik" name="nik" value="<?php echo $dataWarga['nik']; ?>" required>

                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" value="<?php echo $dataWarga['nama']; ?>" required>

                    <label>Jenis Kelamin:</label>
                    <div class="gender-checkboxes">
                        <input type="checkbox" id="laki_laki" name="jenis_kelamin[]" value="L" <?php echo (strpos($dataWarga['jenis_kelamin'], 'L') !== false) ? 'checked' : ''; ?>>
                        <label for="laki_laki">Laki-laki</label>

                        <input type="checkbox" id="perempuan" name="jenis_kelamin[]" value="P" <?php echo (strpos($dataWarga['jenis_kelamin'], 'P') !== false) ? 'checked' : ''; ?>>
                        <label for="perempuan">Perempuan</label>
                    </div>

                    <label for="no_hp">No HP:</label>
                    <input type="text" id="no_hp" name="no_hp" value="<?php echo $dataWarga['no_hp']; ?>" required>

                    <label for="alamat">Alamat:</label>
                    <textarea id="alamat" name="alamat" required><?php echo $dataWarga['alamat']; ?></textarea>

                    <label for="no_rumah">No Rumah:</label>
                    <input type="text" id="no_rumah" name="no_rumah" value="<?php echo $dataWarga['no_rumah']; ?>" required>

                    <button type="submit">Simpan</button>
                </form>
            </section>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const wrapper = document.querySelector('.wrapper');
                const sidebarToggleBtn = document.querySelector('.toggle-btn');

                sidebarToggleBtn.addEventListener('click', function () {
                    wrapper.classList.toggle('closed');
                    const isClosed = wrapper.classList.contains('closed');
                    sidebarToggleBtn.textContent = isClosed ? '☰' : '✖';
                });
            });
        </script>

        <footer>
            <p>&copy; 2024, Teknik Informatika, Universitas Pelita Bangsa, Sistem Iuran Kas RT</p>
        </footer>
    </div>
</body>
</html>
