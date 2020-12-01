<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if(!CModule::IncludeModule('iblock')){
    echo json_encode(['VALUE'=>'', 'TYPE'=>'ERROR']);
    die();
}

$bbox = $_REQUEST['bbox'];
$callback = $_REQUEST['callback'];
$iblockID = $_REQUEST['iblock'];
$service = $_REQUEST['parent'];

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

$arCordinate = getCoordinate($bbox);


$arSelect = [
    'IBLOCK_ID',
    'ID',
    'NAME',
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
];

$result = [
    "type"=>"FeatureCollection",
    "features"=>[]
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
        $result["features"][] = array(
            "type" => "Feature",
            "id" => $value['FIELDS']['ID'],
            "geometry" => array(
                "type" => "Point",
                "coordinates" => array(
                    "0" => (double) $coordinates[0],
                    "1" => (double) $coordinates[1]
                )
            ),
            "properties" => array(
                //'balloonContent' => '<div class="baloon-content"><a href="'.$value['FIELDS']['DETAIL_PAGE_URL'].'">'.$value['FIELDS']['NAME'].'</a></div>',
                'balloonContent' => '<div class="baloon-content"><a href="'.$value['FIELDS']['DETAIL_PAGE_URL'].'">'.$value['FIELDS']['NAME'].'</a></div>',

            )
        );

        $cnt++;
    }
}

$jsData = json_encode($result);
$jsData = $callback.'('.$jsData.')';
echo $jsData;