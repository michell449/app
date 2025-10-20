<?php
// Muestra los colaboradores asignados a un proyecto y permite quitarlos
require_once __DIR__ . '/class/db.php';
$proyecto_id = isset($proyecto_id) ? $proyecto_id : ($_GET['id'] ?? 1);
$db = (new Database())->getConnection();

// Obtener colaboradores agregados manualmente al proyecto
$sql = "SELECT c.id_colab, c.nombre, c.apellidos, c.correo, 'manual' as tipo
        FROM proy_colabproyectos pc
        JOIN sys_colaboradores c ON pc.id_colab = c.id_colab
        WHERE pc.id_proyecto = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$proyecto_id]);
$colaboradores_manuales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener colaboradores del equipo asignado al proyecto
$sql_equipo = "SELECT c.id_colab, c.nombre, c.apellidos, c.correo, 'equipo' as tipo, ec.rol
               FROM proy_proyectos p
               JOIN proy_equiposcolab ec ON p.id_equipo = ec.id_equipo
               JOIN sys_colaboradores c ON ec.id_colab = c.id_colab
               WHERE p.id_proyecto = ? AND p.id_equipo IS NOT NULL";
$stmt_equipo = $db->prepare($sql_equipo);
$stmt_equipo->execute([$proyecto_id]);
$colaboradores_equipo = $stmt_equipo->fetchAll(PDO::FETCH_ASSOC);

// Combinar ambos tipos de colaboradores, evitando duplicados
$colaboradores = [];
$ids_agregados = [];

// Primero agregar colaboradores manuales
foreach ($colaboradores_manuales as $colab) {
    $colaboradores[] = $colab;
    $ids_agregados[] = $colab['id_colab'];
}

// Luego agregar colaboradores del equipo que no estén ya agregados manualmente
foreach ($colaboradores_equipo as $colab) {
    if (!in_array($colab['id_colab'], $ids_agregados)) {
        $colaboradores[] = $colab;
        $ids_agregados[] = $colab['id_colab'];
    }
}

if (empty($colaboradores)) {
    // Mostrar tarjeta de supervisor aunque no haya colaboradores
    $sqlSupervisor = "SELECT c.id_colab, c.nombre, c.apellidos, c.correo FROM proy_proyectos p JOIN sys_colaboradores c ON p.supervisor = c.id_colab WHERE p.id_proyecto = ?";
    $stmtSupervisor = $db->prepare($sqlSupervisor);
    $stmtSupervisor->execute([$proyecto_id]);
    $rowSupervisor = $stmtSupervisor->fetch(PDO::FETCH_ASSOC);
    if ($rowSupervisor) {
        echo '<div class="row g-3">';
        echo '<div class="col-12 col-md-6 col-lg-4">';
        echo '<div class="card shadow-sm border-warning h-100">';
        echo '<div class="card-body d-flex flex-column align-items-center">';
        echo '<div class="mb-2" style="font-size:32px;color:#ffc107;"><i class="bi bi-person-badge"></i></div>';
        echo '<div class="fw-bold mb-1" style="font-size:18px;">' . htmlspecialchars($rowSupervisor['nombre'] . ' ' . $rowSupervisor['apellidos']) . '</div>';
        echo '<div class="text-muted mb-2" style="font-size:15px;">' . htmlspecialchars($rowSupervisor['correo']) . '</div>';
        echo '<span class="badge bg-warning text-dark mb-2">Supervisor del proyecto</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="d-flex flex-column align-items-center justify-content-center py-5" style="min-height:200px;">' . "\n";
        echo '<div class="mb-3" style="font-size:60px;color:#6c757d;">' . "\n";
        echo '<i class="bi bi-people"></i>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="mb-2 text-secondary" style="font-size:18px;">' . "\n";
        echo 'Invita a tus compañeros de equipo a colaborar en la plataforma' . "\n";
        echo '</div>' . "\n";
        echo '<button class="btn btn-outline-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalAgregarColaborador">' . "\n";
        echo 'Invitar a compañeros de equipo' . "\n";
        echo '</button>' . "\n";
        echo '</div>' . "\n";
    }
} else {
    // Mostrar tarjeta de supervisor primero
    $sqlSupervisor = "SELECT c.id_colab, c.nombre, c.apellidos, c.correo FROM proy_proyectos p JOIN sys_colaboradores c ON p.supervisor = c.id_colab WHERE p.id_proyecto = ?";
    $stmtSupervisor = $db->prepare($sqlSupervisor);
    $stmtSupervisor->execute([$proyecto_id]);
    $rowSupervisor = $stmtSupervisor->fetch(PDO::FETCH_ASSOC);
    echo '<div class="row g-3">' . "\n";
    if ($rowSupervisor) {
        echo '<div class="col-12 col-md-6 col-lg-4">';
        echo '<div class="card shadow-sm border-warning h-100">';
        echo '<div class="card-body d-flex flex-column align-items-center">';
        echo '<div class="mb-2" style="font-size:32px;color:#ffc107;"><i class="bi bi-person-badge"></i></div>';
        echo '<div class="fw-bold mb-1" style="font-size:18px;">' . htmlspecialchars($rowSupervisor['nombre'] . ' ' . $rowSupervisor['apellidos']) . '</div>';
        echo '<div class="text-muted mb-2" style="font-size:15px;">' . htmlspecialchars($rowSupervisor['correo']) . '</div>';
        echo '<span class="badge bg-warning text-dark mb-2">Supervisor del proyecto</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    foreach ($colaboradores as $colab) {
        // No mostrar al supervisor como colaborador normal
        if ($rowSupervisor && $colab['id_colab'] == $rowSupervisor['id_colab']) continue;
        echo '<div class="col-12 col-md-6 col-lg-4">' . "\n";
        echo '<div class="card shadow-sm border-0 h-100">' . "\n";
        echo '<div class="card-body d-flex flex-column align-items-center">' . "\n";
        echo '<div class="mb-2" style="font-size:32px;color:#0d6efd;">' . "\n";
        echo '<i class="bi bi-person-circle"></i>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="fw-bold mb-1" style="font-size:18px;">' . htmlspecialchars($colab['nombre'] . ' ' . $colab['apellidos']) . '</div>' . "\n";
        echo '<div class="text-muted mb-2" style="font-size:15px;">' . htmlspecialchars($colab['correo']) . '</div>' . "\n";
        
        // Mostrar badge según el tipo de colaborador
        if ($colab['tipo'] === 'equipo') {
            echo '<span class="badge bg-info mb-2">Miembro del equipo';
            if (isset($colab['rol']) && !empty($colab['rol'])) {
                echo ' (' . htmlspecialchars($colab['rol']) . ')';
            }
            echo '</span>' . "\n";
        } else {
            echo '<span class="badge bg-success mb-2">Agregado al proyecto</span>' . "\n";
        }
        
        // Solo mostrar botón de quitar para colaboradores agregados manualmente
        if ($colab['tipo'] === 'manual') {
            echo '<form method="post" action="core/quitar-colaborador-proyecto.php" class="mt-auto">' . "\n";
            echo '<input type="hidden" name="id_proyecto" value="' . intval($proyecto_id) . '">' . "\n";
            echo '<input type="hidden" name="id_colab" value="' . intval($colab['id_colab']) . '">' . "\n";
            echo '<button type="submit" class="btn btn-outline-danger btn-sm w-100" title="Quitar">' . "\n";
            echo '<i class="bi bi-dash"></i> Quitar' . "\n";
            echo '</button>' . "\n";
            echo '</form>' . "\n";
        } else {
            echo '<div class="text-muted mt-auto" style="font-size:12px;">Miembro del equipo asignado</div>' . "\n";
        }
        
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
    }
    echo '</div>' . "\n";
}
