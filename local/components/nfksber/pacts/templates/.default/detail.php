<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
    <div class="tender cardPact">
		<?//компонент выводит детальный просмотр сделки
		$APPLICATION->IncludeComponent("nfksber:pactview.detail",
		"",
			Array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				// "SEF_MODE" => "N",
				// "SEF_FOLDER" => "/pacts/view_pact/",
				// "SEF_URL_TEMPLATES" => array(
				// 	"list" => "",
				// 	"detail" => "#ID#"
				// ),
				"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
				"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
				"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
				"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
				"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["pacts"],
				"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
				"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"SET_CANONICAL_URL" => $arParams["SET_CANONICAL_URL"],
				"SET_TITLE" => $arParams["SET_TITLE"],
				"SET_BROWSER_TITLE" => $arParams["SET_BROWSER_TITLE"],
				"SET_META_KEYWORDS" => $arParams["SET_META_KEYWORDS"],
				"SET_META_DESCRIPTION" => $arParams["SET_META_DESCRIPTION"],
				"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
				"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
				"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
				"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
				"ADDITIONAL_FILTER" => $arParams["ADDITIONAL_FILTER"],
			)
		);
		?>
		<? $APPLICATION->IncludeComponent(
			"nfksber:comment.list",
			".default",
			array(
				"COMPONENT_TEMPLATE" => ".default",
				"IBLOCK_ID" => "9",
				"ELEMENT_ID" => $_GET['ELEMENT_ID'],
				"LOAD_MARK" => "N",
				"COUNT" => "5",
				"ACTIVE_DATE_FORMAT" => "d.m.Y/ H:i",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"PAGER_TEMPLATE" => ".default",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "Y",
				"PAGER_TITLE" => "",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_SHOW_ALL" => "N"
			),
			false
		); ?>
	</div>
</div>
<??>
