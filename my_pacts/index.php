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
    <h1>Личный кабинет</h1>
    <?//компонент выводит список договоров пользователя
    $APPLICATION->IncludeComponent("nfksber:userpacts", 
    "", 
        Array(
            "IBLOCK_ID" => "3",
            "SEF_MODE" => "N",
            "SEF_FOLDER" => "/my_pacts/",
            "SEF_URL_TEMPLATES" => array(                    
                    "list" => "",
                    "detail" => "#ID#"
                )      
            )
    );
    ?> 

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
<br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>