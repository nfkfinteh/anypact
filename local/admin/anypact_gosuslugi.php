<?/*  АО "НФК-Сбережения" 25.05.2020 */
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
IncludeModuleLangFile(__FILE__);

$adminPage->Init();
$adminMenu->Init($adminPage->aModules);

if(empty($adminMenu->aGlobalMenu))
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$APPLICATION->SetTitle(GetMessage("gosuslugi_setting"));

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

if($REQUEST_METHOD=="POST" && $_POST['saveperm'] == 'Y' && check_bitrix_sessid())
{
    COption::SetOptionString("anypact", "block_gosuslugi", $_REQUEST['set_hide_bottom'] == "Y" ? "Y" : "N");
}
?>

<form method="POST" action="<?= $APPLICATION->GetCurPage()?>?lang=<?= LANGUAGE_ID?>" name="gosuslugi_setting">
<input type="hidden" name="site" value="<?= htmlspecialcharsbx($site) ?>">
<input type="hidden" name="saveperm" value="Y">
<input type="hidden" name="lang" value="<?= LANGUAGE_ID?>">
<?= bitrix_sessid_post()?>

<?
$aTabs = array(
	array("DIV" => "gosuslugi_setting", "TAB" => GetMessage("GOSU_SETTING"), "ICON" => "gosuslugi", "TITLE" => GetMessage("gosuslugi_setting"))
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
?>


<?$tabControl->BeginNextTab();?>
<tr>
	<td colspan="2">
		<table>
		<tr>
			<td class="adm-detail-content-cell-l" width="40%">
				<input type="checkbox" name="set_hide_bottom" id="set_hide_bottom" value="Y" <? if (COption::GetOptionString("anypact", "block_gosuslugi", "Y") == "Y") {echo "checked";}?>/>
			</td>
			<td class="adm-detail-content-cell-r" width="60%"><label for="set_hide_bottom"><?= GetMessage('GOSU_BLOCK')?></label></td>
		</tr>
		</table>
	</td>
</tr>

<?$tabControl->EndTab();?>

<?
$tabControl->Buttons(
	array(
		"disabled" => false,
		"back_url" => "/bitrix/admin/?lang=".LANGUAGE_ID."&".bitrix_sessid_get()
	)
);
?>

<?$tabControl->End();?>

</form>

<?
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");
?>