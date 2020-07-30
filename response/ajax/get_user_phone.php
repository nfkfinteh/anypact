<?php   /* АО "НФК-Сбережения" 30.07.20 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;
if ( check_bitrix_sessid() && !empty($_POST['USER_ID']) && $USER -> IsAuthorized() ) {
    $rsUser = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $_POST['USER_ID']), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
    if($arUser = $rsUser -> fetch()){
        echo json_encode([ 'VALUE'=>$arUser["PERSONAL_PHONE"], 'TYPE'=> 'SUCCESS']);
        die();
    }
}
echo json_encode([ 'VALUE'=>'Ошибка', 'TYPE'=> 'ERROR']);
?>