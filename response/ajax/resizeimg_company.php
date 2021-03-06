<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
function cropImg($data_img, $userId){
    if (($data_img['x1'] < 0) || ($data_img['y1'] < 0) || ($data_img['width'] < 0) || ($data_img['height'] < 0)) {
        //echo "Некорректные входные параметры";
        return false;
    }
    list($w_i, $h_i, $type) = getimagesize($data_img['url']); // Получаем размеры и тип изображения (число)
    $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
    $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
    if ($ext) {
        $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
        $img_i = $func($data_img['url']); // Создаём дескриптор для работы с исходным изображением
    } else {
        //echo 'Некорректное изображение';
        return false;
    }
    if ($data_img['x1'] + $data_img['width'] > $w_i) $data_img['width'] = $w_i - $data_img['x1']; // Если ширина выходного изображения больше исходного (с учётом x_o), то уменьшаем её
    if ($data_img['y1'] + $data_img['height'] > $h_i) $data_img['height'] = $h_i - $data_img['y1']; // Если высота выходного изображения больше исходного (с учётом y_o), то уменьшаем её

    if($ext=="png"){
        //заливаем фон белым для png
        $img_o = imagecreatetruecolor($data_img['width'], $data_img['height']); // Создаём дескриптор для выходного изображения
        $white = imagecolorallocate($img_o, 255, 255, 255);
        imagefill($img_o, 0, 0, $white);
    }
    else{
        $img_o = imagecreatetruecolor($data_img['width'], $data_img['height']); // Создаём дескриптор для выходного изображения
    }

    imagecopy($img_o, $img_i, 0, 0, $data_img['x1'], $data_img['y1'], $data_img['width'], $data_img['height']); // Переносим часть изображения из исходного в выходное
    $func = 'image'.$ext; // Получаем функция для сохранения результата
    $fileName = pathinfo($data_img['url'])['filename'];
    $urlFile = $_SERVER['DOCUMENT_ROOT'].'/upload/tmp/company_profile/'.$fileName.'_'.$userId.'.'.$ext;
    $result =  $func($img_o, $urlFile); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
    imagedestroy($img_o);
    if($result){
        return $urlFile;
    }
    else{
        return $result;
    }

}

if (!file_exists($_SERVER['DOCUMENT_ROOT']."/upload/tmp/company_profile")) {
    mkdir($_SERVER['DOCUMENT_ROOT']."/upload/tmp/company_profile/", 0777, true);
}
$arParamsTranslit = array("replace_space"=>"-","replace_other"=>"-");
foreach( $_FILES as $file ){
    $arDataFile = explode('.', $file['name']);

    $fileNameTranslit = Cutil::translit($arDataFile[0],"ru", $arParamsTranslit);
    $fileNameTranslit = $fileNameTranslit.'.'.$arDataFile[1];

    if( move_uploaded_file( $file['tmp_name'],  $_SERVER['DOCUMENT_ROOT'].'/upload/tmp/company_profile/' . $fileNameTranslit ) ){
        $files[] = realpath( $_SERVER['DOCUMENT_ROOT'].'/upload/tmp/company_profile/' . $fileNameTranslit );
    }
    else{
        $error = true;
    }
}

global $USER;
$userId = $USER->GetID();
$data_img = json_decode($_REQUEST['main'], true);
$data_img['url'] = $files[0];

foreach ($data_img as $key=>$value){
    $data_img[$key] = htmlspecialcharsEx($value);
}


if($data_img['rotate']){
    if($data_img['rotate']>0){
        $data_img['rotate'] = 360-$data_img['rotate'];
    }
    elseif ($data_img['rotate']<0){
        $data_img['rotate'] = abs($data_img['rotate']);
    }

    rotate_img($data_img['url'], $data_img['url'], $data_img['rotate']);
}

$result = cropImg($data_img, $userId);

if(empty($result)){
    echo json_encode([ 'VALUE'=>'Область вырезания за пределами картинки', 'TYPE'=> 'ERROR']);
    die();
}

CFile::ResizeImageFile(
    $result, //исходный файл
    $imgResize = $_SERVER['DOCUMENT_ROOT']."/upload/tmp/company_profile/resize_".basename($result), // куда отправляем
    array('width'=>300, 'height'=>300),
    BX_RESIZE_IMAGE_PROPORTIONAL
);

unlink($data_img['url']);
unlink($result);

ob_clean();
echo json_encode(['TYPE'=> 'SUCCESS', 'VALUE'=>basename($imgResize)]);


?>