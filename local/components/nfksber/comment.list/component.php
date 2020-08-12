<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

if (!CModule::IncludeModule("iblock")) return;
if (!CModule::IncludeModule("highloadblock")) return;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
$arParams["ELEMENT_ID"] = htmlspecialcharsEx($arParams["ELEMENT_ID"]);
$arParams["ELEMENT_CODE"] = htmlspecialcharsEx($arParams["ELEMENT_CODE"]);
$arParams["COUNT"] = intval($arParams["COUNT"]);
if ($arParams["COUNT"] <= 0)
	$arParams["COUNT"] = 5;
$arParams["ACTIVE_DATE_FORMAT"] = trim($arParams["ACTIVE_DATE_FORMAT"]);
if (strlen($arParams["ACTIVE_DATE_FORMAT"]) <= 0)
	$arParams["ACTIVE_DATE_FORMAT"] = $GLOBALS["DB"]->DateFormatToPHP(CSite::GetDateFormat('SHORT'));
$arParams["LOAD_MARK"] = ($arParams["LOAD_MARK"] == "Y");

$arParams["CACHE_TIME"] = IntVal($arParams["CACHE_TIME"]);

$arParams["PAGER_TEMPLATE"] = trim($arParams["PAGER_TEMPLATE"]);
$arParams["DISPLAY_TOP_PAGER"] = $arParams["DISPLAY_TOP_PAGER"] == "Y";
$arParams["DISPLAY_BOTTOM_PAGER"] = $arParams["DISPLAY_BOTTOM_PAGER"] == "Y";
$arParams["PAGER_TITLE"] = trim($arParams["PAGER_TITLE"]);
$arParams["PAGER_SHOW_ALWAYS"] = $arParams["PAGER_SHOW_ALWAYS"] == "Y";
$arParams["PAGER_DESC_NUMBERING"] = $arParams["PAGER_DESC_NUMBERING"] == "Y";
$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] = intval($arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]);
$arParams["PAGER_SHOW_ALL"] = $arParams["PAGER_SHOW_ALL"] == "Y";

if($arParams["DISPLAY_TOP_PAGER"] || $arParams["DISPLAY_BOTTOM_PAGER"])
{
    $arNavParams = array(
        "nPageSize" => $arParams["COUNT"],
        "bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
        "bShowAll" => $arParams["PAGER_SHOW_ALL"],
    );
    $arNavigation = CDBResult::GetNavParams($arNavParams);
    if($arNavigation["PAGEN"]==0 && $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]>0)
        $arParams["CACHE_TIME"] = $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];
}
else
{
    $arNavParams = array(
        "nTopCount" => $arParams["NEWS_COUNT"],
        "bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
    );
    $arNavigation = false;
}

$arResult['CURENT_USER'] = $this::GetAuthor($GLOBALS["USER"]->GetID());
$arResult['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
$arFilter = array();
if($arParams["ELEMENT_ID"] <= 0)
    $arParams["ELEMENT_ID"] = CIBlockFindTools::GetElementID(
    $arParams["ELEMENT_ID"],
    $arParams["ELEMENT_CODE"],
    $arParams["STRICT_SECTION_CHECK"]? $arParams["SECTION_ID"]: false,
    $arParams["STRICT_SECTION_CHECK"]? $arParams["~SECTION_CODE"]: false,
    $arFilter
);
$arResult['ID_SDELKA'] = $arParams['ELEMENT_ID'];
$arResult['USER_CREATE_SDELKA'] = $this::getAuthorSdelka($arResult['ID_SDELKA']);

$arResult['JS_DATA'] = [
    'CURENT_USER'=>$arResult['CURENT_USER'],
    'IBLOCK_ID'=>$arResult['IBLOCK_ID'],
    'ID_SDELKA'=>$arResult['ID_SDELKA']
];

/*if ($this->StartResultCache(false, array($arNavigation))) {
    if (defined('ERROR_404') && ERROR_404=='Y' && !defined('ADMIN_SECTION')){
        $this->abortResultCache();
    }*/

    $arResult["ITEMS"] = array();
    $arResult["ELEMENTS"] = array();

    $hlbl = $arParams["IBLOCK_ID"];
    $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "order" => array("ID" => "ASC"),
        "filter" => array("UF_ID_SLEKA"=>$arParams['ELEMENT_ID'], "UF_STATUS"=>1)
    ));

    while($arData = $rsData->Fetch()){
        $arData['USER'] = $this::GetAuthor($arData['UF_ID_USER']);
        $arData['UF_TIME_CREATE_MSG'] = CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arData["UF_TIME_CREATE_MSG"], CSite::GetDateFormat()));
        $arResult['ITEMS'][] = $arData;
    }

    $arResult['BLACKLIST'] = $this::getBlackList($arResult['USER_CREATE_SDELKA'], $arResult['CURENT_USER']['ID']);

 /*   $this->EndResultCache();
}*/
$arResult['EDIT_COMMENT'] = $this::getEditSdelka($_POST['EDIT_ID'], $arResult['IBLOCK_ID']);
$this->IncludeComponentTemplate();
?>