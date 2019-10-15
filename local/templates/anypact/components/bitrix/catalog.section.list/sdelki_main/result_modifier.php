<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$rsSections = CIBlockSection::GetList(
    array("CODE"=>"DESC"),
    array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "DEPTH_LEVEL"=>1
    ),
    false,
    array("ID", "UF_*")
);
while ($obj = $rsSections->GetNext(true, false)){
    $id = $obj['ID'];
    unset($obj['ID']);
    $arResult['UF_FIELDS'][$id ] = $obj;
}
?>