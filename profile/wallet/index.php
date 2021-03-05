<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact || Кошелек");
?>
<?$APPLICATION->IncludeComponent("nfksber:moneta.wallet","",Array());?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
