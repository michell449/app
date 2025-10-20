
<?php
    header('Content-Type: application/json');
    require_once __DIR__ . '/class/db.php';
    require_once __DIR__ . '/class/crud.php';

    function jsonError($msg, $extra = []) {
        echo json_encode(array_merge(['success' => false, 'message' => $msg], $extra));
        exit;
    }

    // Función para generar nombre de carpeta descriptivo
    function generarNombreCarpeta($cliente_id, $nombre_cliente) {
        // Limpiar el nombre del cliente para el sistema de archivos
        $nombre_limpio = preg_replace('/[^\w\s-]/', '', $nombre_cliente);
        $nombre_limpio = preg_replace('/\s+/', '_', trim($nombre_limpio));
        $nombre_limpio = substr($nombre_limpio, 0, 50); // Limitar longitud
        
        return $cliente_id . '_' . $nombre_limpio;
    }

    /**
     * Genera una contraseña segura aleatoria
     */
    function generarPassword($longitud = 8) {
        $caracteres = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
        $password = '';
        for ($i = 0; $i < $longitud; $i++) {
            $password .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $password;
    }

    /**
     * Crea un usuario para el cliente
     */
    function crearUsuarioCliente($pdo, $cliente_id, $datos_cliente) {
        try {
            // Generar email único
            $email = $datos_cliente['correo'];
            if (empty($email)) {
                // Crear email basado en nombre comercial
                $nombre_base = strtolower($datos_cliente['nombre_comercial']);
                $nombre_base = preg_replace('/[^a-z0-9]/', '', $nombre_base); // Solo letras y números
                $email = $nombre_base . '@cliente.com';
            }
            
            // Verificar que el email no exista y generar uno único
            $email_original = $email;
            $contador = 1;
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM us_usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            while ($stmt->fetchColumn() > 0) {
                $email = str_replace('@', $contador . '@', $email_original);
                $stmt->execute([$email]);
                $contador++;
            }
            
            // Usar RFC como contraseña (es algo que el cliente ya conoce)
            $password = $datos_cliente['rfc'];
            
            // Usar nombre comercial o razón social como nombre
            $nombre = !empty($datos_cliente['nombre_comercial']) ? $datos_cliente['nombre_comercial'] : $datos_cliente['razon_social'];
            
            // Crear el usuario
            $sql_usuario = "INSERT INTO us_usuarios (nombre, apellido, email, password, telefono, creacion, status, id_perfil) 
                            VALUES (?, ?, ?, ?, ?, NOW(), 1, 4)";
            $stmt = $pdo->prepare($sql_usuario);
            $stmt->execute([
                $nombre,
                'Cliente',
                $email,
                $password,
                $datos_cliente['telefono'] ?: '',
            ]);
            
            $id_usuario = $pdo->lastInsertId();
            
            // Crear la relación en us_usuarios_clientes
            $sql_relacion = "INSERT INTO us_usuarios_clientes (id_usuario, id_cliente, activo, notas) 
                            VALUES (?, ?, 1, 'Usuario creado automáticamente al registrar cliente')";
            $stmt = $pdo->prepare($sql_relacion);
            $stmt->execute([$id_usuario, $cliente_id]);
            
            return [
                'id_usuario' => $id_usuario,
                'email' => $email,
                'password' => $password
            ];
            
        } catch (Exception $e) {
            throw new Exception("Error al crear usuario: " . $e->getMessage());
        }
    }

    /**
     * Crea la estructura completa de carpetas para un cliente
     */
    function crearEstructuraCarpetasCliente($cliente_id, $nombre_cliente) {
        // Generar nombre de carpeta descriptivo
        $nombreCarpetaCliente = generarNombreCarpeta($cliente_id, $nombre_cliente);
        $base_path = '../uploads/clientes/' . $nombreCarpetaCliente;
        
        // Carpetas principales según el menú de la interfaz
        $carpetas_principales = [
            'documentos-fiscales',    // Documentos Fiscales
            'documentos-legales',     // Documentos Legales  
            'documentos-bancarios',   // Documentos Bancarios
            'identidad-corporativa'   // Identidad Corporativa
        ];
        
        // Carpetas administrativas (solo para control-clientes)
        $carpetas_admin = [
            'control-admin/contactos',
            'control-admin/otros-documentos'
        ];
        
        // Crear todas las carpetas
        $todas_carpetas = array_merge($carpetas_principales, $carpetas_admin);
        
        foreach ($todas_carpetas as $carpeta) {
            $ruta_completa = $base_path . '/' . $carpeta;
            
            // Crear la carpeta si no existe
            if (!file_exists($ruta_completa)) {
                if (!mkdir($ruta_completa, 0755, true)) {
                    throw new Exception("No se pudo crear la carpeta: " . $ruta_completa);
                }
            }
            
            // Crear archivo index.html para proteger el directorio
            $index_file = $ruta_completa . '/index.html';
            if (!file_exists($index_file)) {
                file_put_contents($index_file, '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this resource.</p></body></html>');
            }
        }
        
        return true;
    }

    try {
        $db = new Database();
        $pdo = $db->getConnection();
        
        // Iniciar transacción para asegurar consistencia
        $pdo->beginTransaction();
        
        $crud = new crud($pdo);
        $crud->db_table = 'sys_clientes';

        $data = [
            'razon_social'    => $_POST['razon_social'] ?? '',
            'nombre_comercial' => $_POST['nombre_comercial'] ?? '',
            'regimen_fiscal'  => $_POST['regimen_fiscal'] ?? '',
            'telefono'        => $_POST['telefono'] ?? '',
            'rfc'             => $_POST['rfc'] ?? '',
            'contacto'        => $_POST['contacto'] ?? '',
            'correo'          => $_POST['correo'] ?? '',
            'calle'           => $_POST['calle'] ?? '',
            'n_exterior'      => $_POST['no_exterior'] ?? '',
            'n_interior'      => $_POST['no_interior'] ?? '',
            'entre_calle'     => $_POST['entre_calle'] ?? '',
            'y_calle'         => $_POST['y_calle'] ?? '',
            'pais'            => $_POST['pais'] ?? '',
            'cp'              => $_POST['cp'] ?? '',
            'estado'          => $_POST['estado'] ?? '',
            'municipio'       => $_POST['municipio'] ?? '',
            'poblacion'       => $_POST['poblacion'] ?? '',
            'colonia'         => $_POST['colonia'] ?? '',
            'referencia'      => $_POST['referencia'] ?? '',
            //'descuento'       => $_POST['descuento'] ?? '',
            //'limite_credito'  => $_POST['limite_credito'] ?? '',
            //'dias_credito'    => $_POST['dias_credito'] ?? '',
            'activo'          => 1,
        //    'socio'           => $_POST['socio'] ?? '',
        // 'comision_a'      => $_POST['comision_a'] ?? '',
        // 'comision_b'      => $_POST['comision_b'] ?? '',
            'admin_cfdis'     => isset($_POST['admin_cfdis']) ? 1 : 0,
            

        ];

        // Validaciones básicas
        if ($data['razon_social'] == '') {
            jsonError('Razón social requerida');
        }
        if ($data['rfc'] == '') {
            jsonError('RFC requerido');
        }
        if ($data['regimen_fiscal'] == '') {
            jsonError('Régimen fiscal requerido');
        }
        if ($data['pais'] == '') {
            jsonError('País requerido');
        }
        if ($data['cp'] == '') {
            jsonError('Código postal requerido');
        }
        if ($data['nombre_comercial'] == '') {
            $data['nombre_comercial'] = $data['razon_social']; // Usar razón social como fallback
        }

        // Verificar que el RFC no exista
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM sys_clientes WHERE rfc = ? AND activo = 1");
        $stmt->execute([$data['rfc']]);
        if ($stmt->fetchColumn() > 0) {
            jsonError('Ya existe un cliente con este RFC');
        }

        $crud->data = $data;
        if ($crud->create()) {
            $cliente_id = $pdo->lastInsertId();
            
            // Verificar si se debe crear usuario
            $generar_usuario = isset($_POST['generar_usuario']) && $_POST['generar_usuario'] === 'on';
            $usuario_data = null;
            
            if ($generar_usuario) {
                try {
                    $usuario_data = crearUsuarioCliente($pdo, $cliente_id, $data);
                } catch (Exception $e) {
                    // Si falla la creación del usuario, hacer rollback del cliente
                    $pdo->rollBack();
                    jsonError('Cliente creado pero error al crear usuario: ' . $e->getMessage());
                }
            }
            
            // Confirmar transacción
            $pdo->commit();
            
            // Ya no se crea la estructura de carpetas para el cliente automáticamente
            
            $response = [
                'success' => true, 
                'message' => $generar_usuario ? 'Cliente y usuario creados correctamente' : 'Cliente creado correctamente',
                'cliente_id' => $cliente_id,
                'usuario_creado' => $generar_usuario
            ];
            
            if ($generar_usuario && $usuario_data) {
                $response['credenciales'] = [
                    'email' => $usuario_data['email'],
                    'password' => $usuario_data['password'],
                    'mensaje' => 'Usuario creado automáticamente. Email generado y contraseña = RFC del cliente.'
                ];
            }
            
            echo json_encode($response);
        } else {
            $pdo->rollBack();
            jsonError('Error al agregar cliente');
        }
        
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        jsonError('Error inesperado: ' . $e->getMessage());
    }
