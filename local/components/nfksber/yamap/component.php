<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("iblock")) return;

$arResult['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
$arResult['MAP_WIDTH'] = $arParams['MAP_WIDTH'];
$arResult['MAP_HEIGHT'] = $arParams['MAP_HEIGHT'];

$this->IncludeComponentTemplate();


?>