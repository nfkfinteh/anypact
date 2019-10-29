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
    <?// компонент поисковой строки
    /*$APPLICATION->IncludeComponent(
        "bitrix:search.form",
        "homepage",
        Array()
    );*/
    ?>
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
        <button class="btn btn-tiled active"></button>
        <button class="btn btn-list"></button>
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