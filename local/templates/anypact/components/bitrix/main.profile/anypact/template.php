<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
/*
задача: профиль пользователя существует для нескольких ситуаций
1. Это переход от страницы авторизации с ЕСИА
2. Просто редактирование информации пользователем.
Для пользователя недоступно редактирование некоторых полей
*/
$disabled = '';
if (!empty($arResult['arUser']['UF_ESIA_ID']) && $arResult['arUser']['UF_ESIA_AUT']) {
    $disabled = 'disabled';
}
function hideText($text, $count_b = 3, $count_e = 2) {
    if(empty(trim($text)))
        return $text;
    $text1 = substr($text, 0, $count_b);
    $text2 = substr($text, $count_b);
    $text1 .= str_repeat("*", strlen($text2)-$count_e);
    if($count_e != 0)
        $text1 .= substr($text, -$count_e);
    return $text1;
}
?>
<!--Профиль пользователя-->
<div class="user_profile">
    <div class="user_profile_header">
        <div class="row">
            <div class="col">
                <h1>Профиль</h1>
            </div>
        </div>
    </div>
    <div id="form__personal-data">
        <div class="user_profile_form">
            <div class="row profile-reverse">
                <div class="col-lg-8 col-sm-10 col-xs-10" id="profile_tab_content">
                    <!-- ФИО, Паспорт -->
                    <form id="personal_data" class="edit-profile active show" action="/response/ajax/edit_personal.php">
                        <div class="user_profile_form_editdata">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12">
                                    <div class="user_profile_form_editdata_foto">
                                        <a href="/profile/edit_photo/">
                                        <?if(!empty($arResult['arUser']['IMG_URL'])) {?>
                                            <img src="<?=$arResult['arUser']['IMG_URL']?>" style="height: 100%; width: 100%; object-fit: cover;">
                                            <img src="<?=SITE_TEMPLATE_PATH?>/img/edit_user_photo.png">
                                        <?}else {?>
                                            <img src="<?=SITE_TEMPLATE_PATH?>/img/user_profile_no_foto.png" id="edit_user_photo">
                                        <?}?>
                                        </a>
                                        <!-- <input id='filePicture' name="PERSONAL_PHOTO" type="file" accept=".txt,image/*" style="display: none"> -->
                                    </div>
                                </div>
                            </div>
                            <h3>Личные данные</h3>
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label><?=GetMessage("LOGIN")?>:</label>
                                        
                                        <input type="text" value="<?=hideText($arResult["arUser"]["LOGIN"])?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("LAST_NAME")?></label>
                                        <input type="text" name="LAST_NAME" maxlength="50" value="<?=$arResult["arUser"]["LAST_NAME"]?>" <?=$disabled?>>
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("NAME")?></label>
                                        <input type="text" name="NAME" maxlength="50" value="<?=$arResult["arUser"]["NAME"]?>" <?=$disabled?>>
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("SECOND_NAME")?></label>
                                        <input type="text" name="SECOND_NAME" maxlength="50" value="<?=$arResult["arUser"]["SECOND_NAME"]?>" <?=$disabled?>>
                                    </div>
                                    <div class="form-group">
                                        <label style="width: 100%;"><?=GetMessage("DATAR_POL")?></label>
                                        <select name="PERSONAL_GENDER" style="width: 50%;height: 52px;float: left;margin-right: 5px;">
                                            <option value="" <?if(empty($arResult['arUser']['PERSONAL_GENDER'])):?>selected<?endif?>>не установлено</option>
                                            <option value="M" <?if($arResult['arUser']['PERSONAL_GENDER']=='M'):?>selected<?endif?>>Мужской</option>
                                            <option value="F" <?if($arResult['arUser']['PERSONAL_GENDER']=='F'):?>selected<?endif?>>Женский</option>
                                        </select>
                                        <!-- <input type="text" name="PERSONAL_GENDER" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_GENDER"]?>" style="width: 40%; float: left; margin-right: 10%;" > -->
                                        <span class="param_selected_activ_date">                                        
                                            <input id="param_selected_activ_date" type="text" name="PERSONAL_BIRTHDAY" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_BIRTHDAY"]?>" 
                                            style="width: 35%; border-radius: 5px 0 0 5px;float: left;margin-right: -1px;">                                    
                                            <div id="button_calendar">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/image/icon_calendar.png" />                                   
                                            </div>
                                        </span>                                
                                    </div>
                                    <div class="form-group" id="aut_esia">
                                        <? if (!empty($arResult['arUser']['UF_ETAG_ESIA']) && $arResult['arUser']['UF_ESIA_AUT']) {?>
                                            <img src="<?=SITE_TEMPLATE_PATH?>/img/gos_usl.png" style="margin: 30px 0 10px;"/>
                                            <p>Данные подтверждены с помощью учетной записи портала госуслуг</p>
                                        <?}else {?>
                                                <? // закодируем ссылку на возврат из ЕСИА
                                                    $encodeURL = base64_encode('/profile/');
                                                ?>
                                                <a href="/profile/aut_esia.php?returnurl=<?=$encodeURL?>">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/img/gos_usl.png" style="margin: 30px 0 10px;"/>
                                                <p>Подтверждение данных с помощью учетной записи портала госуслуг</p>                                        
                                            </a>
                                            <? if(!empty($arResult["arUser"]["UF_ESIA_ERROR"])){?>
                                                <p style="color: red;">&#9888; <?=$arResult["arUser"]["UF_ESIA_ERROR"]?></p>
                                            <?}?>
                                        <?}?>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-group ">
                                        <label><?=GetMessage("SNILS")?></label>
                                        <?if(!empty($arResult["arUser"]["UF_SNILS"])){?>
                                            <div class="hidden-value">
                                        <?}?>
                                        <input type="text" id='UF_SNILS' name="UF_SNILS" maxlength="50" value="<?=hideText($arResult["arUser"]["UF_SNILS"])?>" class="js-number" <?if(!empty($arResult["arUser"]["UF_SNILS"])){?>disabled<?}?>>
                                        <?if(!empty($arResult["arUser"]["UF_SNILS"])){?>
                                            </div>
                                        <?}?>
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("INN")?></label>
                                        <?if(!empty($arResult["arUser"]["UF_SNILS"])){?>
                                            <div class="hidden-value">
                                        <?}?>
                                        <input type="text" name="UF_INN" maxlength="12" value="<?=hideText($arResult["arUser"]["UF_INN"])?>" class="js-number" <?if(!empty($arResult["arUser"]["UF_SNILS"])){?>disabled<?}?>>
                                        <?if(!empty($arResult["arUser"]["UF_SNILS"])){?>
                                            </div>
                                        <?}?>
                                    </div>
                                    <div class="form-group">
                                        <label style="width: 100%;"><?=GetMessage("SN_PASSPORT")?></label>
                                        <input type="text" value="<?=hideText($arResult["arUser"]["UF_SPASSPORT"], 2, 0)?>" style="width: 20%; float: left; margin-right: 10%;" disabled>
                                        <input type="text" value="<?=hideText($arResult["arUser"]["UF_NPASSPORT"], 2, 0)?>" style="width: 70%;" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("DATA_PASSPORT")?></label>
                                        <input type="text" value="<?=hideText($arResult["arUser"]["UF_DATA_PASSPORT"], 2, 0)?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("KEM_V_PASSPORT")?></label>
                                        <input type="text" value="<?=hideText($arResult["arUser"]["UF_KEM_VPASSPORT"], 4)?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-aut edit-profile__btn save_profile_button">Сохранить</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--Пароль почта-->
                    <form id="account_settings" style="display: none;" class="edit-profile" action="/response/ajax/edit_personal.php">
                        <div class="user_profile_form_fixdata" style="margin-top: 40px;">
                            <h3 id="lichnue_dannue_bottom">Параметры аккаунта</h3>
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12 ">
                                    <div class="form-group">
                                        <label>Текущая электронная почта:</label>
                                        <?
                                        $hideEmail = explode("@", $arResult["arUser"]["EMAIL"]);
                                        ?>
                                        <input  type="text"  maxlength="50" value="<?=hideText($hideEmail[0])."@".$hideEmail[1]?>" class="js-mask__email" disabled>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-group ">
                                        <label>Изменить эл.почту:</label>
                                        <?if(!empty($arResult["arUser"]["EMAIL"])){?>
                                            <div class="hidden-value">
                                        <?}?>
                                        <input type="text"<?if(empty($arResult["arUser"]["EMAIL"])){?> name="EMAIL"<?}?> maxlength="50" value="<?=hideText($hideEmail[0])."@".$hideEmail[1]?>" class="js-mask__email" <?if(!empty($arResult["arUser"]["UF_SNILS"])){?>disabled<?}?>>
                                        <?if(!empty($arResult["arUser"]["EMAIL"])){?>
                                            </div>
                                        <?}?>
                                    </div>
                                    <button type="submit" class="btn btn-aut edit-profile__btn save_profile_button">Сохранить</button>
                                </div>
                            </div>
                        </div>
                        <div class="user_profile_form_fixdata" style="margin-top: 0px;">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12 ">
                                    <div class="form-group">
                                        <label>Введите старый пароль:</label>
                                        <input type="password" name="OLD_PASSWORD" maxlength="50" value="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                </div>
                            </div>
                        </div>
                        <div class="user_profile_form_fixdata" style="margin-top: 0px;">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12 ">
                                    <div class="form-group">
                                        <label>Сменить пароль:</label>
                                        <input type="password" name="PASSWORD" maxlength="50" value="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Повторите введеный пароль:</label>
                                        <input type="password" name="CONFIRM_PASSWORD" maxlength="50" value="" autocomplete="off">
                                    </div>
                                    <button type="submit" class="btn btn-aut edit-profile__btn save_profile_button">Сохранить</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--Адрес-->
                    <form id="address_data" style="display: none;" class="edit-profile" action="/response/ajax/edit_personal.php">
                        <div class="user_profile_form_fixdata" style="margin-top: 40px;">
                            <h3 id="lichnue_dannue_bottom">Адресные данные</h3>
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12 ">
                                    <div class="form-group">
                                        <label><?=GetMessage("USER_ZIP")?></label>
                                        <input type="text" name="PERSONAL_ZIP" maxlength="6" value="<?=$arResult["arUser"]["PERSONAL_ZIP"]?>" class="js-number">
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("USER_COUNTRY")?></label>
                                        <select name="PERSONAL_COUNTRY">
                                            <option value="<?=$arResult["arUser"]["PERSONAL_COUNTRY"]?>"><?=GetCountryByID($arResult["arUser"]["PERSONAL_COUNTRY"])?></option>
                                            <option value="1">Россия</option>
                                            <option value="4">Беларусь</option>
                                            <option value="6">Казахстан</option>
                                            <option value="7">Киргизия</option>
                                        </select>
                                        <?/*<input type="text" name="PERSONAL_COUNTRY" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_COUNTRY"]?>">*/?>
                                    </div>
                                    <?/*<div class="form-group">
                                        <label><?=GetMessage("USER_COUNTRY")?></label>
                                        <input type="text" name="PERSONAL_COUNTRY" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_COUNTRY"]?>">
                                    </div>*/?>
                                    <div class="form-group">
                                        <label><?=GetMessage("USER_STATE")?></label>
                                        <input type="text" name="PERSONAL_STATE" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_STATE"]?>">
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("USER_REGION")?></label>
                                        <input type="text" name="UF_REGION" maxlength="50" value="<?=$arResult["arUser"]["UF_REGION"]?>">
                                    </div>
                                    <?/*<div class="form-group">
                                        <label><?=GetMessage("USER_CITY")?></label>
                                        <input type="text" name="PERSONAL_CITY" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_CITY"]?>">
                                    </div>*/?>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                        <div class="form-group ">
                                                <label><?=GetMessage("USER_CITY")?></label>
                                                <input type="text" name="PERSONAL_CITY" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_CITY"]?>">
                                            </div>
                                    <!--<div class="form-group ">
                                        <label><?=GetMessage("USER_NPUNKT")?></label>
                                        <input type="text" name="UF_N_PUNKT" maxlength="50" value="<?=$arResult["arUser"]["UF_N_PUNKT"]?>">
                                        </div>-->
                                    <div class="form-group">
                                        <label><?=GetMessage("USER_STREET")?></label>
                                        <input type="text" name="UF_STREET" maxlength="50" value="<?=$arResult["arUser"]["UF_STREET"]?>">
                                    </div>
                                    <div class="form-group">
                                        <label style="width: 100%;"><?=GetMessage("USER_HOUSE")?></label>
                                        <input type="text" name="UF_N_HOUSE" maxlength="10" value="<?=$arResult["arUser"]["UF_N_HOUSE"]?>" class="js-number" style="width: 30%; float: left; margin-right: 10%;" >
                                        <input type="text" name="UF_N_HOUSING" maxlength="10" value="<?=$arResult["arUser"]["UF_N_HOUSING"]?>" class="js-number"  style="width: 20%; float: left; margin-right: 10%;" >
                                        <input type="text" name="UF_N_APARTMENT" maxlength="10" value="<?=$arResult["arUser"]["UF_N_APARTMENT"]?>" class="js-number" style="width: 30%;" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--Дополнительная информация-->
                    <form id="additional_information" style="display: none;" class="edit-profile" action="/response/ajax/edit_personal.php">
                        <div class="user_profile_form_editdata" style="margin-top: 40px;">
                            <h3 id="lichnue_dannue_bottom">Дополнительная информация</h3>
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 ">
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label><?=GetMessage("USER_PHONE")?></label>
                                                <input type="text" id="user_personal_phone" name="PERSONAL_PHONE" maxlength="225" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" class="js-mask__phone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div id="save_phone_error">
                                            <p>Указанный номер телефона уже используется другим пользователем.</p>
                                            <p>Это мой номер телефона - <a href="#">подтвердить</a></p>
                                        </div>
                                        <label><?=GetMessage("USER_WORK")?></label>
                                        <input type="text" name="UF_WORK" maxlength="225" value="<?=$arResult["arUser"]["UF_WORK"]?>">
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("USER_EDUCATION")?></label>
                                        <input type="text" name="UF_EDUCATION" maxlength="225" value="<?=$arResult["arUser"]["UF_EDUCATION"]?>">
                                    </div>
                                    <div class="form-group">
                                        <label>О себе:</label>
                                        <textarea name="UF_ABOUT" maxlength="1000"><?=$arResult["arUser"]["UF_ABOUT"]?></textarea>
                                    </div>
                                    <div class="form-group form-checkbox" style="padding-left: 21px;">
                                        <input type="checkbox"
                                            <?if($arResult["arUser"]["UF_HIDE_PROFILE"]):?>checked<?endif?>
                                            id="UF_HIDE_PROFILE"
                                            class="js-checkbox"
                                        >
                                        <label for="UF_HIDE_PROFILE">не показывать меня в результатах по итогам запроса через глобальный поиск</label>
                                        <input type="hidden" name="UF_HIDE_PROFILE" value="<?=$arResult["arUser"]["UF_HIDE_PROFILE"]?>" class="js-input_checkbox">
                                    </div>
                                    <div class="form-group form-checkbox" style="padding-left: 21px;">
                                        <input type="checkbox"
                                            <?if($arResult["arUser"]["UF_DISPLAY_PHONE"]):?>checked<?endif?>
                                            id="UF_DISPLAY_PHONE"
                                            class="js-checkbox"
                                        >
                                        <label for="UF_DISPLAY_PHONE">отображать телефон на странице</label>
                                        <input type="hidden" name="UF_DISPLAY_PHONE" value="<?=$arResult["arUser"]["UF_DISPLAY_PHONE"]?>" class="js-input_checkbox">
                                    </div>
                                    <div class="form-group form-checkbox" style="padding-left: 21px;">
                                        <input type="checkbox"
                                            <?if($arResult["arUser"]["UF_DISPLAY_DATE"]):?>checked<?endif?>
                                            id="UF_DISPLAY_DATE"
                                            class="js-checkbox"
                                        >
                                        <label for="UF_DISPLAY_DATE">отображать дату рождения на странице</label>
                                        <input type="hidden" name="UF_DISPLAY_DATE" value="<?=$arResult["arUser"]["UF_DISPLAY_DATE"]?>" class="js-input_checkbox">
                                    </div>
                                    <div class="form-group form-checkbox" style="padding-left: 21px;">
                                        <input type="checkbox"
                                            <?if($arResult["arUser"]["UF_DISPLAY_ADDRESS"]):?>checked<?endif?>
                                            id="UF_DISPLAY_ADDRESS"
                                            class="js-checkbox"
                                        >
                                        <label for="UF_DISPLAY_ADDRESS">отображать адрес на странице</label>
                                        <input type="hidden" name="UF_DISPLAY_ADDRESS" value="<?=$arResult["arUser"]["UF_DISPLAY_ADDRESS"]?>" class="js-input_checkbox">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-8 col-md-12 col-sm-12 ">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-aut edit-profile__btn save_profile_button">Сохранить</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Банк -->
                    <form id="bank_details" style="display: none;" class="edit-profile" action="/response/ajax/edit_personal.php">
                        <div class="user_profile_form_editdata" style="margin-bottom:50px;">
                            <h3 id="bankovskie_dannue">Банковские реквизиты</h3>
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12 ">
                                    <div class="form-group">
                                        <label><?=GetMessage("BANK_NAME")?></label>
                                        <input type="text" name="UF_N_BANK" maxlength="50" value="<?=$arResult["arUser"]["UF_N_BANK"]?>" >
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("BANK_BIC")?></label>
                                        <input type="text" name="UF_BIC_BANK" maxlength="9" value="<?=$arResult["arUser"]["UF_BIC_BANK"]?>" class="js-number">
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("UF_INN_BANK")?></label>
                                        <input type="text" name="UF_INN_BANK" maxlength="12" value="<?=$arResult["arUser"]["UF_INN_BANK"]?>" class="js-number">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-group ">
                                        <label><?=GetMessage("BANK_KS")?></label>
                                        <input type="text" name="UF_KS_BANK" maxlength="20" value="<?=$arResult["arUser"]["UF_KS_BANK"]?>" class="js-number">
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("BANK_RS")?></label>
                                        <input type="text" name="UF_RS_BANK" maxlength="20" value="<?=$arResult["arUser"]["UF_RS_BANK"]?>" class="js-number">
                                    </div>
                                    <div class="form-group">
                                        <label><?=GetMessage("UF_OTHER_PARAMS_BANK")?></label>
                                        <input type="text" name="UF_OTHER_PARAMS_BANK" maxlength="200" value="<?=$arResult["arUser"]["UF_OTHER_PARAMS_BANK"]?>">
                                    </div>
                                    <button type="submit" class="btn btn-aut save_profile_button">Сохранить</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div  class="col-lg-4 col-sm-2 col-xs-2">
                    <div class="profile-tabs">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active fi-rr-mode-portrait" data-toggle="pill" href="#personal_data"><span>Личные данные</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fi-rr-settings-sliders" data-toggle="pill" href="#account_settings"><span>Параметры аккаунта</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fi-rr-marker" data-toggle="pill" href="#address_data"><span>Адресные данные</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fi-rr-briefcase" data-toggle="pill" href="#additional_information"><span>Дополнительная информация</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fi-rr-credit-card" data-toggle="pill" href="#bank_details"><span>Банковские реквизиты</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Компания -->
<div class="user_profile_form_editdata" style="margin-bottom:50px;">
    <div class="row">
        <div class="col-xl-2 col-md-6 col-sm-12 ">
            <h3>Компании</h3>
        </div>
    </div>
    <div class="row">
        <?if($arResult['COMPANIES']){?>
            <?$i=0;?>
            <?foreach($arResult['COMPANIES'] as $key => $arCompany){?>
                <?if($arCompany['PROPERTY_TYPE_VALUE'] != "ИП"){?>
                    <div class="col-xl-4 col-md-6 col-sm-12 <?if($i==0 || $i % 2 === 0):?><?endif?>">
                        <p><?=$arCompany['NAME']?></p>
                        <?if($arCompany['PROPERTY_DIRECTOR_ID_VALUE'] == $arResult['ID']):?>
                            <a href="/profile/company/?id=<?=$arCompany['ID']?>" class="btn btn-aut" style="margin-bottom:15px;">Изменить компанию</a>
                            <a href="#" class="btn btn-aut btn-company-delete" data-company-id="<?=$arCompany['ID']?>" data-type="Компанию">Удалить компанию</a>
                        <?else:?>
                            <a href="/profile/company/?id=<?=$arCompany['ID']?>" class="btn btn-aut" style="margin-bottom:15px;">Реквизиты компаниии</a>
                        <?endif?>
                        <?if($i + 1 != count($arResult['COMPANIES'])){?><hr><?}?>
                    </div>
                    <?$i++;?>
                <?}?>
            <?}?>
        <?}?>
    </div>
    <div class="row add_company">
        <div class="col-xl-4 col-md-6 col-sm-12  add_company-button">
            <a href="/profile/company/" class="btn btn-aut <?if($arResult['arUser']['UF_ESIA_AUT']==0):?>disabled<?endif?>">+ Добавить компанию</a>
        </div>
    </div>
</div>
<!-- ИП -->
<?if($arResult['IS_IP'] != "Y"){?>
<div class="user_profile_form_editdata" style="margin-bottom:50px;">
    <div class="row">
        <div class="col-xl-2 col-md-6 col-sm-12 ">
            <h3>ИП</h3>
        </div>
    </div>
    <div class="row">
        <?if($arResult['COMPANIES']){?>
            <?foreach($arResult['COMPANIES'] as $key => $arCompany){?>
                <?if($arCompany['PROPERTY_TYPE_VALUE'] == "ИП"){?>
                    <?$is_ip = true;?>
                    <div class="col-xl-4 col-md-6 col-sm-12 ">
                        <p><?=$arCompany['NAME']?></p>
                        <?if($arCompany['PROPERTY_DIRECTOR_ID_VALUE'] == $arResult['ID']):?>
                            <a href="/profile/ip/?id=<?=$arCompany['ID']?>" class="btn btn-aut" style="margin-bottom:15px;">Изменить ИП</a>
                            <a href="#" class="btn btn-aut btn-company-delete" data-company-id="<?=$arCompany['ID']?>" data-type="ИП">Удалить ИП</a>
                        <?else:?>
                            <a href="/profile/ip/?id=<?=$arCompany['ID']?>" class="btn btn-aut" style="margin-bottom:15px;">Реквизиты ИП</a>
                        <?endif?>
                    </div>
                <?}?>
            <?}?>
        <?}?>
    </div>
    <?if(!$is_ip){?>
        <div class="row add_company">
            <div class="col-xl-4 col-md-6 col-sm-12  add_company-button">
                <a href="/profile/ip/" class="btn btn-aut <?if($arResult['arUser']['UF_ESIA_AUT']==0):?>disabled<?endif?>">+ Добавить ИП</a>
            </div>
        </div>
    <?}?>
</div>
<?}?>
</div>
</div>

    <!-- окно предупреждения удаления компании -- -->
    <noindex>
        <div id="companyDeleteWarning" class="bgpopup" style="display:none;">
            <div class="container">
                <div class="row align-items-center justify-content-center">            
                    <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6">
                        <div class="regpopup_win">     
                            <div id="signpopup_close">Х</div>                                       
                            <div class="regpopup_autorisation">
                                <label>Вы уверены что хотите удалить <span></span>?</label>
                                <a href="#" class="btn btn-nfk" id="delete_contact" style="width:45%;">Удалить</a>
                                <button class="btn btn-nfk" id="close_sign_popup" style="width:45%">Отмена</button>                      
                            </div>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
    </noindex>
    <!-- \\окно предупреждения подписания по ЕСИА -->   