<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*
 *  $GlobalParamsCompany - основные параметры компании
 *  $AdressCompany -  адрес компании
 *  $BanksParamsCompany - банковские реквизиты компании
 * */
    $arrGlobalParamsKey = ['NAME', 'INN', 'KPP', 'OGRN' ];
    $GlobalParamsCompany  = array_intersect_key($arResult['PROPERTIES'], $arrGlobalParamsKey);


?>
<div class="company_profile">
    <div class="company_profile_header">
        <div class="row">
            <div class="col">
                <?if($arResult['COMPANY']){?>
                    <h1>Компания</h1>
                <?}else{?>
                    <h1>Добавить компанию</h1>
                <?}?>
            </div>
        </div>
    </div>
    <? // пользователь в с татусе директора может редактировать реквизиты?>
    <?if($arResult['IS_DIRECTOR']=='Y'):?>
    <form id="form__company_profile" action="<?=$APPLICATION->GetCurPage()?>" enctype="multipart/form-data" method="post">
        <?if($arResult['ERROR']){?>
            <p class="error"><?=$arResult['ERROR']?></p>
        <?}?>
        <div class="row">
            <div class="col-xl-4 col-md-6 col-sm-12 offset-xl-3"">
                <h3>Общие</h3>
            </div>
        </div>
        <? ////////////////////// поля общих реквизитов /////////////////////////?>
        <div class="row">
            <div class="col-xl-7 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-xl-5 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Логотип компании</label>
                            <?if($arResult['COMPANY']['PREVIEW_PICTURE']){?>
                                <img class="company-logo" src="<?=$arResult['COMPANY']['PREVIEW_PICTURE']?>" alt="">
                            <?}?>
                            <input class="company-logo-input" name="PREVIEW_PICTURE" type="file" accept="image/jpeg,image/png,image/gif">
                        </div>
                    </div>
                    <div class="col-xl-7 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Название компании</label>
                            <input name="NAME" type="text" value="<?=$arResult['COMPANY']['NAME']?>" required>
                        </div>
                        <div class="form-group">
                            <label>ИНН</label>
                            <input name="INN" type="text" value="<?=$arResult['COMPANY']['INN']?>" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-12 col-sm-12">
                <div class="form-group">
                    <label>ОГРН</label>
                    <input name="OGRN" type="text" value="<?=$arResult['COMPANY']['OGRN']?>" required>
                </div>
                <div class="form-group">
                    <label>КПП</label>
                    <input name="KPP" type="text" value="<?=$arResult['COMPANY']['KPP']?>" required>
                </div>
            </div>
        </div>
        <? ////////////////////// поля адреса /////////////////////////?>
        <div class="row" style="margin-top: 40px;">
            <div class="col-xl-4 col-md-6 col-sm-12 offset-xl-3"">
                <h3>Юридический адрес компании</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-md-6 col-sm-12 offset-xl-3">
                <div class="form-group">
                    <label>Индекс</label>
                    <input name="INDEX" type="text" value="<?=$arResult['COMPANY']['INDEX']?>" required>
                </div>
                <div class="form-group">
                    <label>Город</label>
                    <input name="CITY" type="text" value="<?=$arResult['COMPANY']['CITY']?>" required>
                </div>
                <div class="form-group">
                    <label>Улица</label>
                    <input name="STREET" type="text" value="<?=$arResult['COMPANY']['STREET']?>" required>
                </div>
                <div class="form-group">
                    <label>Дом</label>
                    <input name="HOUSE" type="text" value="<?=$arResult['COMPANY']['HOUSE']?>" required>
                </div>
                <div class="form-group">
                    <label>Офис</label>
                    <input name="OFFICE" type="text" value="<?=$arResult['COMPANY']['OFFICE']?>" required>
                </div>
            </div>
            <div class="col-xl-4 col-md-12 col-sm-12">
                <div class="form-group">
                    <label>Область</label>
                    <input name="REGION" type="text" value="<?=$arResult['COMPANY']['REGION']?>" required>
                </div>
                <div class="form-group">
                    <label>Район</label>
                    <input name="DISTRICT" type="text" value="<?=$arResult['COMPANY']['DISTRICT']?>" required>
                </div>
                <div class="form-group">
                    <label>Населенный пункт</label>
                    <input name="LOCALITY" type="text" value="<?=$arResult['COMPANY']['LOCALITY']?>" required>
                </div>
                <div class="form-group">
                    <label>Корпус</label>
                    <input name="KORP" type="text" value="<?=$arResult['COMPANY']['KORP']?>" required>
                </div>
            </div>
        </div>
        <? ////////////////////// платежные реквизиты /////////////////////////?>
        <div class="row" style="margin-top: 40px;">
            <div class="col-xl-4 col-md-6 col-sm-12 offset-xl-3">
                <h3>Банковские реквизиты компании</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-md-6 col-sm-12 offset-xl-3">
                <div class="form-group">
                    <label>Наименование банка</label>
                    <input name="BANK" type="text" value="<?=$arResult['COMPANY']['BANK']?>" required>
                </div>                
                <div class="form-group">
                    <label>Расчетный счет</label>
                    <input name="RAS_ACCOUNT" type="text" value="<?=$arResult['COMPANY']['RAS_ACCOUNT']?>" required>
                </div>
                <div class="form-group">
                    <label>ИНН Банка</label>
                    <input name="INN_BANK" type="text" value="<?=$arResult['COMPANY']['INN_BANK']?>" required>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 col-sm-12">
                <div class="form-group">
                    <label>БИК</label>
                    <input name="BIK" type="text" value="<?=$arResult['COMPANY']['BIK']?>" required>
                </div>
                <div class="form-group">
                    <label>Кор. Счет</label>
                    <input name="OGRN" type="text" value="<?=$arResult['COMPANY']['NAME']?>" required>
                </div>
            </div>
        </div>
        <? ////////////////////// сохранение  /////////////////////////?>
        <div class="form-group">
            <?if($arResult['COMPANY']['ID']){?>
                <input name="ID_EXIST" value="<?=$arResult['COMPANY']['ID']?>" hidden>
            <?}?>
            <input name="STAFF" value="<?=$value_staff?>" hidden>
            <input name="STAFF_NO_ACTIVE" value="<?=$value_staff_no?>" hidden>
            <input name="DIRECTOR_ID" value="<?=$USER->GetID()?>" hidden>
            <input name="DIRECTOR_NAME" value="<?echo $USER->GetLastName().' '.$USER->GetFirstName(). ' '.$USER->GetParam("SECOND_NAME")?>" hidden>            
        </div>
        <div class="row" style="margin: 40px 0;">
            <div class="col-xl-4 col-md-6 col-sm-12 offset-xl-3">
                <button type="submit" class="btn btn-aut edit-profile__btn" id="save_company">Сохранить</button>
            </div>
        </div>
    </form>
</div>

<!---
        <div class="row">
            <div class="col-xl-6 col-md-12 col-sm-12">
                <div class="form-group">
                    <label>Название</label>
                    <input name="NAME" type="text" value="<?=$arResult['COMPANY']['NAME']?>" required>
                </div>
                <?$i=0;
                foreach($arResult['PROPERTIES'] as $key => $prop){?>
                    <div class="form-group">
                        <label><?=$prop["NAME"]?></label>
                        <input type="text" name="<?=$prop["CODE"]?>" value="<?=$arResult['COMPANY']['PROPERTIES'][$prop["CODE"]]['VALUE']?>"<?if(in_array($prop["CODE"], $arParams['PROPERTIES_NEED'])){?> required<?}?>>
                    </div>
                    <?unset($arResult['PROPERTIES'][$key]);?>
                    <?if($prop['CODE'] == 'OFFICE' || $i == 15) break;?>
                <?}?>
            </div>
            <div class="col-xl-6 col-md-12 col-sm-12">
                <?foreach($arResult['PROPERTIES'] as $prop){?>
                    <div class="form-group">
                        <label><?=$prop["NAME"]?></label>
                        <input type="text" name="<?=$prop["CODE"]?>" value="<?=$arResult['COMPANY']['PROPERTIES'][$prop["CODE"]]['VALUE']?>"<?if(in_array($prop["CODE"], $arParams['PROPERTIES_NEED'])){?> required<?}?>>
                    </div>
                <?}?>
                <div class="form-group" id="preview-picture">
                    <label>Логотип</label>
                    <?if($arResult['COMPANY']['PREVIEW_PICTURE']){?>
                        <img class="company-logo" src="<?=$arResult['COMPANY']['PREVIEW_PICTURE']?>" alt="">
                    <?}?>
                    <input class="company-logo-input" name="PREVIEW_PICTURE" type="file" accept="image/jpeg,image/png,image/gif">
                </div>
                <div class="form-group">
                    <label>Сотрудники</label>
                    <div class="staff_list">
                        <?//подтвержденные сотрудники?>
                        <?if(!empty($arResult['STAFF'])):?>
                            <?foreach($arResult['STAFF'] as $arItem){?>
                                <?$value_staff .= ' '.$arItem['ID'].','?>
                                <div class="staff_man" data-id="<?=$arItem['ID']?>">
                                    <?$name = '';
                                    if($arItem['NAME']) $name = $arItem['NAME'];
                                    if($arItem['LAST_NAME']){
                                        if($name) $name .= ' '.$arItem['LAST_NAME']; else $name = $arItem['LAST_NAME'];
                                    }
                                    if($name){?>
                                        <p><?=$name?> (<?=$arItem['EMAIL']?>)</p>
                                    <?}else{?>
                                        <p><?=$arItem['EMAIL']?></p>
                                    <?}?>
                                    <div class="staff_znak_block add_staff staff_znak-ok"><span class="staff_znak"></span></div>
                                </div>
                            <?}?>
                        <?endif?>
                        <?//не подтвержденные сотрудники?>
                        <?if(!empty($arResult['STAFF_NO_ACTIVE'])):?>
                            <?foreach($arResult['STAFF_NO_ACTIVE'] as $arItem){?>
                                <?$value_staff_no .= ' '.$arItem['ID'].','?>
                                <div class="staff_man" data-id="<?=$arItem['ID']?>">
                                    <?$name = '';
                                    if($arItem['NAME']) $name = $arItem['NAME'];
                                    if($arItem['LAST_NAME']){
                                        if($name) $name .= ' '.$arItem['LAST_NAME']; else $name = $arItem['LAST_NAME'];
                                    }
                                    if($name){?>
                                        <p><?=$name?> (<?=$arItem['EMAIL']?>) - не подтвержден</p>
                                    <?}else{?>
                                        <p><?=$arItem['EMAIL']?> - не подтвержден</p>
                                    <?}?>
                                    <div class="staff_znak_block add_staff__no-active staff_znak-ok"><span class="staff_znak"></span></div>
                                </div>
                            <?}?>
                        <?endif?>
                    </div>
                    <div class="form-group">
                        <input id="staff" type="text" value="" placeholder="Введите email сотрудника">
                        <div class="btn btn-aut" id="search_staff">Поиск</div>
                    </div>
                    <div class="search-result"></div>
                </div>
                <div class="form-group">
                    <?if($arResult['COMPANY']['ID']){?>
                        <input name="ID_EXIST" value="<?=$arResult['COMPANY']['ID']?>" hidden>
                    <?}?>
                    <input name="STAFF" value="<?=$value_staff?>" hidden>
                    <input name="STAFF_NO_ACTIVE" value="<?=$value_staff_no?>" hidden>
                    <input name="DIRECTOR_ID" value="<?=$USER->GetID()?>" hidden>
                    <input name="DIRECTOR_NAME" value="<?echo $USER->GetLastName().' '.$USER->GetFirstName(). ' '.$USER->GetParam("SECOND_NAME")?>" hidden>
                    <button type="submit" class="btn btn-aut edit-profile__btn" id="save_company">Сохранить</button>
                </div>
            </div>
        </div>
    </form>
    --->
    <?// только просмотр реквизитов?>
    <?else:?>
        <div class="row">
                <div class="col-xl-6 col-md-12 col-sm-12">
                    <div class="form-group">
                        <label>Название</label>
                        <div class="editbox"><?=$arResult['COMPANY']['NAME']?></div>
                    </div>
                    <?$i=0;
                    foreach($arResult['PROPERTIES'] as $key => $prop){?>
                        <div class="form-group">
                            <label><?=$prop["NAME"]?></label>
                            <div class="editbox"><?=$arResult['COMPANY']['PROPERTIES'][$prop["CODE"]]['VALUE']?></div>
                        </div>
                        <?unset($arResult['PROPERTIES'][$key]);?>
                        <?if($prop['CODE'] == 'OFFICE' || $i == 15) break;?>
                    <?}?>
                </div>
                <div class="col-xl-6 col-md-12 col-sm-12">
                    <?foreach($arResult['PROPERTIES'] as $prop){?>
                        <div class="form-group">
                            <label><?=$prop["NAME"]?></label>
                            <div class="editbox"><?=$arResult['COMPANY']['PROPERTIES'][$prop["CODE"]]['VALUE']?></div>
                        </div>
                    <?}?>
                    <? /*Логотип компании*/?>
                    <?if($arResult['COMPANY']['PREVIEW_PICTURE']){?>
                        <div class="form-group">
                            <label>Логотип</label>
                            <img class="company-logo" src="<?=$arResult['COMPANY']['PREVIEW_PICTURE']?>" alt="">
                        </div>
                    <?}?>
                    <?if($arResult['STAFF']){?>
                        <div class="form-group">
                            <label>Сотрудники</label>
                            <div class="staff_list">
                                <?foreach($arResult['STAFF'] as $arItem){?>
                                    <?$value_staff = ' '.$arItem['ID'].','?>
                                    <div class="staff_man" data-id="<?=$arItem['ID']?>">
                                        <?$name = '';
                                        if($arItem['NAME']) $name = $arItem['NAME'];
                                        if($arItem['LAST_NAME']){
                                            if($name) $name .= ' '.$arItem['LAST_NAME']; else $name = $arItem['LAST_NAME'];
                                        }
                                        if($name){?>
                                            <p><?=$name?> (<?=$arItem['EMAIL']?>)</p>
                                        <?}else{?>
                                            <p><?=$arItem['EMAIL']?></p>
                                        <?}?>
                                    </div>
                                <?}?>
                            </div>
                        </div>
                    <?}?>
                </div>
            </div>
    <?endif?>
</div>
<pre>
<?
    print_r($arResult['COMPANY']);
?>
</pre>