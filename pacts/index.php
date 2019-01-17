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
    <h1>Все сделки</h1>
    <?//компонент выводит список всех предложений
    $Section = $_GET['SECTION_ID'];    
    
    $APPLICATION->IncludeComponent("nfksber:sectionlist", 
    "", 
        Array(
            "IBLOCK_ID" => "3",
            "SECTION_ID" => $Section,   
            )
    );
    ?>
    <div class="ads-type d-flex justify-content-end align-middle">
        <span class="ads-type-name">Вид обьявлений</span>
        <button class="btn btn-nfk btn-tiled active"></button>
        <button class="btn btn-nfk btn-list"></button>
    </div>
    <?//компонент выводит список всех предложений
    $APPLICATION->IncludeComponent("nfksber:pacts", 
    "", 
        Array(
            "IBLOCK_ID" => "3",
            "SEF_MODE" => "N",
            "SEF_FOLDER" => "/pacts/",
            "SECTION_ID" => $Section,
            "SEF_URL_TEMPLATES" => array(                    
                    "list" => "",
                    "detail" => "#ID#"
                )      
            )
    );
    ?> 

    <!--//-->
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
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>