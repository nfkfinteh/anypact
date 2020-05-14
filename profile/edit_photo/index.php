<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
// проверяем авторизован ли пользователь
if (!$USER->IsAuthorized()) {
    LocalRedirect("/");
}
?>
<?$APPLICATION->IncludeComponent("nfksber:resizeimg",
    "",
    Array()
);?>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>