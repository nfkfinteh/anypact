<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("iblock")) return;

$arFilter = [
    'IBLOCK_ID'=>$arParams['IBLOCK_ID'],
    'ACTIVE'=>'Y',
    'PROPERTY_LOCATION_CITY'=>$arParams['LOCATION']
];

if ($this->StartResultCache(false, array($arFilter))) {
    if (defined('ERROR_404') && ERROR_404=='Y' && !defined('ADMIN_SECTION')){
        $this->abortResultCache();
    }


    //$items = GetIBlockElementList($arParams['IBLOCK_ID'], false, array("SORT"=>"ASC"), $arParams['COUNT_POINT'], $arFilter);
    $items = CIBlockElement::GetList(["SORT"=>"ASC"], $arFilter, false, ['nTopCount'=>$arParams['COUNT_POINT']], ['ID', 'IBLOCK_ID', '*']);

    $i=0;
    while($obj = $items->GetNextElement()){
        $arResult['ITEMS'][$i]['FIELDS'] = $obj->GetFields();
        $arResult['ITEMS'][$i]['PROPERTY'] = $obj->GetProperties();
        $i++;
    }

    $arResult['CENTER_MAP'] = $arResult['ITEMS'][0]['PROPERTY']['COORDINATES_AD']['VALUE'];

    $DOTS_ARR = array();
    $cnt = 0;
    foreach($arResult['ITEMS'] as $value) {
        $coordinates = explode(',', $value['PROPERTY']['COORDINATES_AD']['VALUE']);
        $DOTS_ARR[$cnt] = array(
            "type" => "Feature",
            "id" => $cnt,
            "geometry" => array(
                "type" => "Point",
                "coordinates" => array(
                    "0" => (double) $coordinates[0],
                    "1" => (double) $coordinates[1]
                )
            ),
            "properties" => array(
               //'balloonContent' => '<div class="baloon-content"><a href="'.$value['FIELDS']['DETAIL_PAGE_URL'].'">'.$value['FIELDS']['NAME'].'</a></div>',
               'balloonContent' => '<div class="baloon-content"><a href="/pacts/view_pact/?ELEMENT_ID='.$value['FIELDS']['ID'].'">'.$value['FIELDS']['NAME'].'</a></div>',
               
            )
        );
        $cnt++;
    }

    $arResult['MAP_DATA'] = $DOTS_ARR;

    $arResult['MAP_WIDTH'] = $arParams['MAP_WIDTH'];
    $arResult['MAP_HEIGHT'] = $arParams['MAP_HEIGHT'];

    $this->IncludeComponentTemplate();
}

?>