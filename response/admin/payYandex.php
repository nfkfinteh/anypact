<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
include_once ('class/payYandex.php');

// классы для соединения с посредником платежной системы.
$ConnectPayYandex = new payYandex();

// параметры которые пришли
$UserParamsPay = base64_decode($_POST["payParams"]);
$arrUserParamsPay = explode("#", $UserParamsPay);
$ID_USER_PAY = $arrUserParamsPay[0];

//$ConnectPayYandex->test();

$url = 'https://nfksber.ru/esiafast/public/cardmake.php';

print_r($_POST);
$ParamsUserPayYandex['payParams'] = $_POST['payParams'];
$ResultYR = $ConnectPayYandex->postParamsUserPay($url, $ParamsUserPayYandex);

// получаем ответ от посредника платежной системы
echo $ResultYR;

// обновляем запись у пользователя при успешном платеже
$user = new CUser;
$fields = Array( 
    "UF_PAY_YANDEX" => "Y", 
); 
$user->Update($ID_USER_PAY, $fields);


// пишем ответ в лог


/*
$hlbl = 8;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$data = array(
    "UF_ID_USER"=>$idUser,
    "UF_TEXT_MESSAGE_USER"=>$messageText,
    "UF_STATUS"=>1,
    "UF_TIME_CREATE_MSG"=>ConvertTimeStamp(time(), "FULL"),
    "UF_TITLE_MESSAGE"=>"Сообщение от пользователя"
);

$result = $entity_data_class::add($data);
*/

// шифруем данные с ключом данные конвертим в json
$arrJsonParamsUserPayYandex = json_encode($ParamsUserPayYandex);
$EncodeData = base64_encode($arrJsonParamsUserPayYandex);
// зашифрованные данные отправляем

// получаем ответ 

// полученный ответ пишем в лог


?>