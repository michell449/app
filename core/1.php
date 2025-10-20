<?php
require_once __DIR__ . '/../core/class/db.php';
$db = (new Database())->getConnection();

$tablas = [
    // min_acuerdos
    "CREATE TABLE IF NOT EXISTS min_acuerdos (
      id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      id_minuta int(11) NOT NULL,
      id_tema int(11) DEFAULT NULL,
      descripcion text NOT NULL,
      idresponsable int(11) DEFAULT NULL,
      fecha_limite date DEFAULT NULL,
      estado enum('Pendiente','En proceso','Concluido','Vencido','Cancelado') DEFAULT 'Pendiente',
      fecha_creacion timestamp NOT NULL DEFAULT current_timestamp(),
      fecha_cumplimiento date DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // min_minutas
    "CREATE TABLE IF NOT EXISTS min_minutas (
      id_minuta int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      titulo varchar(200) NOT NULL,
      objetivo text DEFAULT NULL,
      idcliente int(11) DEFAULT NULL,
      idresponsable int(11) DEFAULT NULL,
      lugar varchar(150) DEFAULT NULL,
      fecha date NOT NULL,
      hora_inicio time DEFAULT NULL,
      fecha_creacion timestamp NOT NULL DEFAULT current_timestamp(),
      fecha_actualizacion timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // min_participantes
    "CREATE TABLE IF NOT EXISTS min_participantes (
      id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      idminuta int(11) NOT NULL,
      idcontacto int(11) DEFAULT NULL,
      idcolab int(11) DEFAULT NULL,
      nombre_completo varchar(150) NOT NULL,
      rol varchar(150) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // min_temas
    "CREATE TABLE IF NOT EXISTS min_temas (
      id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      id_minuta int(11) NOT NULL,
      titulo varchar(200) NOT NULL,
      descripcion text NOT NULL,
      observaciones text DEFAULT NULL,
      orden int(11) DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // exp_expedientes
    "CREATE TABLE IF NOT EXISTS exp_expedientes (
        id_expediente int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        numero_expediente int(11) NOT NULL,
        expediente_unico varchar(100) NOT NULL,
        materia varchar(20) NOT NULL,
        parte varchar(50) NOT NULL,
        tipo_organo varchar(20) NOT NULL,
        organo_jur varchar(20) NOT NULL,
        tipo_asunto varchar(20) NOT NULL,
        asunto varchar(100) NOT NULL,
        lugar varchar(20) NOT NULL,
        fecha_creacion datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // exp_acuerdos
    "CREATE TABLE IF NOT EXISTS exp_acuerdos (
      id_acuerdo int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      id_expediente int(11) NOT NULL,
      tipo int(11) NOT NULL,
      nombre_quejoso varchar(50) NOT NULL,
      autoridad varchar(50) NOT NULL,
      fecha_acuerdo datetime NOT NULL,
      sintesis text DEFAULT NULL,
      documento enum('ver','descargar') NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // exp_documentos
    "CREATE TABLE IF NOT EXISTS exp_documentos (
      id_doc int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      id_expediente int(11) NOT NULL,
      fecha datetime NOT NULL,
      tipo_archivo varchar(50) NOT NULL,
      fecha_presentacion datetime DEFAULT NULL,
      fecha_acuerdo datetime DEFAULT NULL,
      fecha_publicacion datetime DEFAULT NULL,
      documento enum('ver','descargar') NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // exp_list_estados
    "CREATE TABLE IF NOT EXISTS exp_list_estados (
      clave varchar(15) NOT NULL PRIMARY KEY,
      nombre varchar(150) NOT NULL,
      descripcion varchar(255) DEFAULT NULL,
      activo tinyint(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // exp_list_materia
    "CREATE TABLE IF NOT EXISTS exp_list_materia (
      clave varchar(15) NOT NULL PRIMARY KEY,
      nombre varchar(150) NOT NULL,
      descripcion varchar(255) DEFAULT NULL,
      activo tinyint(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // exp_list_org_juris
    "CREATE TABLE IF NOT EXISTS exp_list_org_juris (
      clave varchar(15) NOT NULL PRIMARY KEY,
      nombre varchar(150) NOT NULL,
      descripcion varchar(255) DEFAULT NULL,
      activo tinyint(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // exp_notificaciones
    "CREATE TABLE IF NOT EXISTS exp_notificaciones (
      id_noti int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      id_expediente int(11) NOT NULL,
      asunto varchar(100) NOT NULL,
      documento enum('ver','descargar') NOT NULL,
      fecha_determinacion datetime NOT NULL,
      actuario text DEFAULT NULL,
      sintesis text DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // exp_promo_rec
    "CREATE TABLE IF NOT EXISTS exp_promo_rec (
      id_prorec int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      id_expediente int(11) NOT NULL,
      texto_promocion varchar(200) NOT NULL,
      organo_jurisdiccional varchar(20) NOT NULL,
      tipo_asunto varchar(15) NOT NULL,
      observaciones text DEFAULT NULL,
      documento enum('ver','descargar') NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // exp_tipos_asunto
    "CREATE TABLE IF NOT EXISTS exp_tipos_asunto (
      clave varchar(15) NOT NULL PRIMARY KEY,
      nombre varchar(150) NOT NULL,
      descripcion varchar(255) DEFAULT NULL,
      activo tinyint(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // exp_tipos_org_juris
    "CREATE TABLE IF NOT EXISTS exp_tipos_org_juris (
      clave varchar(15) NOT NULL PRIMARY KEY,
      nombre varchar(150) NOT NULL,
      descripcion varchar(255) DEFAULT NULL,
      activo tinyint(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // exp_tipos_proce
    "CREATE TABLE IF NOT EXISTS exp_tipos_proce (
      clave varchar(15) NOT NULL PRIMARY KEY,
      nombre varchar(150) NOT NULL,
      descripcion varchar(255) DEFAULT NULL,
      activo tinyint(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // sys_clientes
    "CREATE TABLE IF NOT EXISTS sys_clientes (
      id_cliente int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      razon_social varchar(200) NOT NULL,
      nombre_comercial varchar(200) DEFAULT NULL,
      regimen_fiscal varchar(100) DEFAULT NULL,
      telefono varchar(20) DEFAULT NULL,
      rfc varchar(13) DEFAULT NULL,
      contacto varchar(150) DEFAULT NULL,
      correo varchar(150) DEFAULT NULL,
      calle varchar(150) DEFAULT NULL,
      n_exterior varchar(20) DEFAULT NULL,
      n_interior varchar(20) DEFAULT NULL,
      entre_calle varchar(150) DEFAULT NULL,
      y_calle varchar(150) DEFAULT NULL,
      pais varchar(100) DEFAULT 'México',
      cp varchar(10) DEFAULT NULL,
      estado varchar(100) DEFAULT NULL,
      municipio varchar(100) DEFAULT NULL,
      poblacion varchar(100) DEFAULT NULL,
      colonia varchar(100) DEFAULT NULL,
      referencia text DEFAULT NULL,
      descuento decimal(5,2) DEFAULT 0.00,
      limite_credito decimal(12,2) DEFAULT 0.00,
      dias_credito int(11) DEFAULT 0,
      fecha_registro datetime DEFAULT current_timestamp(),
      activo tinyint(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
    // sys_contactos
    "CREATE TABLE IF NOT EXISTS sys_contactos (
      id_contacto int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      cliente_empresa int(11) NOT NULL,
      nombre varchar(150) NOT NULL,
      telefono varchar(20) DEFAULT NULL,
      whatsapp varchar(20) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;"

];

foreach ($tablas as $sql) {
    try {
        $db->exec($sql);
        echo "Tabla creada o ya existe: " . strtok($sql, "(") . "<br>\\n";
    } catch (PDOException $e) {
        echo "Error creando tabla: " . $e->getMessage() . "<br>\\n";
    }
}
echo "Proceso terminado.";
?>