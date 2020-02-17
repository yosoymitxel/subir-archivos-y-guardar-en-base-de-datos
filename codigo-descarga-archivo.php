<?php
//En $direccion_archivo lo cambias por dirección del archivo a descargar ejemplo ./descargar/archivo/texto.txt
//En $nombre_nuevo_archivo_sin_formato irá el nombre pero ojo debe ir un ".txt" o ".pdf" , o "." cualquier formato para que al descargar no te salga un archivo "descarga" sino "descarga.txt"

$file_example = $direccion_archivo;
if (file_exists($file_example)) {
    header('Content-Description: File Transfer');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='.$nombre_nuevo_archivo_sin_formato;
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_example));
    ob_clean();
    flush();
    readfile($file_example);
    exit;
}
else {
    echo 'Archivo no disponible.';
}
