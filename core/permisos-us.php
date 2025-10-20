
<?php
// Inicia la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Carga el JSON de permisos por perfil
$json = file_get_contents(__DIR__ . '/permisos_usuario.json');
$perfiles = json_decode($json, true);

// Obtén el id_perfil desde la sesión
$id_perfil = isset($_SESSION['USR_TYPE']) ? $_SESSION['USR_TYPE'] : null;

// Obtén las opciones permitidas para ese perfil
$opcionesPerfil = ($id_perfil && isset($perfiles[$id_perfil])) ? $perfiles[$id_perfil] : [];

// Convierte a array de IDs permitidos
$idsPermitidos = array_column($opcionesPerfil, 'id_opcion');
// Ahora puedes usar $idsPermitidos en tu sidebar para activar/desactivar los botones
?>