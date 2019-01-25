<? //print_r($arResult["PROPERTY"]["ID_DOGOVORA"]) ;?>
<? //print_r($arResult) ;?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
<div class="tender cardPact">
    <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="cardPact-box" data="<?=$arResult["ELEMENT"]["ID"]?>">
                    <div class="cardPact-box-edit">                        
                        <?
                            if(!empty($arResult["ELEMENT"]["DETAIL_PICTURE"])){
                                $resize_img = CFile::ResizeImageGet($arResult["ELEMENT"]["DETAIL_PICTURE"], array('width'=>'855', 'height'=>'460'),
                                BX_RESIZE_IMAGE_EXACT);
                                ?>
                                <div class="cardPact-box-BoxMainImg">
                                    <img src="<?=$resize_img["src"]?>" />
                                    <div id="cardPact-box-edit-rem_img">
                                        <span>-</span>
                                    </div>
                                </div>
                                <?
                            }else {
                                ?>
                                <div id="cardPact-box-edit-add_img">
                                    <span>+</span>
                                </div>
                                <?
                            }
                        ?>
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
        <div class="col-lg-4 col-md-4 col-sm-4 cardPact-rightPanel">
            <h1><span id="cardPact-EditText-Summ" contenteditable="true"><?=$arResult["PROPERTY"]["SUMM_PACT"]["VALUE"]?></span> руб.</h1>
            <button class="btn btn-nfk save" id="save_summ">Сохранить</button>
            <? 
                $disable_a = "";
                if (!empty($arResult["PROPERTY"]["ID_DOGOVORA"]["VALUE"])){                    
                    $text_btn_dogovor = 'редактировать договор';
                } else {
                    $text_btn_dogovor = 'добавить договор';
                }
            ?>
            <a class="btn btn-nfk" href="/pacts/view_pact/view_dogovor/?ELEMENT_ID=<?=$arResult["PROPERTY"]["ID_DOGOVORA"]["VALUE"]?>"><?=$text_btn_dogovor?></a>
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