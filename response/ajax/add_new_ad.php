<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/local/class/CFormatHTMLText.php");
global $USER;
$postData = $_POST['main'];
$data = json_decode($postData, true);

foreach ($data as $key => $value){
    if($key=='DETAIL_TEXT') continue;
    $data[$key] = htmlspecialcharsEx($value);
}

if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    echo json_encode([ 'VALUE'=>'Не подключен модуль инфоблоки', 'TYPE'=> 'ERROR']);
    die();
}
#проверка на аторизацию
/*
if($USER->GetID() != $data['MODIFIED_BY']){
    echo json_encode([ 'VALUE'=>'Вы не авторизваны', 'TYPE'=> 'ERROR']);
    die;
}
*/
if (!$USER->IsAuthorized()){
    echo json_encode([ 'VALUE'=>'Вы не авторизваны', 'TYPE'=> 'ERROR']);
    die();
}

function generateStr($length = 8){
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $numChars = strlen($chars);
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return $string;
}

#проверка доступа пользователя на создание объявления не дает создавать объявления
/*
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

    if(!$arUser['UF_ESIA_AUT']){
        echo json_encode([ 'VALUE'=>'Нет доступа на создание объявлений', 'TYPE'=> 'ERROR']);
        die();
    }
}
*/

$arGroups = $USER->GetUserGroupArray();

#проверка на принадлежность пользователя к группам
if(in_array( 1, $arGroups) || in_array( 6, $arGroups)){
    /*$detailPicture = reset($_FILES);
    $keyDetailPicture = key($_FILES);*/
    $dopPicture = $_FILES;
    //unset($dopPicture[$keyDetailPicture]);

    if(count($dopPicture)>0){
        foreach($dopPicture as $key => $file){
            $image_src = $file['tmp_name'];
            $tmp_image = $_SERVER['DOCUMENT_ROOT'] . "/upload/image/" . $file['name'];
            $file_name = explode(".", $tmp_image);
            $tmp_image = "";
            foreach($file_name as $key => $value){
                if($key == (array_key_last($file_name) - 1)){
                    $value .= generateStr(5);
                }
                $tmp_image .= $value;
            }
            $resize_img = CFile::ResizeImageFile($image_src, $tmp_image, array('width'=>'730', 'height'=>'500'), BX_RESIZE_IMAGE_PROPORTIONAL, array(
                'type' => 'image',
                'size' => 'small',
                'position' => 'bottomright',
                'file' => $_SERVER['DOCUMENT_ROOT'] . '/local/templates/anypact/img/logo3.png'
            ));
            if($resize_img){
                $dopPicture[$key]['tmp_name'] = $tmp_image;
            }
        }
        $data['PROPERTY_VALUES']['INPUT_FILES'] = $dopPicture;
    }

    #данные по пользователю
    $resUser = CUser::GetByID($USER->GetID());
    if($obj = $resUser->GetNext()) $arUser = $obj;

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
    $data['PROPERTY_VALUES']['ID_COMPANY'] = $arUser['UF_CUR_COMPANY'];
    $data['PROPERTY_VALUES']['CONDITIONS_PACT'] = CFormatHTMLText::TextFormatting($data['PROPERTY_VALUES']['CONDITIONS_PACT']);

    $el = new CIBlockElement;
    $elDogovor = new CIBlockElement;

    $res = $el -> GetList(Array(), array("IBLOCK_ID" => 3, "CODE" => $data['CODE']), false, false, array("ID", "CODE"));
    if($ob = $res->GetNext())
    {
        $data['CODE'] = $data['CODE'] . '-' . rand(1000, 9999);
    }

    if($data['PROPERTY_VALUES'] == 18){
        $time = "";
    }else{
        if(empty($data['DATE_ACTIVE_TO'])){
            $time = ConvertTimeStamp(time()+(86400*10), "SHORT");
        }
        else{
            $time = ConvertTimeStamp(MakeTimeStamp($data['DATE_ACTIVE_TO'], "DD.MM.YYYY"), "SHORT");
        }
    }

    $sort = 500;

    $list = CIBlockSection::GetNavChain(3, $data['IBLOCK_SECTION_ID'], array("ID"), true);
    foreach ($list as $arSectionPath){
        if($arSectionPath['ID'] == 62){
            $sort = 10000;
            break;
        }
    }

    $arLoadProductArray = Array(
        "MODIFIED_BY"    => $data['MODIFIED_BY'],
        "IBLOCK_SECTION_ID" => $data['IBLOCK_SECTION_ID'],
        "SORT"           => $sort,
        "IBLOCK_ID"      => $data['IBLOCK_ID'],
        "PROPERTY_VALUES"=> $data['PROPERTY_VALUES'],
        "NAME"           => $data['NAME'],
        "CODE"           => $data['CODE'],
        "ACTIVE"         => $data['ACTIVE'],
        "DETAIL_TEXT"    => CFormatHTMLText::TextFormatting($data['DETAIL_TEXT']['TEXT']),
        "DETAIL_TEXT_TYPE" => $data['DETAIL_TEXT']['TYPE'],
        /*"DETAIL_PICTURE" => $detailPicture,
        "PREVIEW_PICTURE" => $detailPicture,*/
        "DATE_ACTIVE_FROM" => ConvertTimeStamp(time(), "SHORT"),
        "DATE_ACTIVE_TO" => $time
    );

    if($PRODUCT_ID = $el->Add($arLoadProductArray)){

        if(!empty($data['DOGOVOR_KEY'])){
            $error_message = '';
            $cacheName = $data['DOGOVOR_KEY'];
            $cache = \Bitrix\Main\Data\Cache::createInstance();
            $cacheInitDir = 'dogovor_create_sdelka';
            if ($cache->initCache(600, $cacheName, $cacheInitDir)){
                $arLoadProductArray = $cache->getVars();
                $arLoadProductArray['NAME'] = $data['NAME'];
                if(!empty($arLoadProductArray['PROPERTY_VALUES']['DOGOVOR_IMG'])){
                    foreach ($arLoadProductArray['PROPERTY_VALUES']['DOGOVOR_IMG'] as &$img){
                        $img = CFile::MakeFileArray($img);
                    }
                }
                if($DOGOVOR_ID = $elDogovor->Add($arLoadProductArray)) {
                    $prop = array(
                        "ID_DOGOVORA"=>$DOGOVOR_ID
                    );

                    CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, '3', $prop);
                }
                else{
                    $error_message = 'Ошибка сохранения договора. '.$elDogovor->LAST_ERROR;
                }
            }
            else{
                $error_message = 'Ошибка сохранения договора. Договор не найден повторите попытку';
            }

        }

        $arResult = [ 'VALUE'=>$PRODUCT_ID, 'TYPE'=> 'SUCCES'];
        if(!empty($error_message)){
            $arResult['DOGOVOR'] = [
                'TYPE'=>'ERROR',
                'VALUE'=>$error_message
            ];
        }
        else{
            $arResult['DOGOVOR'] = [
                'TYPE'=>'SUCCESS',
                'VALUE'=>''
            ];
        }

        //отправка сообщения для модератора
        if($data['IBLOCK_ID'] && $PRODUCT_ID){
            $resIblockSleka = CIBlock::GetByID($data['IBLOCK_ID']);
            if($obj = $resIblockSleka->GetNext()) $typeIBlock = $obj['IBLOCK_TYPE_ID'];
            $send_data = Array(
                'URL_ADMIN_SDELKA' => 'https://'.SITE_SERVER_NAME.'/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$data['IBLOCK_ID'].'&type='.$typeIBlock.'&ID='.$PRODUCT_ID,
            );
            CEvent::Send("NEW_SDELKA", "s1", $send_data, 'N');
        }


        die(json_encode($arResult));
    }
    else{
        die(json_encode([ 'VALUE'=>$el->LAST_ERROR, 'TYPE'=> 'ERROR']));
    }
}
else{
    echo json_encode([ 'VALUE'=>'Вы не состоите в грппе пользователей разрешенным создавать объявленя', 'TYPE'=> 'ERROR']);
    die();
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
?>