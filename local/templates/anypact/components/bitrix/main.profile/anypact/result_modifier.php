<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

// фото пользователя
$url_img = CFile::GetPath($arResult['arUser']['PERSONAL_PHOTO']);
$arResult['arUser']['IMG_URL'] = $url_img;

//проверка наличия компании
$res = CIBlockElement::GetList(
    ["SORT"=>"ASC"],
    [
        'IBLOCK_ID'=>8,
        'ACTIVE'=>'Y',
        [
            'LOGIC'=> 'OR',
            ['=PROPERTY_DIRECTOR_ID'=>$arResult['arUser']['ID']],
            ['=PROPERTY_STAFF'=>$arResult['arUser']['ID']]
        ]

    ],
    false,
    false,
    ['ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_DIRECTOR_ID']);
while($arCompany = $res->GetNext(true, false)){
    $arResult['COMPANIES'][] = $arCompany;
}
?>