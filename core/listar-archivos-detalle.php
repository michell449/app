
<?php
header('Content-Type: application/json');
try {
    require_once __DIR__ . '/class/db.php';
    $db = (new Database())->getConnection();
    $carpeta = $_GET['carpeta'] ?? '';
    if ($carpeta === '') {
        echo json_encode(['success' => false, 'msg' => 'Carpeta inválida', 'archivos' => []]);
        exit;
    }
    // Buscar el id_categoria por nombre de carpeta (insensible a acentos y mayúsculas)
    $stmtCat = $db->prepare('SELECT id_categoria FROM arch_categorias WHERE nombre COLLATE utf8mb4_unicode_ci = ? LIMIT 1');
    $stmtCat->execute([$carpeta]);
    $catRow = $stmtCat->fetch(PDO::FETCH_ASSOC);
    if (!$catRow) {
        echo json_encode(['success' => false, 'msg' => 'Categoría no encontrada', 'archivos' => []]);
        exit;
    }
    $id_categoria = $catRow['id_categoria'];
    $sql = "SELECT a.*, i.nombre AS institucion, p.nombre AS proyecto, a.id_categoria AS categoria
            FROM arch_archivos a
            LEFT JOIN sys_instituciones i ON a.id_institucion = i.id_institucion
            LEFT JOIN proy_proyectos p ON a.id_proyecto = p.id_proyecto
            WHERE a.id_categoria = :id_categoria
            ORDER BY a.id_archivo DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id_categoria' => $id_categoria]);
    $archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'archivos' => $archivos]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'msg' => 'Error: ' . $e->getMessage(), 'archivos' => []]);
}
