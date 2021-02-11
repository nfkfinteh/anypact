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
    }else{
        if(empty($fields['OLD_PASSWORD'])){
            die(json_encode(array('TYPE' => 'ERROR', 'VALUE' => "Не введен старый пароль")));
        }else{
            $rsUser = CUser::GetByID($idUser);
            $arUser = $rsUser->Fetch();
           
            $arAuthResult = $USER->Login($arUser['LOGIN'], $fields['OLD_PASSWORD']);
        
            if(isset($arAuthResult['TYPE']) && $arAuthResult['TYPE'] == 'ERROR'){
                die(json_encode(array('TYPE' => 'ERROR', 'VALUE' => $arAuthResult['MESSAGE'])));
            }
        }
        
    }

    $arWhiteList = [
        "LAST_NAME",
        "NAME",
        "SECOND_NAME",
        "PERSONAL_GENDER",
        "PERSONAL_BIRTHDAY",
        "UF_SNILS",
        "UF_INN",
        "EMAIL",
        "PASSWORD",
        "CONFIRM_PASSWORD",
        "PERSONAL_ZIP",
        "PERSONAL_CITY",
        "PERSONAL_COUNTRY",
        "UF_STREET",
        "PERSONAL_STATE",
        "UF_N_HOUSE",
        "UF_N_HOUSING",
        "UF_N_APARTMENT",
        "UF_REGION",
        "PERSONAL_PHONE",
        "UF_WORK",
        "UF_EDUCATION",
        "UF_ABOUT",
        "UF_HIDE_PROFILE",
        "UF_DISPLAY_PHONE",
        "UF_DISPLAY_DATE",
        "UF_DISPLAY_ADDRESS",
        "UF_N_BANK",
        "UF_KS_BANK",
        "UF_BIC_BANK",
        "UF_RS_BANK",
        "UF_INN_BANK",
        "UF_OTHER_PARAMS_BANK",
        "PERSONAL_PHOTO"
    ];

    $arDelFields = [
        "LOGIN",
        "UF_SPASSPORT",
        "UF_NPASSPORT",
        "UF_DATA_PASSPORT",
        "UF_KEM_VPASSPORT",
    ];

    foreach($arDelFields as $del){
        if(isset($fields[$del]))
            unset($fields[$del]);
    }

    if(!empty($fields['EMAIL'])){
        if (!filter_var($fields['EMAIL'], FILTER_VALIDATE_EMAIL)) {
            die();
        }
    }else{
        unset($fields['EMAIL']);
    }

    $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $idUser), array('SELECT' => array("UF_ESIA_ID", "UF_ESIA_AUT"), 'FIELDS' => array("ID", "PERSONAL_PHONE")));
    if($array = $res -> fetch()){
        if(!empty($array['UF_ESIA_ID']) && $array['UF_ESIA_AUT'] == 1){
            unset($fields['NAME']);
            unset($fields['LAST_NAME']);
            unset($fields['SECOND_NAME']);
        }
    }

    if(!empty($fields['PERSONAL_PHONE']) && $array['PERSONAL_PHONE'] != $fields['PERSONAL_PHONE']){
        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("PERSONAL_PHONE" => $fields['PERSONAL_PHONE']), array('FIELDS' => array("ID")));
        if($array = $res -> fetch()){
            echo json_encode([ 'VALUE' => "Номер телефона уже занят", 'TYPE'=> 'ERROR', 'PHONE' => 'Y']);
            die();
        }
    }

    #проверка на картинку
    if(!empty($imgData) && $imgData['PERSONAL_PHOTO']['size']>0){
        $fields = array_merge($fields, $imgData);
    }

    if(!empty($fields['NAME'])) $fields['NAME'] = mb_convert_case($fields['NAME'], MB_CASE_TITLE);
    if(!empty($fields['LAST_NAME'])) $fields['LAST_NAME'] = mb_convert_case($fields['LAST_NAME'], MB_CASE_TITLE);
    if(!empty($fields['SECOND_NAME'])) $fields['SECOND_NAME'] = mb_convert_case($fields['SECOND_NAME'], MB_CASE_TITLE);

    foreach($fields as $key => $value)
        if(!in_array($key, $arWhiteList))
            unset($fields[$key]);

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