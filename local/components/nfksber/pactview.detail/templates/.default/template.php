<? //print_r($arResult["ELEMENT"]) ;?>
<?  //print_r($arResult) ;?>
<?
    $disable_a = "";
    if (empty($arResult["PROPERTY"]["ID_DOGOVORA"]["VALUE"])){
        $disable_a = 'disabled';
    } 
?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
<!---------------------------------------------------------------------------------------------------------->

        <div class="row">            
            <div class="col-md-8 mt-4">
                <? if(!empty($arResult["ELEMENT"]["DETAIL_PICTURE"])){ ?>
                <div class="cardPact-box">
                    <?                        
                            $resize_img = CFile::ResizeImageGet($arResult["ELEMENT"]["DETAIL_PICTURE"], array('width'=>'855', 'height'=>'460'),
                            BX_RESIZE_IMAGE_EXACT);
                            ?>
                            <div class="cardPact-box-BoxMainImg">
                                <img src="<?=$resize_img["src"]?>" />
                            </div>                    
                        <div class="cardPact-box-BoxPrewImg">
                        <?
                            // изображения 
                            $arr_img = $arResult["PROPERTY"]["IMG_FILE"];                    
                            if(!empty($arResult["PROPERTY"]["IMG_FILE"])){
                                foreach ($arr_img as $url_img){
                                    ?>
                                    <img src="<?=$url_img["URL"]?>" class="cardPact-box-BoxPrewImg-img"/>
                                    <?
                                }
                            }
                        ?>
                    </div>                     
                </div>
                <?
                        }
                    ?> 
                <h5>Описание</h5>
                    <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
                <h5>Условия</h5>
                    <?=$arResult["PROPERTY"]["CONDITIONS_PACT"]["VALUE"]["TEXT"]?>
                <?/*<h5 class="mt-5">Комментарии</h5>*/?>
            </div>
            <div class="col-md-5 col-lg-4">
                <span class="cardPact-price"><?=$arResult["PROPERTY"]["SUMM_PACT"]["VALUE"]?> руб.</span>
                <a href="/pacts/view_pact/view_dogovor/?ELEMENT_ID=<?=$arResult["ELEMENT"]["ID"]?>" class="btn btn-nfk cardPact-bBtn <?=$disable_a?>">Посмотреть договор</a>
                <a href="#" class="btn btn-nfk cardPact-bBtn">Посмотреть спецификацию</a>
                <div class="cardPact-person">
                    <?if(!empty($arResult["CONTRACT_HOLDER"]["PERSONAL_PHOTO"])):?>
                        <img src="<?=$arResult["CONTRACT_HOLDER"]["PERSONAL_PHOTO"]?>">
                    <?else:?>
                        <div class="cardPact-person__avatar">
                            <span><?=substr($arResult["CONTRACT_HOLDER"]["NAME"], 0, 1)?></span>
                        </div>
                    <?endif?>                    
                    <span><a href="/profile_user/?ID=<?=$arResult["CONTRACT_HOLDER"]["ID"]?>"><?=$arResult["CONTRACT_HOLDER"]["LAST_NAME"]?> <?=$arResult["CONTRACT_HOLDER"]["NAME"]?></a></span><br>
                    <span class="text-gray">Неизвестно</span><br>
                </div>
                <div class="cardPact-info">
                    <span class="float-left">Репутация</span><span class="float-right cardPact-rating">&#9733; 3,9</span><br>
                    <span class="float-left">Выполненных сделок</span><span class="float-right "><?=$arResult['DOGOVOR']['CNT']?></span><br>
                    <span>9 оценок</span>
                </div>
                <button type="button" class="btn btn-nfk d-block cardPact-bBtn" data-toggle="modal" data-target=".bd-message-modal-sm">Написать сообщение</button>
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




