<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["peran"] !== "ADMIN") {
    header("Location: ../index.php");
    exit;
}

include '../../koneksi.php';

if (isset($_POST["simpan"])) {
    $karyawan_id = $_POST["karyawan_id"];
    $tahun = $_POST["tahun"];
    $bulan = $_POST["bulan"];
    $gapok = $_POST["gapok"];
    $tunjangan = $_POST["tunjangan"];
    $uang_makan = $_POST["uang_makan"];

    mysqli_query($conn, "INSERT INTO penggajian (karyawan_id, tahun, bulan, gapok, tunjangan, uang_makan)
        VALUES ($karyawan_id, '$tahun', '$bulan', $gapok, $tunjangan, $uang_makan)");
    
    echo "<script>location='index.php'</script>";
    exit;
}

$karyawans = mysqli_query($conn, "SELECT * FROM karyawan ORDER BY nama_lengkap ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Penggajian | PRAKTIKUM FTI UNISKA</title>
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
                            <h1>Tambah Penggajian</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="index.php">Penggajian</a></li>
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
                                    <h3 class="card-title">Form Tambah Penggajian</h3>
                                </div>
                                <form action="" method="post">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Karyawan</label>
                                            <select name="karyawan_id" class="form-control" required>
                                                <option value="">-- Pilih Karyawan --</option>
                                                <?php while ($k = mysqli_fetch_assoc($karyawans)) : ?>
                                                    <option value="<?php echo $k['id']; ?>"><?php echo $k['nik'] . " - " . $k['nama_lengkap']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <input type="number" name="tahun" class="form-control" value="<?php echo date('Y'); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Bulan (01-12)</label>
                                            <input type="text" name="bulan" class="form-control" maxlength="2" value="<?php echo date('m'); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Gaji Pokok</label>
                                            <input type="number" name="gapok" class="form-control" value="0" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Tunjangan</label>
                                            <input type="number" name="tunjangan" class="form-control" value="0" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Uang Makan</label>
                                            <input type="number" name="uang_makan" class="form-control" value="0" required>
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
