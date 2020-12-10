<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');
$arData = json_decode($_POST['arr'], true);
$arFiles = $_FILES;
$el = new CIBlockElement;

function generateStr($length = 8){
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $numChars = strlen($chars);
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return $string;
}

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
    case 'delete':

        $ELEMENT_ID = intval($arData['id_element']);
        $subId = intval($arData['sub_id']);
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
        );
        $PROPERTY_CODE  = "INPUT_FILES";
        $FILE_ID = intval($arData['id_value']);
        $PROPERTY_VALUE = Array(
            "del" => "Y",
            "MODULE_ID" => "iblock"
        );
        // $checkUpdate = CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, Array ($FILE_ID => ['VALUE'=>$PROPERTY_VALUE]) );
        if(!empty($subId)){
            $db_props = CIBlockElement::GetProperty(10, $subId, "sort", "asc", array('CODE'=>'INPUT_FILES'));
            while ($obj=$db_props->GetNext()){
                if(!empty($obj["VALUE"]) && $FILE_ID != $obj["VALUE"]){
                    $arFil[] = CFile::MakeFileArray($obj["VALUE"]);
                }
            }
        }else{
            $db_props = CIBlockElement::GetProperty(3, $ELEMENT_ID, "sort", "asc", array('CODE'=>'INPUT_FILES'));
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
        
        if($checkUpdate){
            // if(!$res = $el->Update($ELEMENT_ID, $arLoadProductArray)){
            //     echo 'ERROR';
            //     die();
            // }
            $db_props = CIBlockElement::GetProperty(10, $checkUpdate, "sort", "asc", array('CODE'=>'INPUT_FILES'));
            while ($obj=$db_props->GetNext()){
                if(!empty($obj["VALUE"])){
                    $result[] = [
                        "URL" => CFile::GetPath($obj["VALUE"]),
                        "ID" => $obj["PROPERTY_VALUE_ID"],
                        "ID_FILE" => $obj["VALUE"]
                    ];
                }
            }
            ob_clean();
            ?>
            <?if(!empty($result)):?>
                <div class="sp-slides">
                    <?// изображения
                    foreach ($result as $arImg):?>
                        <?if(!empty($arImg['URL'])):?>
                            <div class="sp-slide">
                                <img class="sp-image" src="<?=$arImg['URL']?>">
                                <span class="cardPact-box-edit-rem_img" data-id="<?=$arImg['ID_FILE']?>" data-sub-id="<?=$checkUpdate?>">-</span>
                            </div>
                        <?endif?>
                    <?endforeach?>
                </div>
                <div class="sp-thumbnails">
                    <?// изображения
                    foreach ($result as $arImg):?>
                        <?if(!empty($arImg['URL'])):?>
                            <img class="sp-thumbnail" src="<?=$arImg["URL"]?>">
                        <?endif?>
                    <?endforeach?>
                    <?if(count($result) < 20){?>
                        <img id="cardPact-box-edit-add_img" class="sp-thumbnail js-add_img" src="<?=SITE_TEMPLATE_PATH?>/image/add_img.png">
                    <?}?>
                </div>
            <?else:?>
                <div class="sp-slides">
                    <div class="sp-slide">
                        <img class="sp-image js-add_img" src="<?=SITE_TEMPLATE_PATH?>/image/add_img.png">
                    </div>

                </div>
                <div class="sp-thumbnails">
                    <img id="cardPact-box-edit-add_img" class="sp-thumbnail js-add_img" src="<?=SITE_TEMPLATE_PATH?>/image/add_img.png">
                </div>
            <?endif?>
            <?
            die();
        }
        else{
            echo 'ERROR';
            die();
        }
        break;
    case 'add':

        // код свойства
        $ELEMENT_ID = intval($arData['id_element']);

        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
        );

        /*$dbSdelka = CIBlockElement::GetByID($ELEMENT_ID);
        if($obj = $dbSdelka->GetNext()) $arElement = $obj;*/

        /*if(empty($arElement['DETAIL_PICTURE'])){
            $detailImg = array_shift($arFiles);
            $arLoadProductArray['DETAIL_PICTURE'] = $detailImg;
        }*/

        foreach($arFiles as $key => $file){
            $image_src = $file['tmp_name'];
            $tmp_image = $_SERVER['DOCUMENT_ROOT'] . "/upload/image/" . $file['name'];
            $file_name = explode(".", $tmp_image);
            $tmp_image = "";
            foreach($file_name as $key2 => $value){
                if($key2 == (array_key_last($file_name) - 1)){
                    $value .= generateStr(5);
                }
                $tmp_image .= $value;
            }
            $resize_img = CFile::ResizeImageFile($image_src, $tmp_image, array('width'=>'730', 'height'=>'500'), BX_RESIZE_IMAGE_PROPORTIONAL, array(
                'type' => 'image',
                'size' => 'small',
                'position' => 'bottomright',
                'file' => $_SERVER['DOCUMENT_ROOT'] . '/local/templates/anypact/img/logo3.png'
            ));
            if($resize_img){
                $arFiles[$key]['tmp_name'] = $tmp_image;
            }
        }

        $checkUpdate = addEditDeal($ELEMENT_ID, $arLoadProductArray, array("INPUT_FILES" => $arFiles));
        // if($res = $el->Update($ELEMENT_ID, $arLoadProductArray)){
            //$checkUpdate = CIBlockElement::SetPropertyValueCode($ELEMENT_ID, "INPUT_FILES", $arFiles);


            if($checkUpdate){
                $db_props = CIBlockElement::GetProperty(10, $checkUpdate, "sort", "asc", array('CODE'=>'INPUT_FILES'));
                while ($obj=$db_props->GetNext()){
                    if(!empty($obj["VALUE"])){
                        $result[] = [
                            "URL" => CFile::GetPath($obj["VALUE"]),
                            "ID" => $obj["PROPERTY_VALUE_ID"],
                            "ID_FILE" => $obj["VALUE"]
                        ];
                    }
                }?>

                <div class="sp-slides">
                    <?// изображения
                    foreach ($result as $arImg):?>
                        <?if(!empty($arImg['URL'])):?>
                            <div class="sp-slide">
                                <img class="sp-image" src="<?=$arImg['URL']?>">
                                <span class="cardPact-box-edit-rem_img" data-id="<?=$arImg['ID_FILE']?>" data-sub-id="<?=$checkUpdate?>">-</span>
                            </div>
                        <?endif?>
                    <?endforeach?>
                </div>
                <div class="sp-thumbnails">
                    <?// изображения
                    foreach ($result as $arImg):?>
                        <?if(!empty($arImg['URL'])):?>
                            <img class="sp-thumbnail" src="<?=$arImg["URL"]?>">
                        <?endif?>
                    <?endforeach?>
                    <?if(count($result) < 20){?>
                        <img id="cardPact-box-edit-add_img" class="sp-thumbnail js-add_img" src="<?=SITE_TEMPLATE_PATH?>/image/add_img.png">
                    <?}?>
                </div>
                <?
                die();
            }
            else{
                echo 'ERROR';
                die();
            }
        // }
        // else{
        //     echo 'ERROR';
        //     die();
        // }
        break;
}
?>
