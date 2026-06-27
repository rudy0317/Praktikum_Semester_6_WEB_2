<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["peran"] !== "ADMIN") {
    header("Location: ../../index.php");
    exit;
}

include '../../koneksi.php';

$id = $_GET["id"];
mysqli_query($conn, "DELETE FROM bagian WHERE id=$id");
echo "<script>location='index.php'</script>";
exit;
