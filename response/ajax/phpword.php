<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once 'class/getdocument.php';

global $USER;
$paramsCode = Array(
    "max_len" => "30", // обрезает символьный код до 100 символов
    "change_case" => "L", // буквы преобразуются к нижнему регистру
    "replace_space" => "_", // меняем пробелы на нижнее подчеркивание
    "replace_other" => "_", // меняем левые символы на нижнее подчеркивание
    "delete_repeat_replace" => "true", // удаляем повторяющиеся нижние подчеркивания
    "use_google" => "false", // отключаем использование google
);

$DOCX = new getdocument();
// загрузка файла на сервер
//загрузка файла
if( isset( $_GET['uploadfiles'] ) ){
    $error = false;
    $files = array();

    $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/upload/tmp/';

    //формируем масив
    foreach ($_FILES['file'] as $key=>$fields){
        $cntFile = count($fields);
        for ($i=0; $i<$cntFile; $i++){
            //ограничение на размер файла
            if($key=='size'){
                if($fields[$i]>1000000){
                    $arResult[] = [
                        'FORMAT'=>'ERROR',
                        'CONTENT'=>'Размер файла не должен привышать 1мб',
                    ];
                    echo json_encode($arResult);
                    die();
                }
            }

            if($key=='type'){
                $arFormat[$fields[$i]] = $fields[$i];
            }
            $arFiles[$i][$key] = $fields[$i];
        }
    }

    //проверка форматов при загрузке нескольких файлов
    if(count($arFormat)>=2){
        unset($arFormat['image/png'], $arFormat['image/jpeg']);
        if(count($arFormat) !=0){
            $arResult[] = [
                'FORMAT'=>'ERROR',
                'CONTENT'=>'Файл текстового формата должен быть загружен только один',
            ];
            echo json_encode($arResult);
            die();
        }
    }

    // переместим файлы из временной директории в указанную
    foreach( $arFiles as $file ){
        $temp_name_file = $DOCX->getExtension($file['name']);

        $temp_name_file_full = CUtil::translit($DOCX->getFileName($file['name']), "ru" , $paramsCode).'.'.$temp_name_file;
        $temp_name_file = 'tempfile'.'_'.$USER->GetLogin().'_'. $temp_name_file_full;

        if(move_uploaded_file( $file['tmp_name'], $uploaddir . basename($temp_name_file) ) ){
            $UrlDocFile[] = realpath( $uploaddir . $temp_name_file );
            $UrlDocFile2[] = '/upload/tmp/' . $temp_name_file;
        }
        else{
            $error = true;
        }
    }
    $data = $error ? array('error' => 'Ошибка загрузки файлов.') : array('files' => $files );
}

// Загрузка содержимого документа
foreach ($UrlDocFile as $key=>$url){
    $ext_file = $DOCX->getExtension($url);
    switch ($ext_file) {
        case 'docx':
            $content = $DOCX->readDOCX2($url);
            $arResult[] = [
                'FORMAT'=>$ext_file,
                'CONTENT'=>$content,
            ];
            break;
        case 'txt':
            $content = $DOCX->readFileTXT($url);
            $arResult[] = [
                'FORMAT'=>$ext_file,
                'CONTENT'=>$content,
            ];
            break;
        case 'rtf':
            $content = $DOCX->readFileRTF2($url);
            $arResult[] = [
                'FORMAT'=>$ext_file,
                'CONTENT'=>$content,
            ];
            break;
        case 'png':
            $content = $DOCX->getImg($UrlDocFile2[$key]);
            $arResult[] = [
                'FORMAT'=>$ext_file,
                'CONTENT'=>$content,
            ];
            break;
        case 'jpg':
            $content = $DOCX->getImg($UrlDocFile2[$key]);
            $arResult[] = [
                'FORMAT'=>$ext_file,
                'CONTENT'=>$content,
            ];
            break;
        default:
            $content = 'Используйте один из слевующих фарматов: docx, txt, rtf, png, jpg';
            $ext_file = 'ERROR';
            $arResult[] = [
                'FORMAT'=>$ext_file,
                'CONTENT'=>$content,
            ];
            break;
    }
}

echo json_encode($arResult);


?>