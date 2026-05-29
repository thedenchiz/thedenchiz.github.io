<?php
header('Content-Type: application/json');

// --- НАСТРОЙКИ БАЗЫ ДАННЫХ ---
$db_host = '80.242.59.112;        // host
$db_name = 'gs332651;        // db мода
$db_user = 'gs332651;             // user
$db_pass = 'revineswag;      // password

// Название таблицы с аккаунтами и колонка с ником
$table_name = 'accounts';      // акки
$nick_column = 'Name';         // ники

$nick = isset($_GET['nick']) ? trim($_GET['nick']) : '';

if (empty($nick)) {
    echo json_encode(['exists' => false, 'error' => 'Empty nick']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `$table_name` WHERE `$nick_column` = :nick");
    $stmt->execute(['nick' => $nick]);
    
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }

} catch (PDOException $e) {
    echo json_encode(['exists' => false, 'error' => 'Database connection failed']);
}
?>
