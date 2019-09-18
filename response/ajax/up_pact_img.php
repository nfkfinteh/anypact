<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$arData = json_decode($_POST['arr']);
$arFiles = $_FILES;
$el = new CIBlockElement;

switch ($arData->atrr_text) {
    case 'delete':
        $detailURL = $arData->detailUrl;
        $detailID = $arData->detailID;
        if(!empty($detailURL) && !empty($detailID)){
            $detailImg = CFile::MakeFileArray($detailURL);
            $deleteImg = $detailID;
        }
        else{
            $arImg = false;
            $detailImg = array('del' => 'Y');
        }

        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "DETAIL_PICTURE" =>$detailImg,
        );

        // код свойства
        $PRODUCT_ID = $arData->id_element;
        $res = $el->Update($PRODUCT_ID, $arLoadProductArray);

        if(!empty($deleteImg)){
            CIBlockElement::SetPropertyValueCode($PRODUCT_ID, "INPUT_FILES", array($deleteImg => array("MODULE_ID"=>"iblock", "del"=>"Y")));
        }
        echo json_encode([ 'VALUE'=>"обновили ".$_POST['atrr_text'], 'TYPE'=> 'SUCCES']);
        break;
    case 'add':

        // код свойства
        $PRODUCT_ID = $arData->id_element;

        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
        );

        $dbSdelka = CIBlockElement::GetByID($PRODUCT_ID);
        if($obj = $dbSdelka->GetNext()) $arElement = $obj;

        if(empty($arElement['DETAIL_PICTURE'])){
            $detailImg = array_shift($arFiles);
            $arLoadProductArray['DETAIL_PICTURE'] = $detailImg;
        }

        if($res = $el->Update($PRODUCT_ID, $arLoadProductArray)){
            CIBlockElement::SetPropertyValueCode($PRODUCT_ID, "INPUT_FILES", $arFiles);
            echo json_encode([ 'VALUE'=>$PRODUCT_ID, 'TYPE'=> 'SUCCES']);
        }
        else{
            echo json_encode([ 'VALUE'=>$el->LAST_ERROR, 'TYPE'=> 'ERROR']);
            die();
        }

        break;
}

?>