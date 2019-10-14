<?php
/*Codigo original se encuentra en https://github.com/tutsplus/how-to-upload-a-file-in-php-with-example*/
//Tabla:  archivos
//Campos: id, archivo_nombre, archivo_peso, archivo_tipo, archivo_nombre_real, fecha_insercion
include_once("/conexion.php");
$message = '';

//Valida que esté subido un archivo
if ( isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
    //Consigue los datos del archivo
    $fileTmpPath   = $_FILES['uploadedFile']['tmp_name'];
    $fileName      = $_FILES['uploadedFile']['name'];
    $nombre_real   = $_FILES['uploadedFile']['name'];
    $fileSize      = $_FILES['uploadedFile']['size'];
    $fileType      = $_FILES['uploadedFile']['type'];
    $fileNameCmps  = explode (".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    //Pone un nombre de archivo encriptado
    $newFileName = 'Archivo-'.(md5(time() . $fileName) . '.' . $fileExtension);

    //Checa la extensión si es válida
    $extensionesValidas = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc', 'pdf', 'epub', 'docx', 'ppt', 'xlsx','pptx');

    if (in_array($fileExtension, $extensionesValidas)) {
        //Aqui será movido el archivo
        $uploadFileDir = './uploaded_files/';
        $dest_path = $uploadFileDir . $newFileName;

        //Verifica la subida del archivo
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $message = 'Archivo se ha subido correctamente.';

            //insertamos los datos en la BD.
            try {
                $consulta_insertar = "
                    INSERT INTO archivos 
                        (archivo_nombre, archivo_nombre_real, archivo_peso, archivo_tipo) 
                    VALUES 
                        ('$newFileName','$nombre_real', '$fileSize', '$fileExtension')
                    ";
                $stmt = $dbh->prepare($consulta_insertar);
                $stmt->execute();
            } catch (Exception $exception) {
                echo $exception;
            }

            //Saber si se insertó el archivo
            try {
                $sql = "SELECT id FROM archivos WHERE archivo_nombre = '$newFileName'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $lastId = $stmt->fetch();
            } catch (Exception $exception) {
                echo $exception;
            }

            //Verifica si está en la base de datos y establece variable global de su id
            $lastId[0]=(isset($lastId))?$lastId[0]:1;
        } else {
            $message = 'Se produjo un error al mover el archivo para cargar el directorio. Asegúrese de que el servidor web pueda escribir en el directorio de carga.';
        }
    } else {
        $message = 'Error al subir. Solo se admiten los siguientes formatos: ' . implode(', ', $extensionesValidas);
    }
} else {
    $message = "Hubo un error al intentar subir el archivo.";
}
echo "$message";

