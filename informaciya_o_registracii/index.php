<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация в сервисе AnyPact");
?>
<div class="container">
	<?
    global $USER;
    if ($USER->IsAuthorized()){
    ?>
        <div class="d-flex flex-column align-items-center text-center mt-5 pt-5 mb-5" >
            <img src="<?=SITE_TEMPLATE_PATH?>/image/ok_reg.png" alt="Необходима регистрация">
            <h3 class="text-uppercase font-weight-bold mt-3" style="max-width: 550px">Спасибо за регистрацию в сервисе AnyPact!</h3>
            <p align="center" style="padding: 30px 0;">
                Для того, что бы пользоваться всеми предоставленными возможностями,
                <br>Вам необходимо заполнить профиль и пройти подтверждение ваших регистрационных данных.
            </p>
            <!--<a href="#" class="btn btn-nfk mt-4" style="width: 262px; height: 46px; padding-top: 10px;">Региcтрация</a>-->
            <a href="/profile/" class="mt-3">Пройти регистрацию через портал "Госуслуг"</a>
        </div>
    <?} else {?>
        <?  // заглушка на авторизацию доступа
            $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/not_access.php", Array());            
        ?>
    <?}?>
</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>