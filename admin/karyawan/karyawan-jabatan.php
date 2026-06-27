<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["peran"] !== "ADMIN") {
    header("Location: ../../index.php");
    exit;
}

include '../../koneksi.php';

$karyawan_id = $_GET["id"];

// Get Karyawan Detail
$karyawan_q = mysqli_query($conn, "SELECT * FROM karyawan WHERE id = $karyawan_id");
$karyawan = mysqli_fetch_assoc($karyawan_q);

if (!$karyawan) {
    echo "<script>alert('Karyawan tidak ditemukan'); location='index.php';</script>";
    exit;
}

// Handle Add Jabatan History
if (isset($_POST["simpan"])) {
    $jabatan_id = $_POST["jabatan_id"];
    $tanggal_mulai = $_POST["tanggal_mulai"];

    mysqli_query($conn, "INSERT INTO jabatan_karyawan (karyawan_id, jabatan_id, tanggal_mulai) 
        VALUES ($karyawan_id, $jabatan_id, '$tanggal_mulai')");
    echo "<script>location='karyawan-jabatan.php?id=$karyawan_id'</script>";
    exit;
}

// Get Jabatan options
$jabatan_options = mysqli_query($conn, "SELECT * FROM jabatan ORDER BY nama_jabatan ASC");

// Get Position History
$history = mysqli_query($conn, "SELECT JK.*, J.nama_jabatan 
    FROM jabatan_karyawan JK 
    INNER JOIN jabatan J ON JK.jabatan_id = J.id 
    WHERE JK.karyawan_id = $karyawan_id 
    ORDER BY JK.tanggal_mulai DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jabatan Karyawan | PRAKTIKUM FTI UNISKA</title>
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
                            <h1>Jabatan Karyawan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="index.php">Karyawan</a></li>
                                <li class="breadcrumb-item active">Jabatan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Karyawan</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" class="form-control" value="<?php echo $karyawan['nama_lengkap']; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input type="text" class="form-control" value="<?php echo $karyawan['nik']; ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Tambah Jabatan</h3>
                                </div>
                                <form action="" method="post">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Jabatan</label>
                                            <select name="jabatan_id" class="form-control" required>
                                                <option value="">-- Pilih Jabatan --</option>
                                                <?php while ($j = mysqli_fetch_assoc($jabatan_options)) : ?>
                                                    <option value="<?php echo $j['id']; ?>"><?php echo $j['nama_jabatan']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Riwayat Jabatan</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Jabatan</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; while ($row = mysqli_fetch_assoc($history)) : ?>
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><?php echo $row['nama_jabatan']; ?></td>
                                                    <td><?php echo $row['tanggal_mulai']; ?></td>
                                                    <td>
                                                        <a href="karyawan-jabatan-hapus.php?id=<?php echo $row['id']; ?>&karyawan_id=<?php echo $karyawan_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus riwayat jabatan ini?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
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
