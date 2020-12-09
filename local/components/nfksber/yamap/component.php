<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("iblock")) return;

$arResult['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
$arResult['MAP_WIDTH'] = $arParams['MAP_WIDTH'];
$arResult['MAP_HEIGHT'] = $arParams['MAP_HEIGHT'];

function getCoordinate($str){
    $ar = explode(',', $str);
    $arCordinate['x1'] = $ar[0];
    $arCordinate['y1'] = $ar[1];
    $arCordinate['x3'] = $ar[2];
    $arCordinate['y3'] = $ar[3];
    /*$arCordinate['x2'] = $arCordinate['x1'];
    $arCordinate['y2'] = $arCordinate['y3'];
    $arCordinate['x4'] = $arCordinate['x3'];
    $arCordinate['y4'] = $arCordinate['y1'];*/
    return $arCordinate;
}

$arCordinate = getCoordinate("");

global $USER;

$arSelect = [
    'IBLOCK_ID',
    'ID',
    'NAME',
    'DETAIL_PAGE_URL'
];
$arFilter = [
    'IBLOCK_ID'=>$iblockID,
    'ACTIVE'=>'Y',
    //'!COORDINATES_AD'=>false,
    array(
        "LOGIC" => "AND",
        array(">=PROPERTY_LONG" => $arCordinate['x1'], "<=PROPERTY_LONG" => $arCordinate['x3']),
        array(">=PROPERTY_LAT" => $arCordinate['y1'], "<=PROPERTY_LAT" => $arCordinate['y3']),
    ),
    "PROPERTY_MODERATION_VALUE" => 'Y',
	array(
		'LOGIC' => 'OR',
		array("!=PROPERTY_PRIVATE_VALUE" => "Y"),
		array(
			"PROPERTY_PRIVATE_VALUE" => "Y",
			"=PROPERTY_ACCESS_USER" => empty( $USER -> GetID() ) ? 0 : $USER -> GetID()
		),
		array(
			"PROPERTY_PRIVATE_VALUE" => "Y",
			"=CREATED_BY" => empty( $USER -> GetID() ) ? 0 : $USER -> GetID()
		),
    ),
    array(
        "LOGIC" => "OR",
        array("PROPERTY_INDEFINITELY" => 18),
        array(">=DATE_ACTIVE_TO" => new \Bitrix\Main\Type\DateTime())
    )
];

$arItems = [];

$items = CIBlockElement::GetList(['SORT'=>'ASC'], $arFilter, false, false, $arSelect);

$i=0;
while($obj = $items->GetNextElement()){
    $arItems[$i]['FIELDS'] = $obj->GetFields();
    $arItems[$i]['PROPERTY']['COORDINATES_AD'] = $obj->GetProperty('COORDINATES_AD');
    $i++;
}

$cnt = 0;
if($arItems){
    foreach($arItems as $value) {
        $coordinates = explode(',', $value['PROPERTY']['COORDINATES_AD']['VALUE']);
        $result[] = array(
            "geo" => array((double) $coordinates[0],(double) $coordinates[1]),
            'balloonContent' => '<div class="baloon-content"><a href="'.$value['FIELDS']['DETAIL_PAGE_URL'].'">'.$value['FIELDS']['NAME'].'</a></div>',
        );

        $cnt++;
    }
}

$arResult['POINTS'] = $result;

$this->IncludeComponentTemplate();


?>