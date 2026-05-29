<?php
// /donate/pay.php
// НАСТРОЕНО ПОД ЭНИПЕЙ ( anypay ).

$project_id = ''; // id проекта
$secret_key = ''; // первый ключ 

$amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 0;
$nickname = isset($_POST['nickname']) ? trim($_POST['nickname']) : '';

if ($amount < 10 || empty($nickname)) {
    die('Ошибка: некорректная сумма или никнейм.');
}

$pay_id = time(); 
$currency = 'RUB';
$desc = 'Пополнение счета игрока ' . $nickname;

$sign_string = "{$project_id}:{$pay_id}:{$amount}:{$currency}:{$desc}:{$secret_key}";
$sign = hash('sha256', $sign_string);

$payment_url = "https://anypay.io/merchant?" . http_build_query([
    'merchant_id' => $project_id,
    'pay_id'      => $pay_id,
    'amount'      => $amount,
    'currency'    => $currency,
    'desc'        => $desc,
    'sign'        => $sign,
    'custom'      => $nickname
]);

header("Location: " . $payment_url);
exit;
?>
