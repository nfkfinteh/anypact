<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
?>
<div class="container">
	<?
    // проверяем авторизован ли пользователь
    global $USER;
    if ($USER->IsAuthorized()){
    
    //компонент выводит детальный просмотр сделки для редактирования     
    $arTemplate = array(
        'EDIT'  => 'edit',
        'ADD'   => 'add'
    );    
 
    $APPLICATION->IncludeComponent("nfksber:pactview.detail", 
    $arTemplate[$_GET['ACTION']], 
        Array(
            "IBLOCK_ID" => 3,
            "SEF_MODE" => "N",
            "SEF_FOLDER" => "",
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
    <!--//Созданные или подписанные пользователем документы-->
    <?} else {?>
        <?  // заглушка на авторизацию доступа
            $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/not_access.php", Array());            
        ?>
    <?}?>
</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>