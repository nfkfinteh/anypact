<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
    return;

$arTypes = CIBlockParameters::GetIBlockTypes();

$arIBlocks=array();
$db_iblock = CIBlock::GetList(array("SORT"=>"ASC"), array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
    $arIBlocks[$arRes["ID"]] = "[".$arRes["ID"]."] ".$arRes["NAME"];

$arProperty_LNS = array();
$rsProp = CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>(isset($arCurrentValues["IBLOCK_ID"])?$arCurrentValues["IBLOCK_ID"]:$arCurrentValues["ID"])));
while ($arr=$rsProp->Fetch())
{
    $arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
    if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S")))
    {
        $arProperty_LNS[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
    }
}

$arComponentParameters = array(
    "GROUPS" => array(
    ),
    "PARAMETERS" => array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("T_IBLOCK_DESC_LIST_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => $arTypes,
            "DEFAULT" => "news",
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "DEFAULT" => '8',
            "ADDITIONAL_VALUES" => "Y",
            "REFRESH" => "Y",
        ),
        "PROPERTIES_SHOW" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("T_IBLOCK_PROPERTY"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arProperty_LNS,
            "ADDITIONAL_VALUES" => "Y",
        ),
        "PROPERTIES_NEED" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("PROPERTIES_NEED"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arProperty_LNS,
            "ADDITIONAL_VALUES" => "Y",
        ),
        "PROPERTIES_NUMBER" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("PROPERTIES_NUMBER"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arProperty_LNS,
            "ADDITIONAL_VALUES" => "Y",
        ),
    ),
);
