<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
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
            <a href="<?=SITE_SERVER_NAME?>/esia/open_gu.php" class="btn btn-primary">Пройти регистрацию через портал "Госуслуг"</a>
        </p>
        <p align="center" size="16">
            M-Group Investments Limited <br>
            Contact us: <a href="mailto:mail@m-group.investments">mail@m-group.investments</a>
        </p>        
        <p align="center">
            © 2018
        </p>
    <?} else {?>
        <div class="container">
        <p align="center" style="padding: 50px 0;">
            <img src="<?=SITE_TEMPLATE_PATH.'/img/logo.png?ioi'?>" />
        </p>
        <p align="center" style="padding: 50px 0;">
            Вам необходимо Зарегистрироваться.
        </p>
        <p align="center" size="16">
            M-Group Investments Limited <br>
            Contact us: <a href="mailto:mail@m-group.investments">mail@m-group.investments</a>
        </p>	 
        <p align="center">
    <button type="button" class="btn btn-aut" id="reg_button">Зарегистрироваться</button>
        </p>
        <p align="center">
            © 2018
        </p>
    <?}?>
</div>
<br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>