<?
$disable_a = "";
if (empty($arResult["PROPERTY"]["ID_DOGOVORA"]["VALUE"]) || $arResult['USER']['UF_ESIA_AUT'] != 1){
    $disable_a = 'disabled';
}

if($USER->IsAuthorized()){
    $is_aut = true;
}

foreach ($arResult["PROPERTY"]["IMG_FILE"] as $item){
    $file =CFile::ResizeImageGet($item['PROPERTY']['VALUE'], array('width'=>'180', 'height'=>'110'), BX_RESIZE_IMAGE_EXACT);
    $resize_img =CFile::ResizeImageGet($item['PROPERTY']['VALUE'], array('width'=>'730', 'height'=>'500'), BX_RESIZE_IMAGE_PROPORTIONAL);
    $arr_img[] =[
        'URL' => $resize_img['src'],
        'THUMB_URL'=>$file['src']
    ];
}
$DATE_ACTIVE_TO = MakeTimeStamp($arResult['ELEMENT']['DATE_ACTIVE_TO'], "DD.MM.YYYY");
if(!empty($arResult['CONTRACT_HOLDER']['UF_BLACKLIST'])){
    $arBlackList = json_decode($arResult['CONTRACT_HOLDER']['UF_BLACKLIST']);
}
else{
    $arBlackList = [];
}
?>
<div class="row">
    <div class="col-md-7 col-lg-8">
        <? if(!empty($arr_img)){ ?>
            <div class="slider-sdelka" id="my-slider">
                <div class="sp-slides">
                    <?
                    foreach ($arr_img as $url_img){
                        ?>
                        <?if(!empty($url_img['URL'])):?>
                            <div class="sp-slide" data-src="<?=$url_img["URL"]?>">
                            <span class="gallery-img-cover" style="background-image: url('<?=$url_img["URL"]?>');"></span>
                                <img class="sp-image" src="<?=$url_img["URL"]?>">
                                <?if(count($arr_img) > 1){?>
                                    <img class="sp-thumbnail" src="<?=$url_img['THUMB_URL']?>">
                                <?}?>
                            </div>
                        <?endif?>
                        <?
                    }
                    ?>
                </div>
            </div>
        <? } ?>
        <h5>Описание</h5>
        <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
        <? if(isset($arResult["PROPERTY"]["CONDITIONS_PACT"]["VALUE"]["TEXT"])){ ?>
            <h5>Условия</h5>
            <?=$arResult["PROPERTY"]["CONDITIONS_PACT"]["VALUE"]["TEXT"]?>
        <? } ?>
        <div class="detail-share">
            <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,twitter,viber,whatsapp,telegram"></div>
        </div>     
    </div>
    <div class="col-md-5 col-lg-4">
        <span class="cardPact-price">
            <?if($arResult['PROPERTY']['PRICE_ON_REQUEST']['VALUE_ENUM'] == "Y"){?>
                Цена по запросу
            <?}else{?>
                <?=$arResult["PROPERTY"]["SUMM_PACT"]["VALUE"]?> руб.
            <?}?>
        </span>
        <?if(!$arResult['BLACKLIST']):?>
            <?if($arResult["PROPERTY"]["SHOW_PHONE"]["VALUE_ENUM"] == "Y" && !empty(str_replace(array("+", "-", "(", ")", " ", 8), '', $arResult["PROPERTY"]["DEAL_PHONE"]["VALUE"]))){?>
                <div class="position-relative not_auth-error-block">
                    <a href="#" class="btn btn-nfk cardPact-bBtn <?if(!$is_aut){?>disabled<?}?>" id="show_phone" data-pact-id="<?=$arResult["ELEMENT"]["ID"]?>">Показать телефон<br>8(XXX) XXX-XX-XX</a>
                    <? if(!$is_aut):?>
                        <div class="not_auth-error not_auth-error-phone">
                            <span class="triangle" style="display: block; z-index: 1;">▲</span>
                            <div>Для просмотра телефона необходимо <a id="open_reg_form" href="#">зарегистрироваться</a></div>
                        </div>
                    <?endif;?>
                </div>
            <?}?>

            <?//скрытие кнопки при окончане активности?>
            <? if($is_aut):?>
                <?if($arResult['ELEMENT']['ACTIVE']=='Y' && $DATE_ACTIVE_TO>=time()):?>
                    <div class="position-relative not_auth-error-block">
                        <a href="/pacts/view_pact/view_dogovor/?ELEMENT_ID=<?=$arResult["ELEMENT"]["ID"]?>" class="btn btn-nfk cardPact-bBtn <?=$disable_a?>" onclick="ym(64629523,'reachGoal','docs_link');">Посмотреть или подписать договор</a>
                        <?if($arResult['USER']['UF_ESIA_AUT'] != 1):?>
                            <div class="not_auth-error">
                                <span class="triangle" style="display: block; z-index: 1;">▲</span>
                                <div>Для подписания предложения необходимо <a target="__blank" href="/profile/#aut_esia">подтвердить свой аккаунт с помощью учетной записи портала Госуслуг</a></div>
                            </div>
                        <?endif;?>
                    </div>
                <?endif?>
            <?endif?>
        <?endif;?>

        <!-- <a href="#" class="btn btn-nfk cardPact-bBtn">Посмотреть спецификацию</a> -->
        <div class="cardPact-person">
            <a href="/profile_user/?ID=<?=$arResult["CONTRACT_HOLDER"]["ID"]?>&type=<?=$arResult["CONTRACT_HOLDER"]["TYPE"]?>">
                <?if(!empty($arResult["CONTRACT_HOLDER"]["PERSONAL_PHOTO"])):?>
                    <?$resizeImg = CFile::ResizeImageGet($arResult["CONTRACT_HOLDER"]["PERSONAL_PHOTO"], array("width" => "68", "height" => "68"), BX_RESIZE_IMAGE_EXACT);?>
                    <img src="<?=$resizeImg["src"]?>">
                <?else:?>
                    <div class="cardPact-person__avatar">
                        <span><?=substr($arResult["CONTRACT_HOLDER"]["NAME"], 0, 1)?></span>
                    </div>
                <?endif?>
                <span>
                    <?=$arResult["CONTRACT_HOLDER"]["LAST_NAME"]?>
                </span>
                <span>
                    <?=$arResult["CONTRACT_HOLDER"]["NAME"]?>
                </span>
            </a>
            <br>
            <span class="text-gray"><?=$arResult["CONTRACT_HOLDER"]["CITY"]?></span><br>
        </div>
        <div class="cardPact-info">
            <?/*<span class="float-left">Репутация</span><span class="float-right cardPact-rating">&#9733; 3,9</span><br>*/?>
            <?if(empty($arResult['PROPERTY']['ID_COMPANY']['VALUE']) && !empty($arResult['DOGOVOR']['CNT'])):?>
                <span class="float-left">Заключенных сделок</span><span class="float-right "><?=$arResult['DOGOVOR']['CNT']?></span>
            <?endif?>
            <br>
            <?/*<span>9 оценок</span>*/?>
        </div>
        <?//скрытие кнопки при окончане активности?>
        <? if($is_aut && !$arResult['BLACKLIST']):?>
            <? if($arResult['ELEMENT']['ACTIVE']=='Y' && $DATE_ACTIVE_TO>=time()): ?>
                <button type="button" class="btn btn-nfk d-block cardPact-bBtn" data-toggle="modal" data-target=".bd-message-modal-sm" onclick="ym(64629523,'reachGoal','message_post');">
                    Написать сообщение
                </button>
            <? endif ?>
            <button type="button" class="btn btn-nfk d-block cardPact-bBtn" data-toggle="modal" data-target=".bd-complaints-modal" style="margin-top: 35px;">
                Пожаловаться на предложение
            </button>
        <? endif?>
    </div>
</div>

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
                    <input type="hidden" name="login" value="<?=$arResult['CONTRACT_HOLDER']['LOGIN']?>">
                    <div class="form-group">
                        <textarea class="form-control " name="message-text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-nfk d-block cardPact-bBtn submit_message">Отправить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-complaints-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Жалоба на предложение</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="complaints_on_deal" action="/response/ajax/complaints_on_deal.php">
                    <?=bitrix_sessid_post()?>
                    <input type="hidden" name="id" value="<?=$arResult["ELEMENT"]["ID"]?>">
                    <div class="complaints_type-select">
                        <lable>Тип нарушения:</lable>
                        <select name="complaints_type">
                            <?
                            $rsEnum = CIBlockPropertyEnum::GetList(array("ID" => "asc"), array("IBLOCK_ID" => 9, "CODE" => "TYPE"));
                            while($enum = $rsEnum->Fetch())
                            {
                            ?>
                                <option value="<?=$enum['ID'];?>"><?=$enum['VALUE'];?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control " name="message-text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-nfk d-block cardPact-bBtn submit_message">Отправить</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery( document ).ready(function( $ ) {
        $( '#my-slider' ).sliderPro({
            width : "100%",
            aspectRatio : 1.6, //соотношение сторон
            loop : false,
            autoplay : false,
            fade : true,
            thumbnailWidth : 164,
            thumbnailHeight : 101,
            imageScaleMode: 'contain',
            // fullScreen: true,
            // fadeFullScreen: false,
            breakpoints: {
                450: {
                    thumbnailWidth : 82,
                    thumbnailHeight : 50
                }
            },
            init: function( event ) {
                $('.sp-slide').each(function(index,value){
                    $(value).prepend($(value).children('.gallery-img-cover'));
                });
                $('#my-slider .sp-slides').lightGallery({
                    download: false
                });
            }
        });
    });
</script>

