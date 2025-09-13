<?php
require_once __DIR__ . '/../Conexion.php';

// Marca de inicio de ejecución
echo "============================\n";
echo "Ejecución iniciada: " . date('Y-m-d H:i:s') . "\n";
echo "============================\n";

// Actualizar los periodos que ya terminaron
$sql = "UPDATE periodos
        SET estado = 0
        WHERE fecha_fin < CURDATE()";

if ($conn->query($sql) === TRUE) {
    $filas = $conn->affected_rows;
    echo "✔ $filas periodo(s) desactivado(s) correctamente.\n";
} else {
    echo "❌ Error al desactivar periodos: " . $conn->error . "\n";
}

$conn->close();

// Marca de fin de ejecución
echo "============================\n";
echo "Ejecución finalizada: " . date('Y-m-d H:i:s') . "\n";
echo "============================\n\n";
