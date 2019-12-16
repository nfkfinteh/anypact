<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$arData = json_decode($_POST['arr'], true);
$arFiles = $_FILES;
$el = new CIBlockElement;
$arLoadProductArray = Array(
    "MODIFIED_BY"    => $USER->GetID(),
);
switch ($arData['atrr_text']) {
    case 'delete_incl_file':

        // Удаление свойства
        $ELEMENT_ID = $arData['id_element'];
        $PROPERTY_CODE  = "MAIN_FILES";
        $PROPERTY_VALUE = Array(
            "del" => "Y",
            "MODULE_ID" => "additionFiles"
        );
        $checkUpdate = CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, Array ($arData['id_value'] => $PROPERTY_VALUE) );
        if($checkUpdate){
            if(!$res = $el->Update($ELEMENT_ID, $arLoadProductArray)){
                echo 'ERROR';
                die();
            }
            $db_props = CIBlockElement::GetProperty(3, $ELEMENT_ID, "sort", "asc", array('CODE'=>$PROPERTY_CODE));
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
                    <button class="btn btn-nfk delete_unclude_file" style="float: left;width: 25%;" data="<?=$files['ID']?>" data-file ="<?=$files['ID']?>">Удалить</button>
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
        $checkUpdate = CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, $PROPERTY_VALUE);
        if($checkUpdate){
            if(!$res = $el->Update($ELEMENT_ID, $arLoadProductArray)){
                echo 'ERROR';
                die();
            }
            $db_props = CIBlockElement::GetProperty(3, $ELEMENT_ID, "sort", "asc", array('CODE'=>$PROPERTY_CODE));
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
                <button class="btn btn-nfk delete_unclude_file" style="float: left;width: 25%;" data="<?=$files['ID']?>" data-file ="<?=$files['ID']?>">Удалить</button>
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
            "DETAIL_TEXT" => html_entity_decode($arData['DETAIL_TEXT'])
            //"DETAIL_TEXT"    => $_POST['text']
        );
        $PROPERTY_CODE  = "MAIN_FILES";
        $PROPERTY_VALUE = $arFiles;

        if($el->Update($ELEMENT_ID, $arLoadProductArray)){
            CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, $arData['PROPERTY']);
            $checkUpdate = CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, $PROPERTY_VALUE);
            die(json_encode([ 'VALUE'=>'', 'TYPE'=> 'SUCCESS']));
        }
        else{
            die(json_encode([ 'VALUE'=>$el->LAST_ERROR, 'TYPE'=> 'ERROR']));
        }
        break;
}




