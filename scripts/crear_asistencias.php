<?php
require_once __DIR__ . '/../Conexion.php';

// Marca de inicio de ejecución
echo "============================\n";
echo "Ejecución iniciada: " . date('Y-m-d H:i:s') . "\n";
echo "============================\n";

$sql = "INSERT INTO asistencias (fecha, hora_entrada, hora_salida, id_programador, estado)
        SELECT
            p.fecha,
            p.hora_inicio,
            p.hora_salida,
            p.id_programador,
            CASE
                WHEN p.estado = 'Vista' THEN 'Vista'
                WHEN p.estado = 'Perdida' THEN 'Perdida'
            END AS estado
        FROM programador p
        WHERE p.fecha = CURDATE()
          AND p.estado IN ('Vista', 'Perdida')
          AND NOT EXISTS (
            SELECT 1 FROM asistencias a WHERE a.id_programador = p.id_programador
          )";

if ($conn->query($sql) === TRUE) {
    echo "✔ Asistencias creadas para la fecha de hoy.\n";
} else {
    echo "❌ Error: " . $conn->error . "\n";
}

$conn->close();

// Marca de fin de ejecución
echo "============================\n";
echo "Ejecución finalizada: " . date('Y-m-d H:i:s') . "\n";
echo "============================\n\n";
