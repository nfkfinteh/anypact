<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact || сообщения пользователя");?>
<?
global $USER;

if ($USER->IsAuthorized()){
?>
<?
$APPLICATION->IncludeComponent("nfksber:message.list",
    "",
    Array(
        "IBLOCK_ID"=>6,
        "PAGEN_ID" => "anypact_pagination",
        "ROWS_PER_PAGE"=> 20
    )
);
?>
<?
} else {
    // заглушка на авторизацию доступа
    $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/not_access.php", Array());
}?>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>