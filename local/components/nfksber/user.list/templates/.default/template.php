<div class="row">
<?foreach($arResult["USER"] as $user):?>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="tender-post">
            <a href="/profile_user/?ID=<?=$user['ID']?>">
                <div class="tender-img">
                  <?if (!isset($user['PERSONAL_PHOTO'])){ ?>
                    <img src="<?=SITE_TEMPLATE_PATH?>/img/no_img_pacts.jpg">
                  <?} else {?>
                    <img src="<?=CFile::GetPath($user['PERSONAL_PHOTO'])?>">
                  <?}?>
                </div>
            </a>
            <div class="tender-text">
                <a href="/profile_user/?ID=<?=$user['ID']?>">
                    <span><?=$user['LOGIN']?></span><br>
                    <span><?=$user['NAME'].' '.$user['LAST_NAME']?></span>
                </a>
            </div>
        </div>
    </div>
<?endforeach?>
</div>
<?=$arResult["NAV_STRING"]?>
