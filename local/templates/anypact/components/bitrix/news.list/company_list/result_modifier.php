<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
#получение компаний в которые добавили но пользователь еще не принял приглашение
$res = CIBlockElement::GetList(
    [],
    [
        'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
        'ACTIVE'=>'Y',
        'PROPERTY_STAFF_NO_ACTIVE'=>$arParams['USER_ID'],
        '!PROPERTY_DIRECTOR_ID'=>$arParams['USER_ID']
    ],
    false,
    false,
    ['IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_PICTURE']
);
$arResult['ITEMS_NO_ACTIVE'] = [];
while($obj=$res->GetNext(true ,false)){
    $arResult['ITEMS_NO_ACTIVE'][] = $obj;
}