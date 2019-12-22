<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");?>


<?
// компонент вывода сообщений
$APPLICATION->IncludeComponent("nfksber:message.view", 
"", 
    Array(     
        )
);
?>

</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>