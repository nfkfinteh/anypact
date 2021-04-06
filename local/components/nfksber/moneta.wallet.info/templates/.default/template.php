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
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/__Moneta.log");
AddMessage2Log($_REQUEST, "request");
?><?
if($arResult['GET_WITHDRAWAL'] == "Y"){
    ?>
    <div>
        <form name="WITHDRAWAL">
            <?if(is_array($arResult['PAYMENTS']) && count($arResult['PAYMENTS']) >= 1){
                $pay = "Y";?>
                <select name="cart_id">
                    <option disabled selected>Выберите карту</option>
                    <?foreach($arResult['PAYMENTS'] as $payment){?>
                        <option value="<?=$payment['ID']?>"><?=$payment['UF_CARD_NAME']?></option>
                    <?}?>
                    <option value="0">Другая карта</option>
                </select>
                <?
            }?>
            <input type="text" name="cart_number" value="" placeholder="Номер карты" inputmode="text">
            <input type="password" placeholder="Платежный пароль" aria-invalid="true" class="js-number validate-error" name="payment_pass" value="">
            <input type="text" class="js-number validate-error" aria-invalid="true" name="amount" placeholder="Сумма пополнения">
        </form>
    </div>
    <?
}else{?>
<h2>Счет</h2>
<p>Номер кошелька</p>
<div class="wallet-number">
    <p><span id="wallet-number"><?=$arResult['USER']['UF_MONETA_ACCOUNT_ID']?></span></p>
    <div class="wallet-tooltip">
        <span class="tooltiptext" id="myTooltip">Копировать</span>
        <img src="<?=SITE_TEMPLATE_PATH.'/image/copy-wallet.svg'?>" id="copyText" alt="">
    </div>
</div>
<?if($arResult['USER']['UF_MONETA_CHECK_STAT'] == 'CREATED'){?>
    <div class="acc_status">
        Аккаунт проходит идентификацию, пожалуйста подождите
    </div>
<?}else if($arResult['USER']['UF_MONETA_CHECK_STAT'] != 'SUCCESS'){?>
    <div class="acc_status">
        Что бы воспользоваться всеми возможностями кошелька <a href="" id="profile_identify">идентифицируйте свой профиль</a>
    </div>
<?}?>
<div class="balance">
    <div class="balance-container">
        <div class="balance-col">
            <p>Баланс</p>
            <p><span><?=number_format($arResult['USER']['UF_MONETA_BALANCE'], 2, ',', ' ');?> ₽</span></p>
        </div>
        
    </div>
    <div class="btn-balance-container">
        <a href="#" id="moneta_deposit_btn">Пополнить</a>
        <?if($arResult['USER']['UF_MONETA_CHECK_STAT'] == 'SUCCESS'){?>
            <a href="#" id="moneta_transfer_btn">Перевод</a>
            <a href="#" id="moneta_withdrawal_btn">Вывод средств</a>
        <?}?>
    </div>
</div>
<?
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'moneta.wallet.info');
?>
<script>
    var MWI_component = {
        params: <?=CUtil::PhpToJSObject($arParams)?>,
        signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
        siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
        ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
        templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
    };
</script>
<?if($_GET['SuccessfulDebit'] == "Y"){
    ?>
    <script>
        showPaymentPopup("Баланс успешно пополнен!");
    </script>
<?}else if($_GET['FailedDebit'] == "Y"){
    ?>
    <script>
        showPaymentPopup("Пополение баланса было отменено!");
    </script>
<?}?>
<?}?>