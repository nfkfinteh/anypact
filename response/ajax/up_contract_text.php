<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$el = new CIBlockElement;
$text = $_POST['contect'];

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

$arLoadProductArray = Array(
    "MODIFIED_BY"    => $USER->GetID(),
    "DETAIL_TEXT_TYPE" =>"html",
    "DETAIL_TEXT" => html_entity_decode($text)
    //"DETAIL_TEXT"    => $_POST['text']
); 

// код свойства
$PRODUCT_ID = 18; //$_POST['contect'];
$res = $el->Update($PRODUCT_ID, $arLoadProductArray);

echo "обновили !";

?>