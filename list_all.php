<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$baseDir = __DIR__ . '/../pdfs';
$result = [];

if (is_dir($baseDir)) {
    foreach (scandir($baseDir) as $folder) {
        if ($folder === '.' || $folder === '..') continue;
        $folderPath = $baseDir . '/' . $folder;
        if (!is_dir($folderPath)) continue;
        
        $files = [];
        foreach (scandir($folderPath) as $f) {
            if ($f === '.' || $f === '..') continue;
            $path = $folderPath . '/' . $f;
            if (is_file($path) && strtolower(pathinfo($f, PATHINFO_EXTENSION)) === 'pdf') {
                $files[] = [
                    'name' => $f,
                    'size' => filesize($path),
                    'url'  => 'pdfs/' . rawurlencode($folder) . '/' . rawurlencode($f)
                ];
            }
        }
        if (count($files) > 0) {
            $result[$folder] = $files;
        }
    }
}
echo json_encode(['ok'=>true, 'pdfs'=>$result]);
