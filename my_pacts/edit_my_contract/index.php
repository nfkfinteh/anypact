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
    if(!empty($_GET["EDIT"])){
        $template_page = mb_strtolower($_GET["EDIT"]);
    }else{
        $template_page = "add";
    }

    $APPLICATION->IncludeComponent("nfksber:dogovorview.detail", 
    $template_page, 
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

    <!--//-->
    <?} else {?>
        <?  // заглушка на авторизацию доступа
            $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/not_access.php", Array());            
        ?>
    <?}?>
</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>