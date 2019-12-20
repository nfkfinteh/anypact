<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$el = new CIBlockElement;
$arUrl = $_POST['contect'];
$idSdelka = $_POST['id'];

foreach ($arUrl as $url){
    $arImg[] = CFile::MakeFileArray($url);
}




#получение данных по сделке
$res = CIBlockElement::GetByID($idSdelka);
if($obj = $res->GetNext(true, false)) $arSdelka = $obj;

$resUser = CUser::GetByID($USER->GetID());
if($obj = $resUser->GetNext()) $arUser = $obj;

$arLoadProductArray = Array(
    "IBLOCK_ID"=> 4,
    "MODIFIED_BY"    => $arUser['ID'],
    "NAME"=>$arSdelka['NAME'],
    "ACTIVE" => "Y",
    "PROPERTY_VALUES"=> array(
        "USER_A"=>$arUser['ID'],
        "DOGOVOR_IMG"=>$arImg,
        "COMPANY_A"=>$arUser['UF_CUR_COMPANY']
    )
);


if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
    $prop = array(
        "ID_DOGOVORA"=>$PRODUCT_ID
    );

    CIBlockElement::SetPropertyValuesEx($arSdelka['ID'], '3', $prop);

    echo json_encode(['VALUE' => "Новый договор: ".$PRODUCT_ID, 'ID'=>$arSdelka['ID'], 'TYPE' => 'SUCCESS']);
}
else{
    echo json_encode([ 'VALUE'=>$el->LAST_ERROR, 'TYPE'=> 'ERROR']);
    die();
}

?>