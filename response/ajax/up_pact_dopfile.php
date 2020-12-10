<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/class/CFormatHTMLText.php");
CModule::IncludeModule('iblock');

$arData = json_decode($_POST['arr'], true);
$arFiles = $_FILES;
$el = new CIBlockElement;
$arLoadProductArray = Array(
    "MODIFIED_BY"    => $USER->GetID(),
);

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
        $el -> SetPropertyValuesEx($arEdit['ID'], 10, $arAdditionalFields);
        return $arEdit['ID'];
    }else{
        $res = $el -> GetList(array(), array("ID" => $id), false, false, array("ID", "NAME", "CODE", "PROPERTY_PACT_USER", "PROPERTY_ID_COMPANY"));
        if($arDeal = $res -> GetNext()){
            foreach($arAdditionalFields as $key => $value){
                $arAdditionalFields[$key."_EDIT"] = "Y";
            }
            $arAdditionalFields['ORIGINAL_DEAL'] = $id;
            $arAdditionalFields['PACT_USER'] = $arDeal['PROPERTY_PACT_USER_VALUE'];
            $arAdditionalFields['ID_COMPANY'] = $arDeal['PROPERTY_ID_COMPANY_VALUE'];
            $arData = array(
                "NAME" => $arDeal['NAME'],
                "CODE" => $arDeal['CODE'],
                "IBLOCK_ID" => 10,
                "PROPERTY_VALUES" => $arAdditionalFields,
                "ACTIVE" => "Y"
            );
            if($edit_id = $el -> Add($arData)){
                sendEmail($arAdditionalFields['PACT_USER'], $id, $edit_id, $arDeal['NAME']);
                return $edit_id;
            }
        }
    }
}

switch ($arData['atrr_text']) {
    case 'delete_incl_file':

        // Удаление свойства
        $ELEMENT_ID = $arData['id_element'];
        $subId = intval($arData['sub_id']);
        $PROPERTY_CODE  = "MAIN_FILES";
        $PROPERTY_VALUE = Array(
            "del" => "Y",
            "MODULE_ID" => "additionFiles"
        );
        if(!empty($subId)){
            $db_props = CIBlockElement::GetProperty(10, $subId, "sort", "asc", array('CODE'=>$PROPERTY_CODE));
            while ($obj=$db_props->GetNext()){
                if(!empty($obj["VALUE"]) && $FILE_ID != $obj["VALUE"]){
                    $arFil[] = CFile::MakeFileArray($obj["VALUE"]);
                }
            }
        }else{
            $db_props = CIBlockElement::GetProperty(3, $ELEMENT_ID, "sort", "asc", array('CODE'=>$PROPERTY_CODE));
            while ($obj=$db_props->GetNext()){
                if(!empty($obj["VALUE"]) && $FILE_ID != $obj["VALUE"]){
                    $arFil[] = CFile::MakeFileArray($obj["VALUE"]);
                }
            }
        }
        if(!empty($arFil)){
            $checkUpdate = addEditDeal($ELEMENT_ID, $arLoadProductArray, array($PROPERTY_CODE => $arFil));
        }else{
            $checkUpdate = addEditDeal($ELEMENT_ID, $arLoadProductArray, array($PROPERTY_CODE => Array($FILE_ID => ['VALUE'=>$PROPERTY_VALUE])));
        }

        // $checkUpdate = addEditDeal($ELEMENT_ID, $arLoadProductArray, array($PROPERTY_CODE => $arFiles));
        // $checkUpdate = addEditDeal($ELEMENT_ID, $arLoadProductArray, array($PROPERTY_CODE => Array($arData['id_value'] => $PROPERTY_VALUE)));
        // $checkUpdate = CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, Array ($arData['id_value'] => $PROPERTY_VALUE) );
        if($checkUpdate){
            // if(!$res = $el->Update($ELEMENT_ID, $arLoadProductArray)){
            //     echo 'ERROR';
            //     die();
            // }
            $db_props = CIBlockElement::GetProperty(10, $checkUpdate, "sort", "asc", array('CODE'=>$PROPERTY_CODE));
            while ($obj=$db_props->GetNext()){
                if(!empty($obj["VALUE"])){
                    $result[] = [
                        "FILE" => CFile::GetFileArray($obj["VALUE"]),
                        "ID" => $obj["PROPERTY_VALUE_ID"],
                        "ID_FILE" => $obj["VALUE"]
                    ];
                }
            }
            ob_clean();?>
            <?if(!empty($result)):?>
                <?foreach($result as $files):?>
                    <a href="<?=$files['FILE']['SRC']?>" class="cardPact-rightPanel-url" target="_blank" style="float: left;width: 74%;">
                        <img src="<?=SITE_TEMPLATE_PATH?>/image/icon-contract.png" > <?=$files['FILE']['ORIGINAL_NAME']?>
                    </a>
                    <button class="btn btn-nfk delete_unclude_file" style="float: left;width: 25%;" data="<?=$files['ID']?>" data-file ="<?=$files['ID_FILE']?>" data-sub-id="<?=$checkUpdate?>">Удалить</button>
                <?endforeach?>
            <?endif?>
            <?
        }
        else{
            echo 'ERROR';
            die();
        }

        break;
    case 'add_incl_file':
        $ELEMENT_ID     = $arData['id_element'];
        $PROPERTY_CODE  = "MAIN_FILES";
        $PROPERTY_VALUE = $arFiles;
        // $checkUpdate = CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, $PROPERTY_VALUE);
        
        $checkUpdate = addEditDeal($ELEMENT_ID, $arLoadProductArray, array($PROPERTY_CODE => $PROPERTY_VALUE));

        if($checkUpdate){
            // if(!$res = $el->Update($ELEMENT_ID, $arLoadProductArray)){
            //     echo 'ERROR';
            //     die();
            // }
            $db_props = CIBlockElement::GetProperty(10, $checkUpdate, "sort", "asc", array('CODE'=>$PROPERTY_CODE));
            while ($obj=$db_props->GetNext()){
                if(!empty($obj["VALUE"])){
                    $result[] = [
                        "FILE" => CFile::GetFileArray($obj["VALUE"]),
                        "ID" => $obj["PROPERTY_VALUE_ID"],
                        "ID_FILE" => $obj["VALUE"]
                    ];
                }
            }
            foreach($result as $files){?>
                <a href="<?=$files['FILE']['SRC']?>" class="cardPact-rightPanel-url" target="_blank" style="float: left;width: 74%;">
                    <img src="<?=SITE_TEMPLATE_PATH?>/image/icon-contract.png" > <?=$files['FILE']['ORIGINAL_NAME']?>
                </a>
                <button class="btn btn-nfk delete_unclude_file" style="float: left;width: 25%;" data="<?=$files['ID']?>" data-file ="<?=$files['ID_FILE']?>" data-sub-id="<?=$checkUpdate?>">Удалить</button>
            <?}
        }
        else{
            echo 'ERROR';
            die();
        }
        break;
    case 'all_save':
        $ELEMENT_ID = $arData['id_element'];
        $PROPERTY_CODE  = "MAIN_FILES";
        $PROPERTY_VALUE = $arFiles;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "DETAIL_TEXT_TYPE" =>"html",
            "DETAIL_TEXT" => CFormatHTMLText::TextFormatting(html_entity_decode($arData['DETAIL_TEXT']))
            //"DETAIL_TEXT"    => $_POST['text']
        );
        $PROPERTY_CODE  = "MAIN_FILES";
        $PROPERTY_VALUE = $arFiles;
        if(isset($arData['PROPERTY']['CONDITIONS_PACT']))
            $arData['PROPERTY']['CONDITIONS_PACT'] = CFormatHTMLText::TextFormatting(html_entity_decode($arData['PROPERTY']['CONDITIONS_PACT']));

        $arData['PROPERTY'][$PROPERTY_CODE] = $PROPERTY_VALUE;

        // if($el->Update($ELEMENT_ID, $arLoadProductArray)){
        //     CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, $arData['PROPERTY']);
        //     $checkUpdate = CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, $PROPERTY_VALUE);
        $checkUpdate = addEditDeal($ELEMENT_ID, $arLoadProductArray, $arData['PROPERTY']);
        if($checkUpdate){
            die(json_encode([ 'VALUE'=>'', 'TYPE'=> 'SUCCESS']));
        }
        else{
            die(json_encode([ 'VALUE'=>$el->LAST_ERROR, 'TYPE'=> 'ERROR']));
        }
        break;
}




