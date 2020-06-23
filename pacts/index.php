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
    <div class="ads-type d-flex justify-content-between position-relative deal-btn-block">
        <div class="d-flex justify-content-start align-middle">
            <span class="d-flex align-middle align-items-center">Сортировать:</span>
            <select class="deal-sort" name="sort">
                <option value="default" default>По умолчанию</option>
                <option value="PRICE" data-order="asc">По возростанию цены</option>
                <option value="PRICE" data-order="desc">По убыванию цены</option>
                <option value="NAME" data-order="asc">От А до Я</option>
                <option value="NAME" data-order="desc">От Я до А</option>
            </select>
        </div>
        <div class="d-flex justify-content-end align-middle">
            <button class="btn btn-filter"></button>
            <span class="ads-type-name">Вид обьявлений</span>
            <button class="btn btn-tiled active"></button>
            <button class="btn btn-list"></button>
        </div>
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