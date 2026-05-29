<?php
// /donate/handler.php

// db
$db_host = '80.242.59.112;
$db_user = 'gs332651; // Пользователь БД
$db_pass = 'revineswag;
$db_name = 'gs332651;

// anypay
$project_id = '17501';
$secret_key_2 = 'arhRjj1lMQnupqVzZY6SqbDnZ3m3kxhaPwyQ5nT'; // ВТОРОЙ КЛЮЧ! (для уведомлений)

$anypay_ips = ['185.162.128.38', '185.162.128.39', '185.162.128.88'];
if (!in_array($_SERVER['REMOTE_ADDR'], $anypay_ips)) {
    die('Access denied');
}

$amount = $_POST['amount'];
$pay_id = $_POST['pay_id'];
$currency = $_POST['currency'];
$status = $_POST['status']; // 'paid' - значит оплачено
$sign = $_POST['sign'];
$nickname = $_POST['custom']; // Никнейм, который мы передали в pay.php

// status
if ($status !== 'paid') {
    die('OK'); // Платеж еще не завершен
}

// подпись
$my_sign_string = "{$currency}:{$amount}:{$pay_id}:{$project_id}:{$status}:{$secret_key_2}";
$my_sign = hash('sha256', $my_sign_string);

if ($sign !== $my_sign) {
    die('Invalid signature');
}

// true

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed");
}

// иньектция
$nickname_safe = $conn->real_escape_string($nickname);
$donate_amount = (int)$amount; // Сколько рублей пополнили = столько доната (меняй множитель, если нужно X2)

$sql = "UPDATE `accounts` SET `donate` = `donate` + {$donate_amount} WHERE `name` = '{$nickname_safe}'";

if ($conn->query($sql) === TRUE) {
    echo 'OK';
} else {
    echo 'DB Error';
}

$conn->close();
?>
