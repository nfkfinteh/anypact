<? // print_r($arResult["PROPERTY"]) ;?>
<? //print_r($arResult) ;?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender cardPact">
    <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-8">
                <div class="cardPact-box">
                <?
                    if(!empty($arResult["ELEMENT"]["DETAIL_PICTURE"])){
                        $resize_img = CFile::ResizeImageGet($arResult["ELEMENT"]["DETAIL_PICTURE"], array('width'=>'855', 'height'=>'460'),
                        BX_RESIZE_IMAGE_EXACT);
                        ?>
                        <div class="cardPact-box-BoxMainImg">
                            <img src="<?=$resize_img["src"]?>" />
                        </div>
                        <?
                    }
                ?>
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
                <h3>Описание</h3>
                <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
                <h3>Условия</h3>
                <?=$arResult["PROPERTY"]["CONDITIONS_PACT"]["VALUE"]["TEXT"]?>
            </div>
        <div class="col-lg-3 col-md-4 col-sm-4 cardPact-rightPanel">
            <h1 style="color:#ff6416;"><?=$arResult["PROPERTY"]["SUMM_PACT"]["VALUE"]?> руб.</h1>

            <button class="btn btn-nfk">Посмотреть договор</button>
            <div>Репутация</div>
            <div>Количество сделок</div>
            <div>Оценки</div>
            <button class="btn btn-nfk">Написать сообщение</button>
        </div>            
    </div>
</div>