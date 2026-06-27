<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["peran"] !== "ADMIN") {
    header("Location: ../../index.php");
    exit;
}

include '../../koneksi.php';

$id = $_GET["id"];
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bagian WHERE id=$id"));

if (isset($_POST["simpan"])) {
    $nama_bagian = $_POST["nama_bagian"];
    $karyawan_id = $_POST["karyawan_id"];
    $lokasi_id = $_POST["lokasi_id"];

    mysqli_query($conn, "UPDATE bagian SET
        nama_bagian='$nama_bagian', karyawan_id='$karyawan_id', lokasi_id='$lokasi_id'
        WHERE id=$id");
    echo "<script>location='index.php'</script>";
    exit;
}

$karyawan_options = mysqli_query($conn, "SELECT * FROM karyawan ORDER BY nama_lengkap ASC");
$lokasi_options = mysqli_query($conn, "SELECT * FROM lokasi ORDER BY nama_lokasi ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Bagian | PRAKTIKUM FTI UNISKA</title>
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
                            <h1>Ubah Bagian</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="index.php">Bagian</a></li>
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
                                    <h3 class="card-title">Form Ubah Bagian</h3>
                                </div>
                                <form action="" method="post">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Nama Bagian</label>
                                            <input type="text" name="nama_bagian" class="form-control" value="<?php echo $row["nama_bagian"]; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Kepala Bagian</label>
                                            <select name="karyawan_id" class="form-control" required>
                                                <option value="">-- Pilih Kepala Bagian --</option>
                                                <?php while ($k = mysqli_fetch_assoc($karyawan_options)) : ?>
                                                    <option value="<?php echo $k['id']; ?>" <?php echo $row["karyawan_id"] == $k["id"] ? 'selected' : ''; ?>><?php echo $k['nama_lengkap']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Lokasi</label>
                                            <select name="lokasi_id" class="form-control" required>
                                                <option value="">-- Pilih Lokasi --</option>
                                                <?php while ($l = mysqli_fetch_assoc($lokasi_options)) : ?>
                                                    <option value="<?php echo $l['id']; ?>" <?php echo $row["lokasi_id"] == $l["id"] ? 'selected' : ''; ?>><?php echo $l['nama_lokasi']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
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
