<?php   /* АО "НФК-Сбережения" 30.07.20 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;
if ( check_bitrix_sessid() && !empty($_POST['EL_ID']) && $USER -> IsAuthorized() && \Bitrix\Main\Loader::includeModule("iblock") ) {
    $rsEl = CIBlockElement::GetList(array(), array("ID" => $_POST['EL_ID']), false, false, array("PROPERTY_DEAL_PHONE"));
    if($arEl = $rsEl -> fetch()){
        echo json_encode([ 'VALUE'=>$arEl["PROPERTY_DEAL_PHONE_VALUE"], 'TYPE'=> 'SUCCESS']);
        die();
    }
}
echo json_encode([ 'VALUE'=>'Ошибка', 'TYPE'=> 'ERROR']);
?>