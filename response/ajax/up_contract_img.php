<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$el = new CIBlockElement;
$arUrl = $_POST['contect'];
$idSdelka = $_POST['id'];
$error = false;

foreach ($arUrl as $url){
    $arImg[] = CFile::MakeFileArray($url);
}

$resUser = CUser::GetByID($USER->GetID());
if($obj = $resUser->GetNext()) {
    $arUser = $obj;
}
else{
    die(json_encode(['VALUE' => "Пользователь не авторизован", 'TYPE' => 'ERROR']));
}

if(empty($arImg)){
    die(json_encode(['VALUE' => "Отсутствует текст договора", 'TYPE' => 'ERROR']));
}

if($_REQUEST['type']=='create_sdelka') {
    //договор еще без созданной сделки
    $url_root = $_SERVER['DOCUMENT_ROOT'].'/upload/tmp/dogovor_create_sdelka_img/';
    if (!file_exists($url_root)) {
        mkdir($url_root, 0777, true);
    }

    $arParamsTranslit = array(
        "max_len" => "30", // обрезает символьный код до 100 символов
        "change_case" => "L", // буквы преобразуются к нижнему регистру
        "replace_space" => "_", // меняем пробелы на нижнее подчеркивание
        "replace_other" => "_", // меняем левые символы на нижнее подчеркивание
        "delete_repeat_replace" => "true", // удаляем повторяющиеся нижние подчеркивания
        "use_google" => "false", // отключаем использование google
    );
    foreach( $arImg as $file ){
        $arDataFile = explode('.', $file['name']);

        $fileNameTranslit = Cutil::translit($arDataFile[0],"ru", $arParamsTranslit);
        $fileNameTranslit = $fileNameTranslit.'.'.$arDataFile[1];


        if( rename( $file['tmp_name'],  $url_root . $fileNameTranslit) ){
            $files[] = realpath( $url_root . $fileNameTranslit);
        }
        else{
            $error = true;
        }
    }

    if($error){
        die(json_encode(['VALUE' => "Ошибка сохранения изображения", 'TYPE' => 'ERROR']));
    }


    $arLoadProductArray = Array(
        "IBLOCK_ID" => 4,
        "MODIFIED_BY" => $arUser['ID'],
        "ACTIVE" => "Y",
        "PROPERTY_VALUES" => array(
            "USER_A" => $arUser['ID'],
            "DOGOVOR_IMG" => $files,
            "COMPANY_A" => $arUser['UF_CUR_COMPANY']
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
    if ($obj = $res->GetNext(true, false)) $arSdelka = $obj;


    $arLoadProductArray = Array(
        "IBLOCK_ID" => 4,
        "MODIFIED_BY" => $arUser['ID'],
        "NAME" => $arSdelka['NAME'],
        "ACTIVE" => "Y",
        "PROPERTY_VALUES" => array(
            "USER_A" => $arUser['ID'],
            "DOGOVOR_IMG" => $arImg,
            "COMPANY_A" => $arUser['UF_CUR_COMPANY']
        )
    );


    if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
        $prop = array(
            "ID_DOGOVORA" => $PRODUCT_ID
        );

        CIBlockElement::SetPropertyValuesEx($arSdelka['ID'], '3', $prop);

        echo json_encode(['VALUE' => "Новый договор: " . $PRODUCT_ID, 'ID' => $arSdelka['ID'], 'TYPE' => 'SUCCESS']);
    } else {
        echo json_encode(['VALUE' => $el->LAST_ERROR, 'TYPE' => 'ERROR']);
        die();
    }
}

?>