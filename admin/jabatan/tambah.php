<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["peran"] !== "ADMIN") {
    header("Location: ../index.php");
    exit;
}

include '../../koneksi.php';

if (isset($_POST["simpan"])) {
    $nama_jabatan = $_POST["nama_jabatan"];
    $gapok_jabatan = $_POST["gapok_jabatan"];
    $tunjangan_jabatan = $_POST["tunjangan_jabatan"];
    $uang_makan_perhari = $_POST["uang_makan_perhari"];

    mysqli_query($conn, "INSERT INTO jabatan (nama_jabatan, gapok_jabatan, tunjangan_jabatan, uang_makan_perhari)
        VALUES ('$nama_jabatan', '$gapok_jabatan', '$tunjangan_jabatan', '$uang_makan_perhari')");
    echo "<script>location='index.php'</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Jabatan | PRAKTIKUM FTI UNISKA</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <?php include "../theme-header.php"; ?>
        <?php include "../theme-sidebar.php"; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Tambah Jabatan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="index.php">Jabatan</a></li>
                                <li class="breadcrumb-item active">Tambah</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Form Tambah Jabatan</h3>
                                </div>
                                <form action="" method="post">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Nama Jabatan</label>
                                            <input type="text" name="nama_jabatan" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Gaji Pokok</label>
                                            <input type="number" name="gapok_jabatan" class="form-control" step="0.01" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Tunjangan</label>
                                            <input type="number" name="tunjangan_jabatan" class="form-control" step="0.01" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Uang Makan per Hari</label>
                                            <input type="number" name="uang_makan_perhari" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                                        <a href="index.php" class="btn btn-secondary">Batal</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include "../theme-footer.php"; ?>

    </div>

    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <script src="../../dist/js/demo.js"></script>
</body>

</html>
