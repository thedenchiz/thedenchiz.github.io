<?php
// путь к файлу
$file = 'count.txt';

if (!file_exists($file)) {
    file_put_contents($file, '0');
}

$action = isset($_GET['action']) ? $_GET['action'] : 'get';
$count = (int)file_get_contents($file);

if ($action === 'hit') {
    $count++;
    file_put_contents($file, (string)$count);
}

header('Content-Type: application/json');
echo json_encode(['count' => $count]);
?>

