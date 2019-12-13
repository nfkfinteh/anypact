<?php
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
 
 $arAuthResult = $USER->Login($data['LOGIN'], $data['PASSWORD'], "Y");

 if(isset($arAuthResult['TYPE']) && $arAuthResult['TYPE'] == 'ERROR'){
     print_r($arAuthResult);
    die(json_encode(array('TYPE' => 'ERROR', 'VALUE' => $arAuthResult['MESSAGE'])));
 }else {
    die(json_encode(array('TYPE' => 'SUCCES', 'VALUE' => 'Вы успешно авторизаваны')));
 } 