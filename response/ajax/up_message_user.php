<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

global $USER;
$postData = $_POST;
$IDUser = $USER->GetID();

$Params = json_decode($postData['arrParams'], true);
// id сообщения 
$IDMessage = $Params['IDMess'];

foreach ($postData as $key => $value){
    $data[$key] = htmlspecialcharsEx($value);
}

if($USER->IsAuthorized()){
    //echo "Пользователь авторизован";
} else {
    echo "Пользователь не авторизован";
    die();
}

if (!\Bitrix\Main\Loader::includeModule('highloadblock')) {
    echo json_encode([ 'VALUE'=>'Не подключен модуль highloadblock', 'TYPE'=> 'ERROR']);
    die();
}


$hlbl = 6;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("ID" => "ASC"),
    "filter" => array("ID"=>$IDMessage)  // Задаем параметры фильтра выборки
));
 
$arData = $rsData->Fetch();
$arrMessages =  json_decode($arData['UF_TEXT_MESSAGE_USER'], true);

$arrMessage = array(
  'user' => $IDUser,
  'data' => date("m.d.y H:m"),
  'message' => $Params['message']
);

$arrMessages[] = $arrMessage;
print_r($arrMessages);
 
$jsonArrMessages = json_encode($arrMessages);

#id пользователя получающий сообщение
if($IDUser == $arData['UF_ID_USER']){
    $idRecipient = $arData['UF_ID_SENDER'];
}
else{
    $idRecipient = $arData['UF_ID_USER'];
}

// обновление
$data = array(    
    "UF_TEXT_MESSAGE_USER"=> $jsonArrMessages,
    "UF_STATUS"=>0,
    "UF_ID_RECIPIENT"=>$idRecipient
);

$result = $entity_data_class::update($IDMessage, $data);

?>