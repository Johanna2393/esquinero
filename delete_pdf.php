<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$codCad = isset($_POST['cod_cad']) ? sanitizar($_POST['cod_cad']) : '';
$archivo = isset($_POST['archivo']) ? sanitizar($_POST['archivo']) : '';

if (empty($codCad) || empty($archivo)) {
    echo json_encode(['success' => false, 'message' => 'Parámetros inválidos']);
    exit;
}

$rutaArchivo = __DIR__ . '/pdfs/' . $codCad . '/' . $archivo;

if (file_exists($rutaArchivo) && is_file($rutaArchivo)) {
    if (unlink($rutaArchivo)) {
        echo json_encode(['success' => true, 'message' => 'Archivo eliminado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Archivo no encontrado']);
}

function sanitizar($texto) {
    $texto = preg_replace('/[^a-zA-Z0-9._-]/', '_', $texto);
    return trim($texto, '_');
}
?>
