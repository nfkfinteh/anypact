<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;
$postData = $_POST;
$ELEMENT_ID = $postData['id'];
//$data = json_decode($postData, true);
if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    echo json_encode([ 'VALUE'=>'Не подключен модуль инфоблоки', 'TYPE'=> 'ERROR']);
    die();
}

if(CIBlockElement::Delete($ELEMENT_ID))
{
    echo json_encode([ 'VALUE'=>'Элемент удален', 'TYPE'=> 'SUCCESS']);
}
else{
    echo json_encode([ 'VALUE'=>'Ошибка при удалении', 'TYPE'=> 'ERROR']);
    die();
}