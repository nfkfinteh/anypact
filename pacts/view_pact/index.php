<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
?>
<div class="container" style="margin-bottom: 100px;">
	<?
    // проверяем авторизован ли пользователь
    global $USER;
    if ($USER->IsAuthorized()){
    ?>
        <div class="tender cardPact">
            <?//компонент выводит детальный просмотр сделки
            $APPLICATION->IncludeComponent("nfksber:pactview.detail",
            "",
                Array(
                    "IBLOCK_ID" => 3,
                    "SEF_MODE" => "N",
                    "SEF_FOLDER" => "/pacts/view_pact/",
                    "SECTION_ID" => $_GET['SECTION_ID'],
                    "ELEMENT_ID" => $_GET['ELEMENT_ID'],
                    "SEF_URL_TEMPLATES" => array(
                            "list" => "",
                            "detail" => "#ID#"
                        )
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
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>