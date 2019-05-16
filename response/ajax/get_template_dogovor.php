<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

/*$IBLOCK_ID    = 5;
$arFilter    = Array(
      'IBLOCK_ID'=>$IBLOCK_ID, 
      'GLOBAL_ACTIVE'=>'Y');
$obSection    = CIBlockSection::GetTreeList($arFilter);

$arr_sections = array();

while($arResult = $obSection->GetNext()){       
    $arr_sections[] = $arResult['NAME'];
}*/

$items = GetIBlockSectionList(5, 0, Array("sort"=>"asc"), 10);
while($arResult = $items->GetNext()){       
    $arr_sections[] = $arResult['NAME'];
}
echo json_encode($arr_sections);
?>