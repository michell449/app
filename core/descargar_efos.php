<?php
    $efosUrl = 'http://omawww.sat.gob.mx/cifras_sat/Documents/Listado_Completo_69-B.csv';
    $uploadDir = __DIR__ . '/../uploads/efos/';
    $filename = 'Listado_Completo_69-B_' . date('Y-m-d') . '.csv';
    $savePath = $uploadDir . $filename;

    // Crear carpeta si no existe
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Descargar archivo
    $fileContent = @file_get_contents($efosUrl);
    if ($fileContent === false || strlen($fileContent) < 1000) {
        // Si falla la descarga o el archivo es sospechosamente pequeño, alerta
        $errorMsg = "Error: No se pudo descargar el archivo EFOS o el enlace ha cambiado. Verifica el link oficial del SAT.";
        // Puedes guardar un log, enviar correo o mostrar el mensaje
        echo $errorMsg;
        // Opcional: guardar log
        file_put_contents(__DIR__ . '/../logs/efos_error.log', date('Y-m-d H:i:s') . " - $errorMsg\n", FILE_APPEND);
        exit;
    }

    // Guardar archivo
    file_put_contents($savePath, $fileContent);
    echo "Archivo EFOS guardado en: $savePath";
