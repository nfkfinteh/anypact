<? //print_r($arResult["PROPERTY"]["ID_DOGOVORA"]) ;?>
<? //print_r($arResult) ;?>
<div id="params_object" style="display:none" data="<?=$arResult["ELEMENT"]["ID"]?>"></div>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
<div class="tender cardPact">
    <div class="row">        
        <div class="col-lg-8 col-md-8 col-sm-8">
            <!--//Слайдер изображений -->
            <div class="slider-sdelka" id="my-slider">
                <div class="sp-slides">                     
                    <? if(!empty($arResult["ELEMENT"]["DETAIL_PICTURE"])){
                        $resize_img = CFile::ResizeImageGet($arResult["ELEMENT"]["DETAIL_PICTURE"], array('width'=>'855', 'height'=>'460'), BX_RESIZE_IMAGE_EXACT);?>
                        <div class="sp-slide">
                            <img class="sp-image" src="<?=$resize_img["src"]?>">
                            <img class="sp-thumbnail" src="<?=$resize_img["src"]?>">
                        </div> 
                    <?} ?>
                    <?// изображения 
                        $arr_img = $arResult["PROPERTY"]["IMG_FILE"];                    
                        if(!empty($arResult["PROPERTY"]["IMG_FILE"])){
                            foreach ($arr_img as $url_img){?>
                                <div class="sp-slide">
                                    <img class="sp-image" src="<?=$url_img["URL"]?>">
                                    <img class="sp-thumbnail" src="<?=$url_img["URL"]?>">
                                </div>                                
                            <?}
                        }?>
                    <div class="sp-slide">
                        <img class="sp-image" src="<?=SITE_TEMPLATE_PATH?>/image/add_img.png">
                        <img id="cardPact-box-edit-add_img" class="sp-thumbnail" src="<?=SITE_TEMPLATE_PATH?>/image/add_img.png">
                    </div>            
                </div>
            </div>     
            <!--//Слайдер изображений -->
            <input id='filePicture' type="file" multiple="multiple" accept=".txt,image/*" style="display: none">
            <!-- блок редактирования текста -->
            <div class="cardPact-EditText">
                <div class="cardPact-EditText-Descript">
                    <h3>Описание</h3><span>(режим редактирования)</span>
                    <div class="editbox" contenteditable="true">
                        <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
                    </div>
                    <button class="btn btn-nfk save" id="save_descript">Сохранить</button>
                </div>
                <div class="cardPact-EditText-Сonditions">
                    <h3>Условия</h3><span>(режим редактирования)</span>
                    <div class="editbox" contenteditable="true">
                        <?=$arResult["PROPERTY"]["CONDITIONS_PACT"]["VALUE"]["TEXT"]?>
                    </div>
                    <button class="btn btn-nfk save" id="save_conditions">Сохранить</button>
                </div>
            </div>
        </div>
        <!-- Правая часть карточки -->
        <div class="col-lg-4 col-md-4 col-sm-4 cardPact-rightPanel">
            <h1><span id="cardPact-EditText-Summ" contenteditable="true"><?=$arResult["PROPERTY"]["SUMM_PACT"]["VALUE"]?></span> руб.</h1>
            <button class="btn btn-nfk save" id="save_summ">Сохранить</button>
                <?
                $disable_a = "";
                if (!empty($arResult["PROPERTY"]["ID_DOGOVORA"]["VALUE"])){
                    $text_btn_dogovor = 'Редактировать договор';
                    $action_dogovor = 'EDIT=EDIT';
                } else {
                    $text_btn_dogovor = 'Добавить договор';
                    $action_dogovor = 'EDIT=ADD';
                }
                ?>
            <a class="btn btn-nfk" href="/my_pacts/add_my_dogovor/?ELEMENT_ID=<?=$arResult["ELEMENT"]["ID"]?>&<?=$action_dogovor?>"><?=$text_btn_dogovor?></a>
            <div id="select_spetification">
                    <a href="#" class="cardPact-rightPanel-url">Спецификация №1</a>
            </div>
            <button class="btn btn-nfk">Добавить спецификацию</button>
            <div id="select_spetification">
                    <a href="#" class="cardPact-rightPanel-url">Приложение №1</a>
            </div>
            <button class="btn btn-nfk">Добавит приложение</button>
            <div id="select_spetification">
                    <a href="#" class="cardPact-rightPanel-url">Файл/скан документа №1</a>
            </div>
            <button class="btn btn-nfk">Добавит документ</button>
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
<script>
    var arImg = <?=CUtil::PhpToJSObject($arResult['JS_DATA']['IMG_FILE'])?>
</script>