<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$el = new CIBlockElement;
$text = $_POST['contect'];
$idSdelka = $_POST['id'];

/* записать в файл
$url_root           = $_SERVER['DOCUMENT_ROOT'].'/upload/private/pacts/';
$name_root_dir      = substr($hash_Send, 0, 1);
$name_reroot_dir    = substr($hash_Send, 2, 3);
// урл новой папки
$url_root_dir       = $url_root.'/'.$name_root_dir;
$url_contract_dir   = '/'.$url_root.'/'.$name_reroot_dir;

if (!file_exists($url_root_dir)) {
    mkdir($url_contract_dir, 0777, true);
}else {
    mkdir($url_contract_dir, 0777, true);
}

$file_contract_text = fopen($url_contract_dir.'/'.$hash_Send.'.txt', 'w');
$text_contract = $arrContractProperty['PREVIEW_TEXT'];
fwrite($file_contract_text, $text_contract);
fclose($file_contract_text);

*/

#получение данных по сделке
$res = CIBlockElement::GetByID($idSdelka);
if($obj = $res->GetNext(true, false)) $arSdelka = $obj;



$arLoadProductArray = Array(
    "IBLOCK_ID"=> 4,
    "MODIFIED_BY"    => $USER->GetID(),
    "NAME"=>$arSdelka['NAME'],
    "DETAIL_TEXT_TYPE" =>"html",
    "DETAIL_TEXT" => html_entity_decode($text),
    "ACTIVE" => "Y",
    "PROPERTY_VALUES"=> array(
        "USER_A"=>$USER->GetID()
    )
);


if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
    $prop = array(
        "ID_DOGOVORA"=>$PRODUCT_ID
    );

    CIBlockElement::SetPropertyValuesEx($arSdelka['ID'], '3', $prop);

    echo json_encode(['VALUE' => "Новый договор: ".$PRODUCT_ID, 'ID'=>$arSdelka['ID'], 'TYPE' => 'SUCCESS']);
}
else{
    echo json_encode([ 'VALUE'=>$el->LAST_ERROR, 'TYPE'=> 'ERROR']);
    die();
}

?>