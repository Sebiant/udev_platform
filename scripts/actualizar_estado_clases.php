<?php
require_once __DIR__ . '/../Conexion.php';

// Marca de inicio de ejecución
echo "============================\n";
echo "Ejecución iniciada: " . date('Y-m-d H:i:s') . "\n";
echo "============================\n";

$sql = "UPDATE programador
        SET estado = 'Vista'
        WHERE estado = 'Pendiente'
          AND fecha = CURDATE()";

if ($conn->query($sql) === TRUE) {
    echo "✔ Estado de clases actualizado a 'Vista' para las del día.\n";
} else {
    echo "❌ Error: " . $conn->error . "\n";
}

$conn->close();

// Marca de fin de ejecución
echo "============================\n";
echo "Ejecución finalizada: " . date('Y-m-d H:i:s') . "\n";
echo "============================\n\n";
