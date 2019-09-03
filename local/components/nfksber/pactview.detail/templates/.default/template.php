<? //print_r($arResult["ELEMENT"]) ;?>
<?  //print_r($arResult) ;?>
<?    $disable_a = "";
    if (empty($arResult["PROPERTY"]["ID_DOGOVORA"]["VALUE"])){
        $disable_a = 'disabled';
    } 
?>
<h1 class="d-inline-block"><?=$arResult["ELEMENT"]["NAME"]?></h1>    
    <div class="tender cardPact">
        <div class="row">
            <div class="col-md-7 col-lg-8">
                <? if(!empty($arResult["ELEMENT"]["DETAIL_PICTURE"])){ ?>
                <div class="slider-sdelka" id="my-slider">
                    <div class="sp-slides">
                            <?                        
                                $resize_img = CFile::ResizeImageGet($arResult["ELEMENT"]["DETAIL_PICTURE"], array('width'=>'855', 'height'=>'460'),
                                BX_RESIZE_IMAGE_EXACT);
                            ?>
                        <?
                            // изображения 
                            $arr_img = $arResult["PROPERTY"]["IMG_FILE"];                    
                            if(!empty($arResult["PROPERTY"]["IMG_FILE"])){
                                foreach ($arr_img as $url_img){
                                    ?>
                                    <div class="sp-slide">
                                        <img class="sp-image" src="<?=$url_img["URL"]?>">
                                        <img class="sp-thumbnail" src="<?=$url_img["URL"]?>">
                                    </div>                                    
                                    <?
                                }
                            }
                        ?>
                    </div>
                </div>
                <? } ?>
            </div>
            <div class="col-md-5 col-lg-4">
                <span class="cardPact-price">800 000 руб.</span>
                <a href="#" class="btn btn-nfk cardPact-bBtn">Посмотреть договор</a>
                <a href="#" class="btn btn-nfk cardPact-bBtn">Посмотреть спецификацию</a>
                <div class="cardPact-person">
                    <img src="image/sample_face_150x150.png">
                    <span>tap-64</span><br>
                    <span>Анатолий Степанович</span><br>
                    <span class="text-gray">Неизвестно</span><br>
                </div>
                <div class="cardPact-info">
                    <span class="float-left">Репутация</span><span class="float-right cardPact-rating">&#9733; 3,9</span><br>
                    <span class="float-left">Выполненных сделок</span><span class="float-right ">7</span><br>
                    <span>9 оценок</span>
                </div>
                <button class="btn btn-nfk d-block cardPact-bBtn">Написать сообщение</button>
            </div>
            <div class="col-md-8 mt-4">
                <h5>Описание</h5>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ipsum ipsum, sagittis sollicitudin dignissim et, placerat a arcu. Nunc viverra neque placerat ultrices vehicula. Pellentesque fringilla nibh nec urna pellentesque dignissim.</p>
                <p>Sed ut feugiat diam. Integer sit amet sollicitudin ipsum. Morbi ut imperdiet mi. Aliquam aliquet lectus sed justo efficitur viverra. Duis ac mauris purus. Nullam quis pulvinar tellus, non hendrerit urna. Curabitur non augue vel velit pulvinar posuere. Morbi quis venenatis lectus. Phasellus lacinia, diam non viverra euismod, nibh dui scelerisque nisi, sed luctus ex sem sit amet ipsum. Maecenas mollis odio quis sem auctor vulputate. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eu nisl orci. </p>
                <h5>Условия</h5>
                <p class="mb-5">Sed ut feugiat diam. Integer sit amet sollicitudin ipsum. Morbi ut imperdiet mi. Aliquam aliquet lectus sed justo efficitur viverra. Duis ac mauris purus. Nullam quis pulvinar tellus, non hendrerit urna. Curabitur non augue vel velit pulvinar posuere. Morbi quis venenatis lectus. Phasellus lacinia, diam non viverra euismod, nibh dui scelerisque nisi, sed luctus ex sem sit amet ipsum. Maecenas mollis odio quis sem auctor vulputate. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eu nisl orci. </p>
                <h5 class="mt-5">Комментарии</h5>
            </div>
        </div>
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
