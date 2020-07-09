<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

if(!CModule::IncludeModule("iblock"))
	return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(array("-"=>" "));

$arIBlocks=array();
$db_iblock = CIBlock::GetList(array("SORT"=>"ASC"), array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = "[".$arRes["ID"]."] ".$arRes["NAME"];

$arSites = Array("-" => "");
$rsSites = CSite::GetList($by="sort", $order="desc");
while ($arSite = $rsSites->GetNext())
	$arSites[$arSite["ID"]] = "[".$arSite["ID"]."] ".$arSite["NAME"];


$arComponentParameters = array(
	"PARAMETERS" => array(

		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "4",
			"REFRESH" => "Y",
		),

		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '={$_REQUEST["ID"]}',
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		),

		"SECTION_ID" => Array(
			"NAME" => GetMessage("IBLOCK_SECTION_ID"),
			"TYPE" => "TEXT",
			"DEFAULT"=>'',
			"PARENT" => "ADDITIONAL_SETTINGS",
		),

		"ELEMENT_ID" => Array(
			"NAME" => GetMessage("IBLOCK_ELEMENT_ID"),
			"TYPE" => "TEXT",
			"DEFAULT"=>'',
			"PARENT" => "ADDITIONAL_SETTINGS",
		),

		"SITE_ID" => Array(
			"NAME"=>GetMessage("BREADCRUMB_SITE_ID"),
			"TYPE" => "LIST",
			"DEFAULT"=>'-',
			"VALUES" => $arSites,
			"ADDITIONAL_VALUES" => "N",
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
	)
);
?>
