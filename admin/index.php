<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["peran"] !== "ADMIN") {
    header("Location: ../index.php");
    exit;
}

include '../koneksi.php';

// Menghitung statistik untuk Dashboard
$q_karyawan = mysqli_query($conn, "SELECT COUNT(*) as total FROM karyawan");
$total_karyawan = mysqli_fetch_assoc($q_karyawan)['total'];

$q_bagian = mysqli_query($conn, "SELECT COUNT(*) as total FROM bagian");
$total_bagian = mysqli_fetch_assoc($q_bagian)['total'];

$q_jabatan = mysqli_query($conn, "SELECT COUNT(*) as total FROM jabatan");
$total_jabatan = mysqli_fetch_assoc($q_jabatan)['total'];

// Total gaji bulan ini
$bulan_ini = date('m');
$tahun_ini = date('Y');
$q_gaji = mysqli_query($conn, "SELECT SUM(gapok + tunjangan + uang_makan) as total_gaji FROM penggajian WHERE bulan='$bulan_ini' AND tahun='$tahun_ini'");
$r_gaji = mysqli_fetch_assoc($q_gaji);
$total_gaji_bulan_ini = $r_gaji['total_gaji'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin | PRAKTIKUM FTI UNISKA</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <?php include "theme-header.php"; ?>
        <?php include "theme-sidebar.php"; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>SISTEM INFORMASI LAPORAN DATA PENGGAJIAN</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <!-- Menampilkan Alert Selamat Datang -->
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-info"></i> Selamat datang, <?php echo $_SESSION["nama"]; ?>!</h5>
                        Anda login sebagai Administrator.
                    </div>

                    <!-- Row Info Boxes -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?php echo $total_karyawan; ?></h3>
                                    <p>Total Karyawan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <a href="karyawan/" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3 style="font-size: 1.5rem;">Rp <?php echo number_format($total_gaji_bulan_ini, 0, ',', '.'); ?></h3>
                                    <p>Pengeluaran Gaji Bulan Ini</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <a href="rekap-gaji.php?bulan=<?php echo $bulan_ini; ?>&tahun=<?php echo $tahun_ini; ?>" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?php echo $total_bagian; ?></h3>
                                    <p>Total Bagian</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-sitemap"></i>
                                </div>
                                <a href="bagian/" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?php echo $total_jabatan; ?></h3>
                                    <p>Total Jabatan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <a href="jabatan/" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

                </div>
            </section>
        </div>

        <?php include "theme-footer.php"; ?>

    </div>

    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../plugins/jszip/jszip.min.js"></script>
    <script src="../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="../dist/js/demo.js"></script>
</body>

</html>
