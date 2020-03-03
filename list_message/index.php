<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact || сообщения пользователя");?>
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
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>