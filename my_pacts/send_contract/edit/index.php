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
    "edit", 
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
</div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>