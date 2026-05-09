<?php
header('Content-Type: application/json');

$codCad = isset($_GET['cod_cad']) ? sanitizar($_GET['cod_cad']) : '';

if (empty($codCad)) {
    echo json_encode(['success' => false, 'archivos' => []]);
    exit;
}

$carpetaDestino = __DIR__ . '/pdfs/' . $codCad . '/';

$archivos = [];

if (is_dir($carpetaDestino)) {
    $archivosDir = scandir($carpetaDestino);
    
    foreach ($archivosDir as $archivo) {
        if ($archivo !== '.' && $archivo !== '..' && strtolower(pathinfo($archivo, PATHINFO_EXTENSION)) === 'pdf') {
            $rutaCompleta = $carpetaDestino . $archivo;
            $archivos[] = [
                'name' => $archivo,
                'size' => filesize($rutaCompleta)
            ];
        }
    }
}

echo json_encode([
    'success' => true,
    'archivos' => $archivos
]);

function sanitizar($texto) {
    $texto = preg_replace('/[^a-zA-Z0-9._-]/', '_', $texto);
    return trim($texto, '_');
}
?>
