<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "AnyPact || Все предложения");
$APPLICATION->SetTitle("Все предложения");
$APPLICATION->SetPageProperty("description", "Заключить договор с использованием AnyPact. Категории: Купля-продажа; Работа и услуги; Заём; Пожертвование; Наём жилья; Дарение; Инвестиции; Аренда; Обмен, мена; Иной договор");
?>
<div class="container">
    <!--Созданные или подписанные пользователем документы-->   
    <h1><?$APPLICATION->ShowTitle(false)?></h1>
    <?// компонент поисковой строки
    /*$APPLICATION->IncludeComponent(
        "bitrix:search.form",
        "homepage",
        Array()
    );*/
    ?>
    <?
    $Section = $_GET['SECTION_ID'];    
    $APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0"
	),
	false
);
    // Компонент Хлебные крошки
    /*$APPLICATION->IncludeComponent(
        "nfksber:breadcrumb", 
        "", 
        array(
            "IBLOCK_TYPE" => "4",
            "IBLOCK_ID" => "3",
            "SECTION_ID" => $Section,
            "ELEMENT_ID" => "",
			"SITE_ID" => "s1",			
        ),
        false
	);*/
	?>
	<?
	global $newFilter, $USER;
	$newFilter = array("PROPERTY_MODERATION_VALUE" => 'Y',
	array(
		'LOGIC' => 'OR',
		array("!=PROPERTY_PRIVATE_VALUE" => "Y"),
		array(
			"PROPERTY_PRIVATE_VALUE" => "Y",
			"=PROPERTY_ACCESS_USER" => empty( $USER -> GetID() ) ? 0 : $USER -> GetID()
		),
		array(
			"PROPERTY_PRIVATE_VALUE" => "Y",
			"=CREATED_BY" => empty( $USER -> GetID() ) ? 0 : $USER -> GetID()
		),
	));
	?>
    <?//компонент выводит список всех предложений
    $APPLICATION->IncludeComponent(
	"nfksber:pacts", 
	".default", 
	array(
		"ADDITIONAL_FILTER" => "newFilter",
		"IBLOCK_ID" => "3",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/pacts/",
		"SECTION_ID" => $Section,
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "4",
		"NEWS_COUNT" => "9",
		"USE_SEARCH" => "N",
		"USE_RSS" => "N",
		"USE_RATING" => "N",
		"USE_CATEGORIES" => "N",
		"USE_REVIEW" => "N",
		"USE_FILTER" => "Y",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"CHECK_DATES" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"USE_PERMISSIONS" => "N",
		"STRICT_SECTION_CHECK" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"USE_SHARE" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"DISPLAY_NAME" => "Y",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "NAME",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_SHOW_ALL" => "N",
		"PAGER_TEMPLATE" => "anypact_pagination",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"MESSAGE_404" => "",
		"FILTER_NAME" => "arrFilter",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"SEF_URL_TEMPLATES" => array(
			"pacts" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"detail" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
		),
		"FILE_404" => "/404.php"
	),
	false
);
    ?> 
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>