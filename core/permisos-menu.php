<?php
// Inicia la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cargar el JSON de permisos por perfil
$json = file_get_contents(__DIR__ . '/permisos_usuario.json');
$perfiles = json_decode($json, true);

// Obtener el id_perfil desde la sesión
$id_perfil = isset($_SESSION['USR_TYPE']) ? $_SESSION['USR_TYPE'] : null;

// Obtener las opciones permitidas para ese perfil
$opcionesPerfil = ($id_perfil && isset($perfiles[$id_perfil])) ? $perfiles[$id_perfil] : [];

// Convertir a array de IDs permitidos
$idsPermitidos = array_column($opcionesPerfil, 'id_opcion');

/**
 * Función para verificar si un usuario tiene permiso
 */
function tienePermiso($idOpcion) {
    global $idsPermitidos;
    return in_array($idOpcion, $idsPermitidos);
}

/**
 * Función para validar acceso a una página
 */
function validarAcceso($idOpcion, $redirigir = true) {
    if (!tienePermiso($idOpcion)) {
        if ($redirigir) {
            header('Location: panel?pg=dashboard&error=sin_permisos');
            exit();
        }
        return false;
    }
    return true;
}

/**
 * Función para generar CSS que oculte elementos sin permisos
 */
function generarCSSPermisos() {
    global $idsPermitidos;
    
    $css = "<style>\n";
    $css .= "/* Ocultar elementos del menú sin permisos */\n";
    
    // Si no hay permisos, ocultar todo
    if (empty($idsPermitidos)) {
        $css .= ".nav-item { display: none !important; }\n";
    } else {
        // Ocultar elementos específicos que no tienen permiso
        // Esto se puede expandir según necesidades
        $css .= ".nav-item:not([data-id-opcion]) { display: block; }\n";
        
        // Agregar reglas específicas para elementos sin permiso
        for ($i = 1; $i <= 50; $i++) { // Rango de IDs posibles
            if (!in_array($i, $idsPermitidos)) {
                $css .= ".nav-item[data-id-opcion=\"{$i}\"] { display: none !important; }\n";
            }
        }
    }
    
    $css .= "</style>\n";
    return $css;
}

/**
 * Función para generar JavaScript que maneje permisos dinámicamente
 */
function generarJSPermisos() {
    global $idsPermitidos;
    
    $js = "<script>\n";
    $js .= "// Sistema de permisos dinámico\n";
    $js .= "const idsPermitidos = " . json_encode($idsPermitidos) . ";\n\n";
    
    $js .= "function aplicarPermisos() {\n";
    $js .= "    // Obtener todos los elementos del menú con data-id-opcion\n";
    $js .= "    const elementosMenu = document.querySelectorAll('[data-id-opcion]');\n\n";
    
    $js .= "    elementosMenu.forEach(function(elemento) {\n";
    $js .= "        const idOpcion = parseInt(elemento.getAttribute('data-id-opcion'));\n";
    $js .= "        const padre = parseInt(elemento.getAttribute('data-padre'));\n\n";
    
    $js .= "        // Si el usuario no tiene permiso para esta opción\n";
    $js .= "        if (!idsPermitidos.includes(idOpcion)) {\n";
    $js .= "            elemento.style.display = 'none';\n";
    $js .= "        } else {\n";
    $js .= "            elemento.style.display = 'block';\n";
    $js .= "        }\n";
    $js .= "    });\n\n";
    
    $js .= "    // Ocultar menús padre que no tengan hijos visibles\n";
    $js .= "    ocultarMenusPadreVacios();\n";
    $js .= "}\n\n";
    
    $js .= "function ocultarMenusPadreVacios() {\n";
    $js .= "    const menusPadre = document.querySelectorAll('[data-padre=\"0\"]');\n\n";
    
    $js .= "    menusPadre.forEach(function(menuPadre) {\n";
    $js .= "        const idPadre = menuPadre.getAttribute('data-id-opcion');\n";
    $js .= "        const hijos = document.querySelectorAll('[data-padre=\"' + idPadre + '\"]');\n";
    $js .= "        let tieneHijosVisibles = false;\n\n";
    
    $js .= "        // Verificar si tiene hijos visibles\n";
    $js .= "        hijos.forEach(function(hijo) {\n";
    $js .= "            if (hijo.style.display !== 'none') {\n";
    $js .= "                tieneHijosVisibles = true;\n";
    $js .= "            }\n";
    $js .= "        });\n\n";
    
    $js .= "        // Si el menú padre no tiene permiso propio y no tiene hijos visibles\n";
    $js .= "        if (!idsPermitidos.includes(parseInt(idPadre)) && !tieneHijosVisibles) {\n";
    $js .= "            menuPadre.style.display = 'none';\n";
    $js .= "        }\n";
    $js .= "    });\n";
    $js .= "}\n\n";
    
    $js .= "// Aplicar permisos cuando se carga la página\n";
    $js .= "document.addEventListener('DOMContentLoaded', function() {\n";
    $js .= "    aplicarPermisos();\n";
    $js .= "});\n\n";
    
    $js .= "// Función para validar acceso desde JavaScript\n";
    $js .= "function validarAccesoJS(idOpcion) {\n";
    $js .= "    return idsPermitidos.includes(idOpcion);\n";
    $js .= "}\n";
    
    $js .= "</script>\n";
    return $js;
}

/**
 * Función para generar toda la lógica de permisos (CSS + JS)
 */
function generarSistemaPermisos() {
    return generarCSSPermisos() . generarJSPermisos();
}
?>