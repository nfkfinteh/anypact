<div class="text-right btn-view mt-3">
    <span>Вид списка</span>
    <button class="btn btn-tiled active"></button>
    <button class="btn btn-list"></button>
</div>
<div class="row grid-view">
    <?foreach($arResult["USER"] as $user):?>   
    <!------------------------------------>
    <div class="view-item col-lg-3 col-sm-6 col-12 mt-4 pb-3">
        <div class="people-s-photo">
            <a href="/profile_user/?ID=<?=$user['ID']?>">
                <div class="people-s-photo-img">
                    <? if(!empty($user['PERSONAL_PHOTO'])){ ?>
                        <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png" alt="">
                        <img class="people-s-user-photo" src="<?=CFile::GetPath($user['PERSONAL_PHOTO'])?>" alt="">
                    <?}else {?>
                        <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png" alt="">                
                    <? } ?>
                </div>
            </a>
        </div>    
        <div class="people-s-photo-text">
            <div class="people-s-photo-text-block">
                <h6><?=$user['NAME'].' '.$user['LAST_NAME']?></h6>
                <div class="grid-hidden-text">
                    <span class="d-block text-gray">г. Чебоксары</span>
                    <span class="d-block font-weight-bold mt-4">Подтвержденная регистрация</span>
                    <span class="d-block registration-checked mt-2">ЕСИА</span>
                </div>
            </div>
            <div class="people-s-photo-btn-block">
                <button class="btn btn-clean"><img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-message.png" alt=""></button>
                <button class="btn btn-clean"><img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-document.png" alt=""></button>
                <?if(!in_array($user['ID'], $arResult['FRENDS'])):?>
                    <button class="btn btn-clean js-add-frends" data-login="<?=$user['LOGIN']?>">
                        <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-add-people.png" alt="">
                    </button>
                <?endif?>
            </div>
        </div>    
    </div>
    <?endforeach?>
  </div>
<?=$arResult["NAV_STRING"]?>


