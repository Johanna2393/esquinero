<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok'=>false,'error'=>'Solo POST']); exit;
}

$cod = isset($_POST['cod']) ? preg_replace('/[^a-zA-Z0-9._-]/', '', $_POST['cod']) : '';
if (!$cod) { echo json_encode(['ok'=>false,'error'=>'cod requerido']); exit; }

if (!isset($_FILES['files'])) {
    echo json_encode(['ok'=>false,'error'=>'No hay archivos']); exit;
}

$dir = __DIR__ . '/../pdfs/' . $cod;
if (!is_dir($dir)) {
    if (!mkdir($dir, 0755, true)) {
        echo json_encode(['ok'=>false,'error'=>'No se pudo crear carpeta']); exit;
    }
}

$files = $_FILES['files'];
$total = is_array($files['name']) ? count($files['name']) : 1;
$uploaded = 0;
$errors = [];

for ($i = 0; $i < $total; $i++) {
    $name  = is_array($files['name']) ? $files['name'][$i] : $files['name'];
    $tmp   = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
    $err   = is_array($files['error']) ? $files['error'][$i] : $files['error'];
    $size  = is_array($files['size']) ? $files['size'][$i] : $files['size'];

    if ($err !== UPLOAD_ERR_OK) { $errors[] = "$name: error $err"; continue; }
    if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) !== 'pdf') { $errors[] = "$name: no es PDF"; continue; }
    if ($size > 50 * 1024 * 1024) { $errors[] = "$name: > 50MB"; continue; }

    $clean = preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
    $dest = $dir . '/' . $clean;
    if (file_exists($dest)) {
        $clean = time() . '_' . $clean;
        $dest = $dir . '/' . $clean;
    }
    if (move_uploaded_file($tmp, $dest)) {
        @chmod($dest, 0644);
        $uploaded++;
    } else {
        $errors[] = "$name: error al guardar";
    }
}

echo json_encode(['ok'=> $uploaded > 0, 'uploaded'=>$uploaded, 'errors'=>$errors, 'cod'=>$cod]);
