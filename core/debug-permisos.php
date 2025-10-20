<?php
/**
 * Debug: Verificar sistema de permisos
 */

session_start();

// Incluir el sistema de permisos
include_once 'permisos-menu.php';

header('Content-Type: application/json');

// Obtener información de debug
$debug_info = [
    'session_data' => [
        'USR_ID' => $_SESSION['USR_ID'] ?? 'NO_SET',
        'USR_TYPE' => $_SESSION['USR_TYPE'] ?? 'NO_SET',
        'USR_NAME' => $_SESSION['USR_NAME'] ?? 'NO_SET'
    ],
    'permisos_data' => [
        'id_perfil' => $id_perfil ?? 'NO_SET',
        'opciones_perfil' => $opcionesPerfil ?? [],
        'ids_permitidos' => $idsPermitidos ?? [],
        'json_existe' => file_exists(__DIR__ . '/permisos_usuario.json'),
        'json_contenido_valido' => !empty($perfiles)
    ],
    'funciones_disponibles' => [
        'tienePermiso' => function_exists('tienePermiso'),
        'validarAcceso' => function_exists('validarAcceso'),
        'generarSistemaPermisos' => function_exists('generarSistemaPermisos')
    ],
    'tests' => []
];

// Test de permisos específicos si hay sesión
if (isset($_SESSION['USR_TYPE']) && !empty($idsPermitidos)) {
    $debug_info['tests'] = [
        'permiso_1_dashboard' => tienePermiso(1),
        'permiso_5_usuarios' => tienePermiso(5),
        'permiso_23_config_usuarios' => tienePermiso(23),
        'total_permisos' => count($idsPermitidos)
    ];
}

echo json_encode($debug_info, JSON_PRETTY_PRINT);
?>