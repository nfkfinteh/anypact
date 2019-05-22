<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$postData = $_POST['checkin'];
$data = json_decode($postData, true);

switch ($data['CHECK_TYPE']) {
    case 'login':
        $rsUser = CUser::GetByLogin($data["CHECK_TEXT"]);
        $arUser = $rsUser->Fetch();
        
        if(!empty($arUser["LOGIN"])){
            die(json_encode(array('TYPE' => 'ERROR', 'VALUE' => 'Логин занят')));
        }else{
            die(json_encode(array('TYPE' => 'SUCCES', 'VALUE' => '')));
        }

    break;
    
    case 'email':
        $filter = Array("EMAIL" => $data["CHECK_TEXT"]);
        $rsUser = CUser::GetList(($by="id"), ($order="desc"), $filter);
        $arUser = $rsUser->Fetch();
        if(!empty($arUser["LOGIN"])){
            die(json_encode(array('TYPE' => 'ERROR', 'VALUE' => 'Почта занята')));
        }else{
            die(json_encode(array('TYPE' => 'SUCCES', 'VALUE' => '')));
        }
    break;
    
    default:
        
        break;
}
?>