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

$res = CUser::GetByID($curentUser['ID']);
$curentUser['DATA'] = $res->GetNext(true, false);

$urlProfile = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '') . '://';
$urlProfile .= $_SERVER['SERVER_NAME'].'/profile_user/?ID='.$curentUser['ID'];

#формируем сообщение
$messageText = 'Cообщение от :'.$curentUser['DATA']['NAME'].' '.$curentUser['DATA']['LAST_NAME'].' '.$curentUser['DATA']['SECOND_NAME'].' '
    .' <a href="'.$urlProfile.'"ссылка на профиль</a> <br>'
    .$data['message-text'];

$message[] = array(
    'user' => $curentUser["ID"],
    'data' => date("m.d.y H:m"),
    'message' => $data['message-text']
);

$JsonMess = json_encode($message);
$title = htmlspecialchars($data['title']);

$hlbl = 6;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$data = array(
    "UF_ID_USER"            => $idUser,
    "UF_ID_SENDER"          => $curentUser["ID"],
    "UF_TEXT_MESSAGE_USER"  => $JsonMess,
    "UF_STATUS"             => 1,
    "UF_TIME_CREATE_MSG"    => ConvertTimeStamp(time(), "FULL"),
    "UF_TITLE_MESSAGE"      => $title,
    "UF_ID_RECIPIENT"       => $idUser
);
$result = $entity_data_class::add($data);
echo json_encode([ 'VALUE'=>'Сообщение отправлено', 'TYPE'=> 'SUCCESS']);