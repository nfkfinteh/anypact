<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
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
                        "IBLOCK_TYPE" => "news",
                        "IBLOCK_ID" => "3",
                        "FILTER_NAME" => "arrFilter",
                        "FIELD_CODE" => array("NAME",'DATE_ACTIVE_FROM'),
                        "PROPERTY_CODE" => array('SUMM_PACT', 'LOCATION_CITY'),
                        "OFFERS_FIELD_CODE" => array(),
                        "OFFERS_PROPERTY_CODE" => array(),
                        "PRICE_CODE" => array(),
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000",
                        "CACHE_GROUPS" => "Y",
                        "LIST_HEIGHT" => "5",
                        "TEXT_WIDTH" => "20",
                        "NUMBER_WIDTH" => "5",
                        "SAVE_IN_SESSION" => "N",
                        "PAGER_PARAMS_NAME" => "arrPager"
                    ),
                    false
                );?>
            </div>
        </div>
        <div class="col-lg-9 col-md-8 col-sm-12 tenders-list">
            <?$APPLICATION->IncludeComponent("nfksber:allpacts.list",
            "",
                Array(
                    "IBLOCK_ID" => "3",
                    "SEF_MODE" => "N",
                    "SEF_FOLDER" => "/pacts/",
                    "SECTION_ID" => $_GET['SECTION_ID'],
                    "FILTER_NAME" => "arrFilter",
                    "NEWS_COUNT"=>9,
                    "PAGER_TEMPLATE"=>'anypact_pagination',
                    "SEF_URL_TEMPLATES" => array(
                            "list" => "",
                            "detail" => "#ID#"
                        )
                    )
            );
            ?>
        </div>
	</div>
</div>

</div>