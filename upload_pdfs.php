<?php
header('Content-Type: application/json');

$carpetaBase = __DIR__ . '/pdfs/';
$maxTamano = 50 * 1024 * 1024;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$codCad = isset($_POST['cod_cad']) ? sanitizar($_POST['cod_cad']) : '';

if (empty($codCad)) {
    echo json_encode(['success' => false, 'message' => 'Código de carpeta no especificado']);
    exit;
}

$carpetaDestino = $carpetaBase . $codCad . '/';

if (!is_dir($carpetaDestino)) {
    if (!mkdir($carpetaDestino, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'No se pudo crear la carpeta']);
        exit;
    }
}

$archivosSubidos = [];
$errores = [];

if (!isset($_FILES['pdfs'])) {
    echo json_encode(['success' => false, 'message' => 'No hay archivos']);
    exit;
}

$archivos = $_FILES['pdfs'];
$totalArchivos = is_array($archivos['name']) ? count($archivos['name']) : 1;

for ($i = 0; $i < $totalArchivos; $i++) {
    $nombreArchivo = is_array($archivos['name']) ? $archivos['name'][$i] : $archivos['name'];
    $tipoArchivo = is_array($archivos['type']) ? $archivos['type'][$i] : $archivos['type'];
    $tamanoArchivo = is_array($archivos['size']) ? $archivos['size'][$i] : $archivos['size'];
    $rutaTemporal = is_array($archivos['tmp_name']) ? $archivos['tmp_name'][$i] : $archivos['tmp_name'];
    $error = is_array($archivos['error']) ? $archivos['error'][$i] : $archivos['error'];

    if ($error !== UPLOAD_ERR_OK) {
        $errores[] = "$nombreArchivo: Error de carga";
        continue;
    }

    if ($tipoArchivo !== 'application/pdf') {
        $errores[] = "$nombreArchivo: Solo se permiten PDFs";
        continue;
    }

    if ($tamanoArchivo > $maxTamano) {
        $errores[] = "$nombreArchivo: Archivo muy grande (máx 50MB)";
        continue;
    }

    $nombreLimpio = sanitizar($nombreArchivo);
    $rutaDestino = $carpetaDestino . $nombreLimpio;

    if (file_exists($rutaDestino)) {
        $nombreLimpio = time() . '_' . $nombreLimpio;
        $rutaDestino = $carpetaDestino . $nombreLimpio;
    }

    if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
        chmod($rutaDestino, 0644);
        $archivosSubidos[] = $nombreLimpio;
    } else {
        $errores[] = "$nombreArchivo: Error al guardar";
    }
}

echo json_encode([
    'success' => count($archivosSubidos) > 0,
    'archivosSubidos' => $archivosSubidos,
    'errores' => $errores,
    'total' => count($archivosSubidos)
]);

function sanitizar($texto) {
    $texto = preg_replace('/[^a-zA-Z0-9._-]/', '_', $texto);
    $texto = preg_replace('/_+/', '_', $texto);
    return trim($texto, '_');
}
?>
