<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once 'class/getdocument.php';

global $USER;

$DOCX = new getdocument();

// загрузка файла на сервер
//загрузка файла
if( isset( $_GET['uploadfiles'] ) ){
    $error = false;
    $files = array();

    $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/upload/tmp/';

    // переместим файлы из временной директории в указанную
    foreach( $_FILES as $file ){
        $temp_name_file = $DOCX->getExtension($file['name']);

        $temp_name_file = 'tempfile'.'_'.$USER->GetLogin().'.'.$temp_name_file;

        if(move_uploaded_file( $file['tmp_name'], $uploaddir . basename($temp_name_file) ) ){
            $UrlDocFile = realpath( $uploaddir . $temp_name_file );
            $UrlDocFile2 = '/upload/tmp/' . $temp_name_file;
        }
        else{
            $error = true;
        }
    }
    $data = $error ? array('error' => 'Ошибка загрузки файлов.') : array('files' => $files );
}


// Загрузка содержимого документа

$ext_file = $DOCX->getExtension($UrlDocFile);

switch ($ext_file) {
    case 'docx':
        $content = $DOCX->readDOCX2($UrlDocFile);
        /*$content = str_replace('</w:r></w:p></w:tc><w:tc>', '', $content);
        $content = str_replace('</w:r></w:p>', '<p>', $content);
        $striped_content = strip_tags($content, '<p>');
        echo $striped_content;*/
        echo $content;
        break;
    case 'txt':
        $content = $DOCX->readFileTXT($UrlDocFile);
        echo $content;
        break;
    case 'rtf':
        $content = $DOCX->readFileRTF2($UrlDocFile);
        echo $content;
        break;
    case 'png':
        $content = $DOCX->getImg($UrlDocFile2);
        echo $content;
        break;
    case 'jpg':
        $content = $DOCX->getImg($UrlDocFile2);
        echo $content;
        break;
    default:
        echo 'Используйте один из слевующих фарматов: docx, txt, rtf, png, jpg';
        break;
}

?>