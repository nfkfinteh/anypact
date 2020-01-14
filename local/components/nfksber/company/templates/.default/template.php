<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
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
    <?if($arResult['IS_DIRECTOR']=='Y'):?>
        <form id="form__company_profile" action="<?=$APPLICATION->GetCurPage()?>" enctype="multipart/form-data" method="post">
        <?if($arResult['ERROR']){?>
            <p class="error"><?=$arResult['ERROR']?></p>
        <?}?>
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
                        <input <?if(in_array($prop["CODE"], $arParams['PROPERTIES_NUMBER'])){?>onkeypress='validateNumber(event)'<?}?> type="text" name="<?=$prop["CODE"]?>" value="<?=$arResult['COMPANY']['PROPERTIES'][$prop["CODE"]]['VALUE']?>"<?if(in_array($prop["CODE"], $arParams['PROPERTIES_NEED'])){?> required<?}?>>
                    </div>
                    <?unset($arResult['PROPERTIES'][$key]);?>
                    <?if($prop['CODE'] == 'OFFICE' || $i == 15) break;?>
                <?}?>
            </div>
            <div class="col-xl-6 col-md-12 col-sm-12">
                <?foreach($arResult['PROPERTIES'] as $prop){?>
                    <div class="form-group">
                        <label><?=$prop["NAME"]?></label>
                        <input <?if(in_array($prop["CODE"], $arParams['PROPERTIES_NUMBER'])){?>onkeypress='validateNumber(event)'<?}?> type="text" name="<?=$prop["CODE"]?>" value="<?=$arResult['COMPANY']['PROPERTIES'][$prop["CODE"]]['VALUE']?>"<?if(in_array($prop["CODE"], $arParams['PROPERTIES_NEED'])){?> required<?}?>>
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
                    <?if($arResult['STAFF']){?>
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
                                    <div class="staff_znak_block add_staff staff_znak-ok"><span class="staff_znak"></span></div>
                                </div>
                            <?}?>
                        </div>
                    <?}?>
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
                    <input name="DIRECTOR_ID" value="<?=$USER->GetID()?>" hidden>
                    <input name="DIRECTOR_NAME" value="<?echo $USER->GetLastName().' '.$USER->GetFirstName(). ' '.$USER->GetParam("SECOND_NAME")?>" hidden>
                    <button type="submit" class="btn btn-aut edit-profile__btn" id="save_company">Сохранить</button>
                </div>
            </div>
        </div>
    </form>
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
                    <?if($arResult['COMPANY']['PREVIEW_PICTURE']){?>
                        <div class="form-group">
                            <label>Логотип</label>
                            <img class="company-logo" src="<?=$arResult['COMPANY']['PREVIEW_PICTURE']?>" alt="">
                        </div>
                    <?}?>
                    <div class="form-group">
                        <label>Сотрудники</label>
                        <?if($arResult['STAFF']){?>
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
                        <?}?>
                    </div>
                </div>
            </div>
    <?endif?>
</div>