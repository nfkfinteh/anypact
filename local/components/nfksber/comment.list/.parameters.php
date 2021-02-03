<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var Array $arCurrentValues */

if (!CModule::IncludeModule("iblock"))
	return;
$arIBlockType = CIBlockParameters::GetIBlockTypes();
$arIBlock = array();
$iblockFilter = (
!empty($arCurrentValues['IBLOCK_TYPE'])
    ? array('TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y')
    : array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch())
{
    $id = (int)$arr['ID'];
    $arIBlock[$id] = '['.$id.'] '.$arr['NAME'];
}
unset($id, $arr, $rsIBlock, $iblockFilter);

$arComponentParameters = Array(
	"PARAMETERS" => Array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("ALLOK_COMMENT_IBLOCK_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("ALLOK_COMMENT_IBLOCK_ID"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
            "REFRESH" => "Y",
        ),
		'ELEMENT_ID' => Array(
			'NAME' => GetMessage("ALLOK_COMMENT_ELEMENT_ID"),
			'TYPE' => 'TEXT',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'PARENT' => 'BASE',
		),
		"COUNT" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ALLOK_COMMENT_COUNT"),
			"TYPE" => "INT",
			"DEFAULT" => "10",
		),
		"ACTIVE_DATE_FORMAT" => CIBlockParameters::GetDateFormat(GetMessage("ALLOK_COMMENT_ACTIVE_DATE_FORMAT"), "BASE"),
		'LOAD_MARK' => Array(
			'NAME' => GetMessage("ALLOK_COMMENT_LOAD_MARK"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'BASE',
			"DEFAULT" => 'Y',
			'ADDITIONAL_VALUES' => 'N',
		),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
	),
);
CIBlockParameters::AddPagerSettings($arComponentParameters, '', true, true);
?>