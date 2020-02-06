<?php

include_once ('class/payYandex.php');

$ConnectPayYandex = new payYandex();

//$ConnectPayYandex->test();

$url = 'https://nfksber.ru/esiafast/public/cardmake.php';

print_r($_POST);
$ParamsUserPayYandex['payParams'] = $_POST['payParams'];
$ResultYR = $ConnectPayYandex->postParamsUserPay($url, $ParamsUserPayYandex);
echo $ResultYR;
// получаем ключ
$key = '3cXPgr8aebKtY267vFCHAEdxMzufkZ';
// шифруем данные с ключом данные конвертим в json
$arrJsonParamsUserPayYandex = json_encode($ParamsUserPayYandex);
$EncodeData = base64_encode($arrJsonParamsUserPayYandex);
// зашифрованные данные отправляем

// получаем ответ 

// полученный ответ пишем в лог


?>