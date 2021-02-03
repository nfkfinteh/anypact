<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//компонент выводит список всех предложений
$APPLICATION->IncludeComponent(
	"nfksber:sectionlist",
	"",
	Array(
        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"COUNT_ELEMENTS" => "Y",
		"FILTER_NAME" => $arParams["ADDITIONAL_FILTER"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"SECTION_FIELDS" => array("",""),
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array("",""),
		"SHOW_PARENT_NAME" => "Y",
        "TOP_DEPTH" => "10",
        "VIEW_MODE" => "TEXT",
        "COMPONENT_TEMPLATE" => "pacts_list"
	)
);?>
<?
if(isset($_GET['sort'])){
    if($_GET['sort'] == "NAME"){
        $sort = $_GET['sort'];
    }
    if($_GET['sort'] == "PRICE"){
        $sort = "PROPERTY_SUMM_PACT";
    }
    if(isset($_GET['order'])){
        if($_GET['order'] == "asc" || $_GET['order'] == "desc"){
            $order = $_GET['order'];
        }
    }
    $arSort[$sort] = $order;
    $_SESSION['DEAL_SORT'] = $arSort;
}
?>
<div class="ads-type d-flex justify-content-between position-relative deal-btn-block">
    <div class="d-flex justify-content-start align-middle">
        <span class="d-flex align-middle align-items-center">Сортировать:</span>
        <select class="deal-sort" name="sort">
            <option value="default" data-order="asc" default>По умолчанию</option>
            <option value="PRICE" data-order="asc" <?if(is_array($_SESSION['DEAL_SORT'])){if(key($_SESSION['DEAL_SORT']) == 'PROPERTY_SUMM_PACT' && current($_SESSION['DEAL_SORT']) == 'asc'){?>selected<?}}?>>По возростанию цены</option>
            <option value="PRICE" data-order="desc" <?if(is_array($_SESSION['DEAL_SORT'])){if(key($_SESSION['DEAL_SORT']) == 'PROPERTY_SUMM_PACT' && current($_SESSION['DEAL_SORT']) == 'desc'){?>selected<?}}?>>По убыванию цены</option>
            <option value="NAME" data-order="asc" <?if(is_array($_SESSION['DEAL_SORT'])){if(key($_SESSION['DEAL_SORT']) == 'NAME' && current($_SESSION['DEAL_SORT']) == 'asc'){?>selected<?}}?>>От А до Я</option>
            <option value="NAME" data-order="desc" <?if(is_array($_SESSION['DEAL_SORT'])){if(key($_SESSION['DEAL_SORT']) == 'NAME' && current($_SESSION['DEAL_SORT']) == 'desc'){?>selected<?}}?>>От Я до А</option>
        </select>
    </div>
    <div class="d-flex justify-content-end align-middle">
        <button class="btn btn-filter"></button>
        <span class="ads-type-name">Вид обьявлений</span>
        <button class="btn btn-tiled active"></button>
        <button class="btn btn-list"></button>
    </div>
</div>
<div class="tender">
	<div class="row">
        <div class="col-lg-3 col-md-4 col-sm-12">
            <div class="filter-tender">
                <!-- <span>Ключевое слово</span> -->
                <!--<input class="filter-key" type="text" placeholder="Например, продать автомобиль">-->
                <?/*$APPLICATION->IncludeComponent(
                    "bitrix:search.form",
                    "main",
                    Array()
                );*/?>
                <?$APPLICATION->IncludeComponent (
                    "bitrix:catalog.filter",
                    "sdelki",
                    Array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "FILTER_NAME" => $arParams["FILTER_NAME"],
                        "FIELD_CODE" => array("NAME",'DATE_ACTIVE_FROM'),
                        "PROPERTY_CODE" => array('SUMM_PACT', 'LOCATION_CITY'),
                        "OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
                        "OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
                        "PRICE_CODE" => $arParams["PRICE_CODE"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "LIST_HEIGHT" => "5",
                        "TEXT_WIDTH" => "20",
                        "NUMBER_WIDTH" => "5",
                        "SAVE_IN_SESSION" => "N",
                        "PAGER_PARAMS_NAME" => "arrPager",
                    ),
                    $component
                );?>
            </div>
        </div>
        <div class="col-lg-9 col-md-8 col-sm-12 tenders-list">
            <?$APPLICATION->IncludeComponent("nfksber:allpacts.list",
            "",
                Array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "SEF_MODE" => $arParams["SEF_MODE"],
                    "SEF_FOLDER" => $arParams["SEF_MODE"],
                    //"SECTION_ID" => $_GET['SECTION_ID'],
                    "FILTER_NAME" => $arParams["FILTER_NAME"],
                    "NEWS_COUNT" => $arParams["NEWS_COUNT"],
                    "PAGER_TEMPLATE"=>$arParams["PAGER_TEMPLATE"],
                    "PARENT_SECTION" => $arResult["VARIABLES"]["SECTION_ID"],
                    "PARENT_SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                    "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                    "IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["pacts"],
                    "SET_CANONICAL_URL" => $arParams["SET_CANONICAL_URL"],
                    "SET_TITLE" => $arParams["SET_TITLE"],
                    "SET_BROWSER_TITLE" => $arParams["SET_BROWSER_TITLE"],
                    "SET_META_KEYWORDS" => $arParams["SET_META_KEYWORDS"],
                    "SET_META_DESCRIPTION" => $arParams["SET_META_DESCRIPTION"],
                    "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
                    "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
                    "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                    "ADDITIONAL_FILTER" => $arParams["ADDITIONAL_FILTER"],
                    // "SEF_URL_TEMPLATES" => array(
                    //     "list" => "",
                    //     "detail" => "#ID#"
                    // )
                ),
                $component
            );
            ?>
        </div>
	</div>
</div>

</div>