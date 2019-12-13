<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');
$arData = json_decode($_POST['arr'], true);
$arFiles = $_FILES;
$el = new CIBlockElement;

switch ($arData['atrr_text']) {
    case 'delete':

        $ELEMENT_ID = intval($arData['id_element']);
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
        );
        $PROPERTY_CODE  = "INPUT_FILES";
        $FILE_ID = intval($arData['id_value']);
        $PROPERTY_VALUE = Array(
            "del" => "Y",
            "MODULE_ID" => "iblock"
        );
        $checkUpdate = CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, Array ($FILE_ID => ['VALUE'=>$PROPERTY_VALUE]) );
        if($checkUpdate){
            if(!$res = $el->Update($ELEMENT_ID, $arLoadProductArray)){
                echo 'ERROR';
                die();
            }
            $db_props = CIBlockElement::GetProperty(3, $ELEMENT_ID, "sort", "asc", array('CODE'=>'INPUT_FILES'));
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
                                <span class="cardPact-box-edit-rem_img" data-id="<?=$arImg['ID']?>">-</span>
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
                    <img id="cardPact-box-edit-add_img" class="sp-thumbnail js-add_img" src="<?=SITE_TEMPLATE_PATH?>/image/add_img.png">
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

        if($res = $el->Update($ELEMENT_ID, $arLoadProductArray)){
            $checkUpdate = CIBlockElement::SetPropertyValueCode($ELEMENT_ID, "INPUT_FILES", $arFiles);


            if($checkUpdate){
                $db_props = CIBlockElement::GetProperty(3, $ELEMENT_ID, "sort", "asc", array('CODE'=>'INPUT_FILES'));
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
                                <span class="cardPact-box-edit-rem_img" data-id="<?=$arImg['ID']?>">-</span>
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
                    <img id="cardPact-box-edit-add_img" class="sp-thumbnail js-add_img" src="<?=SITE_TEMPLATE_PATH?>/image/add_img.png">
                </div>
                <?
                die();
            }
            else{
                echo 'ERROR';
                die();
            }
        }
        else{
            echo 'ERROR';
            die();
        }
        break;
}
?>
