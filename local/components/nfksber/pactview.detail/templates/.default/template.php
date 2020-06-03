<?
$disable_a = "";
if (empty($arResult["PROPERTY"]["ID_DOGOVORA"]["VALUE"])){
    $disable_a = 'disabled';
}

foreach ($arResult["PROPERTY"]["IMG_FILE"] as $item){
    $file =CFile::ResizeImageGet($item['PROPERTY']['VALUE'], array('width'=>'180', 'height'=>'110'), BX_RESIZE_IMAGE_EXACT);
    $arr_img[] =[
        'URL' => $item['URL'],
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
<h1 class="d-inline-block"><?=$arResult["ELEMENT"]["NAME"]?></h1>
<div class="row">
    <div class="col-md-7 col-lg-8">
        <? if(!empty($arr_img)){ ?>
            <div class="slider-sdelka" id="my-slider">
                <div class="sp-slides">
                    <?
                    foreach ($arr_img as $url_img){
                        ?>
                        <?if(!empty($url_img['URL'])):?>
                            <div class="sp-slide">
                                <img class="sp-image" src="<?=$url_img["URL"]?>">
                                <img class="sp-thumbnail" src="<?=$url_img['THUMB_URL']?>">
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
    </div>
    <div class="col-md-5 col-lg-4">
        <span class="cardPact-price"><?=$arResult["PROPERTY"]["SUMM_PACT"]["VALUE"]?> руб.</span>

        <?//скрытие кнопки при окончане активности?>
        <? if($USER->IsAuthorized()):?>
            <?if($arResult['ELEMENT']['ACTIVE']=='Y' && $DATE_ACTIVE_TO>=time()):?>
                <a href="/pacts/view_pact/view_dogovor/?ELEMENT_ID=<?=$arResult["ELEMENT"]["ID"]?>" class="btn btn-nfk cardPact-bBtn <?=$disable_a?>">Посмотреть или подписать договор</a>
            <?endif?>
        <?endif?>

        <!-- <a href="#" class="btn btn-nfk cardPact-bBtn">Посмотреть спецификацию</a> -->
        <div class="cardPact-person">
            <a href="/profile_user/?ID=<?=$arResult["CONTRACT_HOLDER"]["ID"]?>&type=<?=$arResult["CONTRACT_HOLDER"]["TYPE"]?>">
                <?if(!empty($arResult["CONTRACT_HOLDER"]["PERSONAL_PHOTO"])):?>
                    <img src="<?=$arResult["CONTRACT_HOLDER"]["PERSONAL_PHOTO"]?>">
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
        <? if($USER->IsAuthorized()):?>
            <? if($arResult['ELEMENT']['ACTIVE']=='Y' && $DATE_ACTIVE_TO>=time() && !in_array($arResult['USER']['ID'], $arBlackList)): ?>
                <button type="button" class="btn btn-nfk d-block cardPact-bBtn" data-toggle="modal" data-target=".bd-message-modal-sm">
                    Написать сообщение
                </button>
            <? endif ?>
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
            breakpoints: {
                450: {
                    thumbnailWidth : 82,
                    thumbnailHeight : 50
                }
            }
        });
    });
</script>

