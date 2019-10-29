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
    <?//компонент выводит детальный просмотр сделки    
        $APPLICATION->IncludeComponent("nfksber:dogovorview.detail",
            "new_redaction",
            Array(
                "IBLOCK_ID"             => 3,
                "IBLOCK_ID_CONTRACT"    => 4,
                "SEF_MODE"              => "N",
                "SEF_FOLDER"            => "/pacts/view_pact/",
                "SECTION_ID"            => $_GET['SECTION_ID'],
                "ELEMENT_ID"            => $_POST['ELEMENT_ID'],
                "SEF_URL_TEMPLATES"     => array(
                    "list"      => "",
                    "detail"    => "#ID#"
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