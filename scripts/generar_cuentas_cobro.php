<?php
require_once __DIR__ . '/../Conexion.php'; 

// Marca de inicio de ejecución
echo "============================\n";
echo "Ejecución iniciada: " . date('Y-m-d H:i:s') . "\n";
echo "============================\n";

$sql = "INSERT INTO cuentas_cobro (fecha, valor_hora, horas_trabajadas, numero_documento)
        SELECT
            CURRENT_DATE AS fecha,
            25000 AS valor_hora,
            SUM(TIMESTAMPDIFF(HOUR, p.hora_inicio, p.hora_salida)) AS horas_trabajadas,
            p.numero_documento
        FROM programador p
        WHERE p.estado = 'Vista'
          AND MONTH(p.fecha) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
          AND YEAR(p.fecha) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
          AND NOT EXISTS (
            SELECT 1
            FROM cuentas_cobro c
            WHERE c.numero_documento = p.numero_documento
              AND MONTH(c.fecha) = MONTH(CURRENT_DATE)
              AND YEAR(c.fecha) = YEAR(CURRENT_DATE)
          )
        GROUP BY p.numero_documento";

if ($conn->query($sql) === TRUE) {
    echo "✔ Cuentas de cobro generadas.\n";
} else {
    echo "❌ Error: " . $conn->error . "\n";
}

$conn->close();

// Marca de fin de ejecución
echo "============================\n";
echo "Ejecución finalizada: " . date('Y-m-d H:i:s') . "\n";
echo "============================\n\n";
