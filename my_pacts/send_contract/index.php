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
    <!--Созданные или подписанные пользователем документы 
    <h1>Личный кабинет</h1>-->  
    <?//компонент выводит список договоров пользователя
    $APPLICATION->IncludeComponent("nfksber:sendcontract.view", 
    "", 
        Array(
            "IBLOCK_ID" => "3",
            "SEF_MODE" => "N",
            "SEF_FOLDER" => "/my_pacts/",
            "ID_CONTRACT" => $_GET["ID"],
            "SEF_URL_TEMPLATES" => array(                    
                    "list" => "",
                    "detail" => "#ID#"
            ),
            "DISPLAY_PROFILE"=> array(
                array(
                    "USER"=>"UF_ID_USER_A",
                    "COMPANY"=>"UF_ID_COMPANY_A"
                )
            )
        )
    );
    ?> 
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