<?php
// File logout.php

session_start();

// Hapus semua variabel sesi
session_unset();

// Hapus sesi
session_destroy();

// Redirect ke halaman login
header("Location: ../../admin/module/login.php");
exit();
?>
