<?php
    session_start();
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/class/db.php';
    require_once __DIR__ . '/class/class_users.php';

    // ----------------------------------------------
    // Procesamiento de validación de login, condiciones de index.php y panel.php
    // ----------------------------------------------

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'], $_POST['password'])) {
        $correo = trim($_POST['email']); 
        $contrasena = trim($_POST['password']); // No hashear aquí, se hace en la clase
        // Validar que el correo y la contraseña no estén vacíos y que el correo tenga un formato válido

        $db = new Database();
        $conn = $db->getConnection();
        $users = new users($conn); //llamar a la clase users
        $users->data = ['email' => $correo, 'password' => $contrasena];
        if ($users->loginuser()) {
            $userData = $users->data;
            if (isset($userData['status']) && $userData['status'] != 1) {
                $_SESSION['USR_ID'] = '';
                $_SESSION['USR_NAME'] = '';
                $_SESSION['USR_TYPE'] = '';
                $_SESSION['USR_MAIL'] = '';
                $_SESSION['ERROR_MSG'] = 'El usuario está inactivo.';
            } else {
                $_SESSION['USR_ID'] = $userData['id_usuario'];
                $_SESSION['USR_NAME'] = $userData['nombre'];
                $_SESSION['USR_TYPE'] = $userData['id_perfil'];
                $_SESSION['USR_MAIL'] = $userData['email'];
                $_SESSION['ERROR_MSG'] = '';
                $_SESSION['DEFAULT_MSG'] = '';
                // Buscar el id_colab correspondiente al usuario logueado
                $stmtColab = $conn->prepare("SELECT id_colab FROM sys_colaboradores WHERE id_usuario = ?");
                $stmtColab->execute([$userData['id_usuario']]);
                $idColab = $stmtColab->fetchColumn();
                $_SESSION['ID_COLAB'] = $idColab ? $idColab : null;
                $_SESSION['id_colab'] = $idColab ? $idColab : null; // Para compatibilidad con dropdown-notification.php para las notificaciones
            }
        } else {
            $_SESSION['USR_ID']    = '';
            $_SESSION['USR_NAME']  = '';
            $_SESSION['USR_TYPE']  = '';
            $_SESSION['USR_MAIL']  = '';
            $_SESSION['ERROR_MSG'] = $users->error ?: "Las credenciales no son válidas";
        }
    }
    header('Location: ' . HOMEURL);
?>