<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/local/class/CFormatHTMLText.php");
CModule::IncludeModule('iblock');

$el = new CIBlockElement;
$PRODUCT_ID = $_POST['id_element'];

switch ($_POST['atrr_text']) {
    case 'descript':
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "DETAIL_TEXT_TYPE" =>"html",
            "DETAIL_TEXT" => CFormatHTMLText::TextFormatting(html_entity_decode($_POST['text']))
            //"DETAIL_TEXT"    => $_POST['text']
        );        
        break;
    case 'conditions':
        
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID()               
        );

        $ELEMENT_ID = $_POST['id_element']; 
        $PROPERTY_CODE = "CONDITIONS_PACT";
        $PROPERTY_VALUE = CFormatHTMLText::TextFormatting(html_entity_decode($_POST['text']));  // значение свойства

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
        $checkUpdate = CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, $PROPERTY_VALUE);

        if($checkUpdate){
            $resultData = $PROPERTY_VALUE;
        }

        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID()
        );
        break;
    // продление срока объявления
    case 'up_date_active':
        $res = CIBlockElement::GetList([], ['IBLOCK_ID'=>3, 'ID'=>$PRODUCT_ID], false, false, ['IBLOCK_ID', 'ID', 'DATE_ACTIVE_TO', 'PROPERTY_INDEFINITELY']);
        if($obj=$res->GetNext()){
            $arSdelka = $obj;
        }

        if($arSdelka['PROPERTY_INDEFINITELY_VALUE'] == "Y"){
            echo json_encode([ 'VALUE' => "Нельзя продлить. Сделка бессрочная", 'TYPE'=> 'ERROR']);
            die();
        }

        if(empty($arSdelka['DATE_ACTIVE_TO'])){
            $time = ConvertTimeStamp(time()+(86400*10), "SHORT");
        }
        else{
            $time = ConvertTimeStamp( MakeTimeStamp($arSdelka['DATE_ACTIVE_TO'], "DD.MM.YYYY")+(86400*10), "SHORT");
        }

        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "DATE_ACTIVE_TO" => $time
        );

        $resultData = $time;
        break;
    case 'up_location':
        $arProperty = [
            'LOCATION_CITY'=>htmlspecialcharsEx($_POST['cityName']),
            'COORDINATES_AD'=>htmlspecialcharsEx($_POST['coordinates']),
        ];
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
        );

        $checkUpdate = CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, false, $arProperty);
        break;
    case 'up_private':
        $arProperty = [
            'PRIVATE'=>$_POST['PRIVATE']
        ];
        $arLoadProductArray = Array(
             "MODIFIED_BY"    => $USER->GetID(),
        );
    
        $checkUpdate = CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, false, $arProperty);
        break;
    case 'up_priceOnRequest':
        $arProperty = [
            'PRICE_ON_REQUEST'=>$_POST['PRICE_ON_REQUEST']
        ];
        $arLoadProductArray = Array(
             "MODIFIED_BY"    => $USER->GetID(),
        );
    
        $checkUpdate = CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, false, $arProperty);
        break;
    case 'up_showPhone':
        $arProperty = [
            'SHOW_PHONE'=>$_POST['SHOW_PHONE']
        ];
        $arLoadProductArray = Array(
             "MODIFIED_BY"    => $USER->GetID(),
        );
    
        $checkUpdate = CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, false, $arProperty);
        break;
    case 'access_user':
        $arProperty = [
            'ACCESS_USER'=>$_POST['ACCESS_USER']
        ];
        $arLoadProductArray = Array(
             "MODIFIED_BY"    => $USER->GetID(),
        );
    
        $checkUpdate = CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, false, $arProperty);
        break;
    case 'deal_phone':
        $arProperty = [
            'DEAL_PHONE'=>$_POST['DEAL_PHONE']
        ];
        $arLoadProductArray = Array(
                "MODIFIED_BY"    => $USER->GetID(),
        );
    
        $checkUpdate = CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, false, $arProperty);
        break;
    case 'up_indefinitely':
        $arProperty = [
            'INDEFINITELY'=>$_POST['INDEFINITELY']
        ];
        if($_POST['INDEFINITELY'] == 18){
            $time = "";
        }else{
            $time = ConvertTimeStamp(time()+(86400*10), "SHORT");    
        }
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "DATE_ACTIVE_TO" => $time
        );
    
        $checkUpdate = CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, false, $arProperty);
        break;
}

// код свойства
$res = $el->Update($PRODUCT_ID, $arLoadProductArray);
if($res){
    echo json_encode([ 'VALUE'=>$PRODUCT_ID, 'TYPE'=> 'SUCCESS', 'DATA'=>$resultData]);
}
else{
    echo json_encode([ 'VALUE'=>$el->LAST_ERROR, 'TYPE'=> 'ERROR']);
}
?>