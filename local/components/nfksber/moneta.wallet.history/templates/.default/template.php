<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */
?>
<?if($arResult['IS_AJAX'] != "Y"){?>
<div class="wallet-container_col" id="moneta_history">
    <h1>История операций</h1>
    <div class="date">
        <?
        $startDate = date('d.m.Y');
        $prevMonth = date('d.m.Y', strtotime("last month", strtotime($startDate)));
        ?>
        <input name="dateFrom" type="text" placeholder="Дата начала периода" value="<?=$prevMonth;?>">
        <input name="dateTo" type="text" placeholder="Дата конца периода" value="<?=$startDate;?>">
        <button class="btn" name="show" type="submit">Показать</button>
    </div>
    <div id="history_table">

    </div>
</div>
<?
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'moneta.wallet.history');
?>
<script>
    var MWH_component = {
        params: <?=CUtil::PhpToJSObject($arParams)?>,
        signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
        siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
        ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
        templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
    };
</script>
<?}else if($arResult['IS_AJAX'] == "Y"){?>
    <div>
    <table>
        <thead>
            <tr>
                <th scope="col">Номер</th>
                <th scope="col">Дата</th>
                <th scope="col">Тип операции</th>
                <th scope="col">Статус</th>
                <th scope="col">Сумма</th>
            </tr>
        </thead>
        <tbody>
        <?if(!empty($arResult['ITEMS'])){?>
            <?foreach($arResult['ITEMS'] as $arItem){?>
                <tr>
                    <td data-label="Номер"><?if($arItem['CATEGORY'] == 'Пополнение' && $arItem['STATUS'] == "Создан"){?><a target="_blank" href="https://www.payanyway.ru/assistant.htm?operationId=<?=$arItem['ID']?>&paymentSystem.unitId=card&paymentSystem.limitIds=card&followup=true"><?=$arItem['ID']?></a><?}else{echo $arItem['ID'];}?></td>
                    <td data-label="Дата"><?=$arItem['DATE']?></td>
                    <td data-label="Тип операции"><span class="wallet-td-black"><?=$arItem['CATEGORY']?></span></td>
                    <td data-label="Статус"><?if($arItem['STATUS'] == "Выполнен"){ echo '<span class="wallet-td-active">'; }echo $arItem['STATUS']; if($arItem['STATUS'] == "Выполнен"){ echo '</span>'; }?></td>
                    <td data-label="Сумма"><span class="wallet-td-black"><?=number_format($arItem['AMOUNT'], 2, ',', ' ');?> ₽</span></td>
                </tr>
            <?}?>
        <?}else if($arResult['page'] < 2){?>
            <tr>
                <td colspan="5" style="text-align: center;"><span class="wallet-td-black">Нет данных</span></td>
            </tr>
        <?}?>
        </tbody>
    </table>
    <?if($arResult['pagination']){
        echo $arResult['pagination'];
    }?>
    </div>
<?}?>