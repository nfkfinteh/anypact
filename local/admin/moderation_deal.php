<?/*  АО "НФК-Сбережения" 09.06.2020 */
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$adminPage->Init();
$adminMenu->Init($adminPage->aModules);

if(empty($adminMenu->aGlobalMenu))
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$APPLICATION->SetTitle(GetMessage("moderation_deal"));

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

CJSCore::Init(array("jquery2"));

?>
<style>
a.mod_faild {
    position: relative;
}
span.moderation_company-delete-icon {
    width: 14px!important;
    height: 14px;
    position: absolute;
    top: 0px;
    left: -20px;
    color: #2a2a2a;
	background-position: -10px -788px!important;
}
a.mod_success {
    position: relative;
}
.mod_success img {
    position: absolute;
    left: -65px;
}
</style>

<form method="POST" action="<?= $APPLICATION->GetCurPage()?>?lang=<?= LANGUAGE_ID?>" name="moderation_deal">
<input type="hidden" name="site" value="<?= htmlspecialcharsbx($site) ?>">
<input type="hidden" name="lang" value="<?= LANGUAGE_ID?>">
<?= bitrix_sessid_post()?>

<?
$aTabs = array(
	array("DIV" => "moderation_deal", "TAB" => GetMessage("MODERN_TITLE"), "ICON" => "gosuslugi", "TITLE" => GetMessage("moderation_deal"))
);

if(CModule::IncludeModule('iblock')){
	$rsData = CIBlockElement::GetList(array('id' => 'desc'), array('!=PROPERTY_MODERATION' => '7', 'IBLOCK_ID' => 3), false, false, array('ID', 'NAME', 'CREATED_BY'));
	while($array = $rsData -> getNext()){
		$arData[] = $array;
		$arUserIds[] = $array['CREATED_BY'];
	}
}

$arUserIds = array_unique($arUserIds);

$rsUser = CUser::GetList($by="personal_country", $order="desc", array('ID' => $arUserIds), array('FIELDS' => array('ID', 'LOGIN', 'NAME', 'LAST_NAME', 'SECOND_NAME')));
while($array = $rsUser -> getNext()){
	$arUser[$array['ID']] = empty(trim($array['LAST_NAME'].$array['NAME'].$array['SECOND_NAME'])) ? $array['LOGIN'] : $array['LAST_NAME']." ".$array['NAME']." ".$array['SECOND_NAME'];
}

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
?>

<?$tabControl->BeginNextTab();?>
<tr>
	<td colspan="2">
		<div id="tbl_perfmon_table_moderation_deal_result_div" class="adm-list-table-layout">
			<div class="adm-list-table-wrap adm-list-table-without-footer">
				<form method="POST" id="form_tbl_perfmon_table_moderation_deal" name="form_tbl_perfmon_table_moderation_deal">
					<input type="hidden" name="sessid" id="sessid" value="<?=bitrix_sessid_get()?>">
					<table class="adm-list-table" id="tbl_perfmon_table_moderation_deal">
						<thead>
							<tr class="adm-list-table-header">
								<td class="adm-list-table-cell">
									<div class="adm-list-table-cell-inner"><?=GetMessage("moderation_deal_USER")?></div>
								</td>
								<td class="adm-list-table-cell">
									<div class="adm-list-table-cell-inner"><?=GetMessage("moderation_deal_DEAL")?></div>
								</td>
								<td class="adm-list-table-cell">
									<div class="adm-list-table-cell-inner"><?=GetMessage("moderation_deal_ACTION")?></div>
								</td>
							</tr>
						</thead>
						<tbody>
							<?if($arData){
								foreach($arData as $data){?>
									<tr class="adm-list-table-row">
										<td class="adm-list-table-cell align-right"><a href="https://localhost/bitrix/admin/user_edit.php?lang=ru&ID=<?=$data['CREATED_BY'];?>" target="__blank"><?=$arUser[$data['CREATED_BY']];?></a></td>
										<td class="adm-list-table-cell align-right"><a href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=3&type=4&ID=<?=$data['ID'];?>&lang=ru" target="__blank"><?=$data['NAME'];?></a></td>
										<td class="adm-list-table-cell align-right">
											<a href="#" data-id="<?=$data['ID'];?>" class="mod_success" title="<?=GetMessage("moderation_deal_DEAL_SUCCESS")?>"><img src="/local/templates/anypact/img/accept_green.png"/></a>
											<a href="#" data-id="<?=$data['ID'];?>" class="mod_faild"   title="<?=GetMessage("moderation_deal_DEAL_DELETE")?>"><span class="bx-core-popup-menu-item-icon moderation_company-delete-icon"></span></a>
										</td>
									</tr>
								<?}?>
							<?}else{?>
								<tr><td colspan="3" class="adm-list-table-cell adm-list-table-empty">- <?=GetMessage("moderation_deal_EMPTY_DATA")?> -</td></tr>
							<?}?>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</td>
</tr>

<?$tabControl->EndTab();?>
<?$tabControl->End();?>

</form>
<script>
	$('.mod_success').on('click', function(e) {
		e.preventDefault();
		var el = this;
		let id = $(el).attr('data-id');
		if (confirm("<?=GetMessage('moderation_deal_DEAL_ACCEPT_WARNING')?>")) {
			$.ajax({
				type: "POST",
				url: '/response/ajax/moderation_deal.php',
				data: {
					Moderation: '7',
					IDElement: id
				},
				success: function (result) {
					if(result){
						$(el).parent('td').parent('tr').remove();
					}
				}
			});
		}
		return false;
	});
	$('.mod_faild').on('click', function(e) {
		e.preventDefault();
		var el = this;
		let id = $(el).attr('data-id');
		if (confirm("<?=GetMessage('moderation_deal_DEAL_DELETE_WARNING')?>")) {
			$.ajax({
				type: "POST",
				url: '/response/ajax/delete_item.php',
				data: {
					id: id
				},
				success: function (result) {
					if(result !== null || result.TYPE !== null || result.TYPE == 'SUCCESS'){
						$(el).parent('td').parent('tr').remove();
					}
				}
			});
		}
		return false;
	});
</script>
<?
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");
?>