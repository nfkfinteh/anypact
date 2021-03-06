<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact создание компании");
// проверяем авторизован ли пользователь
if (!$USER->IsAuthorized()) {
    LocalRedirect("/");
}
?>
<?
 $TypePage = $_GET['typepage'];

 switch ($TypePage) {
     case 'new_company':?>
        <div class="d-flex flex-column align-items-center text-center mt-5 pt-5 mb-5" >
            <img src="<?=SITE_TEMPLATE_PATH?>/image/forbidden.png" alt="Необходима регистрация">
            <h3 class="text-uppercase font-weight-bold mt-3" style="max-width: 550px">Поздравляем, Вы создали Компанию!</h3>
            <p>На текущий момент она находится на проверке.</p>
            <p>После успешной проверки Ваша Компания будет автоматически активирована.</p>
            <!--<a href="#" class="btn btn-nfk mt-4" style="width: 262px; height: 46px; padding-top: 10px;">Региcтрация</a>-->
            <a href="/" class="mt-3">Вернуться на главную страницу</a>
        </div><?
    break;

    case 'new_ip':?>
        <div class="d-flex flex-column align-items-center text-center mt-5 pt-5 mb-5" >
            <img src="<?=SITE_TEMPLATE_PATH?>/image/forbidden.png" alt="Необходима регистрация">
            <h3 class="text-uppercase font-weight-bold mt-3" style="max-width: 550px">Поздравляем, Вы создали ИП!</h3>
            <p>На текущий момент она находится на проверке.</p>
            <p>После успешной проверки Ваш ИП будет автоматически активирован.</p>
            <!--<a href="#" class="btn btn-nfk mt-4" style="width: 262px; height: 46px; padding-top: 10px;">Региcтрация</a>-->
            <a href="/" class="mt-3">Вернуться на главную страницу</a>
        </div><?
    break;
     
    default: ?>
        <div class="d-flex flex-column align-items-center text-center mt-5 pt-5 mb-5" >
            <img src="<?=SITE_TEMPLATE_PATH?>/image/forbidden.png" alt="Необходима регистрация">
            <h3 class="text-uppercase font-weight-bold mt-3" style="max-width: 550px">В текущий момент у Вас нет активных Компаний</h3>
            <p>Если вы создавали Компанию то в текущий момент она находится на проверке.</p>
            <p>После успешной проверки Ваша Компания будет автоматически активирована.</p>
            <!--<a href="#" class="btn btn-nfk mt-4" style="width: 262px; height: 46px; padding-top: 10px;">Региcтрация</a>-->
            <a href="/" class="mt-3">Вернуться на главную страницу</a>
        </div><?
    break;?>
<? }?>
</div>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>