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
    "", 
        Array(
            "IBLOCK_ID"             => 3,
            "IBLOCK_ID_CONTRACT"    => 4,
            "SEF_MODE"              => "N",
            "SEF_FOLDER"            => "/pacts/view_pact/",            
            "SECTION_ID"            => $_GET['SECTION_ID'],
            "ELEMENT_ID"            => $_GET['ELEMENT_ID'],
            "SEF_URL_TEMPLATES"     => array(                    
                    "list"      => "",
                    "detail"    => "#ID#"
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
<?if (COption::GetOptionString("anypact", "block_gosuslugi", "Y") == "Y") {?>
    <noindex>
        <div id="regpopup_bg_deal" class="bgpopup" style="display: none;">
            <div class="container">
                <div class="row align-items-center justify-content-center">            
                    <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6">
                        <div class="regpopup_win">
                            <div id="regpopup_close_deal">Х</div>
                            <div class="regpopup_autorisation" id="regpopup_autarisation_deal">
                                <h2>Авторизация</h2>
                                <?$APPLICATION->IncludeComponent("bitrix:system.auth.form",
                                "anypact_popup_deal",
                                Array(
                                    )
                                );?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </noindex>
<?}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>