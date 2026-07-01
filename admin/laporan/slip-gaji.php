<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["peran"] !== "ADMIN") {
    header("Location: ../../index.php");
    exit;
}

include '../../koneksi.php';

// Ambil daftar karyawan untuk dropdown
$karyawan_query = mysqli_query($conn, "SELECT id, nik, nama_lengkap FROM karyawan ORDER BY nama_lengkap ASC");
$karyawan_list = [];
while($k = mysqli_fetch_assoc($karyawan_query)) {
    $karyawan_list[] = $k;
}

// Array Bulan
$nama_bulan = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

// Filter
$filter_karyawan = isset($_GET['karyawan_id']) ? $_GET['karyawan_id'] : '';
$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$filter_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$data_slip = null;

if (!empty($filter_karyawan) && !empty($filter_bulan) && !empty($filter_tahun)) {
    $query = "SELECT P.*, K.nama_lengkap, K.nik, 
              (SELECT J.nama_jabatan FROM jabatan_karyawan JK INNER JOIN jabatan J ON JK.jabatan_id = J.id WHERE JK.karyawan_id = K.id ORDER BY JK.tanggal_mulai DESC LIMIT 1) as jabatan_terkini,
              (SELECT B.nama_bagian FROM bagian_karyawan BK INNER JOIN bagian B ON BK.bagian_id = B.id WHERE BK.karyawan_id = K.id ORDER BY BK.tanggal_mulai DESC LIMIT 1) as bagian_terkini
              FROM penggajian P 
              INNER JOIN karyawan K ON P.karyawan_id = K.id 
              WHERE P.karyawan_id = '$filter_karyawan' AND P.bulan = '$filter_bulan' AND P.tahun = '$filter_tahun'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0) {
        $data_slip = mysqli_fetch_assoc($result);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Slip Gaji | PRAKTIKUM FTI UNISKA</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .content-wrapper {
                margin-left: 0 !important;
            }
        }
        .slip-header {
            text-align: center;
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .slip-header img {
            height: 85px;
            width: auto;
            flex-shrink: 0;
            margin-right: 20px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <div class="no-print">
            <?php include "../theme-header.php"; ?>
            <?php include "../theme-sidebar.php"; ?>
        </div>

        <div class="content-wrapper">
            <section class="content-header no-print">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Cetak Slip Gaji</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Slip Gaji</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    
                    <!-- Filter Laporan (Hidden when printing) -->
                    <div class="card card-primary card-outline no-print">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-filter"></i> Pilih Data Gaji</h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="slip-gaji.php">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Karyawan</label>
                                            <select name="karyawan_id" class="form-control" required>
                                                <option value="">Pilih Karyawan</option>
                                                <?php foreach($karyawan_list as $k): ?>
                                                    <option value="<?php echo $k['id']; ?>" <?php echo ($filter_karyawan == $k['id']) ? 'selected' : ''; ?>>
                                                        <?php echo $k['nik'] . ' - ' . $k['nama_lengkap']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Bulan</label>
                                            <select name="bulan" class="form-control" required>
                                                <option value="">Pilih Bulan</option>
                                                <?php foreach($nama_bulan as $num => $name): ?>
                                                    <option value="<?php echo $num; ?>" <?php echo ($filter_bulan == $num) ? 'selected' : ''; ?>>
                                                        <?php echo $name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <input type="number" name="tahun" class="form-control" value="<?php echo $filter_tahun; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
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

                    <?php if ($data_slip): ?>
                    <!-- Tampilan Slip Gaji -->
                    <div class="card">
                        <div class="card-body" style="padding: 40px;">
                            <div class="slip-header" style="position: relative;">
                                <!-- Logo -> adjust path if necessary -->
                                <img src="../../assets/Uniska.png" alt="Logo Uniska" style="position: absolute; left: 10px; top: 0; height: 85px;">
                                <div class="text-center">
                                    <h4 class="mb-1" style="font-weight: bold;">UNIVERSITAS ISLAM KALIMANTAN (UNISKA)</h4>
                                    <h5 class="mb-1" style="font-weight: bold;">MUHAMMAD ARSYAD AL BANJARI</h5>
                                    <h6 class="mb-0" style="font-weight: bold;">FAKULTAS TEKNOLOGI INFORMASI</h6>
                                </div>
                            </div>
                            
                            <h4 class="text-center mb-4" style="text-decoration: underline;">SLIP GAJI KARYAWAN</h4>

                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="30%"><strong>NIK</strong></td>
                                            <td width="5%">:</td>
                                            <td><?php echo $data_slip['nik']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama</strong></td>
                                            <td>:</td>
                                            <td><?php echo $data_slip['nama_lengkap']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="30%"><strong>Bulan/Tahun</strong></td>
                                            <td width="5%">:</td>
                                            <td><?php echo $nama_bulan[$data_slip['bulan']] . ' ' . $data_slip['tahun']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jabatan/Bagian</strong></td>
                                            <td>:</td>
                                            <td><?php echo ($data_slip['jabatan_terkini'] ?? '-') . ' / ' . ($data_slip['bagian_terkini'] ?? '-'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Keterangan Pendapatan</th>
                                        <th class="text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Gaji Pokok</td>
                                        <td class="text-right">Rp <?php echo number_format($data_slip["gapok"], 0, ',', '.'); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tunjangan</td>
                                        <td class="text-right">Rp <?php echo number_format($data_slip["tunjangan"], 0, ',', '.'); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Uang Makan</td>
                                        <td class="text-right">Rp <?php echo number_format($data_slip["uang_makan"], 0, ',', '.'); ?></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-right">Total Penerimaan Bersih</th>
                                        <th class="text-right">Rp <?php echo number_format($data_slip["gapok"] + $data_slip["tunjangan"] + $data_slip["uang_makan"], 0, ',', '.'); ?></th>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="row mt-5">
                                <div class="col-sm-8"></div>
                                <div class="col-sm-4 text-center">
                                    <p>Banjarmasin, <?php echo date('d') . ' ' . $nama_bulan[date('m')] . ' ' . date('Y'); ?></p>
                                    <p class="mb-5">Mengetahui,<br>Manajer HRD</p>
                                    <br><br>
                                    <p style="text-decoration: underline; font-weight: bold;">( .................................... )</p>
                                </div>
                            </div>

                            <div class="row mt-4 no-print">
                                <div class="col-12 text-center">
                                    <button onclick="window.print()" class="btn btn-success"><i class="fas fa-print"></i> Cetak Slip</button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php elseif(isset($_GET['karyawan_id'])): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Data gaji tidak ditemukan untuk karyawan dan periode yang dipilih.
                        </div>
                    <?php endif; ?>

                </div>
            </section>
        </div>

        <div class="no-print">
            <?php include "../theme-footer.php"; ?>
        </div>

    </div>

    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
</body>

</html>
