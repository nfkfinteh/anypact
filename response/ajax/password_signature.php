<?php
/*  АО "НФК-Сбережения" 03.06.2020 */
/*  Автризация пользователя без перезагрузки страницы 
    $_POST['main'] получаем от клиента login и  password
*/

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;
//$postData = '{"LOGIN":"skiminok","PASSWORD":"123qwe"}'; //$_POST['main'];
$postData = $_POST['main'];
$data = json_decode($postData, true);

if (!$data['LOGIN'] || strlen(trim($data['LOGIN'])) < 1) {     
     die(json_encode(array('TYPE' => 'ERROR', 'VALUE' => 'Пустое значение логина')));
 }

 if (!$data['PASSWORD'] || strlen(trim($data['PASSWORD'])) < 1) {
    die(json_encode(array('TYPE' => 'ERROR', 'VALUE' => 'Пустое значение пароля')));
 }
 
 $rsUser = CUser::GetByLogin($data['LOGIN']);
 $arUser = $rsUser->Fetch();

if($arUser['ID'] !== $USER -> GetID()){
    die(json_encode(array('TYPE' => 'ERROR', 'VALUE' => 'Неверный логин или пароль')));
}else{
    $arAuthResult = $USER->Login($data['LOGIN'], $data['PASSWORD'], "Y");

    if(isset($arAuthResult['TYPE']) && $arAuthResult['TYPE'] == 'ERROR'){
        die(json_encode(array('TYPE' => 'ERROR', 'VALUE' => $arAuthResult['MESSAGE'])));
    }else {
        if($arUser['UF_ESIA_AUT'] == 1 && !empty($arUser['UF_ETAG_ESIA']) && !empty($arUser['UF_ESIA_ID']) && !empty($arUser['UF_PASSPORT'])){
            $hash_key = hash('md5', $data['LOGIN'] . $data['PASSWORD'] . time());
            $password_signature = base64_encode(serialize(array('hash' => $hash_key, 'eTag' => $arUser['UF_ETAG_ESIA'])));
            die(json_encode(array('TYPE' => 'SUCCES', 'VALUE' => 'Вы успешно авторизаваны', 'PASSWORD_SIGNATURE' => $password_signature)));
        }else{
            die(json_encode(array('TYPE' => 'ERROR', 'VALUE' => 'Ваш аккаунт не был верифицирован через Госуслуги')));
        }
    }
}