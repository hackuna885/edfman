<?php
// Conectar a la base de datos SQLite
$db = new SQLite3('../data/nom035.db');

// Carpeta donde se guardarán los archivos CSV
$carpeta = '../datacsv/';

// Verifica si la carpeta existe, si no, la crea
if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

// Función para exportar una tabla a CSV
function exportarTablaACSV($db, $tabla, $carpeta) {
    $csvFile = $carpeta . $tabla . '.csv';
    $handle = fopen($csvFile, 'w');

    if ($handle === false) {
        die("No se pudo crear el archivo CSV para la tabla $tabla");
    }

    $queryDatos = "SELECT * FROM $tabla";
    $results = $db->query($queryDatos);

    // Obtener y escribir los encabezados de la tabla
    $firstRow = $results->fetchArray(SQLITE3_ASSOC);
    if ($firstRow) {
        // Escribir los encabezados
        fputcsv($handle, array_keys($firstRow));
        // Escribir la primera fila de datos
        fputcsv($handle, $firstRow);
    }

    // Recorrer y escribir el resto de las filas
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        fputcsv($handle, $row);
    }

    // Cerrar el archivo CSV
    fclose($handle);

    echo "Exportación de la tabla $tabla completada en $csvFile.<br>";
}

// 1. Exportar la tabla dataEmpleados
exportarTablaACSV($db, 'dataEmpleados', $carpeta);

// 2. Incluir el archivo activaCuestionarios.php para verificar qué guía está activa
include 'activarCuestionarios.php';

// 3. Determinar qué tabla exportar (nom035R2a o nom035R3a) dependiendo de las guías activas

if ($cuestionarioGuia1Activo) {
    // Si la Guía I está activa, exportar la tabla nom035R2a
    exportarTablaACSV($db, 'nom035R1a', $carpeta);
} else {
    echo "No está la guía activa para exportar (Guía I).";
}

if ($cuestionarioGuia2Activo) {
    // Si la Guía II está activa, exportar la tabla nom035R2a
    exportarTablaACSV($db, 'nom035R2a', $carpeta);
} else {
    echo "No está la guía activa para exportar (Guía II).";
}

if ($cuestionarioGuia3Activo) {
    // Si la Guía III está activa, exportar la tabla nom035R3a
    exportarTablaACSV($db, 'nom035R3a', $carpeta);
} else {
    echo "No está la guía activa para exportar (Guía III).";
}

