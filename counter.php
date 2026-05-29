<?php
// путь к файлу
$file = 'count.txt';

// Инициализация файла
if (!file_exists($file)) {
    file_put_contents($file, '0');
}

$action = isset($_GET['action']) ? $_GET['action'] : 'get';
$response = ['success' => false, 'count' => 0, 'error' => null];

try {
    $count = (int)file_get_contents($file);
    
    if ($action === 'hit') {
        // Атомарная операция с блокировкой файла
        $fp = fopen($file, 'r+');
        if (flock($fp, LOCK_EX)) {
            $count = (int)fread($fp, filesize($file));
            $count++;
            rewind($fp);
            ftruncate($fp, 0);
            fwrite($fp, (string)$count);
            flock($fp, LOCK_UN);
            $response['success'] = true;
        }
        fclose($fp);
    } elseif ($action === 'get') {
        $response['success'] = true;
    } else {
        $response['error'] = 'Invalid action';
    }
    
    $response['count'] = $count;
} catch (Exception $e) {
    $response['error'] = 'Server error: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
