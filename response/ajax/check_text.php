<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/local/class/CFormatHTMLText.php");
if(!empty($_POST['text']) && check_bitrix_sessid()){
    $text = CFormatHTMLText::TextFormatting($_POST['text'], array('<a>'));
    echo json_encode($text);
}