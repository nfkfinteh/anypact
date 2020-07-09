<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $this
 */

if (!$this->InitComponentTemplate())
	return;

$template = &$this->GetTemplate();
$templatePath = $template->GetFile();
$templateFolder = $template->GetFolder();

//Params
$arParams["IBLOCK_ID"] = (isset($arParams["IBLOCK_ID"]) && intval($arParams["IBLOCK_ID"]) > 0 ? intval($arParams["IBLOCK_ID"]) : 0);
$arParams["SECTION_ID"] = (isset($arParams["SECTION_ID"]) && intval($arParams["SECTION_ID"]) > 0 ? intval($arParams["SECTION_ID"]) : 0);
$arParams["ELEMENT_ID"] = (isset($arParams["ELEMENT_ID"]) && intval($arParams["ELEMENT_ID"]) > 0 ? intval($arParams["ELEMENT_ID"]) : 0);
$arParams["SITE_ID"] = (isset($arParams["SITE_ID"]) && strlen($arParams["SITE_ID"]) == 2 ? htmlspecialcharsbx($arParams["SITE_ID"]) : false);

if(!empty($arParams["ELEMENT_ID"])){
	$res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ID" => $arParams["ELEMENT_ID"]), false, false, array("ID", "NAME", "IBLOCK_SECTION_ID"));
	if($ob = $res->GetNext())	{
		$arElement = array("NAME" => $ob['NAME'], "ID" => $ob['ID']);
		$section_id = $ob['IBLOCK_SECTION_ID'];
	}
}elseif(!empty($arParams["SECTION_ID"])){
	$section_id = $arParams['SECTION_ID'];
}

$arSection[] = array("NAME" => "Все предложения", "DEPTH_LEVEL" => 0, "ID" => 0);

$nav = CIBlockSection::GetNavChain(
	$arParams["IBLOCK_ID"],
	$section_id,
	array("ID", "NAME", "DEPTH_LEVEL")
);
while($section = $nav->GetNext()){
	$arSection[] = $section;
}

if($arElement)
	$arSection[] = $arElement;

$arResult['SECTION'] = $arSection;
$arResult['SECTION_ID'] = $arParams["SECTION_ID"];
$arResult['ELEMENT_ID'] = $arParams["ELEMENT_ID"];
$this->arResult = $arResult;
$this->includeComponentTemplate();