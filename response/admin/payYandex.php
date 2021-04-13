<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
include_once ('class/payYandex.php');

$count = COption::GetOptionInt("main", "pay_count");
if(empty($count)){
    $count = 0;
}
// if($count > 1000){
//     echo json_encode(array("STATUS" => "ERROR", "DESCRIPTION" => "Достигнут лимит в 1000 выплат"));
//     die();
// }

// классы для соединения с посредником платежной системы.
$ConnectPayYandex = new payYandex();

// параметры которые пришли
$UserParamsPay = base64_decode($_POST["payParams"]);
$arrUserParamsPay = explode("#", $UserParamsPay);
$ID_USER_PAY = $arrUserParamsPay[0];

//$ConnectPayYandex->test();

$url = 'https://nfksber.ru/esiafast/public/cardmake.php';

$ParamsUserPayYandex['payParams'] = $_POST['payParams'];
$ResultYR = $ConnectPayYandex->postParamsUserPay($url, $ParamsUserPayYandex);

// получаем ответ от посредника платежной системы
echo $ResultYR;

$arResult = json_decode($ResultYR, 1);
if(is_array($arResult) && $arResult['STATUS'] == "SUCCESS"){
    $count++;
    COption::SetOptionInt("main", "pay_count", $count);
    // обновляем запись у пользователя при успешном платеже
    $user = new CUser;
    $fields = Array( 
        "UF_PAY_YANDEX" => "Y", 
    ); 
    $user->Update($ID_USER_PAY, $fields);


    // пишем ответ в лог
    $hlbl = 12;
    $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $data = array(
        "UF_ID_USER"    =>$ID_USER_PAY,
        "UF_TIME_CREATE"=>ConvertTimeStamp(time(), "FULL"),
        "UF_RESPONSE"   => $ResultYR
    );

    $result = $entity_data_class::add($data);

    // шифруем данные с ключом данные конвертим в json
    $arrJsonParamsUserPayYandex = json_encode($ParamsUserPayYandex);
    $EncodeData = base64_encode($arrJsonParamsUserPayYandex);
    // зашифрованные данные отправляем

    // получаем ответ 

    // полученный ответ пишем в лог
}
?>