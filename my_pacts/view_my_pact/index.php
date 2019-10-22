<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
?>
<div class="container">
	<?
    // проверяем авторизован ли пользователь
    global $USER;
    if ($USER->IsAuthorized()){    
    ?>
    <!--Созданные или подписанные пользователем документы-->   
    <h1>Проcмотр договора</h1>
    <?//компонент выводит список договоров пользователя
    $APPLICATION->IncludeComponent("nfksber:userpacts.detail", 
    "", 
        Array(
            "IBLOCK_ID" => "3",
            "SEF_MODE" => "N",
            "SEF_FOLDER" => "/my_pacts/",
            "ID_PACT" => $_GET["ID"],
            "SEF_URL_TEMPLATES" => array(                    
                    "list" => "",
                    "detail" => "#ID#"
                )      
            )
    );
    ?> 
    <!--<iframe src="http://anypact.nfksber.ru/upload/private/userfiles/<?=$id_group?>/<?=$id_user_group?>/pact/<?=$id_dogovor?>/pact/dog.pdf" 
style="width: 600px; height: 600px;" frameborder="0">Ваш браузер не поддерживает фреймы</iframe>-->

    <!--//Созданные или подписанные пользователем документы-->
    <?} else {?>
        <div class="d-flex flex-column align-items-center text-center mt-5 pt-5 mb-5" >
            <img src="<?=SITE_TEMPLATE_PATH?>/image/forbidden.png" alt="Необходима регистрация">
            <h3 class="text-uppercase font-weight-bold mt-3" style="max-width: 550px">Вам необходимо зарегистрироваться, чтобы увидеть данную страницу</h3>
            <!--<a href="#" class="btn btn-nfk mt-4" style="width: 262px; height: 46px; padding-top: 10px;">Региcтрация</a>-->
            <a href="/" class="mt-3">Вернуться на главную страницу</a>
        </div>
    <?}?>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>