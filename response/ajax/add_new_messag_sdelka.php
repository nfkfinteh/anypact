<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

$postData = $_POST;
foreach ($postData as $key => $value){
    $data[$key] = htmlspecialcharsEx($value);
}
#проверка полей
if(empty($data['message-text'])){
    echo json_encode([ 'VALUE'=>'Не заполнено сообщение', 'TYPE'=> 'ERROR']);
    die();
}
if(!empty($data['login'])){
    $rsUser = CUser::GetByLogin($data['login']);
    $idUser = $rsUser->GetNext(true, false)['ID'];
}
else{
    echo json_encode([ 'VALUE'=>'Не найден пользователь', 'TYPE'=> 'ERROR']);
    die();
}

if(empty($idUser)){
    echo json_encode([ 'VALUE'=>'Не найден пользователь', 'TYPE'=> 'ERROR']);
    die();
}

$curentUser['ID'] = $USER->GetID();
if(empty($curentUser['ID'])){
    echo json_encode([ 'VALUE'=>'Вы не авторизваны', 'TYPE'=> 'ERROR']);
    die;
}

if (!\Bitrix\Main\Loader::includeModule('highloadblock')) {
    echo json_encode([ 'VALUE'=>'Не подключен модуль highloadblock', 'TYPE'=> 'ERROR']);
    die();
}

$hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(15)->fetch();
$entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("ID" => "ASC"),
    "filter" => array("UF_USER_A" => $idUser, "UF_USER_B" => $curentUser['ID'])
));
if($arData = $rsData->Fetch()){
    echo json_encode([ 'VALUE'=>'Вы не можете оставить комментарий, т.к. вы находитесь в черном списке', 'TYPE'=> 'ERROR']);
    die;
}

$res = CUser::GetByID($curentUser['ID']);
$curentUser['DATA'] = $res->GetNext(true, false);


#формируем сообщение
$messageText = $data['message-text'];

$hlbl = $data['iblock_id'];
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$data = array(
    "UF_ID_USER"=>$idUser,
    "UF_TEXT_MESSAGE"=>$messageText,
    "UF_STATUS"=>1,
    "UF_TIME_CREATE_MSG"=>ConvertTimeStamp(time(), "FULL"),
    "UF_ID_SLEKA"=>$data['object']
);

$result = $entity_data_class::add($data);
echo json_encode([ 'VALUE'=>'', 'TYPE'=> 'SUCCESS']);