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
                "CACHE_TIME"=>3600000,
                "ITEM_COUNT"=> 9,
                "PAGER_TEMPLATE"=>'anypact_pagination',
                "TYPE" => $_REQUEST['type']=='company' ? 'company' : ''
            )
        );?>
        </div>
    <?if($_REQUEST['AJAX_SDEL'] == 'Y') exit();?>
<?endif?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>