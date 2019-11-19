<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация в сервисе AnyPact");
?>
<div class="container">
	<?
    global $USER;
    if ($USER->IsAuthorized()){
    ?>
        
        <p align="center" style="padding: 50px 0;">
            Спасибо за регистрацию в сервисе AnyPact!
        </p>
        <p align="center" style="padding: 50px 0;">
            Для того, что бы пользоваться всеми предоставленными возможностями, Вам необходимо заполнить профиль и пройти подтверждение ваших регистрационных данных.
        </p>
        <p align="center" style="padding: 50px 0;">
            <a href="/profile/" class="btn btn-nfk">Пройти регистрацию через портал "Госуслуг"</a>
        </p>        
        <p align="center">
            © 2019
        </p>
    <?} else {?>
        <?  // заглушка на авторизацию доступа
            $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/not_access.php", Array());            
        ?>
    <?}?>
</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>