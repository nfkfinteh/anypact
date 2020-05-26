<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:search.page", 
	"suggest", 
	array(
		"AJAX_MODE" => "N",
		"RESTART" => "N",
		"CHECK_DATES" => "Y",
		"USE_TITLE_RANK" => "N",
		"DEFAULT_SORT" => "rank",
		"arrWHERE" => "",
		"arrFILTER" => array(
			0 => "iblock_4",
			1 => "iblock_sprav",
		),
		"SHOW_WHERE" => "N",
		"PAGE_RESULT_COUNT" => "50",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"PAGER_TITLE" => "Результаты поиска",
		"PAGER_SHOW_ALWAYS" => "Y",
		"PAGER_TEMPLATE" => "",
		"AJAX_OPTION_SHADOW" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"COMPONENT_TEMPLATE" => "suggest",
		"NO_WORD_LOGIC" => "N",
		"FILTER_NAME" => "",
		"arrFILTER_iblock_4" => array(
			0 => "all",
		),
		"SHOW_WHEN" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"USE_LANGUAGE_GUESS" => "Y",
		"SHOW_RATING" => "",
		"RATING_TYPE" => "",
		"PATH_TO_USER_PROFILE" => "",
		"DISPLAY_TOP_PAGER" => "Y",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"arrFILTER_main" => "",
		"arrFILTER_blog" => array(
			0 => "all",
		),
		"arrFILTER_iblock_sprav" => array(
			0 => "8",
		)
	),
	false
);?>
</div></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>