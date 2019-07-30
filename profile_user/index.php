<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
// проверяем авторизован ли пользователь
global $USER;

if ($USER->IsAuthorized()){
    $APPLICATION->IncludeComponent("nfksber:user.profile",
        "",
        Array(
            "USER_ID" => $_REQUEST['ID'],
        )
    );
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>