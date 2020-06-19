<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact || Все предложения");
$APPLICATION->SetPageProperty("description", "Заключить договор с использованием AnyPact. Категории: Купля-продажа; Работа и услуги; Заём; Пожертвование; Наём жилья; Дарение; Инвестиции; Аренда; Обмен, мена; Иной договор");
?>
<div class="container">
	<?
    // проверяем авторизован ли пользователь
    global $USER;
    $Check = 1;
    //if ($USER->IsAuthorized()){
    if ($Check == 1){
    ?>
    <!--Созданные или подписанные пользователем документы-->   
    <h1>Все предложения</h1>
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
    <div class="ads-type d-flex justify-content-end align-middle position-relative">
        <button class="btn btn-filter"></button>
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
        <?  // заглушка на авторизацию доступа
            $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/not_access.php", Array());            
        ?>
    <?}?>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>