<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact || Профиль пользователея");
// проверяем авторизован ли пользователь
global $USER;


if ($USER->IsAuthorized()):
    if($_REQUEST['AJAX_SDEL'] == 'Y') $APPLICATION->RestartBuffer();?>

    <div id="ajax_profile">
        <?$APPLICATION->IncludeComponent("nfksber:user.profile",
            "",
            Array(
                "IBLOCK_ID" => 3,
                "IBLOCK_ID_COMPANY" => 8,
                "USER_ID" => $_REQUEST['ID'],
                'CURRENT_USER'=>$USER->GetID(),
                "CACHE_TIME"=>3600000,
                "ITEM_COUNT"=> 9,
                "PAGER_TEMPLATE"=>'anypact_pagination',
                "TYPE" => $_REQUEST['type']=='company' ? 'company' : ''
            )
        );?>
        </div>
    <?if($_REQUEST['AJAX_SDEL'] == 'Y') exit();?>
<?else:?>
    <?  // заглушка на авторизацию доступа
        $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/not_access.php", Array());            
    ?>
<?endif?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>