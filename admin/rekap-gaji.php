<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["peran"] !== "ADMIN") {
    header("Location: index.php");
    exit;
}

include '../koneksi.php';

// Filter Bulan dan Tahun (default ke bulan/tahun sekarang jika tidak diset)
$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$filter_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

$query = "SELECT P.*, K.nama_lengkap, K.nik 
          FROM penggajian P 
          INNER JOIN karyawan K ON P.karyawan_id = K.id ";

$conditions = [];
if (!empty($filter_bulan)) {
    $conditions[] = "P.bulan = '$filter_bulan'";
}
if (!empty($filter_tahun)) {
    $conditions[] = "P.tahun = '$filter_tahun'";
}

if (count($conditions) > 0) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$query .= " ORDER BY P.tahun DESC, P.bulan DESC";
$result = mysqli_query($conn, $query);

// Array Bulan untuk Dropdown
$nama_bulan = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekap Gaji | PRAKTIKUM FTI UNISKA</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
                            <h1>Laporan Gaji Keseluruhan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Rekap Gaji</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    
                    <!-- Filter Laporan -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-filter"></i> Filter Laporan</h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="rekap-gaji.php">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bulan</label>
                                            <select name="bulan" class="form-control">
                                                <option value="">Semua Bulan</option>
                                                <?php foreach($nama_bulan as $num => $name): ?>
                                                    <option value="<?php echo $num; ?>" <?php echo ($filter_bulan == $num) ? 'selected' : ''; ?>>
                                                        <?php echo $name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <input type="number" name="tahun" class="form-control" value="<?php echo $filter_tahun; ?>" placeholder="Semua Tahun">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary d-block w-100">
                                                <i class="fas fa-search"></i> Tampilkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tabel Laporan -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Rekap Gaji Karyawan</h3>
                        </div>
                        <div class="card-body">
                            <table id="tableRekapGaji" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bulan/Tahun</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Gaji Pokok</th>
                                        <th>Tunjangan</th>
                                        <th>Uang Makan</th>
                                        <th>Total Gaji</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1; 
                                    $grand_total = 0;
                                    while ($row = mysqli_fetch_assoc($result)) : 
                                        $total = $row['gapok'] + $row['tunjangan'] + $row['uang_makan'];
                                        $grand_total += $total;
                                    ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $row["bulan"] . " / " . $row["tahun"]; ?></td>
                                            <td><?php echo $row["nik"]; ?></td>
                                            <td><?php echo $row["nama_lengkap"]; ?></td>
                                            <td>Rp <?php echo number_format($row["gapok"], 0, ',', '.'); ?></td>
                                            <td>Rp <?php echo number_format($row["tunjangan"], 0, ',', '.'); ?></td>
                                            <td>Rp <?php echo number_format($row["uang_makan"], 0, ',', '.'); ?></td>
                                            <td><strong>Rp <?php echo number_format($total, 0, ',', '.'); ?></strong></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" class="text-right">Grand Total Gaji:</th>
                                        <th>Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
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
    <script>
        $(function() {
            $("#tableRekapGaji").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#tableRekapGaji_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>

</html>
