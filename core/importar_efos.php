<?php
    require_once __DIR__ . '/class/db.php';

    $uploadDir = __DIR__ . '/../uploads/efos/';
    // Buscar el archivo más reciente
    $files = glob($uploadDir . 'Listado_Completo_69-B_*.csv');
    $latestFile = end($files);

    if (!$latestFile || !file_exists($latestFile)) {
        die('No se encontró el archivo EFOS más reciente.');
    }

    // Conexión a la base de datos
    $db = new Database();
    $conn = $db->getConnection();

    // Crear tabla si no existe
    $sqlCreate = "CREATE TABLE IF NOT EXISTS efos_rfc (
        id INT AUTO_INCREMENT PRIMARY KEY,
        rfc VARCHAR(20) NOT NULL,
        nombre VARCHAR(255),
        fecha_inclusion DATE,
        UNIQUE(rfc)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $conn->exec($sqlCreate);

    // Importar CSV
    $handle = fopen($latestFile, 'r');
    if ($handle === false) {
        die('No se pudo abrir el archivo EFOS.');
    }

    $firstRow = true;
    $sqlInsert = "INSERT IGNORE INTO efos_rfc (rfc, nombre, fecha_inclusion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sqlInsert);
    while (($data = fgetcsv($handle)) !== false) {
        if ($firstRow) {
            $firstRow = false;
            continue; // Saltar encabezado
        }
    $rfc = isset($data[1]) ? trim($data[1]) : '';
    $nombre = isset($data[2]) ? trim($data[2]) : '';
    $fecha = isset($data[5]) ? trim($data[5]) : null; // Columna F (índice 5)
        // Convertir fecha de DD/MM/YYYY a YYYY-MM-DD
        if ($fecha && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
            $partes = explode('/', $fecha);
            $fecha = $partes[2] . '-' . $partes[1] . '-' . $partes[0];
        } else {
            $fecha = null;
        }
        if ($rfc) {
            $stmt->execute([$rfc, $nombre, $fecha]);
        }
    }
    fclose($handle);

    echo "Importación de RFC EFOS completada.";
