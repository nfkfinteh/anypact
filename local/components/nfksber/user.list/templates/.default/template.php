<div class="row">
    <?foreach($arResult["USER"] as $user):?>   
    <!------------------------------------>
    <div class="col-lg-3 col-sm-6 col-12 mt-4 pb-3">
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
            <h6><?=$user['NAME'].' '.$user['LAST_NAME']?></h6>
            <button class="btn btn-clean"><img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-message.png" alt=""></button>
            <button class="btn btn-clean"><img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-document.png" alt=""></button>
            <button class="btn btn-clean"><img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-add-people.png" alt=""></button>
        </div>
    </div>
    <!------------------------------------>
    <?endforeach?>
  </div>
<?=$arResult["NAV_STRING"]?>
