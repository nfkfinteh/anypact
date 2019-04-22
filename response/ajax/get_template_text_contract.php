<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');
    

if(empty($_POST["idcontract"])){
    $ID_CONTRACT_TEMPLATE = 26;
}else {
    $ID_CONTRACT_TEMPLATE = $_POST["idcontract"];
}

$getItem = CIBlockElement::GetByID($ID_CONTRACT_TEMPLATE);
$content = array();
// получить объект по ид
if( $ar_res = $getItem->GetNext() ) {
    $content = $ar_res;
}
//print_r($content);    
echo $content["DETAIL_TEXT"];
?>