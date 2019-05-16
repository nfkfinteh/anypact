<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;
$postData = $_POST['main'];
$data = json_decode($postData, true);

foreach ($data as $key => $value){
    $data[$key] = htmlspecialcharsEx($value);
}

if (!\Bitrix\Main\Loader::includeModule('iblock')) die();
#проверка на аторизацию
if($USER->GetID() != $data['MODIFIED_BY']) die;
if (!$USER->IsAuthorized()) die();

#проверка доступа пользователя на создание объявления
if(!empty($data['MODIFIED_BY'])){
    $filter = [ 'ID' =>  intval($data['MODIFIED_BY']) ];
    $select = [
        'SELECT'=>['UF_ESIA_AUT'],
        'FIELDS'=>['ID']
    ];
    $resUsers = CUser::GetList($by="timestamp_x", $order="desc", $filter, $select);
    if($obj = $resUsers->GetNext()) {
        $arUser = $obj;
    }

    if($arUser['UF_ESIA_AUT'] != 1) die();
}

$arGroups = $USER->GetUserGroupArray();

#проверка на принадлежность пользователя к группам
if(in_array( 1, $arGroups) || in_array( 6, $arGroups)){
    $detailPicture = $_FILES[0];
    $dopPicture = $_FILES;
    unset($dopPicture[0]);

    if(count($dopPicture)>0){
        $data['PROPERTY_VALUES']['INPUT_FILES'] = $dopPicture;
    }

    $paramsCode = Array(
        "max_len" => "30", // обрезает символьный код до 100 символов
        "change_case" => "L", // буквы преобразуются к нижнему регистру
        "replace_space" => "_", // меняем пробелы на нижнее подчеркивание
        "replace_other" => "_", // меняем левые символы на нижнее подчеркивание
        "delete_repeat_replace" => "true", // удаляем повторяющиеся нижние подчеркивания
        "use_google" => "false", // отключаем использование google
    );
    $data['CODE'] = CUtil::translit($data['NAME'], "ru" , $paramsCode);

    //дополнительные свойства
    $data['PROPERTY_VALUES']['PACT_USER'] = $data['MODIFIED_BY'];

    $el = new CIBlockElement;

    $arLoadProductArray = Array(
        "MODIFIED_BY"    => $data['MODIFIED_BY'],
        "IBLOCK_SECTION_ID" => $data['IBLOCK_SECTION_ID'],
        "IBLOCK_ID"      => $data['IBLOCK_ID'],
        "PROPERTY_VALUES"=> $data['PROPERTY_VALUES'],
        "NAME"           => $data['NAME'],
        "CODE"           => $data['CODE'],
        "ACTIVE"         => $data['ACTIVE'],
        "DETAIL_TEXT"    => $data['DETAIL_TEXT'],
        "DETAIL_PICTURE" => $detailPicture,
        "PREVIEW_PICTURE" => $detailPicture,
        "DATE_ACTIVE_FROM" => ConvertTimeStamp(time(), "SHORT"),
        "DATE_ACTIVE_TO" => ConvertTimeStamp(strtotime($data['DATE_ACTIVE_TO']), "SHORT")
    );

    if($PRODUCT_ID = $el->Add($arLoadProductArray))
        echo "New ID: ".$PRODUCT_ID;
    else
        echo "Error: ".$el->LAST_ERROR;

}







require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
?>