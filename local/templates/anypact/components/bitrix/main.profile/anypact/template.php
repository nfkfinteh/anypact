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
?>
<!--Профиль пользователя-->
<div class="user_profile">
    <div class="user_profile_header">
        <div class="row">
            <div class="col">
                <h1>Личный кабинет</h1>
            </div>
        </div>
    </div>
    <div class="user_profile_form">
        <h3 id="lichnue_dannue_top">Личные данные</h3>
        <div class="user_profile_form_editdata">
            <div class="row">
                <form id="form__personal-data" class="edit-profile" action="/response/ajax/edit_personal.php">
                    <div class="col-xl-7 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xl-5 col-md-6 col-sm-12">
                            <div class="user_profile_form_editdata_foto">
                                <?if(!empty($arResult['arUser']['IMG_URL'])) {?>
                                    <img src="<?=$arResult['arUser']['IMG_URL']?>" style="height: 100%; width: 100%; object-fit: cover;">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/img/edit_user_photo.png">
                                <?}else {?>
                                    <img src="<?=SITE_TEMPLATE_PATH?>/img/user_profile_no_foto.png" id="edit_user_photo">
                                <?}?>
                                <input id='filePicture' name="PERSONAL_PHOTO" type="file" accept=".txt,image/*" style="display: none">
                            </div>
                                </div>
                            <div class="col-xl-7 col-md-12 col-sm-12">
                                <h3 id="lichnue_dannue_bottom">Личные данные</h3>
                                <div class="form-group">
                                    <label><?=GetMessage("USER_ZIP")?></label>
                                    <input type="text" name="PERSONAL_ZIP" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_ZIP"]?>">
                                </div>
                                <div class="form-group">
                                    <label><?=GetMessage("USER_COUNTRY")?></label>
                                    <select name="PERSONAL_COUNTRY">                                    
                                        <option value="<?=$arResult["arUser"]["PERSONAL_COUNTRY"]?>"><?=GetCountryByID($arResult["arUser"]["PERSONAL_COUNTRY"])?></option>    
                                        <option value="1">Россия</option>
                                        <option value="4">Беларусь</option>
                                        <option value="6">Казахстан</option>
                                        <option value="7">Киргизия</option>
                                        <option value="76">Китай</option>
                                    </select>
                                    <!-- <input type="text" name="PERSONAL_COUNTRY" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_COUNTRY"]?>"> -->
                                </div>
                                <!-- <div class="form-group">
                                    <label><?=GetMessage("USER_COUNTRY")?></label>
                                    <input type="text" name="PERSONAL_COUNTRY" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_COUNTRY"]?>">
                                </div> -->
                                <div class="form-group">
                                    <label><?=GetMessage("USER_STATE")?></label>
                                    <input type="text" name="PERSONAL_STATE" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_STATE"]?>">
                                </div>
                                <div class="form-group">
                                    <label><?=GetMessage("USER_REGION")?></label>
                                    <input type="text" name="UF_REGION" maxlength="50" value="<?=$arResult["arUser"]["UF_REGION"]?>">
                                </div>
                                <!-- <div class="form-group">
                                    <label><?=GetMessage("USER_CITY")?></label>
                                    <input type="text" name="PERSONAL_CITY" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_CITY"]?>">
                                </div> -->
                                <div class="form-group form-checkbox" style="padding-left: 21px;">
                                    <input type="checkbox" <?if($arResult["arUser"]["UF_HIDE_PROFILE"]):?>checked<?endif?> id="hide_profile" name="hide_profile">
                                    <label for="hide_profile" style="padding: 44px 20px;">не показывать меня в поиске</label>
                                    <input type="hidden" name="UF_HIDE_PROFILE" value="<?=$arResult["arUser"]["UF_HIDE_PROFILE"]?>" class="hide_profile_input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-sm-12">
                            <div class="form-group left_blok_margin_first">
                                    <label><?=GetMessage("USER_CITY")?></label>
                                    <input type="text" name="PERSONAL_CITY" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_CITY"]?>">
                                </div>
                        <!--<div class="form-group left_blok_margin_first">
                            <label><?=GetMessage("USER_NPUNKT")?></label>
                            <input type="text" name="UF_N_PUNKT" maxlength="50" value="<?=$arResult["arUser"]["UF_N_PUNKT"]?>">
                            </div>-->
                        <div class="form-group">
                            <label><?=GetMessage("USER_STREET")?></label>
                            <input type="text" name="UF_STREET" maxlength="50" value="<?=$arResult["arUser"]["UF_STREET"]?>">
                        </div>
                        <div class="form-group">
                            <label style="width: 100%;"><?=GetMessage("USER_HOUSE")?></label>
                            <input type="text" name="UF_N_HOUSE" maxlength="50" value="<?=$arResult["arUser"]["UF_N_HOUSE"]?>" style="width: 30%; float: left; margin-right: 10%;" >
                            <input type="text" name="UF_N_HOUSING" maxlength="50" value="<?=$arResult["arUser"]["UF_N_HOUSING"]?>" style="width: 20%; float: left; margin-right: 10%;" >
                            <input type="text" name="UF_N_APARTMENT" maxlength="50" value="<?=$arResult["arUser"]["UF_N_APARTMENT"]?>" style="width: 30%;" >
                        </div>
                        <div class="form-group">
                            <label><?=GetMessage("EMAIL")?></label>
                            <input type="text" name="EMAIL" maxlength="50" value="<?=$arResult["arUser"]["EMAIL"]?>">
                        </div>
                        <div class="form-group">
                            <label><?=GetMessage("USER_PHONE")?></label>
                            <input type="text" name="PERSONAL_PHONE" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>">
                        </div>
                        <button type="submit" class="btn btn-aut edit-profile__btn" id="save_profile_button">Сохранить</button>
                    </div>
                
            </div>
        </div>
        <div class="user_profile_form_fixdata">
            <div class="row">
                <div class="col-xl-4 col-md-6 col-sm-12 offset-xl-3">
                    <h3>Личные данные</h3>
                    <div class="form-group">
                        <label><?=GetMessage("LOGIN")?></label>
                        <input type="text" name="LOGIN" maxlength="50" value="<?=$arResult["arUser"]["LOGIN"]?>" disabled>
                    </div>
                    <div class="form-group">
                        <label><?=GetMessage("LAST_NAME")?></label>
                        <input type="text" name="LAST_NAME" maxlength="50" value="<?=$arResult["arUser"]["LAST_NAME"]?>" disabled>
                    </div>
                    <div class="form-group">
                        <label><?=GetMessage("NAME")?></label>
                        <input type="text" name="NAME" maxlength="50" value="<?=$arResult["arUser"]["NAME"]?>" disabled>
                    </div>
                    <div class="form-group">
                        <label><?=GetMessage("SECOND_NAME")?></label>
                        <input type="text" name="SECOND_NAME" maxlength="50" value="<?=$arResult["arUser"]["SECOND_NAME"]?>" disabled>
                    </div>
                    <div class="form-group">
                        <label style="width: 100%;"><?=GetMessage("DATAR_POL")?></label>
                        <input type="text" name="PERSONAL_GENDER" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_GENDER"]?>" style="width: 40%; float: left; margin-right: 10%;" >
                        <input type="text" name="PERSONAL_BIRTHDAY" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_BIRTHDAY"]?>" style="width: 50%;">
                    </div>                    
                    <div class="form-group">
                        <? if (!empty($arResult['arUser']['UF_ETAG_ESIA']) && $arResult['arUser']['UF_ESIA_AUT']) {?>                                                
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/gos_usl.png" />
                            <p>Данные подтверждены с помощью учетной записи портала госуслуг</p>                                                   
                        <?}else {?>
                            <a href="/profile/aut_esia.php">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/gos_usl.png" />
                                <p>Подтверждение данных с помощью учетной записи портала госуслуг</p>                                 
                            </a>
                        <?}?>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 col-sm-12">
                    <div class="form-group left_blok_margin_ub">
                        <label><?=GetMessage("SNILS")?></label>
                        <input type="text" name="UF_SNILS" maxlength="50" value="<?=$arResult["arUser"]["UF_SNILS"]?>" >
                    </div>
                    <div class="form-group">
                        <label><?=GetMessage("INN")?></label>
                        <input type="text" name="UF_INN" maxlength="50" value="<?=$arResult["arUser"]["UF_INN"]?>" >
                    </div>
                    <div class="form-group">
                        <label style="width: 100%;"><?=GetMessage("SN_PASSPORT")?></label>
                        <input type="text" name="UF_SPASSPORT" maxlength="50" value="<?=$arResult["arUser"]["UF_SPASSPORT"]?>" style="width: 20%; float: left; margin-right: 10%;" disabled>
                        <input type="text" name="UF_NPASSPORT" maxlength="50" value="<?=$arResult["arUser"]["UF_NPASSPORT"]?>" style="width: 70%;" disabled>
                    </div>                    
                    <div class="form-group">
                        <label><?=GetMessage("DATA_PASSPORT")?></label>
                        <input type="text" name="LAST_NAME" maxlength="50" value="<?=$arResult["arUser"]["UF_DATA_PASSPORT"]?>" disabled>
                    </div>
                    <div class="form-group">
                        <label><?=GetMessage("KEM_V_PASSPORT")?></label>
                        <input type="text" name="LAST_NAME" maxlength="50" value="<?=$arResult["arUser"]["UF_KEM_VPASSPORT"]?>" disabled>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-aut edit-profile__btn" id="save_profile_button">Сохранить</button>
                    </div>    
                </div>                
                </form>
            </div>
        </div>
        <div class="user_profile_form_editdata" style="margin-bottom:50px;">
            <form id="form__bank-data" class="edit-profile" action="/response/ajax/edit_personal.php">
                <div class="row">
                    <div class="col-xl-4 col-md-6 col-sm-12 offset-xl-3">
                        <h3 id="bankovskie_dannue">Банковские реквизиты</h3>
                        <div class="form-group">
                            <label><?=GetMessage("BANK_NAME")?></label>
                            <input type="text" name="UF_N_BANK" maxlength="50" value="<?=$arResult["arUser"]["UF_N_BANK"]?>" >
                        </div>
                        <div class="form-group">
                            <label><?=GetMessage("BANK_BIC")?></label>
                            <input type="text" name="UF_BIC_BANK" maxlength="50" value="<?=$arResult["arUser"]["UF_BIC_BANK"]?>" >
                        </div>
                        <div class="form-group">
                            <label><?=GetMessage("UF_INN_BANK")?></label>
                            <input type="text" name="UF_INN_BANK" maxlength="50" value="<?=$arResult["arUser"]["UF_INN_BANK"]?>" >
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-sm-12">
                        <div class="form-group left_blok_margin">
                            <label><?=GetMessage("BANK_KS")?></label>
                            <input type="text" name="UF_KS_BANK" maxlength="50" value="<?=$arResult["arUser"]["UF_KS_BANK"]?>" >
                        </div>
                        <div class="form-group">
                            <label><?=GetMessage("BANK_RS")?></label>
                            <input type="text" name="UF_RS_BANK" maxlength="50" value="<?=$arResult["arUser"]["UF_RS_BANK"]?>" >
                        </div>
                        <button type="submit" class="btn btn-aut" id="save_profile_button">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>    
</div>