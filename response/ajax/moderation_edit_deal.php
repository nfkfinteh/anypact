<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/class/CFormatHTMLText.php");

GLOBAL $USER;

if(CModule::IncludeModule('iblock') && check_bitrix_sessid() && $USER -> IsAdmin()){

    $el = new CIBlockElement;

    if($_POST['action'] == "accept"){
        $rsData = CIBlockElement::GetList(array('id' => 'desc'), array('IBLOCK_ID' => 10, "ID" => $_POST['id']), false, false, array('ID', 'IBLOCK_ID', "*", "PROPERTY_*"));
        if($ob = $rsData -> getNextElement()){
            $arData = $ob -> GetFields();
            $arProps = $ob -> GetProperties();
            $rsUser = CUser::GetList($by="personal_country", $order="desc", array('ID' => $arData['CREATED_BY']), array('FIELDS' => array('ID', 'EMAIL')));
            if($arUser = $rsUser -> getNext()){

                $arFields["MODIFIED_BY"] = $arData['CREATED_BY'];
                if($arProps['DETAIL_TEXT_EDIT']['VALUE'] == "Y"){
                    $arFields["DETAIL_TEXT"] = $arData['DETAIL_TEXT'];
                }

                $arAdditionalFields = array();

                if($arProps['CONDITIONS_PACT_EDIT']['VALUE'] == "Y"){
                    $arAdditionalFields['CONDITIONS_PACT'] = CFormatHTMLText::TextFormatting($arProps['CONDITIONS_PACT']['VALUE']['TEXT']);
                }
                if($arProps['COORDINATES_AD_EDIT']['VALUE'] == "Y"){
                    $arAdditionalFields['COORDINATES_AD'] = $arProps['COORDINATES_AD']['VALUE'];
                }
                if($arProps['LOCATION_CITY_EDIT']['VALUE'] == "Y"){
                    $arAdditionalFields['LOCATION_CITY'] = $arProps['LOCATION_CITY']['VALUE'];
                }
                if($arProps['MAIN_FILES_EDIT']['VALUE'] == "Y"){
                    if(empty($arProps['MAIN_FILES']['VALUE'])){
                        $db_props = CIBlockElement::GetProperty(3, $arProps['ORIGINAL_DEAL']['VALUE'], "sort", "asc", array('CODE'=>'MAIN_FILES'));
                        while ($obj=$db_props->GetNext()){
                            if(!empty($obj["VALUE"])){
                                $arAdditionalFields['MAIN_FILES'][$obj["VALUE"]] = array("del" => "Y", "MODULE_ID" => "iblock");
                            }
                        }
                    }else{
                        foreach($arProps['MAIN_FILES']['VALUE'] as $value){
                            $arAdditionalFields['MAIN_FILES'][] = CFile::MakeFileArray($value);
                        }
                    }
                }
                if($arProps['INPUT_FILES_EDIT']['VALUE'] == "Y"){
                    if(empty($arProps['INPUT_FILES']['VALUE'])){
                        $db_props = CIBlockElement::GetProperty(3, $arProps['ORIGINAL_DEAL']['VALUE'], "sort", "asc", array('CODE'=>'INPUT_FILES'));
                        while ($obj=$db_props->GetNext()){
                            if(!empty($obj["VALUE"])){
                                $arAdditionalFields['INPUT_FILES'][$obj["VALUE"]] = array("del" => "Y", "MODULE_ID" => "iblock");
                            }
                        }
                    }else{
                        foreach($arProps['INPUT_FILES']['VALUE'] as $value){
                            $arAdditionalFields['INPUT_FILES'][] = CFile::MakeFileArray($value);
                        }
                    }
                }

                if(!empty($arFields))
                    $el -> Update($arProps['ORIGINAL_DEAL']['VALUE'], $arFields);
                if(!empty($arAdditionalFields))
                    $el -> SetPropertyValuesEx($arProps['ORIGINAL_DEAL']['VALUE'], 3, $arAdditionalFields);
                
                $el -> Delete($_POST['id']);

                $arEventFields = array(
                    "EMAIL" => $arUser['EMAIL'],
                    "DEAL_ID" => $arProps['ORIGINAL_DEAL']['VALUE'],
                    "DEAL_NAME" => $arData['NAME']
                );
                CEvent::Send("DEAL_EDIT_MODERATION_ACCEPT", SITE_ID, $arEventFields);
                echo json_encode(array("TYPE" == "SUCCESS"));
            }
        }
    }elseif($_POST['action'] == "delete"){
        $rsData = CIBlockElement::GetList(array('id' => 'desc'), array('IBLOCK_ID' => 10, "ID" => $_POST['id']), false, false, array('ID', 'NAME', 'CREATED_BY', "PROPERTY_ORIGINAL_DEAL"));
        if($arData = $rsData -> getNext()){
            $rsUser = CUser::GetList($by="personal_country", $order="desc", array('ID' => $arData['CREATED_BY']), array('FIELDS' => array('ID', 'EMAIL')));
            if($arUser = $rsUser -> getNext()){
                $el -> Delete($_POST['id']);
                $arEventFields = array(
                    "EMAIL" => $arUser['EMAIL'],
                    "DEAL_ID" => $arData['PROPERTY_ORIGINAL_DEAL_VALUE'],
                    "DEAL_NAME" => $arData['NAME']
                );
                CEvent::Send("DEAL_EDIT_MODERATION_DENIED", SITE_ID, $arEventFields);
                echo json_encode(array("TYPE" == "SUCCESS"));
            }
        }
    }
    
}