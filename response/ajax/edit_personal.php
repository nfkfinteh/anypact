<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;

$imgData = $_FILES;
$data = $_POST;
echo json_encode($data);
if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    echo json_encode([ 'VALUE'=>'Не подключен модуль инфоблоки', 'TYPE'=> 'ERROR']);
    die();
}
#проверка на аторизацию
if (!$USER->IsAuthorized()){
    echo json_encode([ 'VALUE'=>'Вы не авторизваны', 'TYPE'=> 'ERROR']);
    die();
}

$idUser = $USER->GetID();
if(empty($idUser)){
    echo json_encode([ 'VALUE'=>'Вы не авторизваны', 'TYPE'=> 'ERROR']);
    die();
}
if(!empty($data) || !empty($imgData)){

    $fields = $data;
    #проверка на картинку
    if(!empty($imgData) && $imgData['PERSONAL_PHOTO']['size']>0){
        $fields = array_merge($fields, $imgData);
    }

    $user = new CUser;
    $satus = $user->Update($idUser, $fields);

    if($satus){
        echo json_encode(['TYPE'=> 'SUCCES']);
    }
    else{
        $strError .= $user->LAST_ERROR;
        echo json_encode([ 'VALUE'=>$strError, 'TYPE'=> 'ERROR']);
        die();
    }
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
?>