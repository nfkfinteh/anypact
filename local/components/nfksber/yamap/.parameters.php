<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

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

/*"VARIABLE_ALIASES" => array(
      "list" => array(),
      "section" => array(
                        "IBLOCK_ID" => "BID",
                        "SECTION_ID" => "ID"
                        ),
      "element" => array(
      "SECTION_ID" => "SID",
      "ELEMENT_ID" => "ID"
      ),
)*/
$arComponentParameters = Array(
    "PARAMETERS" => Array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => 'Тип инфоблока',
            "TYPE" => "LIST",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => 'Инфоблок',
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
            "REFRESH" => "Y",
        ),
        'MAP_WIDTH' => Array(
            'NAME' => 'Ширина карты',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'ADDITIONAL_VALUES' => 'N',
            'PARENT' => 'BASE',
        ),
        'MAP_HEIGHT' => Array(
            'NAME' => 'Высота карты',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'ADDITIONAL_VALUES' => 'N',
            'PARENT' => 'BASE',
        ),
        'LOCATION' => Array(
            'NAME' => 'Текущее местоположение',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'ADDITIONAL_VALUES' => 'N',
            'PARENT' => 'BASE',
        ),
        'COUNT_POINT' => Array(
            'NAME' => 'Количесвто элементов',
            'TYPE' => 'INT',
            'MULTIPLE' => 'N',
            'ADDITIONAL_VALUES' => 'N',
            'PARENT' => 'BASE',
        ),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
    )
);
?>