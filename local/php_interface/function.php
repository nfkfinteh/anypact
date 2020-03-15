<?
//получаем текущую компанию
function getCurCompany($usrId){
    $rsUsers = CUser::GetList(
        $by="timestamp_x",
        $order="desc",
        [
            'ID'=>$usrId
        ],
        [
            'SELECT'=> [
                'ID',
                'NAME',
                'UF_CUR_COMPANY'
            ]
        ]
    );
    if ($obj=$rsUsers->Fetch()){
        $idCompany = $obj['UF_CUR_COMPANY'];
    }
     return $idCompany;
}

//удаление временных файлов в директории
function deleteTmpFile($dir, $time){
    $scanFile = scandir($_SERVER['DOCUMENT_ROOT'].$dir);
    foreach ($scanFile as $file) {
        if ($file != "." && $file != "..") {
            //удаляем фал старше $time часов
            $urlFile = $_SERVER['DOCUMENT_ROOT'].$dir.$file;
            if (time() - filectime($urlFile) > $time * 60 * 60) {
                unlink($urlFile);
            }
        }
    }
}

//проверка файлов на размер и формат
//$size - в байтах
function checkFileNfk($file, $size, $arFormat){
    $format = end(explode('.' ,$file['name']));
    $result['TYPE'] = 'SUCCESS';
    if($file['size'] == $size){
        $result['TYPE'] = 'ERROR';
        $result['VALUE'] = 'Ограничение максимального размера фала '. $size/(1024 * 1024) .'MB';
    }
    if(!in_array( $format, $arFormat) && !empty($arFormat)){
        $result['TYPE'] = 'ERROR';
        $result['VALUE'] = $format.' - формат файла не поддерживаеться';
    }
    return $result;
}
?>