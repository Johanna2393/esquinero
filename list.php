<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$cod = isset($_GET['cod']) ? preg_replace('/[^a-zA-Z0-9._-]/', '', $_GET['cod']) : '';
if (!$cod) { echo json_encode(['ok'=>false,'error'=>'cod requerido']); exit; }

$dir = __DIR__ . '/../pdfs/' . $cod;
$files = [];
if (is_dir($dir)) {
    foreach (scandir($dir) as $f) {
        if ($f === '.' || $f === '..') continue;
        $path = $dir . '/' . $f;
        if (is_file($path) && strtolower(pathinfo($f, PATHINFO_EXTENSION)) === 'pdf') {
            $files[] = [
                'name' => $f,
                'size' => filesize($path),
                'url'  => 'pdfs/' . rawurlencode($cod) . '/' . rawurlencode($f)
            ];
        }
    }
}
echo json_encode(['ok'=>true, 'cod'=>$cod, 'files'=>$files]);
