<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$el = new CIBlockElement;

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
        
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID()               
        );

        $ELEMENT_ID = $_POST['id_element']; 
        $PROPERTY_CODE = "CONDITIONS_PACT";
        $PROPERTY_VALUE = html_entity_decode($_POST['text']);  // значение свойства

        $value="text";
        CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, array("VALUE"=>array("TEXT"=>$PROPERTY_VALUE, "TYPE"=>"html")));

        break;
    // обновление суммы
    case 'summ':
        $ELEMENT_ID = $_POST['id_element']; 
        $PROPERTY_CODE = "SUMM_PACT";
        $PROPERTY_VALUE = html_entity_decode($_POST['text']);  // значение свойства

        $value="text";
        CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, $PROPERTY_VALUE);

        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID()            
        );        
        break;
    // автоматическое удаление объявления
    case 'aut_delete':
        $ELEMENT_ID = $_POST['id_element'];
        $PROPERTY_CODE = "AV_DELETE";
        $PROPERTY_VALUE = html_entity_decode($_POST['text']);  // значение свойства

        $value="text";
        CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, $PROPERTY_VALUE);

        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID()
        );
        break;
    // продление срока объявления
    case 'up_date_active':        
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "DATE_ACTIVE_TO" => ConvertTimeStamp(time()+(86400*10), "SHORT")
        );
        break;        
}

// код свойства
$PRODUCT_ID = $_POST['id_element'];
$res = $el->Update($PRODUCT_ID, $arLoadProductArray);

?>