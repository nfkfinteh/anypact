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
//поворот изображения
function rotate_img($src, $dest, $degrees){
    if (!file_exists($src)) return false;
    $size_img = getimagesize($src);
    $format = strtolower(substr($size_img['mime'], strpos($size_img['mime'], '/')+1));
    $icfunc = "imagecreatefrom" . $format;
    if (!function_exists($icfunc)) return false;
    $image = $icfunc($src);
    $rotate = imagerotate($image, $degrees, 0);
    imagejpeg($rotate, $dest, 95);
}

/**
 * проверка доступа сделки/договора
 *
 * $arDataDisplay['UF_ESIA_AUT'] int UF_ESIA_AUT
 *               ['ID_COMPANY_ELEMENT'] int id компании текущего договора/сделки
 *               ['ID_COMPANY_USER'] int id компании текущего выбранного профиля
 *               ['ID_USER_ELEMENT'] int id пользователя текущего договора/сделки
 *               ['ID_USER'] int id текущего пользователя
 * @param array $arDataDisplay
 * @return bool
 */
function isDisplayElement($arDataDisplay){
    $result = false;
    if($arDataDisplay['UF_ESIA_AUT']==1){
        if(!empty($arDataDisplay['ID_COMPANY_ELEMENT'])){
            if($arDataDisplay['ID_COMPANY_ELEMENT']==$arDataDisplay['ID_COMPANY_USER']){
                $result = true;
            }
        }
        else{
            if($arDataDisplay['ID_USER_ELEMENT']==$arDataDisplay['ID_USER']){
                $result = true;
            }
        }
    }

    return $result;
}

if (!function_exists("cdump")) {
    function cdump($var){
        echo "<script>";
        echo "console.log(" . json_encode($var) . ");";
        echo "</script>";
    }
}