<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
?>
<?if(!empty($arResult['ITEMS']) || !empty($arResult["ITEMS_NO_ACTIVE"])):?>
    <div class="row grid-view">
        <?foreach($arResult["ITEMS"] as $arItem):?>
            <!-----------------профили компаний------------------->
            <div class="view-item col-lg-2 col-sm-3 col-6 mt-4 pb-3">
                <div class="people-s-photo">
                    <div class="people-s-photo-img <?if($arParams['COMPANY_ID'] != $arItem['ID']):?>js-auth_company<?endif?>" data-id="<?=$arItem['ID']?>">
                        <? if(!empty($arItem['PREVIEW_PICTURE'])){ ?>
                            <?
                            $renderImage = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false);
                            ?>
                            <img class="people-s-user-photo" src="<?=$renderImage["src"];?>">
                        <?}else {?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png" alt="">
                        <? } ?>
                    </div>
                </div>
                <div class="people-s-photo-text">
                    <div class="people-s-photo-text-block">
                        <h6><?=$arItem['NAME']?></h6>
                        <?/*
                        <div class="grid-hidden-text">
                        </div>
                        */?>
                    </div>
                    <?if($arParams['COMPANY_ID'] != $arItem['ID']):?>
                        <div class="people-s-photo-btn-block">
                            <button class="btn btn-aut js-auth_company" data-id="<?=$arItem['ID']?>">Выбрать</button>
                        </div>
                    <?//else:?>
                        <!-- <div class="people-s-photo-btn-block">
                            <a href="/profile/" class="btn btn-aut active-company">
                                Продолжить с текущим профилем
                            </a>
                        </div> -->
                    <?endif?>
                </div>
            </div>
        <?endforeach?>
        <?/*
        <?foreach($arResult["ITEMS_NO_ACTIVE"] as $arItem):?>
            <!-----------------профили компаний не подтвержденных------------------->
            <div class="view-item col-lg-2 col-sm-3 col-6 mt-4 pb-3">
                <div class="people-s-photo">
                    <div class="people-s-photo-img <?if($arParams['COMPANY_ID'] != $arItem['ID']):?>js-auth_company<?endif?>" data-id="<?=$arItem['ID']?>">
                        <? if(!empty($arItem['PREVIEW_PICTURE'])){ ?>
                            <?
                            $renderImage = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false);
                            ?>
                            <img class="people-s-user-photo" src="<?=$renderImage["src"];?>">
                        <?}else {?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png" alt="">
                        <? } ?>
                    </div>
                </div>
                <div class="people-s-photo-text">
                    <div class="people-s-photo-text-block">
                        <h6><?=$arItem['NAME']?></h6>
                    </div>
                    <div class="people-s-photo-btn-block">
                        <button class="btn btn-aut js-accept_company" data-id="<?=$arItem['ID']?>">Присоединиться</button>
                    </div>
                    <div class="people-s-photo-btn-block ">
                        <button class="btn btn-aut js-refus_company" data-id="<?=$arItem['ID']?>">Отказаться</button>
                    </div>

                </div>
            </div>
        <?endforeach?>
        */?>
        <!------------------профили пользователей------------------>
        <div class="view-item col-lg-2 col-sm-4 col-6 mt-4 pb-3">
            <?
                $rsUser = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $USER -> GetId()), array("FIELDS" => array("PERSONAL_PHOTO")));
                $arUser = $rsUser -> GetNext();
            ?>
            <div class="people-s-photo">
                <div class="people-s-photo-img <?if(empty($arParams['COMPANY_ID'])):?>js-auth_user<?endif?>">
                    <img src="<?=CFile::GetPath($arUser['PERSONAL_PHOTO']);?>">
                </div>
            </div>
            <div class="people-s-photo-text">
                <div class="people-s-photo-text-block">
                    <h6><?=$USER->GetFullName()?></h6>
                </div>
                <?if(!empty($arParams['COMPANY_ID'])):?>
                    <div class="people-s-photo-btn-block">
                        <button class="btn btn-aut js-auth_user">Выбрать</button>
                    </div>
                <!-- <?//else:?>
                    <div class="people-s-photo-btn-block">
                        <a href="/profile/" class="btn btn-aut active-company">
                            Продолжить с текущим профилем
                        </a>
                    </div> -->
                <?endif?>
            </div>
        </div>
    </div>
    <?=$arResult["NAV_STRING"]?>
<?else:?>
    <div class="d-flex flex-column align-items-center text-center mt-5 pt-5 mb-5" >
        <img src="<?=SITE_TEMPLATE_PATH?>/image/forbidden.png" alt="Необходима регистрация">
        <h3 class="text-uppercase font-weight-bold mt-3" style="max-width: 550px">НА ТЕКУЩИЙ МОМЕНТ У ВАС НЕТ ДРУГИХ ПРОФИЛЕЙ</h3>
        <p>Если вы заключаете сделки как физическое лицо или индивидуальный предприниматель, то активация дополнительного профиля вам не требуется.</p>
        <p>Если вы ранее создавали профиль Компании, то в данное время информация находится на проверке у Администратора сайта.</p>
        <p>После успешной проверки Ваша Компания будет автоматически активирована, и вы получите возможность переключиться на ее профиль.</p>
        <!--<a href="#" class="btn btn-nfk mt-4" style="width: 262px; height: 46px; padding-top: 10px;">Региcтрация</a>-->
        <a href="/" class="mt-3">Вернуться на главную страницу</a>
    </div>
<?endif?>
</div>
