<?/*  АО "НФК-Сбережения" 09.06.2020 */
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$adminPage->Init();
$adminMenu->Init($adminPage->aModules);

if(empty($adminMenu->aGlobalMenu))
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$APPLICATION->SetTitle(GetMessage("agreement_status"));

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

function GetEntityDataClass($HlBlockId) {
    if (empty($HlBlockId) || $HlBlockId < 1)
    {
        return false;
    }
    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($HlBlockId)->fetch();
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    return $entity_data_class;
}

function paginator($data,$countOnPage = 10){
    // Получаем номер текущей страницы из реквеста
    $page = (intval($_GET['PAGEN_1'])) ? intval($_GET['PAGEN_1']) : 1;
    // Отбираем элементы текущей страницы
    $dataSlice = array_slice($data, (($page-1) * $countOnPage), $countOnPage,true);
    // Подготовка параметров для пагинатора
    $navResult = new CDBResult();
    $navResult->NavPageCount = ceil(count($data) / $countOnPage);
    $navResult->NavPageNomer = $page;
    $navResult->NavNum = 1;
    $navResult->NavPageSize = $countOnPage;
    $navResult->NavRecordCount = count($data);
    return array(
        'ITEMS'=>$dataSlice,
        'PAGINATION'=>$navResult->GetPageNavStringEx($navComponentObject, '', 'modern', 'Y'),
    );
}

$arJsConfig = array(
    'new_anypact_popup' => array(
        'js' => '/local/templates/anypact/js/new_popup.js',
        'css' => '/local/templates/anypact/css/new_popup.css',
        'rel' => array('jquery2'),
    )
);

foreach ($arJsConfig as $ext => $arExt) {
    \CJSCore::RegisterExt($ext, $arExt);
}

CJSCore::Init(array("new_anypact_popup"));

?>

<form method="POST" action="<?= $APPLICATION->GetCurPage()?>?lang=<?= LANGUAGE_ID?>" name="agreement_status">
<input type="hidden" name="site" value="<?= htmlspecialcharsbx($site) ?>">
<input type="hidden" name="lang" value="<?= LANGUAGE_ID?>">
<?= bitrix_sessid_post()?>

<?
$aTabs = array(
	array("DIV" => "agreement_status", "TAB" => GetMessage("AGREEMENT_TITLE"), "ICON" => "gosuslugi", "TITLE" => GetMessage("agreement_status"))
);

if(CModule::IncludeModule('highloadblock') && CModule::IncludeModule('iblock')){
    $entity_data_class = GetEntityDataClass(3);
    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "order" => array("ID" => "DESC"),
        "filter" => array("!UF_ID_USER_A" => "", "!UF_ID_USER_B" => "")
    ));
    while($arFields = $rsData->Fetch()){

        $arUsersIds[] = $arFields['UF_ID_USER_A'];
        $arUsersIds[] = $arFields['UF_ID_USER_B'];
        $arContractsIds[] = $arFields['UF_ID_CONTRACT'];
        $arCompanyIds[] = $arFields['UF_ID_COMPANY_A'];
        $arCompanyIds[] = $arFields['UF_ID_COMPANY_B'];
        $arAgreIds[] = $arFields['ID'];

        $arAgre[] = array(
            "ID" => $arFields['ID'],
            "USER_AUTHOR" => $arFields['UF_ID_USER_A'],
            "USER_CONTRACTOR" => $arFields['UF_ID_USER_B'],
            "DATE_SIGNATURES" => $arFields['UF_TIME_SEND_USER_B'],
            "STATUS" => $arFields['UF_STATUS'],
            "CONTRACT" => $arFields['UF_ID_CONTRACT'],
            "COMPANY_AUTHOR" => $arFields['UF_ID_COMPANY_A'],
            "COMPANY_CONTRACTOR" => $arFields['UF_ID_COMPANY_B'],
            "AUTHOR_SIGNATUR" => $arFields['UF_VER_CODE_USER_A'],
            "CONTRACTOR_SIGNATUR" => $arFields['UF_VER_CODE_USER_B'],
        );

    }

    $arUsersIds = array_unique($arUsersIds);
    $arContractsIds = array_unique($arContractsIds);
    $arCompanyIds = array_unique($arCompanyIds);

    if(!empty($arUsersIds)){
        $rsUser = CUser::GetList($by="personal_country", $order="desc", array('ID' => $arUsersIds), array('FIELDS' => array('ID', 'LOGIN', 'NAME', 'LAST_NAME', 'SECOND_NAME')));
        while($array = $rsUser -> getNext()){
            $arUser[$array['ID']] = empty(trim($array['LAST_NAME'].$array['NAME'].$array['SECOND_NAME'])) ? $array['LOGIN'] : $array['LAST_NAME']." ".$array['NAME']." ".$array['SECOND_NAME'];
        }
    }

    if(!empty($arAgreIds)){
        $entity_data_class = GetEntityDataClass(7);
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID", "UF_TEXT_CONTRACT", "UF_ID_SEND_ITEM"),
            "order" => array("ID" => "DESC"),
            "filter" => array("UF_ID_SEND_ITEM" => $arAgreIds, "!UF_TEXT_CONTRACT" => "")
        ));
        while($arFields = $rsData->Fetch()){
            $arContract[$arFields['UF_ID_SEND_ITEM']] = array("ID" => $arFields['ID'], "TEXT" => $arFields['UF_TEXT_CONTRACT']);
        }


    }

    if(!empty($arContractsIds)){
        $res = CIBlockElement::GetList(Array(), array("ID" => $arContractsIds, "!PROPERTY_ID_PACT" => "", "IBLOCK_ID" => 6), false, false, array("ID", "IBLOCK_ID", "NAME", "PROPERTY_ID_PACT"));
        while($arFields = $res->GetNext())
        {
            $arDealIds[] = $arFields['PROPERTY_ID_PACT_VALUE'];
            $arEdit[$arFields['PROPERTY_ID_PACT_VALUE']] = $arFields['ID'];
        }
        if(!empty($arDealIds)){
            $arDealIds = array_unique($arDealIds);
            $res = CIBlockElement::GetList(Array(), array("ID" => $arDealIds, "IBLOCK_ID" => 3), false, false, array("ID", "IBLOCK_ID", "NAME", "PROPERTY_ID_DOGOVORA"));
            while($arFields = $res->GetNext())
                $arDeal[$arEdit[$arFields['ID']]] = array("ID" => $arFields['ID'], "NAME" => $arFields['NAME']);
        }
        $arContractsIds = array_unique($arContractsIds);
        $res = CIBlockElement::GetList(Array(), array("PROPERTY_ID_DOGOVORA" => $arContractsIds, "IBLOCK_ID" => 3), false, false, array("ID", "IBLOCK_ID", "NAME", "PROPERTY_ID_DOGOVORA"));
        while($arFields = $res->GetNext())
            $arDeal[$arFields['PROPERTY_ID_DOGOVORA_VALUE']] = array("ID" => $arFields['ID'], "NAME" => $arFields['NAME']);
    }

    if(!empty($arCompanyIds)){
        $res = CIBlockElement::GetList(Array(), array("PROPERTY_ID_DOGOVORA" => $arCompanyIds, "IBLOCK_ID" => 8), false, false, array("ID", "IBLOCK_ID", "NAME"));
        while($arFields = $res->GetNext())
        {
            $arCompany[$arFields['ID']] = array("ID" => $arFields['ID'], "NAME" => $arFields['NAME']);
        }
    }
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
									<div class="adm-list-table-cell-inner"><?=GetMessage("NFK_AS_DEAL_NAME")?></div>
								</td>
								<td class="adm-list-table-cell">
									<div class="adm-list-table-cell-inner"><?=GetMessage("NFK_AS_AUTHOR")?></div>
								</td>
								<td class="adm-list-table-cell">
									<div class="adm-list-table-cell-inner"><?=GetMessage("NFK_AS_CONTRACTOR")?></div>
								</td>
                                <td class="adm-list-table-cell">
									<div class="adm-list-table-cell-inner"><?=GetMessage("NFK_AS_STATUS")?></div>
								</td>
                                <td class="adm-list-table-cell">
									<div class="adm-list-table-cell-inner"><?=GetMessage("NFK_AS_TIME")?></div>
								</td>
                                <td class="adm-list-table-cell">
									<div class="adm-list-table-cell-inner"><?=GetMessage("NFK_AS_AUTHOR_COMPANY")?></div>
								</td>
                                <td class="adm-list-table-cell">
									<div class="adm-list-table-cell-inner"><?=GetMessage("NFK_AS_CONTRACTOR_COMPANY")?></div>
								</td>
                                <td class="adm-list-table-cell">
									<div class="adm-list-table-cell-inner"><?=GetMessage("NFK_AS_CONTRACT")?></div>
                                </td>
                                <td class="adm-list-table-cell">
									<div class="adm-list-table-cell-inner"><?=GetMessage("NFK_AS_AUTHOR_SIGNATURE")?></div>
                                </td>
                                <td class="adm-list-table-cell">
									<div class="adm-list-table-cell-inner"><?=GetMessage("NFK_AS_CONTRACTOR_SIGNATURE")?></div>
								</td>
							</tr>
						</thead>
						<tbody>
							<?if($arAgre){
								foreach($arAgre as $key => $data){
                                    if(empty($arDeal[$data['CONTRACT']]['ID'])) unset($arAgre[$key]);
                                    if(empty($arUser[$data['USER_AUTHOR']]) || $data['USER_AUTHOR'] == 1) unset($arAgre[$key]);
                                    if(empty($arUser[$data['USER_CONTRACTOR']]) || $data['USER_CONTRACTOR'] == 1) unset($arAgre[$key]);
                                }
                                AddMessage2Log($arAgre, "arAgre2");
                                $arAgre = paginator($arAgre,20);
                                foreach($arAgre['ITEMS'] as $data){
                                    ?>
									<tr class="adm-list-table-row">
										<td class="adm-list-table-cell align-left"><a href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=3&type=4&ID=<?=$arDeal[$data['CONTRACT']]['ID'];?>&lang=ru&find_section_section=-1&WF=Y" target="__blank"><?=$arDeal[$data['CONTRACT']]['NAME'];?></a></td>
										<td class="adm-list-table-cell align-left"><a href="/bitrix/admin/user_edit.php?lang=ru&ID=<?=$data['USER_AUTHOR'];?>" target="__blank"><?=$arUser[$data['USER_AUTHOR']];?></a></td>
										<td class="adm-list-table-cell align-left"><a href="/bitrix/admin/user_edit.php?lang=ru&ID=<?=$data['USER_CONTRACTOR'];?>" target="__blank"><?=$arUser[$data['USER_CONTRACTOR']];?></a></td>
										<td class="adm-list-table-cell align-left">
                                            <?if($data['STATUS'] == 3){?>
                                                Договор изменен и подписан с одной стороны
                                            <?}elseif($data['STATUS'] == 2){?>
                                                Подписан с двух сторон
                                            <?}elseif($data['STATUS'] == 1){?>
                                                Подписан с одной стороны
                                            <?}else{?>
                                                Отменен/Отказ
                                            <?}?>
                                        </td>
										<td class="adm-list-table-cell align-left">
                                            <?
                                            if ($data['DATE_SIGNATURES']) {
                                                echo $data['DATE_SIGNATURES']->format("d.m.Y H:i:s");
                                            }
                                            ?>
                                        </td>
										<td class="adm-list-table-cell align-left"><a href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=8&type=sprav&ID=<?=$arCompany[$data['COMPANY_AUTHOR']]['ID'];?>&lang=ru&find_section_section=-1&WF=Y" target="__blank"><?=$arCompany[$data['COMPANY_AUTHOR']]['NAME'];?></a></td>
										<td class="adm-list-table-cell align-left"><a href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=8&type=sprav&ID=<?=$arCompany[$data['COMPANY_CONTRACTOR']]['ID'];?>&lang=ru&find_section_section=-1&WF=Y" target="__blank"><?=$arCompany[$data['COMPANY_CONTRACTOR']]['NAME'];?></a></td>
										<td class="adm-list-table-cell align-right">
                                            <?if(!empty($arContract[$data['ID']])){?>
                                                <script>
                                                    var contract_<?=$data['ID']?> = {
                                                        TITLE: "Просмотр текста договора",
                                                        BODY: '<?=CUtil::JSEscape($arContract[$data['ID']]['TEXT'])?>',
                                                        BUTTONS: [
                                                            {
                                                                NAME: 'Закрыть',
                                                                CLOSE: 'Y'
                                                            }
                                                        ],
                                                        CLONE: "N"
                                                    };
                                                </script>
                                                <a onclick="newAnyPactPopUp(contract_<?=$data['ID']?>);" href="#">Показать текст</a>
                                            <?}?>
                                        </td>
                                        <td class="adm-list-table-cell align-left">
                                            <?if(!empty($data['AUTHOR_SIGNATUR'])){?>
                                                <script>
                                                    var AUTHOR_SIGNATUR_<?=$data['ID']?> = {
                                                        TITLE: "Просмотр текста договора",
                                                        BODY: '<p><?=$data['AUTHOR_SIGNATUR']?></p>',
                                                        BUTTONS: [
                                                            {
                                                                NAME: 'Закрыть',
                                                                CLOSE: 'Y'
                                                            }
                                                        ],
                                                        CLONE: "N"
                                                    };
                                                </script>
                                                <a onclick="newAnyPactPopUp(AUTHOR_SIGNATUR_<?=$data['ID']?>);" href="#">Показать</a>
                                            <?}?>
                                        </td>
                                        <td class="adm-list-table-cell align-left">
                                            <?if(!empty($data['CONTRACTOR_SIGNATUR'])){?>
                                                <script>
                                                    var CONTRACTOR_SIGNATUR_<?=$data['ID']?> = {
                                                        TITLE: "Просмотр текста договора",
                                                        BODY: '<p><?=$data['CONTRACTOR_SIGNATUR']?></p>',
                                                        BUTTONS: [
                                                            {
                                                                NAME: 'Закрыть',
                                                                CLOSE: 'Y'
                                                            }
                                                        ],
                                                        CLONE: "N"
                                                    };
                                                </script>
                                                <a onclick="newAnyPactPopUp(CONTRACTOR_SIGNATUR_<?=$data['ID']?>);" href="#">Показать</a>
                                            <?}?>
                                        </td>
									</tr>
								<?}?>
							<?}else{?>
								<tr><td colspan="8" class="adm-list-table-cell adm-list-table-empty">- <?=GetMessage("agreement_status_EMPTY_DATA")?> -</td></tr>
							<?}?>
						</tbody>
					</table>
                </form>
			</div>
        </div>
        <?
echo $arAgre['PAGINATION'];
// $APPLICATION->IncludeComponent("bitrix:main.pagenavigation", "modern", Array(
// 	"NAV_OBJECT" => $nav,
// 		"SEF_MODE" => "N"
// 	),
// 	false
// );
?>
	</td>
</tr>
<?$tabControl->EndTab();?>
<?$tabControl->End();?>

</form>
<?
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");
?>