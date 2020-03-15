<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

global $USER;
foreach ($_POST as $key=>$val){
    $postData[$key] = htmlspecialcharsEx($val);
}
$IDUser = $USER->GetID();

// id сообщения 
$IDChat = $postData['id'];
$IDChat_base64 = base64_encode($IDChat);

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

$result = $entity_data_class::Delete($IDChat);
if($result->isSuccess()){
    //удаление папки с фалами прикрепленными к чату
    DeleteDirFilesEx("/upload/add_users_files/".$IDChat_base64);
    echo json_encode([ 'VALUE'=>'', 'TYPE'=> 'SUCCESS']);
}
else{
    echo json_encode([ 'VALUE'=>'', 'TYPE'=> 'ERROR']);
}
?>