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
$BLACKLIST_display = "";
if($arResult["BLACKLIST"]['CLOSE']){
    $BLACKLIST_display = 'style="display: none;"';
}?>
<div class="col-md-3 new-profile_img-block">
    <h1 class="mobile"><?=$arResult['USER']['LAST_NAME']?> <?=$arResult['USER']['NAME']?> <?=$arResult['USER']['SECOND_NAME']?></h1>
    <?if(!empty($arResult['USER']['PERSONAL_PHOTO'])){?>
        <?$renderImage = CFile::ResizeImageGet($arResult['USER']['PERSONAL_PHOTO'], Array("width" => 261), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false);?>
        <img src="<?=$renderImage['src'];?>" alt="">
    <?}else{?>
        <img src="<?=SITE_TEMPLATE_PATH;?>/image/people-search-no-phpto.png" alt="">
    <?}?>
    <div class="profile-user-control_block">
        <?if(!$arResult["BLACKLIST"]['CLOSED']){?>
            <?if(!empty($arResult['CURRENT_USER']['ID']) && $arResult['USER']['ID'] != $arResult['CURRENT_USER']['ID']):?>
                <button class="btn btn-nfk search-people__button" data-id="<?=$arResult['USER']['ID']?>">Отправить сообщение</button>
            <?endif?>
            <div <?=$BLACKLIST_display;?> class="black-list-show_hide request_sent">
                <?if(!empty($arResult['CURRENT_USER']['ID']) && $arResult['USER']['ID'] != $arResult['CURRENT_USER']['ID']):?>
                    <?if(in_array($arResult['USER']['ID'], $arResult['FRENDS'])):?>
                        <button class="btn btn-nfk gray js-delete-frends" data-id="<?=$arResult['USER']['ID']?>">
                            Удалить из друзей
                        </button>
                    <?elseif(in_array($arResult['USER']['ID'], $arResult['FRIENDS_REQUEST'])):?>
                        <button class="btn btn-nfk gray disabled" data-id="<?=$arResult['USER']['ID']?>">
                            Заявка отправлена
                        </button>
                        <div class="not_auth-error">
                            <div class="triangle" style="display: block; z-index: 1;">▲</div>
                            <a href="#" class="js-delete-frends" data-id="<?=$arResult['USER']['ID']?>">
                                Отменить заявку
                            </a>
                        </div>
                    <?elseif(in_array($arResult['USER']['ID'], $arResult['SUBSCRIPTION'])):?>
                        <button class="btn btn-nfk gray js-delete-frends" data-id="<?=$arResult['USER']['ID']?>">
                            Отписаться
                        </button>
                    <?elseif($arResult['CURRENT_USER']['ID'] != $arResult['USER']['ID']):?>
                        <button class="btn btn-nfk gray js-add-frends" data-id="<?=$arResult['USER']['ID']?>">
                            Добавить в друзья
                        </button>
                    <?endif;?>
                <?else:?>
                    <a href="/profile/" class="btn btn-nfk gray">
                        Редактировать профиль
                    </a>
                <?endif;?>
            </div>
            <?if($arResult['CURRENT_USER']['UF_MONETA_CHECK_STAT'] == "SUCCESS" && $arResult['CURRENT_USER']['ID'] != $arResult['USER']['ID']):?>
                <button <?=$BLACKLIST_display;?> class="btn btn-nfk gray js-send-money black-list-show_hide" data-id="<?=$arResult['USER']['ID']?>">
                    Перевести деньги
                </button>
            <?endif;?>
        <?}?>
        <?if($arResult['CURRENT_USER']['ID'] != $arResult['USER']['ID']){?>
            <?if(!$arResult["BLACKLIST"]['CLOSE']):?>
                <button class="btn btn-nfk gray js-add-blacklist" data-id="<?=$arResult['USER']['ID']?>">
                    Заблокировать
                </button>
            <?else:?>
                <button class="btn btn-nfk gray js-delete-blacklist" data-id="<?=$arResult['USER']['ID']?>">
                    Разблокировать
                </button>
            <?endif?>
        <?}?>
    </div>
    <?if($arResult['CURRENT_USER']['ID'] != $arResult['USER']['ID'] && !empty($arResult['COMPANY_CURRENT_USER'])){?>
        <div class="profile-representative-copmany_block black-list-show_hide">
            <p>Назначить представителем компании:</p>
            <div class="custom-select-new-profile">
                <form class="js-company__btn" data-user="<?=$arResult['USER']['ID']?>">
                    <select class="company list__select">
                        <?foreach($arResult['COMPANY_CURRENT_USER'] as $comp):?>
                            <option value="<?=$comp['ID']?>"><?=$comp['NAME']?></option>
                        <?endforeach?>
                    </select>
                    <?if($arResult['COMPANY_CURRENT_USER'][0]['STAFF_NO_ACTIVE']):?>
                        <button class="btn btn-nfk disabled">Заявка на модерации</button>
                    <?elseif($arResult['COMPANY_CURRENT_USER'][0]['STAFF']):?>
                        <button class="btn btn-nfk js-delete-staff" data-company="<?=$arResult['COMPANY_CURRENT_USER'][0]['ID']?>">Удалить представителя</button>
                    <?else:?>
                        <button class="btn btn-nfk js-add-staff" data-company="<?=$arResult['COMPANY_CURRENT_USER'][0]['ID']?>">Сделать представителем</button>
                    <?endif?>
                </form>
            </div>
        </div>
        <script>
            var bitrixCompanyList = <?=CUtil::PhpToJSObject($arResult['COMPANY_CURRENT_USER'])?>
        </script>
    <?}?>
    <div class="profile-user-info_block">
        <p>Частное лицо</p>
        <?if($arResult['USER']['UF_ESIA_AUT']==1):?>
            <span class="d-block font-weight-bold">Подтвержденная регистрация</span>
            <span class="d-block registration-checked"><img src="/local/templates/anypact/img/gosuslugi.svg"
                    style="width:50%;"></span>
        <?endif?>
    </div>
</div>
<div class="col-md-9">
    <div class="row new-profile-title_block">
        <div class="col-md-12">
            <div class="new-profile_block new-profile_block-bb">
                <h1 class="desc"><?=$arResult['USER']['LAST_NAME']?> <?=$arResult['USER']['NAME']?> <?=$arResult['USER']['SECOND_NAME']?></h1>
                <h2>О себе</h2>
                <p><?=$arResult['USER']['UF_ABOUT']?></p>
            </div>
        </div>
    </div>
    <div class="row new-profile_container__about new-profile_block">
        <div class="col-md-12">
            <div class="new-profile_container__about_items">
                <div class="new-profile_container__about_item new-profile_container__about_item-info_block">
                    <div class="new-profile_container__about_item-info">
                        <?$k = 0;?>
                        <?if(!empty($arResult['USER']['PERSONAL_BIRTHDAY']) && $arResult['USER']['UF_DISPLAY_DATE'] == 1):?>
                            <?$k++;?>
                            <div class="new-profile_container__about_item-info_item">
                                <p>Дата рождения:</p>
                                <p><span><?=$arResult['USER']['PERSONAL_BIRTHDAY']?></span></p>
                            </div>
                        <?endif?>
                        <?if(!empty($arResult['USER']['PERSONAL_GENDER'])){?>
                            <?$k++;?>
                            <div class="new-profile_container__about_item-info_item">
                                <p>Пол:</p>
                                <p>
                                    <span>
                                        <?if($arResult['USER']['PERSONAL_GENDER']=='M'):?>
                                            Мужской
                                        <?elseif($arResult['USER']['PERSONAL_GENDER']=='F'):?>
                                            Женский
                                        <?endif?>
                                    </span>
                                </p>
                            </div>
                        <?}?>
                        <?if(!empty($arResult['USER']['PERSONAL_CITY'])){?>
                            <?$k++;?>
                            <div class="new-profile_container__about_item-info_item">
                                <p>Город:</p>
                                <p><span><?=$arResult['USER']['PERSONAL_CITY']?></span></p>
                            </div>
                        <?}?>
                        <?if(!empty($arResult['USER']['PERSONAL_PHONE']) && $arResult['USER']['UF_DISPLAY_PHONE'] == 1){?>
                            <?$k++;?>
                            <div class="new-profile_container__about_item-info_item">
                                <p>Телефон:</p>
                                <p><span><a href="tel:<?=$arResult['USER']['PERSONAL_PHONE']?>"><?=$arResult['USER']['PERSONAL_PHONE']?></a></span></p>
                            </div>
                        <?}?>
                        <?if($k < 4){?>
                            <?if(!empty($arResult['USER']['UF_WORK'])){?>
                                <?$k++;?>
                                <div class="new-profile_container__about_item-info_item">
                                    <p>Работа:</p>
                                    <?$work = true;?>
                                    <p><span><?=$arResult['USER']['UF_WORK']?></span></p>
                                </div>
                            <?}?>
                        <?}?>
                        <?if($k < 4){?>
                            <?if(!empty($arResult['USER']['UF_EDUCATION'])){?>
                                <?$k++;?>
                                <div class="new-profile_container__about_item-info_item">
                                    <p>Образование:</p>
                                    <?$education = true;?>
                                    <p><span><?=$arResult['USER']['UF_EDUCATION']?></span></p>
                                </div>
                            <?}?>
                        <?}?>
                        <?if($k < 4){?>
                            <?if($arResult['USER']['UF_DISPLAY_ADDRESS'] == 1 && (!empty($arResult['USER']['PERSONAL_ZIP']) || !empty($arResult['USER']['PERSONAL_CITY']) || !empty($arResult['USER']['PERSONAL_COUNTRY']) || !empty($arResult['USER']['UF_STREET']) || !empty($arResult['USER']['PERSONAL_STATE']) || !empty($arResult['USER']['UF_REGION']) || !empty($arResult['USER']['UF_N_HOUSE']) || !empty($arResult['USER']['UF_N_HOUSING']) || !empty($arResult['USER']['UF_N_APARTMENT']))){?>
                                <div class="new-profile_container__about_item-info_item">
                                    <p>Адрес:</p>
                                    <?$address = true;?>
                                    <p>
                                        <span>
                                            <?
                                            $text = "";
                                            if(!empty($arResult['USER']['PERSONAL_ZIP'])) $text .= $arResult['USER']['PERSONAL_ZIP'];
                                            if(!empty($text)) $text .= " ";
                                            if(!empty($arResult['USER']['PERSONAL_COUNTRY'])) $text .= GetCountryByID($arResult['USER']['PERSONAL_COUNTRY'], "ru");
                                            if(!empty($text)) $text .= " ";
                                            if(!empty($arResult['USER']['PERSONAL_STATE'])) $text .= $arResult['USER']['PERSONAL_STATE'];
                                            if(!empty($text)) $text .= ", ";
                                            if(!empty($arResult['USER']['PERSONAL_CITY'])) $text .= "г.".$arResult['USER']['PERSONAL_CITY'];
                                            if(!empty($text)) $text .= ", ";
                                            if(!empty($arResult['USER']['UF_REGION'])) $text .= $arResult['USER']['UF_REGION']." район";
                                            if(!empty($text)) $text .= ", ";
                                            if(!empty($arResult['USER']['UF_STREET'])) $text .= "ул. ".$arResult['USER']['UF_STREET'];
                                            if(!empty($text)) $text .= ", ";
                                            if(!empty($arResult['USER']['UF_N_HOUSE'])) $text .= "дом ".$arResult['USER']['UF_N_HOUSE'];
                                            if(!empty($text)) $text .= ", ";
                                            if(!empty($arResult['USER']['UF_N_HOUSING'])) $text .= "копус ".$arResult['USER']['UF_N_HOUSING'];
                                            if(!empty($text)) $text .= ", ";
                                            if(!empty($arResult['USER']['UF_N_APARTMENT'])) $text .= "кв. ".$arResult['USER']['UF_N_APARTMENT'];
                                            ?>
                                            <?=$text;?>
                                        </span>
                                    </p>
                                </div>
                            <?}?>
                        <?}?>
                    </div>
                </div>
                <?if(!empty($arResult['COMPANY'])):?>
                    <div class="new-profile_container__about_item">
                        <p>Представитель компании:</p>
                        <?
                        if(count($arResult['COMPANY']) > 2){
                            $i = 1;
                            foreach ($arResult['COMPANY'] as $key => $company):?>
                                <p><a href="/profile_user/?type=company&ID=<?=$company['ID']?>"><?=$company['NAME']?></a></p>
                                <?
                                unset($arResult['COMPANY'][$key]);
                                if($i == 2)
                                    break;
                                $i++;
                            endforeach;?>
                            <p class="more-org">Еще <?=count($arResult['COMPANY'])?></p>
                            <div class="extra-profile-org hide">
                                <?foreach ($arResult['COMPANY'] as $company):?>
                                    <p><a href="/profile_user/?type=company&ID=<?=$company['ID']?>"><?=$company['NAME']?></a></p>
                                <?endforeach;?>
                            </div>
                            <?
                        }else{
                            foreach ($arResult['COMPANY'] as $company):?>
                                <p><a href="/profile_user/?type=company&ID=<?=$company['ID']?>"><?=$company['NAME']?></a></p>
                            <?endforeach;
                        }?>
                    </div>
                <?endif?>
            </div>
            <?if($k >= 4 && (!$work || !$education || !$address) && (!empty($arResult['USER']['UF_WORK']) || !empty($arResult['USER']['UF_EDUCATION']) || ($arResult['USER']['UF_DISPLAY_ADDRESS'] == 1 && (!empty($arResult['USER']['PERSONAL_ZIP']) || !empty($arResult['USER']['PERSONAL_CITY']) || !empty($arResult['USER']['PERSONAL_COUNTRY']) || !empty($arResult['USER']['UF_STREET']) || !empty($arResult['USER']['PERSONAL_STATE']) || !empty($arResult['USER']['UF_REGION']) || !empty($arResult['USER']['UF_N_HOUSE']) || !empty($arResult['USER']['UF_N_HOUSING']) || !empty($arResult['USER']['UF_N_APARTMENT']))))){?>
                <div class="new-profile_container__about_items show-extra-info">
                    <div class="new-profile_container__about_item new-profile_container__about_item-info_block">
                        <div class="new-profile_container__about_item-info">
                            <?if(!$work){?>
                                <?if(!empty($arResult['USER']['UF_WORK'])){?>
                                    <div class="new-profile_container__about_item-info_item">
                                        <p>Работа:</p>
                                        <p><span><?=$arResult['USER']['UF_WORK']?></span></p>
                                    </div>
                                <?}?>
                            <?}?>
                            <?if(!$education){?>
                                <?if(!empty($arResult['USER']['UF_EDUCATION'])){?>
                                    <div class="new-profile_container__about_item-info_item">
                                        <p>Образование:</p>
                                        <p><span><?=$arResult['USER']['UF_EDUCATION']?></span></p>
                                    </div>
                                <?}?>
                            <?}?>
                            <?if(!$address){?>
                                <?if($arResult['USER']['UF_DISPLAY_ADDRESS'] == 1 && (!empty($arResult['USER']['PERSONAL_ZIP']) || !empty($arResult['USER']['PERSONAL_CITY']) || !empty($arResult['USER']['PERSONAL_COUNTRY']) || !empty($arResult['USER']['UF_STREET']) || !empty($arResult['USER']['PERSONAL_STATE']) || !empty($arResult['USER']['UF_REGION']) || !empty($arResult['USER']['UF_N_HOUSE']) || !empty($arResult['USER']['UF_N_HOUSING']) || !empty($arResult['USER']['UF_N_APARTMENT']))){?>
                                    <div class="new-profile_container__about_item-info_item">
                                        <p>Адрес:</p>
                                        <p>
                                            <span>
                                                <?
                                                $text = "";
                                                if(!empty($arResult['USER']['PERSONAL_ZIP'])) $text .= $arResult['USER']['PERSONAL_ZIP'];
                                                if(!empty($text)) $text .= " ";
                                                if(!empty($arResult['USER']['PERSONAL_COUNTRY'])) $text .= GetCountryByID($arResult['USER']['PERSONAL_COUNTRY'], "ru");
                                                if(!empty($text)) $text .= " ";
                                                if(!empty($arResult['USER']['PERSONAL_STATE'])) $text .= $arResult['USER']['PERSONAL_STATE'];
                                                if(!empty($text)) $text .= ", ";
                                                if(!empty($arResult['USER']['PERSONAL_CITY'])) $text .= "г.".$arResult['USER']['PERSONAL_CITY'];
                                                if(!empty($text)) $text .= ", ";
                                                if(!empty($arResult['USER']['UF_REGION'])) $text .= $arResult['USER']['UF_REGION']." район";
                                                if(!empty($text)) $text .= ", ";
                                                if(!empty($arResult['USER']['UF_STREET'])) $text .= "ул. ".$arResult['USER']['UF_STREET'];
                                                if(!empty($text)) $text .= ", ";
                                                if(!empty($arResult['USER']['UF_N_HOUSE'])) $text .= "дом ".$arResult['USER']['UF_N_HOUSE'];
                                                if(!empty($text)) $text .= ", ";
                                                if(!empty($arResult['USER']['UF_N_HOUSING'])) $text .= "копус ".$arResult['USER']['UF_N_HOUSING'];
                                                if(!empty($text)) $text .= ", ";
                                                if(!empty($arResult['USER']['UF_N_APARTMENT'])) $text .= "кв. ".$arResult['USER']['UF_N_APARTMENT'];
                                                ?>
                                                <?=$text;?>
                                            </span>
                                        </p>
                                    </div>
                                <?}?>
                            <?}?>
                        </div>
                    </div>
                </div>
            <?}?>
            </div>
            <?if($k >= 4 && (!$work || !$education || !$address) && (!empty($arResult['USER']['UF_WORK']) || !empty($arResult['USER']['UF_EDUCATION']) || ($arResult['USER']['UF_DISPLAY_ADDRESS'] == 1 && (!empty($arResult['USER']['PERSONAL_ZIP']) || !empty($arResult['USER']['PERSONAL_CITY']) || !empty($arResult['USER']['PERSONAL_COUNTRY']) || !empty($arResult['USER']['UF_STREET']) || !empty($arResult['USER']['PERSONAL_STATE']) || !empty($arResult['USER']['UF_REGION']) || !empty($arResult['USER']['UF_N_HOUSE']) || !empty($arResult['USER']['UF_N_HOUSING']) || !empty($arResult['USER']['UF_N_APARTMENT']))))){?>
                <div class="col-md-12 text-center">
                    <p class="more-info" id="more-info">Больше информации<span></span></p>
                </div>
            <?}?>
    </div>

<?if(!$arResult["BLACKLIST"]['CLOSE'] && !$arResult["BLACKLIST"]['CLOSED'] && $arResult['CURRENT_USER']['UF_MONETA_CHECK_STAT'] == "SUCCESS"){
?>
    <script>
        var MWI_component = {
            signedParamsString: 'YToyOntzOjE1OiJBQ1RJT05fVkFSSUFCTEUiO3M6NjoiYWN0aW9uIjtzOjE2OiJ+QUNUSU9OX1ZBUklBQkxFIjtzOjY6ImFjdGlvbiI7fQ==.dcd0780958d211e10d3dfc13fd847865682ffd2da156926b480374e074db8ee8',
            siteID: 's1',
            ajaxUrl: '/local/components/nfksber/moneta.wallet.info/ajax.php',
        };
        $(document).on('click', '.js-send-money', function(){
<?
    if($arResult['USER']['UF_MONETA_CHECK_STAT'] == "SUCCESS"){?>
            var sendMonetaPopup = newAnyPactPopUp({
                TITLE: 'Перевод',
                BODY: '<div><form name="TRANSFER"><input type="hidden" name="acc_id" value="<?=$arResult['USER']['UF_MONETA_ACCOUNT_ID']?>"><input type="text" class="js-number" name="amount" placeholder="Сумма перевода"></form></div>',
                BUTTONS: [
                    {
                        NAME: 'Отмена',
                        SECONDARY: 'Y',
                        CLOSE: 'Y'
                    },
                    {
                        NAME: 'Перевести',
                        CALLBACK: (function(){
                            var amount = $('form[name="TRANSFER"] input[name="amount"]').val();
                            // var payment_pass = $('form[name="TRANSFER"] input[name="payment_pass"]').val();
                            var acc_id = $('form[name="TRANSFER"] input[name="acc_id"]').val();
                            if(amount < 10){
                                showResult('#popup-error','Ошибка! Сумма перевода должна быть больше 9 рублей');
                                return false;
                            }
                            // if(payment_pass.length < 5){
                            //     showResult('#popup-error','Неверный платежный пароль');
                            //     return false;
                            // }
                            if(acc_id.length < 2){
                                showResult('#popup-error','Неверный номер счета');
                                return false;
                            }
                            BX.ajax({
                                url: MWI_component.ajaxUrl,
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    via_ajax: 'Y',
                                    action: 'makeTransfer',
                                    sessid: BX.bitrix_sessid(),
                                    SITE_ID: MWI_component.siteID,
                                    signedParamsString: MWI_component.signedParamsString,
                                    amount: amount,
                                    // payment_pass: payment_pass,
                                    acc_id: acc_id,
                                },
                                onsuccess: function(result){
                                    sendMonetaPopup.parent('.new-pu-overflow').remove();
                                    if($('.new-pu-overflow').length < 1)
                                        $('body').css("overflow", "auto");
                                    if(result["STATUS"] == "WRONG")
                                        showResult('#popup-error', 'Ошибка! ',result['ERROR_DESCRIPTION']);
                                    else
                                        showResult('#popup-error',"Деньги были переведены");
                                },
                                onfailure: function(a, b, c){
                                    console.log(a);
                                    console.log(b);
                                    console.log(c);
                                    showResult('#popup-error','Ошибка! Неизвестная ошибка');
                                }
                            });
                        })
                    }
                ]
            });
    <?}else{?>
        var sendMonetaPopup = newAnyPactPopUp({
                TITLE: 'Кошелек отсутсвует',
                BODY: '<div><p>У данного пользователя <b>отсутвует кошелек</b><br><br>Вы можете выслать ему приглашение зарегистрировать кошелек нажав на кнопку <b>"Отправить"</b>.</p></div>',
                BUTTONS: [
                    {
                        NAME: 'Отмена',
                        SECONDARY: 'Y',
                        CLOSE: 'Y'
                    },
                    {
                        NAME: 'Отправить',
                        CALLBACK: (function(){
                            BX.ajax({
                                url: MWI_component.ajaxUrl,
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    via_ajax: 'Y',
                                    action: 'sendMail',
                                    sessid: BX.bitrix_sessid(),
                                    SITE_ID: MWI_component.siteID,
                                    signedParamsString: MWI_component.signedParamsString,
                                    user_id: <?=$arResult['USER']['ID']?>
                                },
                                onsuccess: function(result){
                                    sendMonetaPopup.parent('.new-pu-overflow').remove();
                                    if($('.new-pu-overflow').length < 1)
                                        $('body').css("overflow", "auto");
                                    if(result["STATUS"] == "WRONG")
                                        showResult('#popup-error', 'Ошибка! ',result['ERROR_DESCRIPTION']);
                                    else
                                        showResult('#popup-error',"Письмо было отправлено");
                                },
                                onfailure: function(a, b, c){
                                    console.log(a);
                                    console.log(b);
                                    console.log(c);
                                    showResult('#popup-error','Ошибка! Неизвестная ошибка');
                                }
                            });
                        })
                    }
                ]
            });
    <?}?>
        });
    </script><?
}?>