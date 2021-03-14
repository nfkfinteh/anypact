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
<noindex>
    <div class="reg-wallet-overflow">
        <div class="reg-wallet-container">
            <div class="wallet-modal">
                <div class="reg-wallet-col">
                    <div class="reg-wallet-form">
                        <h2>Регистрация кошелька</h2>
                        <form action="<?=$arResult["REG_URL"]?>" method="post" name="moneta_reg_form">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <input disabled type="text" placeholder="Дата выдачи паспорта:" value="<?=$arResult["CURRENT_USER"]["UF_DATA_PASSPORT"]?>">
                                    <div class="form-wallet-item">
                                        <input disabled type="text" placeholder="Серия" value="<?=$arResult["CURRENT_USER"]["UF_SPASSPORT"]?>">
                                        <input disabled type="text" placeholder="Номер" value="<?=$arResult["CURRENT_USER"]["UF_NPASSPORT"]?>">
                                    </div>
                                    <input disabled type="text" placeholder="Кем выдан паспорт:" value="<?=$arResult["CURRENT_USER"]["UF_KEM_VPASSPORT"]?>">
                                    <input disabled type="text" placeholder="E-mail" value="<?=$arResult["CURRENT_USER"]["EMAIL"]?>">
                                    <input type="text" placeholder="Код подразделения" name="DEPARTMENT" value="">
                                    <input type="text" placeholder="Телефон" class="js-mask__phone" name="PHONE" value="<?=$arResult["CURRENT_USER"]["PERSONAL_PHONE"]?>">
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                        <input type="password" placeholder="Платежный пароль" aria-invalid="true" class="js-number validate-error" name="PAYMENT_PASS" value="">
                                        <input type="password" placeholder="Повторите платежный пароль" aria-invalid="true" class="js-number validate-error" name="PAYMENT_PASS_REPEAT" value="">
                                        <p class="moneta_p">Пароль должен состоять только из цифр, минимум пять символов</p>
                                    <div class="reg-wallet-form-special">
                                        <input class="<?if(!empty($arResult["CURRENT_USER"]["UF_SNILS"])) echo 'hidden-value';?>" type="text" placeholder="СНИЛС" name="SNILS" value="<?=$arResult["CURRENT_USER"]["UF_SNILS"]?>">
                                        <?if(!empty($arResult["CURRENT_USER"]["UF_SNILS"])){?><input type="hidden" name="D_S" value="Y"><?}?>
                                        <input class="<?if(!empty($arResult["CURRENT_USER"]["UF_INN"])) echo 'hidden-value';?>" type="text" placeholder="ИНН" maxlength="12" aria-invalid="true" class="js-number validate-error" name="INN" value="<?=$arResult["CURRENT_USER"]["UF_INN"]?>">
                                        <?if(!empty($arResult["CURRENT_USER"]["UF_INN"])){?><input type="hidden" name="D_I" value="Y"><?}?>
                                    </div>
                                    <p class="moneta_p">При нажатии на кнопку "Открыть" вы соглашаетесь передать свои персональные данные  сервиву Монета, а так же соглашаетесь с правилами использования сервиса Монета</p>
                                    <button class="btn btn-reg-wallet" type="submit" name="SUBMIT" value="Открыть">Открыть</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="reg-wallet-overflow_close">
                    <span></span>
                </div>
            </div>
        </div>
    </div>
    <?
    $signer = new \Bitrix\Main\Security\Sign\Signer;
    $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'moneta.registration');
    ?>
    <script>
        var MR_component = {
            params: <?=CUtil::PhpToJSObject($arParams)?>,
            signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
            siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
            ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
            templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
        };
    </script>
</noindex>