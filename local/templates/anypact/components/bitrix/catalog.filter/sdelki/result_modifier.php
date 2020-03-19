<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$res = CIBlockElement::GetList(
    [
       'PROPERTY_SUMM_PACT'=>'ASC'
    ],
    [
        'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
        'ACTIVE'=>'Y',
        'ACTIVE_DATE'=>'Y'
    ],
    false,
    ['nTopCount'=>1],
    [
        'IBLOCK_ID',
        'ID',
        'PROPERTY_SUMM_PACT'
    ]
);

if($obj = $res->GetNext(true, false)){
    $arMinPrice = $obj['PROPERTY_SUMM_PACT_VALUE'];
}

$res = CIBlockElement::GetList(
    [
        'PROPERTY_SUMM_PACT'=>'DESC'
    ],
    [
        'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
        'ACTIVE'=>'Y',
        'ACTIVE_DATE'=>'Y'
    ],
    false,
    ['nTopCount'=>1],
    [
        'IBLOCK_ID',
        'ID',
        'PROPERTY_SUMM_PACT'
    ]
);

if($obj = $res->GetNext(true, false)){
    $arMaxPrice = $obj['PROPERTY_SUMM_PACT_VALUE'];
}

//мин и макс цена с учетом фильтра
$arResult['arrPrice'] = [
    'LEFT'=>$arMinPrice,
    'RIGHT'=>$arMaxPrice
];

$arResult['JS_FILTER_PARAMS']['PRICE_BORDERS'] = $arResult['arrPrice'];

//определяем выводимыые цены в фильтре
if(!empty($arResult['ITEMS']['PROPERTY_14']['INPUT_VALUE']['LEFT'])){
    $arResult['JS_FILTER_PARAMS']['PRICE']['LEFT'] = $arResult['ITEMS']['PROPERTY_14']['INPUT_VALUE']['LEFT'];
}
else{
    $arResult['JS_FILTER_PARAMS']['PRICE']['LEFT'] = $arResult['arrPrice']['LEFT'];
}

if(!empty($arResult['ITEMS']['PROPERTY_14']['INPUT_VALUE']['RIGHT'])){
    $arResult['JS_FILTER_PARAMS']['PRICE']['RIGHT'] = $arResult['ITEMS']['PROPERTY_14']['INPUT_VALUE']['RIGHT'];
}
else{
    $arResult['JS_FILTER_PARAMS']['PRICE']['RIGHT'] = $arResult['arrPrice']['RIGHT'];
}

//Список городов
$arFilter = [
    'IBLOCK_ID'=>7,
    'ACTIVE'=>'Y',
];
$arSelect = [
    'IBLOCK_ID',
    'ID',
    'NAME'
];
$res = CIBlockElement::GetList(['NAME'=>'ASC'], $arFilter, false, false, $arSelect);
while ($obj = $res->GetNext(true, false)){
    $arResult['LIST_CITY'][] = $obj['NAME'];
}