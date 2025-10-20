<?php

require_once __DIR__ . '/../core/class/db.php';
require_once __DIR__ . '/../core/class/crud.php';

// Inicializar base de datos y CRUD
$db = new Database();
$conn = $db->getConnection();

// Obtener el id_usuario de la sesión
$id_usuario = $_SESSION['USR_ID'];
$usuarios = [];

// Buscar colaborador enlazado al usuario logueado
$stmt = $conn->prepare("SELECT * FROM sys_colaboradores WHERE id_usuario = ? LIMIT 1");
$stmt->execute([$id_usuario]);
$colaborador = $stmt->fetch(PDO::FETCH_ASSOC);

if ($colaborador) {
    $usuarios[] = $colaborador;
} else {
    // Si no es colaborador, buscar en us_usuarios
    $stmt = $conn->prepare("SELECT * FROM us_usuarios WHERE id_usuario = ? LIMIT 1");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($usuario) {
        // Adaptar los campos para mostrar en el perfil
        $usuarios[] = [
            'id_colab' => '',
            'nombre' => $usuario['nombre'] ?? '',
            'apellidos' => $usuario['apellido'] ?? '',
            'correo' => $usuario['correo'] ?? $usuario['email'] ?? '',
            'email' => $usuario['email'] ?? '',
            'telefono' => $usuario['telefono'] ?? '',
            'departamento' => $usuario['departamento'] ?? '',
            'area' => $usuario['area'] ?? '',
            'foto' => $usuario['foto'] ?? '',
            'id_usuario' => $usuario['id_usuario'],
        ];
    }
}

// Inicio del contenedor principal
echo '<div style="width:100%;max-width:900px;margin:auto;">';

// Scripts JS para abrir/cerrar modal y edición de perfil
echo '<script>
function abrirModalEditar(colab) {
    document.getElementById("edit_id_colab").value = colab.id_colab;
    document.getElementById("edit_nombre").value = colab.nombre || "";
    document.getElementById("edit_apellidos").value = colab.apellidos || "";
    document.getElementById("edit_correo").value = colab.correo || colab.email || "";
    document.getElementById("edit_telefono").value = colab.telefono || "";
    document.getElementById("edit_departamento").value = colab.departamento || "";
    document.getElementById("edit_area").value = colab.area || "";
    document.getElementById("modalEditar").style.display = "flex";
}
function cerrarModalEditar() {
    document.getElementById("modalEditar").style.display = "none";
}
function togglePasswordPerfil() {
    const input = document.getElementById("perfil_contrasena");
    input.type = input.type === "password" ? "text" : "password";
}
function alternarEdicionPerfil() {
    const fields = ["perfil_nombre","perfil_apellidos","perfil_correo","perfil_contrasena","perfil_telefono","perfil_departamento","perfil_area"];
    const btnGuardar = document.getElementById("btnGuardarPerfil");
    const btnVerContrasena = document.getElementById("btnVerContrasena");
    const txtEditar = document.getElementById("txtEditarPerfil");
    let editando = fields.some(id => !document.getElementById(id).disabled);
    fields.forEach(id => document.getElementById(id).disabled = editando);
    btnGuardar.disabled = editando;
    btnGuardar.style.display = editando ? "none" : "inline-block";
    btnVerContrasena.style.display = editando ? "none" : "inline-block";
    txtEditar.textContent = editando ? "Editar" : "Cancelar";
}
</script>';

// Estilos CSS
echo '<style>
.user-profile-card { background: #fff; border-radius: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 2rem 1.5rem 1.5rem 1.5rem; margin-bottom: 2.5rem; text-align: center; position: relative; transition: box-shadow 0.2s; min-width:260px; }
.user-profile-card:hover { box-shadow: 0 4px 24px rgba(0,0,0,0.13); }
.profile-img { width: 110px; height: 110px; object-fit: cover; border-radius: 50%; border: 4px solid #17a2b8; margin-top: -70px; background: #f4f6f9; }
.profile-label { font-weight: 600; color: #6c757d; font-size: 0.95rem; margin-bottom: 0.2rem; }
.profile-value { font-size: 1.15rem; color: #222; margin-bottom: 0.7rem; }
</style>';

// Mostrar perfil si existe usuario
if (is_array($usuarios) && count($usuarios) > 0) {
    $usuario = $usuarios[0];
    $foto = !empty($usuario['foto']) ? $usuario['foto'] : '';
    $valorContrasena = '';

    // Obtener la contraseña desde us_usuarios usando el id_usuario
    if (!empty($usuario['id_usuario'])) {
        $stmt = $conn->prepare("SELECT password FROM us_usuarios WHERE id_usuario = ? LIMIT 1");
        $stmt->execute([$usuario['id_usuario']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && isset($row['password'])) {
            $valorContrasena = $row['password'];
        }
    }

    echo '<div class="user-profile-card mx-auto" style="margin-top: 100px; margin-bottom: 2.5rem; position:relative;">';
    echo '<div style="position:absolute;top:10px;right:10px;z-index:2;display:flex;gap:24px;">';
    echo '<button id="btnEditarPerfil" class="btn btn-warning btn-sm" type="button" onclick="alternarEdicionPerfil()"><i class="fas fa-edit"></i> <span id="txtEditarPerfil">Editar</span></button>';
    echo '</div>';
    if ($foto) {
        echo '<img src="' . htmlspecialchars($foto) . '" alt="Foto de perfil" class="profile-img shadow">';
    } else {
        echo '<div style="width:100%;display:flex;justify-content:center;">';
        echo '<span class="profile-img shadow" style="font-size:60px;color:#17a2b8;background:#f4f6f9;display:flex;align-items:center;justify-content:center;"><i class="fas fa-user"></i></span>';
        echo '</div>';
    }
    echo '<form id="formEditarPerfil" style="margin-top:1.5rem;" method="POST" action="core/editar-perfil.php">';
    echo '<input type="hidden" name="id_colab" value="' . htmlspecialchars($usuario['id_colab']) . '">';
    echo '<div class="mb-2"><label class="profile-label"><span class="bi bi-person" style="margin-right:6px;"></span>Nombre</label><input type="text" class="form-control profile-value" name="nombre" id="perfil_nombre" value="' . htmlspecialchars($usuario['nombre']) . '" disabled></div>';
    echo '<div class="mb-2"><label class="profile-label"><span class="bi bi-person-lines-fill" style="margin-right:6px;"></span>Apellidos</label><input type="text" class="form-control profile-value" name="apellidos" id="perfil_apellidos" value="' . htmlspecialchars($usuario['apellidos'] ?? '') . '" disabled></div>';
    echo '<div class="mb-2"><label class="profile-label"><span class="bi bi-envelope" style="margin-right:6px;"></span>Correo</label><input type="email" class="form-control profile-value" name="correo" id="perfil_correo" value="' . htmlspecialchars($usuario['correo'] ?? $usuario['email'] ?? '') . '" disabled></div>';
    // Campo contraseña debajo de correo
    echo '<div class="mb-2" id="perfil_contrasena_wrap" style="position:relative;">';
    echo '<label class="profile-label"><span class="bi bi-lock" style="margin-right:6px;"></span>Contraseña</label>';
    echo '<div style="display:flex;align-items:center;">';
    echo '<input type="password" class="form-control profile-value" name="contrasena" id="perfil_contrasena" value="' . htmlspecialchars($valorContrasena) . '" disabled style="flex:1;">';
    echo '<button type="button" class="btn btn-outline-secondary ms-2" id="btnVerContrasena" onclick="togglePasswordPerfil()" style="display:none;"><span class="bi bi-eye"></span></button>';
    echo '</div>';
    echo '</div>';
    echo '<div class="mb-2"><label class="profile-label"><span class="bi bi-telephone" style="margin-right:6px;"></span>Teléfono</label><input type="text" class="form-control profile-value" name="telefono" id="perfil_telefono" value="' . htmlspecialchars($usuario['telefono'] ?? '') . '" disabled></div>';
    echo '<div class="mb-2"><label class="profile-label"><span class="bi bi-building" style="margin-right:6px;"></span>Departamento</label><input type="text" class="form-control profile-value" name="departamento" id="perfil_departamento" value="' . htmlspecialchars($usuario['departamento'] ?? '') . '" disabled></div>';
    echo '<div class="mb-2"><label class="profile-label"><span class="bi bi-diagram-3" style="margin-right:6px;"></span>Área</label><input type="text" class="form-control profile-value" name="area" id="perfil_area" value="' . htmlspecialchars($usuario['area'] ?? '') . '" disabled></div>';
    echo '<div style="margin-top:1rem; text-align:right;">';
    echo '<button id="btnGuardarPerfil" class="btn btn-success btn-sm" type="submit" disabled style="display:none;"><i class="fas fa-save"></i> Guardar</button>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
} else {
    // Mensaje si no hay datos
    echo '<div class="alert alert-warning" style="margin-top:40px;">No se encontró información de usuario.</div>';
}
echo '</div>';
?>