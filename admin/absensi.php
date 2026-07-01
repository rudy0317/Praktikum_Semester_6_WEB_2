<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["peran"] !== "ADMIN") {
    header("Location: ../index.php");
    exit;
}

include '../koneksi.php';

// Ambil data absensi (presensi) dijoin dengan karyawan
$query = "SELECT p.*, k.nik, k.nama_lengkap 
          FROM presensi p 
          INNER JOIN karyawan k ON p.karyawan_id = k.id 
          ORDER BY p.tanggal DESC, k.nama_lengkap ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Absensi | PRAKTIKUM FTI UNISKA</title>
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
                            <h1>Data Absensi (Presensi)</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Absensi</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Kehadiran Karyawan</h3>
                        </div>
                        <div class="card-body">
                            <table id="tableAbsensi" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Keluar</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($row["tanggal"])); ?></td>
                                            <td><?php echo $row["nik"]; ?></td>
                                            <td><?php echo $row["nama_lengkap"]; ?></td>
                                            <td><?php echo $row["jam_masuk"] ? $row["jam_masuk"] : '-'; ?></td>
                                            <td><?php echo $row["jam_keluar"] ? $row["jam_keluar"] : '-'; ?></td>
                                            <td>
                                                <?php 
                                                    // Styling badge berdasarkan keterangan
                                                    $ket = $row["keterangan"];
                                                    if ($ket == 'HADIR') echo "<span class='badge badge-success'>$ket</span>";
                                                    elseif ($ket == 'SAKIT') echo "<span class='badge badge-warning'>$ket</span>";
                                                    elseif ($ket == 'IZIN' || $ket == 'CUTI') echo "<span class='badge badge-info'>$ket</span>";
                                                    else echo "<span class='badge badge-secondary'>$ket</span>";
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
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
            $("#tableAbsensi").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", {
                    extend: 'print',
                    customize: function ( win ) {
                        $(win.document.body).find('h1').remove();
                        $(win.document.body)
                            .css( 'font-size', '10pt' )
                            .prepend(
                                '<div style="text-align:center; margin-bottom: 20px; position: relative;">' +
                                '<img src="../assets/Uniska.png" style="width:100px; position:absolute; left:0; top:0;" />' +
                                '<h3>UNIVERSITAS ISLAM KALIMANTAN (UNISKA)</h3>' +
                                '<h4>MUHAMMAD ARSYAD AL BANJARI</h4>' +
                                '<h5>FAKULTAS TEKNOLOGI INFORMASI</h5>' +
                                '<hr style="border-top: 3px solid black; margin-top: 20px; margin-bottom: 20px;">' +
                                '</div>' +
                                '<h4 style="text-align: center; font-weight: bold; text-transform: uppercase; margin-top: 10px; margin-bottom: 20px;">Laporan Data Absensi Karyawan</h4>'
                            );
                        
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }
                }]
            }).buttons().container().appendTo('#tableAbsensi_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>

</html>
