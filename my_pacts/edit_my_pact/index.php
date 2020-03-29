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

    if(!empty($_SESSION['FORM_SDELKA']) && !$_GET['dogovor']){
        unset($_SESSION['FORM_SDELKA']);
    }
 
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
            ),
            "LOCATION" => $getGeo['cityName'],
            "FORM_SDELKA"=>$_SESSION['FORM_SDELKA'],
            "DOGOVOR"=>$_GET['dogovor']
        )
    );
    unset($_SESSION['FORM_SDELKA']);
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
<script src="https://api-maps.yandex.ru/2.1/?apikey=08f051a6-35f1-4392-a988-5024961ee1a8&lang=ru_RU" type="text/javascript"></script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>