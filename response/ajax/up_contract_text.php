<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$el = new CIBlockElement;
$text = $_POST['contect'];
$idSdelka = $_POST['id'];

$resUser = CUser::GetByID($USER->GetID());
if($obj = $resUser->GetNext()) {
    $arUser = $obj;
}
else{
    die(json_encode(['VALUE' => "Пользователь не авторизован", 'TYPE' => 'ERROR']));
}

if(empty($text)){
    die(json_encode(['VALUE' => "Отсутствует текст договора", 'TYPE' => 'ERROR']));
}

if($_REQUEST['type']=='create_sdelka'){

    $arLoadProductArray = Array(
        "IBLOCK_ID"=> 4,
        "MODIFIED_BY"    => $arUser['ID'],
        "DETAIL_TEXT_TYPE" =>"html",
        "DETAIL_TEXT" => html_entity_decode($text),
        "ACTIVE" => "Y",
        "PROPERTY_VALUES"=> array(
            "USER_A"=>$arUser['ID'],
            "COMPANY_A"=>$arUser['UF_CUR_COMPANY']
        )
    );
    //записываем в кэш
    $cacheName = md5($arUser['ID'].'_'.rand(1, 100000));
    $cache = \Bitrix\Main\Data\Cache::createInstance();
    $cacheInitDir = 'dogovor_create_sdelka';

    if (!$cache->initCache(600, $cacheName, $cacheInitDir)){
        $cache->startDataCache();
        $cache->endDataCache($arLoadProductArray);
    }

    if ($cache->initCache(600, $cacheName, $cacheInitDir)){
        echo json_encode(['VALUE' => $cacheName, 'TYPE' => 'SUCCESS']);
    }
    else{
        echo json_encode(['VALUE' => "Ошибка сохранения", 'TYPE' => 'ERROR']);
    }
}
else{
    #получение данных по сделке
    $res = CIBlockElement::GetByID($idSdelka);
    if($obj = $res->GetNext(true, false)) $arSdelka = $obj;

    $arLoadProductArray = Array(
        "IBLOCK_ID"=> 4,
        "MODIFIED_BY"    => $arUser['ID'],
        "NAME"=>$arSdelka['NAME'],
        "DETAIL_TEXT_TYPE" =>"html",
        "DETAIL_TEXT" => html_entity_decode($text),
        "ACTIVE" => "Y",
        "PROPERTY_VALUES"=> array(
            "USER_A"=>$arUser['ID'],
            "COMPANY_A"=>$arUser['UF_CUR_COMPANY']
        )
    );


    if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
        $prop = array(
            "ID_DOGOVORA"=>$PRODUCT_ID
        );

        CIBlockElement::SetPropertyValuesEx($arSdelka['ID'], '3', $prop);

        echo json_encode(['VALUE' => "Новый договор: ".$PRODUCT_ID, 'ID'=>$arSdelka['ID'], 'TYPE' => 'SUCCESS']);
    }
    else{
        echo json_encode([ 'VALUE'=>$el->LAST_ERROR, 'TYPE'=> 'ERROR']);
        die();
    }
}

?>