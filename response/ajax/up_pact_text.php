<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/local/class/CFormatHTMLText.php");
CModule::IncludeModule('iblock');

$el = new CIBlockElement;
$PRODUCT_ID = $_POST['id_element'];

function sendEmail($user_id, $original_deal, $edit_deal, $deal_name){
    $rsUser = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id), array("FIELDS" => array("ID", "NAME", "LAST_NAME", "SECOND_NAME")));
    if($arUser = $rsUser->Fetch()){
        $arEventFields = array(
            "USER_ID" => $arUser['ID'],
            "USER_FIO" => $arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME'],
            "DEAL_ORIGINAL" => $original_deal,
            "DEAL_EDIT" => $edit_deal,
            "DEAL_NAME" => $deal_name
        );
        CEvent::Send("NEW_DEAL_EDIT", SITE_ID, $arEventFields);
    }
}

function addEditDeal($id, $arFields = array(), $arAdditionalFields = array()){
    $el = new CIBlockElement();
    $res = $el -> GetList(array(), array("IBLOCK_ID" => 10, "PROPERTY_ORIGINAL_DEAL" => $id), false, false, array("ID"));
    if($arEdit = $res -> GetNext()){
        $el -> Update($arEdit['ID'], $arFields);
        foreach($arAdditionalFields as $key => $value){
            $arAdditionalFields[$key."_EDIT"] = "Y";
        }
        if(isset($arFields['DETAIL_TEXT']))
            $arAdditionalFields["DETAIL_TEXT_EDIT"] = "Y";
        $el -> SetPropertyValuesEx($arEdit['ID'], 10, $arAdditionalFields);
        return $arEdit['ID'];
    }else{
        $res = $el -> GetList(array(), array("ID" => $id), false, false, array("ID", "NAME", "CODE", "PROPERTY_PACT_USER", "PROPERTY_ID_COMPANY"));
        if($arDeal = $res -> GetNext()){
            foreach($arAdditionalFields as $key => $value){
                $arAdditionalFields[$key."_EDIT"] = "Y";
            }
            if(isset($arFields['DETAIL_TEXT']))
                $arAdditionalFields["DETAIL_TEXT_EDIT"] = "Y";
            $arAdditionalFields['ORIGINAL_DEAL'] = $id;
            $arAdditionalFields['PACT_USER'] = $arDeal['PROPERTY_PACT_USER_VALUE'];
            $arAdditionalFields['ID_COMPANY'] = $arDeal['PROPERTY_ID_COMPANY_VALUE'];
            $arData = array(
                "NAME" => $arDeal['NAME'],
                "CODE" => $arDeal['CODE'],
                "IBLOCK_ID" => 10,
                "PROPERTY_VALUES" => $arAdditionalFields,
                "ACTIVE" => "Y",
                "DETAIL_TEXT_TYPE" =>"html",
                "DETAIL_TEXT" => $arFields['DETAIL_TEXT']
            );
            if($edit_id = $el -> Add($arData)){
                sendEmail($arAdditionalFields['PACT_USER'], $id, $edit_id, $arDeal['NAME']);
                return $edit_id;
            }
        }
    }
}

$resultData = "Изменения сохранены";

switch ($_POST['atrr_text']) {
    case 'descript':
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "DETAIL_TEXT_TYPE" =>"html",
            "DETAIL_TEXT" => CFormatHTMLText::TextFormatting(html_entity_decode($_POST['text']))
            //"DETAIL_TEXT"    => $_POST['text']
        );
        addEditDeal($PRODUCT_ID, $arLoadProductArray);
        echo json_encode([ 'VALUE'=>$PRODUCT_ID, 'TYPE'=> 'SUCCESS', 'DATA' => "Изменения вступят в силу после модерации"]);
        exit();
        break;
    case 'conditions':
        
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID()               
        );

        $ELEMENT_ID = $_POST['id_element']; 
        $PROPERTY_CODE = "CONDITIONS_PACT";
        $PROPERTY_VALUE = CFormatHTMLText::TextFormatting(html_entity_decode($_POST['text']));  // значение свойства

        addEditDeal($PRODUCT_ID, $arLoadProductArray, array($PROPERTY_CODE => $PROPERTY_VALUE));
        echo json_encode([ 'VALUE'=>$PRODUCT_ID, 'TYPE'=> 'SUCCESS', 'DATA' => "Изменения вступят в силу после модерации"]);
        exit();

        //$value="text";
        //CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, array("VALUE"=>array("TEXT"=>$PROPERTY_VALUE, "TYPE"=>"html")));

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

        // $checkUpdate = CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, false, $arProperty);

        addEditDeal($PRODUCT_ID, $arLoadProductArray, $arProperty);
        echo json_encode([ 'VALUE' => $PRODUCT_ID, 'TYPE' => 'SUCCESS', 'DATA' => "Изменения вступят в силу после модерации"]);
        exit();

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