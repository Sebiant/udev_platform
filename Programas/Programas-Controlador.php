<?php
include '../Conexion.php';

$accion = isset($_GET['accion']) ? $_GET['accion'] : 'default';

switch ($accion) {
    case 'crear':
        $tipo = $_POST['tipo'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';

        $sql = "INSERT INTO programas (tipo, nombre, descripcion) 
                VALUES ('$tipo', '$nombre', '$descripcion')";
        
        echo ($conn->query($sql) === TRUE) 
            ? "Nuevo registro creado exitosamente."
            : "Error al crear el registro: " . $conn->error;
        break;

    case 'editar':
        if (!isset($_POST['id_programa']) || empty($_POST['id_programa'])) {
            echo json_encode(["success" => false, "message" => "El ID del programa es obligatorio."]);
            break;
        }

        $id_programa = $_POST['id_programa'];
        $tipo = $_POST['tipo'] ?? null;
        $nombre = $_POST['nombre'] ?? null;
        $descripcion = isset($_POST['descripcion']) && $_POST['descripcion'] !== '' ? $_POST['descripcion'] : null;
        $estado = $_POST['estado'] ?? null;

        if (is_null($tipo) && is_null($nombre) && is_null($descripcion) && is_null($estado)) {
            echo json_encode(["success" => false, "message" => "No se han enviado datos para actualizar."]);
            break;
        }

        $sql_select = "SELECT * FROM programas WHERE id_programa = ?";
        $stmt = $conn->prepare($sql_select);
        $stmt->bind_param('i', $id_programa);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $sql_update = "UPDATE programas SET 
                            tipo = IFNULL(?, tipo), 
                            nombre = IFNULL(?, nombre), 
                            descripcion = IFNULL(?, descripcion), 
                            estado = IFNULL(?, estado) 
                            WHERE id_programa = ?";

            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param('sssii', $tipo, $nombre, $descripcion, $estado, $id_programa);
            
            echo ($stmt_update->execute())
                ? json_encode(["success" => true, "message" => "Registro actualizado exitosamente."])
                : json_encode(["success" => false, "message" => "Error al actualizar el registro: " . $stmt_update->error]);
        } else {
            echo json_encode(["success" => false, "message" => "No se encontró el registro con el ID proporcionado."]);
        }
        break;

    case 'cambiarEstado':
        $id_programa = $_POST['id_programa'];
        $estado = $_POST['estado'];
        
        $sql_programa = "UPDATE programas SET estado = $estado WHERE id_programa = '$id_programa'";
        $resultado = $conn->query($sql_programa);
        
        if ($resultado === TRUE) {
            $sql_modulos = "UPDATE modulos SET estado = $estado WHERE id_programa = '$id_programa'";
            $resultado_modulos = $conn->query($sql_modulos);
        
            if ($resultado_modulos === TRUE) {
                echo "Estado cambiado exitosamente a " . ($estado == 1 ? "Activo" : "Inactivo") . " para el programa y sus módulos.";
            } else {
                echo "Programa actualizado, pero error al cambiar estado de módulos: " . $conn->error;
            }
        } else {
            echo "Error al cambiar el estado del programa: " . $conn->error;
        }
        break;        

    case 'BusquedaPorId':
        $id_programa = $_POST['id_programa'];
        $sql = "SELECT id_programa, tipo, nombre, descripcion, estado FROM programas WHERE id_programa='$id_programa'";
        $result = $conn->query($sql);
        
        if ($result === false) {
            die("Error en la consulta SQL: " . $conn->error);
        }
    
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $row['estado'] = ($row['estado'] == 1) ? "Activo" : "Inactivo";
            $data[] = $row;
        }
        echo json_encode(['data' => $data]);
        break;

    default:
        header('Content-Type: application/json');

        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $searchValue = $_POST['search']['value'] ?? '';
        
        $sql = "SELECT id_programa, tipo, nombre, descripcion, estado FROM programas WHERE 1=1";
        
        if (!empty($searchValue)) {
            $sql .= " AND (tipo LIKE '%$searchValue%' 
                        OR nombre LIKE '%$searchValue%'
                        OR descripcion LIKE '%$searchValue%' 
                        OR estado LIKE '%$searchValue%')";
        }
        
        $sql .= " ORDER BY estado DESC LIMIT $start, $length";
        
        $result = $conn->query($sql);
        if (!$result) {
            echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
            exit;
        }
        
        $totalQuery = "SELECT COUNT(*) as total FROM programas";
        $totalResult = $conn->query($totalQuery);
        $totalData = $totalResult ? $totalResult->fetch_assoc()['total'] : 0;
        
        $filteredQuery = "SELECT COUNT(*) as total FROM programas WHERE 1=1";
        if (!empty($searchValue)) {
            $filteredQuery .= " AND (tipo LIKE '%$searchValue%' 
                                OR nombre LIKE '%$searchValue%'
                                OR descripcion LIKE '%$searchValue%' 
                                OR estado LIKE '%$searchValue%')";
        }
        
        $filteredResult = $conn->query($filteredQuery);
        $totalFiltered = $filteredResult ? $filteredResult->fetch_assoc()['total'] : 0;
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                "id_programa" => $row["id_programa"],
                "tipo"        => $row["tipo"],
                "nombre"      => $row["nombre"],
                "descripcion" => $row["descripcion"],
                "estado"      => ($row["estado"] == 1) ? "Activo" : "Inactivo"
            ];
        }
        
        echo json_encode([
            'draw' => $draw,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
        break;
}

$conn->close();
?>
