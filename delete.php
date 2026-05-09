<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$data = json_decode(file_get_contents('php://input'), true);
$cod  = isset($data['cod']) ? preg_replace('/[^a-zA-Z0-9._-]/', '', $data['cod']) : '';
$name = isset($data['name']) ? preg_replace('/[^a-zA-Z0-9._-]/', '', $data['name']) : '';

if (!$cod || !$name) { echo json_encode(['ok'=>false,'error'=>'cod y name requeridos']); exit; }

$path = __DIR__ . '/../pdfs/' . $cod . '/' . $name;
if (!file_exists($path)) { echo json_encode(['ok'=>false,'error'=>'archivo no existe']); exit; }

if (unlink($path)) {
    echo json_encode(['ok'=>true]);
} else {
    echo json_encode(['ok'=>false,'error'=>'no se pudo eliminar']);
}
