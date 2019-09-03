<? //print_r($arResult["ELEMENT"]) ;?>
<?  //print_r($arResult) ;?>
<?    $disable_a = "";
    if (empty($arResult["PROPERTY"]["ID_DOGOVORA"]["VALUE"])){
        $disable_a = 'disabled';
    } 
?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
======
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
>>>>>>> 5c16d271de55b3f067f49dd8a7bce9e2e3e57d24
                <h5>Описание</h5>
                    <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
                <h5>Условия</h5>
                    <?=$arResult["PROPERTY"]["CONDITIONS_PACT"]["VALUE"]["TEXT"]?>
                
=======
                <?/*<h5 class="mt-5">Комментарии</h5>*/?>
>>>>>>> 5c16d271de55b3f067f49dd8a7bce9e2e3e57d24
            </div>
            <!--// Левая часть -->
            <!--Правая часть карточки-->
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
<<<<<<< HEAD
        <!--Комментарии сделки-->
        <div class="row align-items-center mt-4">
            <div class="col-3 col-sm-2  col-lg-1">
                <img src="image/sample_face_150x150.png" class="cardPact-comment-avatar" alt="">
            </div>
            <div class="col-9 col-sm-10 col-lg-7">
                <div class="cardPact-comment-header">
                    <span>tap-64</span><br>
                    <span>Анатолий Степанович</span><span class="cardPact-comment-date">15.01.19/11:08</span>
                </div>
            </div>
            <div class="offset-3 offset-sm-2 offset-lg-1 col-9 col-sm-10 col-lg-7">
                <div class="cardPact-comment-body">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus laoreet gravida libero, ac laoreet felis sagittis et. Nullam fringilla ultrices arcu nec sagittis. Aliquam erat volutpat. Sed vehicula vitae orci a egestas. Donec lacinia ipsum leo, et luctus nisi euismod elementum. Mauris mollis velit eu erat scelerisque, vitae consectetur est ultricies. Proin auctor sit amet sem sed convallis. Quisque pulvinar lectus ut ante fringilla, eu faucibus velit finibus. In porta pharetra fringilla. Donec fermentum semper erat at interdum. Proin eget iaculis sem.
                </div>
            </div>
        </div>
        <div class="row align-items-center mt-4">
            <div class="col-3 col-sm-2 col-lg-1">
                <img src="image/sample_face_150x150.png" class="cardPact-comment-avatar" alt="">
            </div>
            <div class="col-9 col-sm-10 col-lg-7">
                <div class="cardPact-comment-header">
                    <span>tap-64</span><br>
                    <span>Анатолий Степанович</span><span class="cardPact-comment-date">15.01.19/11:08</span>
                </div>
            </div>
            <div class="offset-3 offset-sm-2 offset-lg-1 col-9 col-sm-10 col-lg-7">
                <div class="cardPact-comment-body">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus laoreet gravida libero, ac laoreet felis sagittis et. Nullam fringilla ultrices arcu nec sagittis. Aliquam erat volutpat. Sed vehicula vitae orci a egestas. Donec lacinia ipsum leo, et luctus nisi euismod elementum. Mauris mollis velit eu erat scelerisque, vitae consectetur est ultricies. Proin auctor sit amet sem sed convallis. Quisque pulvinar lectus ut ante fringilla, eu faucibus velit finibus. In porta pharetra fringilla. Donec fermentum semper erat at interdum. Proin eget iaculis sem.
                </div>
            </div>
        </div>
        <div class="row">
            <div class="offset-sm-2 offset-lg-1 col-sm-10 col-lg-7">
                <textarea placeholder="Оставвьте Ваш комментарий"></textarea>
                <button class="btn btn-nfk cardPact-comment-submit">Комментировать</button>
            </div>
        </div>
=======
>>>>>>> 5c16d271de55b3f067f49dd8a7bce9e2e3e57d24

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
            breakpoints: {
                450: {
                    thumbnailWidth : 82,
                    thumbnailHeight : 50
                }
            }
        });
    });
</script>
