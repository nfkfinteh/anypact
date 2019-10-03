<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$postData = $_POST;
foreach ($postData as $key => $value){
    $data[$key] = htmlspecialcharsEx($value);
}

if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    echo json_encode([ 'VALUE'=>'Не подключен модуль инфоблоки', 'TYPE'=> 'ERROR']);
    die();
}
if(empty($data['city'])){
    echo json_encode([ 'VALUE'=>'Пустой запрос', 'TYPE'=> 'ERROR']);
    die();
}
$arSelect = [
    'IBLOCK_ID',
    'ID',
    'NAME'
];
$arFilter = [
    'IBLOCK_ID'=>7,
    'NAME'=>$data['city'],
    'ACTIVE'=>'Y'
];
$res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
if($obj = $res->GetNext(true, false)){
    $result = $obj;
}
;
if(!empty($result['ID'])){
    echo json_encode([ 'VALUE'=>$result, 'TYPE'=> 'SUCCESS']);
    die();
}
else{
    echo json_encode([ 'VALUE'=>'Нет города в справочнике', 'TYPE'=> 'ERROR']);
    die();
}




?>