<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

global $USER;
$postData = json_decode($_POST['main']);
$arFiles = $_FILES;
$IDUser = $USER->GetID();

// id сообщения
foreach ($postData as $key => $value){
    $data[$key] = htmlspecialcharsEx($value);
}

if(!$USER->IsAuthorized()){
    die(json_encode([ 'VALUE'=>'Пользователь не авторизован', 'TYPE'=> 'ERROR']));
}

if (!\Bitrix\Main\Loader::includeModule('highloadblock')) {
    die(json_encode([ 'VALUE'=>'Не подключен модуль highloadblock', 'TYPE'=> 'ERROR']));
}

if (empty($arFiles)) {
    die(json_encode([ 'VALUE'=>'Файлы не переданы', 'TYPE'=> 'ERROR']));
}

foreach ($arFiles as $file){
    $checkFileResult = checkFileNfk($file, 10*1024*1024, ['docx', 'txt', 'rtf', 'doc', 'pdf', 'xlsx', 'jpg', 'png', 'svg', 'jpeg']);
    if($checkFileResult['TYPE']=='ERROR'){
        die(json_encode([ 'VALUE'=>$checkFileResult['VALUE'], 'TYPE'=> 'ERROR']));
    }
}

$IDMessage = $data['idMessage'];
$IDMessage_base64 = base64_encode($IDMessage);

$hlbl = 6;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("ID" => "ASC"),
    "filter" => array("ID"=>$IDMessage)  // Задаем параметры фильтра выборки
));

if($obj = $rsData->Fetch()) $arData = $obj;

if(empty($arData)){
    die(json_encode([ 'VALUE'=>'Переписка с таким id не найдена', 'TYPE'=> 'ERROR']));
}

//сохраняем файл
// загружаем во временную директорию на сервер
if (!file_exists($_SERVER['DOCUMENT_ROOT']."/upload/add_users_files")) {
    mkdir ($_SERVER['DOCUMENT_ROOT']."/upload/add_users_files");
}

foreach ($arFiles as $key=>$uploadFiles) {
    $directoryPath = $_SERVER['DOCUMENT_ROOT']."/upload/add_users_files/".$IDMessage_base64."/".$IDUser;
    if (!file_exists($_SERVER['DOCUMENT_ROOT']."/upload/add_users_files/".$IDMessage_base64)) {
        mkdir ($_SERVER['DOCUMENT_ROOT']."/upload/add_users_files/".$IDMessage_base64);
    }
    if (!file_exists($directoryPath)) {
        mkdir ($directoryPath);
    }

    move_uploaded_file($uploadFiles['tmp_name'], $directoryPath."/".$uploadFiles["name"]);
    $resultFiles["ITEMS"][$key]["SRC"] = "/upload/add_users_files/".$IDMessage_base64."/".$IDUser."/".$uploadFiles["name"];
    $resultFiles["ITEMS"][$key]["NAME"] = $uploadFiles["name"];
}

$arrMessages =  json_decode($arData['UF_TEXT_MESSAGE_USER'], true);

foreach ($resultFiles['ITEMS'] as $file){
    $arFileData[] = [
        'src' => $file['SRC'],
        'file_format' => pathinfo($file['SRC'])['extension'],
        'file_name' => $file['NAME']
    ];
}

$arrMessages[] = array(
    'user' => $IDUser,
    'data' => date("d.m.y H:i"),
    'file' => $arFileData,
);

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

if($result->isSuccess()){
    echo json_encode([ 'VALUE'=>'', 'TYPE'=> 'SUCCESS']);
}
else{
    echo json_encode([ 'VALUE'=>'Ошибка сохранения записи', 'TYPE'=> 'ERROR']);
}

?>