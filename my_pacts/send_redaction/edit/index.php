<?/*  АО "НФК-Сбережения" 27.05.2020 */
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
    $APPLICATION->IncludeComponent("nfksber:redactionview.detail", 
    "ajax_prev", 
    Array(
        "IBLOCK_ID"             => 6,
        "IBLOCK_ID_CONTRACT"    => 6,
        "SEF_MODE"              => "N",
        "SEF_FOLDER"            => "/my_pacts/send_redaction/",
        "SECTION_ID"            => $_GET['SECTION_ID'],
        "ELEMENT_ID"            => $_GET['ID'],
        "SEF_URL_TEMPLATES"     => array(
            "list"      => "",
            "detail"    => "#ID#"
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