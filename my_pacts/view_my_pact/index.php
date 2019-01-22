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
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>