<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact || сообщения пользователя");?>
<?
global $USER;

if ($USER->IsAuthorized()){
?>
<div class="tender" style="margin-bottom: 100px;">
    <h1 class="mb-4">Мои сообщения</h1>
<?
// $APPLICATION->IncludeComponent("nfksber:message.list",
//     "",
//     Array(
//         "IBLOCK_ID"=>6,
//         "PAGEN_ID" => "anypact_pagination",
//         "ROWS_PER_PAGE"=> 20
//     )
// );

$APPLICATION->IncludeComponent("nfksber:messenger_hl",
    "",
    Array()
);
?>
</div>
<?
} else {
    // заглушка на авторизацию доступа
    $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/not_access.php", Array());
}?>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>