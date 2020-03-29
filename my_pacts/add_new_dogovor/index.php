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
    if(!empty($_GET["ADD"])) {

        #TYPE_USER_PROF опредляет способ как выставляються реквизиты:
        # 1 - для зоздающего договор реквизиты выставляються в зависимости от того под каким профилем создавали сделку
        # пустое значение - для остальных пользователей, реквизиты определяються в зависимости от выбраного профиля
        $APPLICATION->IncludeComponent("nfksber:dogovorview.new",
            '',
            Array(
                "IBLOCK_ID" => 3,
                "SEF_MODE" => "N",
                "SEF_FOLDER" => "/pacts/view_pact/",
                "SECTION_ID" => $_GET['SECTION_ID'],
                "SEF_URL_TEMPLATES" => array(
                    "list" => "",
                    "detail" => "#ID#"
                ),
                "TYPE_USER_PROF" => '',
            )
        );
    }
    ?> 

    <!--//-->
    <?} else {?>
        <?  // заглушка на авторизацию доступа
            $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/not_access.php", Array());            
        ?>
    <?}?>
</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>