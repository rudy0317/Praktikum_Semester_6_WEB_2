<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["peran"] !== "ADMIN") {
    header("Location: ../../index.php");
    exit;
}

include '../../koneksi.php';

$id = $_GET["id"];
$karyawan_id = $_GET["karyawan_id"];

mysqli_query($conn, "DELETE FROM jabatan_karyawan WHERE id=$id");
echo "<script>location='karyawan-jabatan.php?id=$karyawan_id'</script>";
exit;
