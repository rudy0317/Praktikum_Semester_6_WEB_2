<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["peran"] !== "ADMIN") {
    header("Location: ../index.php");
    exit;
}

include '../../koneksi.php';

$id = $_GET["id"];
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jabatan WHERE id=$id"));

if (isset($_POST["simpan"])) {
    $nama_jabatan = $_POST["nama_jabatan"];
    $gapok_jabatan = $_POST["gapok_jabatan"];
    $tunjangan_jabatan = $_POST["tunjangan_jabatan"];
    $uang_makan_perhari = $_POST["uang_makan_perhari"];

    mysqli_query($conn, "UPDATE jabatan SET
        nama_jabatan='$nama_jabatan', gapok_jabatan='$gapok_jabatan',
        tunjangan_jabatan='$tunjangan_jabatan', uang_makan_perhari='$uang_makan_perhari'
        WHERE id=$id");
    echo "<script>location='index.php'</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Jabatan | PRAKTIKUM FTI UNISKA</title>
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
                            <h1>Ubah Jabatan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="index.php">Jabatan</a></li>
                                <li class="breadcrumb-item active">Ubah</li>
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
                                    <h3 class="card-title">Form Ubah Jabatan</h3>
                                </div>
                                <form action="" method="post">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Nama Jabatan</label>
                                            <input type="text" name="nama_jabatan" class="form-control" value="<?php echo $row["nama_jabatan"]; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Gaji Pokok</label>
                                            <input type="number" name="gapok_jabatan" class="form-control" step="0.01" value="<?php echo $row["gapok_jabatan"]; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Tunjangan</label>
                                            <input type="number" name="tunjangan_jabatan" class="form-control" step="0.01" value="<?php echo $row["tunjangan_jabatan"]; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Uang Makan per Hari</label>
                                            <input type="number" name="uang_makan_perhari" class="form-control" step="0.01" value="<?php echo $row["uang_makan_perhari"]; ?>" required>
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
