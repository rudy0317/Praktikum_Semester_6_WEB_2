<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["peran"] !== "ADMIN") {
    header("Location: ../index.php");
    exit;
}

include '../../koneksi.php';

$id = $_GET["id"];
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM karyawan WHERE id=$id"));

if (isset($_POST["simpan"])) {
    $nik = $_POST["nik"];
    $nama_lengkap = $_POST["nama_lengkap"];
    $handphone = $_POST["handphone"];
    $email = $_POST["email"];
    $tanggal_masuk = $_POST["tanggal_masuk"];

    mysqli_query($conn, "UPDATE karyawan SET
        nik='$nik', nama_lengkap='$nama_lengkap', handphone='$handphone',
        email='$email', tanggal_masuk='$tanggal_masuk'
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
    <title>Ubah Karyawan | PRAKTIKUM FTI UNISKA</title>
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
                            <h1>Ubah Karyawan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="index.php">Karyawan</a></li>
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
                                    <h3 class="card-title">Form Ubah Karyawan</h3>
                                </div>
                                <form action="" method="post">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>NIK</label>
                                            <input type="text" name="nik" class="form-control" value="<?php echo $row["nik"]; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama Lengkap</label>
                                            <input type="text" name="nama_lengkap" class="form-control" value="<?php echo $row["nama_lengkap"]; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Handphone</label>
                                            <input type="text" name="handphone" class="form-control" value="<?php echo $row["handphone"]; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo $row["email"]; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Tanggal Masuk</label>
                                            <input type="date" name="tanggal_masuk" class="form-control" value="<?php echo $row["tanggal_masuk"]; ?>" required>
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
