<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$el = new CIBlockElement;

$db_props = CIBlockElement::GetProperty("3", "8", "sort", "asc", array());
$ar_prop = array();
while($ar_props = $db_props->Fetch()){
    $ar_prop[] = $ar_props; 
}
print_r($db_prop);

switch ($_POST['atrr_text']) {
    case 'descript':
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "DETAIL_TEXT_TYPE" =>"html",
            "DETAIL_TEXT" => html_entity_decode($_POST['text'])
            //"DETAIL_TEXT"    => $_POST['text']
        );        
        break;
    case 'conditions':
        $PROP = array();    
        $PROP[15] = html_entity_decode($_POST['text']);

        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "PROPERTY_VALUES"=> $PROP            
        );

        break;
    case 'summ':
        $PROP = array();    
        $PROP[14] = html_entity_decode($_POST['text']);

        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "PROPERTY_VALUES"=> $PROP            
        );        
        break;
}


$PRODUCT_ID = $_POST['id_element'];  
$res = $el->Update($PRODUCT_ID, $arLoadProductArray);

echo "обновили ".$_POST['atrr_text'];

?>