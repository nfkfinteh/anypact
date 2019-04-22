<?php
include_once 'class/getdocument.php';

$DOCX = new getdocument();

// загрузка файла на сервер
//загрузка файла
if( isset( $_GET['uploadfiles'] ) ){
    $error = false;
    $files = array();
 
    $uploaddir = '/var/www/domains/anypact.nfksber.ru/upload/tmp/'; 
    
    // переместим файлы из временной директории в указанную
    foreach( $_FILES as $file ){        
        $temp_name_file = $DOCX->getExtension($file['name']);
        $temp_name_file = 'tempfile'.'.'.$temp_name_file;
        
        if(move_uploaded_file( $file['tmp_name'], $uploaddir . basename($temp_name_file) ) ){            
            $UrlDocFile = realpath( $uploaddir . $temp_name_file );            
        }
        else{            
            $error = true;
        }
    }
    $data = $error ? array('error' => 'Ошибка загрузки файлов.') : array('files' => $files ); 
}


// Загрузка содержимого документа

$ext_file = $DOCX->getExtension($temp_name_file );
echo '<pre>'.$ext_file.'</pre>';
switch ($ext_file) {
    case 'docx':
        $content = $DOCX->readDOCX($UrlDocFile);
        $content = str_replace('</w:r></w:p></w:tc><w:tc>', '', $content);
        $content = str_replace('</w:r></w:p>', '<p>', $content);
        $striped_content = strip_tags($content, '<p>');
        echo $striped_content;
        break;
    case 'txt':
        $content = $DOCX->readFileTXT($UrlDocFile);
        echo $content;
        break;
    case 'rtf':
        $content = $DOCX->readFileRTF($UrlDocFile);
        echo $content;
        break;
    
}

?>