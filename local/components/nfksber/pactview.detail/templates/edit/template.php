<? //print_r($arResult["PROPERTY"]["ID_DOGOVORA"]) ;?>
<? //print_r($arResult) ;?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
<div class="tender cardPact">
    <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="cardPact-box" data="<?=$arResult["ELEMENT"]["ID"]?>">
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
                <div class="cardPact-EditText">             
                    <div class="cardPact-EditText-Descript">
                        <h3>Описание</h3><span>(режим редактирования)</span>
                        <div class="editbox" contenteditable="true">
                            <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
                        </div>
                        <button class="btn btn-nfk" id="save_descript">Сохранить</button>
                    </div>
                    <div class="cardPact-EditText-Сonditions">
                        <h3>Условия</h3><span>(режим редактирования)</span>
                        <div class="editbox" contenteditable="true">
                            <?=$arResult["PROPERTY"]["CONDITIONS_PACT"]["VALUE"]["TEXT"]?>
                        </div>
                        <button class="btn btn-nfk" id="save_conditions">Сохранить</button>
                    </div>
                </div>
            </div>
        <div class="col-lg-4 col-md-4 col-sm-4 cardPact-rightPanel">
            <h1 style="color:#ff6416;"><span id="cardPact-EditText-Summ" contenteditable="true"><?=$arResult["PROPERTY"]["SUMM_PACT"]["VALUE"]?></span> руб.</h1>
            <button class="btn btn-nfk" id="save_summ">Сохранить</button>
            <? 
                $disable_a = "";
                if (empty($arResult["PROPERTY"]["ID_DOGOVORA"]["VALUE"])){
                    $disable_a = 'disabled';
                } 
            ?>
            <a class="btn btn-nfk <?=$disable_a?>" href="/pacts/view_pact/view_dogovor/?ELEMENT_ID=<?=$arResult["PROPERTY"]["ID_DOGOVORA"]["VALUE"]?>">Посмотреть договор</a>
            <div>Репутация</div>
            <div>Количество сделок</div>
            <div>Оценки</div>
            <button class="btn btn-nfk">Написать сообщение</button>
        </div>            
    </div>
</div>