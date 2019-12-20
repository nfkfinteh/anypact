<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

// фото пользователя
$url_img = CFile::GetPath($arResult['arUser']['PERSONAL_PHOTO']);
$arResult['arUser']['IMG_URL'] = $url_img;
//print_r($arParams['ESIA_RESPONSE']);
//если в профиль пользователь заходит с ЕСИА, то нужно заполнить данными некоторые поля с ЕСИА
if(!empty($arParams['ESIA_RESPONSE']['user_docs']['elements'][0]['type'])){
    $arResult["arUser"]["UF_SPASSPORT"]     =  $arParams['ESIA_RESPONSE']['user_docs']['elements'][0]['series'];
    $arResult["arUser"]["UF_NPASSPORT"]     =  $arParams['ESIA_RESPONSE']['user_docs']['elements'][0]['number'];
    $arResult["arUser"]["UF_DATA_PASSPORT"] =  $arParams['ESIA_RESPONSE']['user_docs']['elements'][0]['issueDate'];
    $arResult["arUser"]["UF_KEM_VPASSPORT"] =  $arParams['ESIA_RESPONSE']['user_docs']['elements'][0]['issuedBy'];
    $arResult["arUser"]["LAST_NAME"]        =  $arParams['ESIA_RESPONSE']['user_info']['lastName'];
    $arResult["arUser"]["NAME"]             =  $arParams['ESIA_RESPONSE']['user_info']['firstName'];
    $arResult["arUser"]["SECOND_NAME"]      =  $arParams['ESIA_RESPONSE']['user_info']['middleName'];

}
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
    ['ID', 'IBLOCK_ID', 'NAME']);
while($arCompany = $res->GetNext(true, false)){
    $arResult['COMPANIES'][] = $arCompany;
}
?>