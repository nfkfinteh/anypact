<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact || Договор");
?>
<?
mb_parse_str (urldecode($_GET['DEAL_DATA']), $arDeal);
$APPLICATION->IncludeComponent(
	'nfksber:contract.action',
	'',
	array(
		"ELEMENT_ID" => $_GET['ID'],
		"EDITBOX_ID" => "editor",
		"ACTION_VARIABLE" => "action",
		"COMPLETE" => $_GET['COMPLETE'],
		"USER_ID" => $_GET['USER_ID'],
		"NEW_DEAL" => $_GET['NEW_DEAL'],
		"DEAL_DATA" => $arDeal,
		"COMPANY_ID" => $_GET['COMPANY_ID'],
	)
);
?>
</div>
<?if (COption::GetOptionString("anypact", "block_gosuslugi", "Y") == "Y") {?>
    <noindex>
        <div class="regpopup_autorisation" id="regpopup_autarisation_deal" style="display: none;">
            <?$APPLICATION->IncludeComponent("bitrix:system.auth.form",
                "anypact_popup_deal",
                Array()
            );?>
        </div>
    </noindex>
<?}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>