<?php
// core/list-comisiones-clientes.php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$database = new Database();
$db = $database->getConnection();
$crud = new crud($db);

try {
    $sql = "SELECT cc.id_comision, cl.nombre_comercial AS cliente, c.nombre AS comisionista, cc.porcentaje
            FROM com_cliente cc
            LEFT JOIN sys_clientes cl ON cc.id_cliente = cl.id_cliente
            LEFT JOIN comisionistas c ON cc.id_comision = c.id_comision
            ORDER BY cl.nombre_comercial, c.nombre";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($result && count($result) > 0) {
        foreach ($result as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['cliente']) . '</td>';
            echo '<td>' . htmlspecialchars($row['comisionista']) . '</td>';
            echo '<td>' . htmlspecialchars($row['porcentaje']) . '%</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="3" class="text-center text-muted">No hay comisiones registradas.</td></tr>';
    }
} catch (Exception $e) {
    echo '<tr><td colspan="3" class="text-center text-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
}
?>
