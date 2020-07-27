<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;

$imgData = $_FILES;
$data = $_POST;

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

    if ( empty($fields['PASSWORD']) ) {
        unset($fields['PASSWORD']);
        unset($fields['CONFIRM_PASSWORD']);
    }

    #проверка на картинку
    if(!empty($imgData) && $imgData['PERSONAL_PHOTO']['size']>0){
        $fields = array_merge($fields, $imgData);
    }

    if(!empty($fields['NAME'])) $fields['NAME'] = mb_convert_case($fields['NAME'], MB_CASE_TITLE);
    if(!empty($fields['LAST_NAME'])) $fields['LAST_NAME'] = mb_convert_case($fields['LAST_NAME'], MB_CASE_TITLE);
    if(!empty($fields['SECOND_NAME'])) $fields['SECOND_NAME'] = mb_convert_case($fields['SECOND_NAME'], MB_CASE_TITLE);

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