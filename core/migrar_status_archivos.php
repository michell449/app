<?php
require_once(dirname(__DIR__) . '/config.php');
$db_name = defined('DB_NAME') ? DB_NAME : '';
$db_user = defined('DB_USER') ? DB_USER : '';
$db_pass = defined('DB_PASSWORD') ? DB_PASSWORD : '';
$db_host = defined('DB_HOST') ? DB_HOST : 'localhost';
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $sql = "ALTER TABLE arch_archivos ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'activo'";
    $pdo->exec($sql);
    echo "Campo 'status' agregado correctamente.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}