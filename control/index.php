<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
// проверяем авторизован ли пользователь
global $USER;

if ($USER->IsAuthorized() && ($USER->IsAdmin() || $USER->GetID() == 257)){
?>
<h3>Панель управления пользователями</h3>
    <? $APPLICATION->IncludeComponent(
        "nfksber:control.reguser",
        "",
        Array()
    ); ?>

</div>
<?}else{?>
    </div>
<?}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>