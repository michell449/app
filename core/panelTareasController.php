
<?php
require_once 'class/db.php';

class PanelTareasController {
    private $conn;
    public $data = [];

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getProyectos() {
        $sql = "SELECT * FROM proy_proyectos ORDER BY id_proyecto ASC";
        $stmt = $this->conn->query($sql);
        if ($stmt && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->data[] = $row;
            }
        }
        return $this->data;
    }

    // Obtiene las tareas relacionadas a un proyecto específico
    public function getTareasPorProyecto($idProyecto) {
        $tareas = [];
        // Primero obtenemos los IDs de tarea relacionados al proyecto
        $sqlRel = "SELECT id_tarea FROM proy_tareasproyectos WHERE id_proyecto = :idProyecto";
        $stmtRel = $this->conn->prepare($sqlRel);
        $stmtRel->execute(['idProyecto' => $idProyecto]);
        $idsTarea = $stmtRel->fetchAll(PDO::FETCH_COLUMN);
        if (count($idsTarea) > 0) {
            // Ahora obtenemos los datos de esas tareas
            $in = str_repeat('?,', count($idsTarea) - 1) . '?';
            $sqlTareas = "SELECT * FROM proy_tareas WHERE id_tarea IN ($in)";
            $stmtTareas = $this->conn->prepare($sqlTareas);
            $stmtTareas->execute($idsTarea);
            $tareas = $stmtTareas->fetchAll(PDO::FETCH_ASSOC);
        }
        return $tareas;
    }

    // Actualiza los datos de una tarea en proy_tareas
    public function updateTarea($id_tarea, $data) {
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[$key] = $value;
        }
        $params['id_tarea'] = $id_tarea;
        $sql = "UPDATE proy_tareas SET " . implode(', ', $fields) . " WHERE id_tarea = :id_tarea";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Actualiza los datos de un proyecto en proy_proyectos
    public function updateProyecto($id_proyecto, $data) {
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[$key] = $value;
        }
        $params['id_proyecto'] = $id_proyecto;
        $sql = "UPDATE proy_proyectos SET " . implode(', ', $fields) . " WHERE id_proyecto = :id_proyecto";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }
}
