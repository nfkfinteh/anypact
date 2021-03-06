<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arResult['USERS'] = [];
#поиск пользователей
$arFilter['NAME'] = $arResult['REQUEST']['QUERY'];
$arFilter['UF_HIDE_PROFILE'] = 0;
$arFilter['!ID'] = $USER->GetID();
$res = CUser::GetList($by="personal_country", $order="desc", $arFilter);
$res->NavStart($arNavParams['nPageSize']);
while($obj = $res->NavNext(true)) {
    $arUser[] = $obj;
}
$arResult['USERS'] = $arUser;

$arResult["USER_NAV_STRING"] = $res->GetPageNavStringEx(
    $navComponentObject,
    '',
    $arParams["PAGER_TEMPLATE"],
    false
);


if(strlen($arResult["REQUEST"]["~QUERY"]) && is_object($arResult["NAV_RESULT"]))
{
	$arResult["FILTER_MD5"] = $arResult["NAV_RESULT"]->GetFilterMD5();
	$obSearchSuggest = new CSearchSuggest($arResult["FILTER_MD5"], $arResult["REQUEST"]["~QUERY"]);
	$obSearchSuggest->SetResultCount($arResult["NAV_RESULT"]->NavRecordCount);
}

if(!empty($arResult["SEARCH"])){

    foreach($arResult["SEARCH"] as $value){
        $arIds[] = $value['ITEM_ID'];
    }
    $rsEl = CIBlockElement::GetList(array(), array("ID" => $arIds, "IBLOCK_ID" => 3, "!PROPERTY_PRIVATE" => 10), false, false, array("ID", "IBLOCK_ID", "PROPERTY_INPUT_FILES"));
    while($arEl = $rsEl -> GetNextElement()){
        $arFields = $arEl->GetFields();
        $arProps = $arEl->GetProperties();
        $arEls[$arFields['ID']]['ID'] = $arFields['ID'];
        $arEls[$arFields['ID']]['URL_IMG_PREVIEW'] = CFile::ResizeImageGet($arProps['INPUT_FILES']['VALUE'][0], ['width'=>500, 'height'=>500], BX_RESIZE_IMAGE_PROPORTIONAL )['src'];
        $arElIds[] = $arFields['ID'];
    }

    if(!empty($arEls)){
        foreach($arResult["SEARCH"] as $key => $value){
            if(in_array($value['ITEM_ID'], $arElIds)){
                $arResult["SEARCH"][$key]['URL_IMG_PREVIEW'] = $arEls[$value['ITEM_ID']]['URL_IMG_PREVIEW'];
            }else{
                unset($arResult["SEARCH"][$key]);
            }
        }
    }else{
        $arResult["SEARCH"] = array();
    }

}
?>