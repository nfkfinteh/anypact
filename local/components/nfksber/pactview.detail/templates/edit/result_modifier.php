<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arResult['JS_DATA']['IMG_FILE'][$arResult['ELEMENT']['DETAIL_PICTURE']] = CFile::GetPath($arResult['ELEMENT']['DETAIL_PICTURE']);
foreach ($arResult["PROPERTY"]["IMG_FILE"] as $prop){
    $arResult['JS_DATA']['IMG_FILE'][$prop['PROPERTY']['PROPERTY_VALUE_ID']] = $prop['URL'];
}
?>