<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$rsUser = CUser::GetByLogin($_POST["login"]);
$arUser = $rsUser->Fetch();
//print_r($arUser);
if(!empty($arUser["LOGIN"])){
    echo 'false';
}else{
    echo 'true';
}
?>