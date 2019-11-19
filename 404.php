<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("404 Not Found");

// $APPLICATION->IncludeComponent("bitrix:main.map", ".default", Array(
// 	"LEVEL"	=>	"3",
// 	"COL_NUM"	=>	"2",
// 	"SHOW_DESCRIPTION"	=>	"Y",
// 	"SET_TITLE"	=>	"Y",
// 	"CACHE_TIME"	=>	"36000000"
// 	)
// );
?>
    <div class="d-flex flex-column align-items-center text-center mt-5 pt-3 mb-5">
        <h1 style="font-size: 211px; font-weight: 300; color: #ff6416;">404</h1>
        <h3 class="text-uppercase font-weight-bold">К СОЖАЛЕНИЮ! НИЧЕГО НЕ БЫЛО НАЙДЕНО</h3>
        <p class="mt-1" style="max-width: 550px">Возможно, страница, которую вы ищете, была удалена, если ее имя было
            изменено или временно недоступно. <a href="/">Вернуться на главную страницу</a></p>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>