<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/class/CMoneta.php");

if($_GET['check_code'] == '3dyvhfangy8b5R84EOGFADHI' && !empty($_REQUEST['asyncId'])){
    $asyncId = $_REQUEST['asyncId'];
    $res = CMoneta::checkAsync($asyncId);
    define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/checkAsyncError.log");
    if($res !== true)
        AddMessage2Log($res, "result");
}
?>