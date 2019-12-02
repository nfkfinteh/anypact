<div class="text-right btn-view mt-3">
    <span>Вид списка</span>
    <button class="btn btn-tiled active"></button>
    <button class="btn btn-list"></button>
</div>
<div class="row grid-view">
    <?foreach($arResult["USER"] as $user):?>   
    <!-----------------!------------------->
    <div class="view-item col-lg-3 col-sm-6 col-6 mt-4 pb-3">
        <div class="people-s-photo">
            <a href="/profile_user/?ID=<?=$user['ID']?>">
                <div class="people-s-photo-img">
                    <? if(!empty($user['PERSONAL_PHOTO'])){ ?>
                        <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-no-phpto.png" alt="">
                        <img class="people-s-user-photo" src="<?
                            $USER_AV = CFile::GetPath($user['PERSONAL_PHOTO']);
                            $renderImage = CFile::ResizeImageGet($user['PERSONAL_PHOTO'], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false);                      
                            echo $renderImage["src"];
                        ?>" alt="">
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
                <button class="btn btn-clean search-peaople__button" data-toggle="modal" data-target=".bd-message-modal-sm" data-login="<?=$user['LOGIN']?>">
                    <img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-message.png" alt="">
                </button>
                <!-- <button class="btn btn-clean"><img src="<?=SITE_TEMPLATE_PATH?>/image/people-search-document.png" alt=""></button> -->
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

<div class="modal fade bd-message-modal-sm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Новое сообщение</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="message_user" action="/response/ajax/add_new_messag_user.php">
                    <input class="login__input" type="hidden" name="login" value="">
                    <div class="form-group">
                        <label for="">Тема сообщения</label>
                        <input class="form-control" name="title" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Текст сообщения</label>
                        <textarea class="form-control message-textarea" name="message-text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-nfk d-block cardPact-bBtn submit_message">Отправить</button>
            </div>
        </div>
    </div>
</div>


