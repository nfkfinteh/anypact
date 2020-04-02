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
?>