
<?php
// Script para instalar Composer y dependencias automáticamente desde el navegador
// Accede a http://tu-servidor/core/instalar_composer.php

echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Instalación Composer</title></head><body style="font-family:Arial;">';
echo '<h2>Instalador automático de Composer y dependencias</h2>';

$composerInstaller = 'composer-setup.php';
$composerPhar = 'composer.phar';

function printMsg($msg) {
	echo '<div style="margin-bottom:8px;">' . htmlspecialchars($msg) . '</div>';
}

// 1. Descargar el instalador de Composer
printMsg('Descargando instalador de Composer...');
if (!copy('https://getcomposer.org/installer', $composerInstaller)) {
	printMsg('Error: No se pudo descargar el instalador.');
	exit;
}

// 2. Ejecutar el instalador
printMsg('Ejecutando instalador de Composer...');
$output = [];
$result = 0;
@exec("php $composerInstaller 2>&1", $output, $result);
foreach ($output as $line) {
	printMsg($line);
}
if ($result !== 0 || !file_exists($composerPhar)) {
	printMsg('Error: No se pudo instalar Composer.');
	printMsg('Código de resultado: ' . $result);
	printMsg('Salida completa:');
	printMsg('<pre>' . htmlspecialchars(implode("\n", $output)) . '</pre>');
	@unlink($composerInstaller);
	exit;
}

// 3. Eliminar el instalador
printMsg('Eliminando instalador...');
@unlink($composerInstaller);

// 4. Instalar dependencias
if (file_exists(dirname(__DIR__) . '/composer.lock')) {
	printMsg('composer.lock encontrado: se instalarán las versiones exactas de las dependencias.');
} else {
	printMsg('composer.lock no encontrado: se generará uno nuevo con las versiones actuales permitidas por composer.json.');
}
printMsg('Instalando dependencias de composer.json...');
$output = [];
$result = 0;
@exec("php $composerPhar install", $output, $result);
foreach ($output as $line) {
	printMsg($line);
}
if ($result !== 0) {
	printMsg('Error: No se pudieron instalar las dependencias.');
	exit;
}

if (file_exists(dirname(__DIR__) . '/composer.lock')) {
	printMsg('composer.lock está presente después de la instalación.');
} else {
	printMsg('Advertencia: composer.lock no se generó.');
}

printMsg('<strong>Proceso terminado. Composer y dependencias instaladas correctamente.</strong>');
echo '</body></html>';
?> 