<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("iblock")) return;

$arResult['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
$arResult['MAP_WIDTH'] = $arParams['MAP_WIDTH'];
$arResult['MAP_HEIGHT'] = $arParams['MAP_HEIGHT'];

if($_POST['via_ajax'] == "Y" && check_bitrix_sessid() && $_POST[$arParams['ACTION_VARIABLE']] == "loadPoints"){
    if($_REQUEST['COORDINATES']){
        $arCordinate = $_REQUEST['COORDINATES'];
        $arrFilter = array(array(
            "LOGIC" => "AND",
            array(">=PROPERTY_LONG" => $arCordinate[0][0], "<=PROPERTY_LONG" => $arCordinate[1][0]),
            array(">=PROPERTY_LAT" => $arCordinate[0][1], "<=PROPERTY_LAT" => $arCordinate[1][1]),
        ));
    }

    global $USER;

    $userID = $USER->GetID();

    if($this->startResultCache(false, array($arrFilter, $userID)))
    {
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
            "PROPERTY_MODERATION_VALUE" => 'Y',
            array(
                'LOGIC' => 'OR',
                array("!=PROPERTY_PRIVATE_VALUE" => "Y"),
                array(
                    "PROPERTY_PRIVATE_VALUE" => "Y",
                    "=PROPERTY_ACCESS_USER" => empty( $userID ) ? 0 : $userID
                ),
                array(
                    "PROPERTY_PRIVATE_VALUE" => "Y",
                    "=CREATED_BY" => empty( $userID ) ? 0 : $userID
                ),
            ),
            array(
                "LOGIC" => "OR",
                array("PROPERTY_INDEFINITELY" => 18),
                array(">=DATE_ACTIVE_TO" => new \Bitrix\Main\Type\DateTime())
            )
        ];

        if(!empty($arrFilter))
            $arFilter = array_merge($arFilter, $arrFilter);

        $items = CIBlockElement::GetList(['SORT'=>'ASC'], $arFilter, false, false, $arSelect);

        while($obj = $items->GetNextElement()){
            $arItems[] = array('FIELDS' => $obj->GetFields(), 'PROPERTY' => array('COORDINATES_AD' => $obj->GetProperty('COORDINATES_AD')));
        }

        if($arItems){
            foreach($arItems as $value) {
                if(!empty($value['PROPERTY']['COORDINATES_AD']['VALUE'])){
                    $coordinates = explode(',', $value['PROPERTY']['COORDINATES_AD']['VALUE']);
                    $result[] = array(
                        "id" => $value['FIELDS']['ID'],
                        "geo" => array((double) $coordinates[0],(double) $coordinates[1]),
                        'balloonContent' => '<div class="baloon-content"><a href="'.$value['FIELDS']['DETAIL_PAGE_URL'].'">'.$value['FIELDS']['NAME'].'</a></div>'
                    );
                }
            }
        }
        $arResult['POINTS'] = $result;
    }
    echo json_encode($arResult['POINTS']);
}else{
    $this->IncludeComponentTemplate();
}


?>