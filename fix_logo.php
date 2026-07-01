<?php
$files = [
    'rekap-gaji.php',
    'rekap-gaji-karyawan.php',
    'rekap-bagian.php',
    'rekap-karyawan.php'
];

$old_dt = <<<EOD
                                '<div style="text-align:center; margin-bottom: 20px; position: relative;">' +
                                '<img src="../../assets/Uniska.png" style="width:140px; position:absolute; left:0; top:0;" />' +
                                '<h3>UNIVERSITAS ISLAM KALIMANTAN (UNISKA)</h3>' +
                                '<h4>MUHAMMAD ARSYAD AL BANJARI</h4>' +
                                '<h5>FAKULTAS TEKNOLOGI INFORMASI</h5>' +
                                '<hr style="border-top: 3px solid black; margin-top: 20px; margin-bottom: 20px;">' +
                                '</div>'
EOD;

$new_dt = <<<EOD
                                '<div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px; border-bottom: 3px solid black; padding-bottom: 15px;">' +
                                '<img src="../../assets/Uniska.png" style="height: 85px; margin-right: 20px;" />' +
                                '<div style="text-align: center;">' +
                                '<h3 style="margin: 0; font-size: 24px; font-weight: bold;">UNIVERSITAS ISLAM KALIMANTAN (UNISKA)</h3>' +
                                '<h4 style="margin: 5px 0; font-size: 20px; font-weight: bold;">MUHAMMAD ARSYAD AL BANJARI</h4>' +
                                '<h5 style="margin: 0; font-size: 18px; font-weight: bold;">FAKULTAS TEKNOLOGI INFORMASI</h5>' +
                                '</div>' +
                                '</div>'
EOD;

foreach ($files as $f) {
    $path = __DIR__ . '/admin/laporan/' . $f;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        // Replace carriage returns to normalize string
        $content_norm = str_replace("\r\n", "\n", $content);
        $old_dt_norm = str_replace("\r\n", "\n", $old_dt);
        
        $content = str_replace($old_dt_norm, $new_dt, $content_norm);
        file_put_contents($path, $content);
    }
}
