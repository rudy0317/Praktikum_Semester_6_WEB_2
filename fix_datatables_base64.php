<?php
$files = [
    'rekap-gaji.php',
    'rekap-gaji-karyawan.php',
    'rekap-bagian.php',
    'rekap-karyawan.php'
];

$base64 = base64_encode(file_get_contents(__DIR__ . '/assets/Uniska_small.png'));

$new_buttons = <<<EOD
                "buttons": ["copy", "csv", "excel", 
                {
                    extend: 'pdf',
                    customize: function (doc) {
                        doc.content.splice(0, 0, {
                            margin: [0, 0, 0, 12],
                            alignment: 'center',
                            image: 'data:image/png;base64,{$base64}',
                            width: 60
                        });
                        doc.content.splice(1, 0, {
                            text: 'UNIVERSITAS ISLAM KALIMANTAN (UNISKA)\\nMUHAMMAD ARSYAD AL BANJARI\\nFAKULTAS TEKNOLOGI INFORMASI',
                            alignment: 'center',
                            margin: [0, 0, 0, 12],
                            fontSize: 14,
                            bold: true
                        });
                    }
                },
                {
                    extend: 'print',
                    customize: function ( win ) {
                        $(win.document.body)
                            .css( 'font-size', '10pt' )
                            .prepend(
                                '<div style="display:flex; display:-webkit-box; align-items:center; justify-content:center; margin-bottom: 20px; border-bottom: 3px solid black; padding-bottom: 15px;">' +
                                '<img src="data:image/png;base64,{$base64}" style="height: 85px; width: auto; flex-shrink: 0; margin-right: 20px;" />' +
                                '<div style="text-align: center;">' +
                                '<h3 style="margin: 0; font-size: 24px; font-weight: bold;">UNIVERSITAS ISLAM KALIMANTAN (UNISKA)</h3>' +
                                '<h4 style="margin: 5px 0; font-size: 20px; font-weight: bold;">MUHAMMAD ARSYAD AL BANJARI</h4>' +
                                '<h5 style="margin: 0; font-size: 18px; font-weight: bold;">FAKULTAS TEKNOLOGI INFORMASI</h5>' +
                                '</div>' +
                                '</div>'
                            );
                        
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
EOD;

foreach ($files as $f) {
    $path = __DIR__ . '/admin/laporan/' . $f;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        // Find the start of "buttons": ["copy", "csv", "excel", "pdf", {
        $start_pos = strpos($content, '"buttons": ["copy", "csv", "excel", "pdf", {');
        if ($start_pos !== false) {
            $end_pos = strpos($content, '.css( \'font-size\', \'inherit\' );', $start_pos);
            if ($end_pos !== false) {
                $before = substr($content, 0, $start_pos);
                $after = substr($content, $end_pos + strlen('.css( \'font-size\', \'inherit\' );'));
                
                $new_content = $before . $new_buttons . $after;
                file_put_contents($path, $new_content);
            }
        }
    }
}
echo 'Fixed DataTables PDF and Print with Base64!';
