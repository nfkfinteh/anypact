<? //print_r($arResult["INFOBLOCK_SECTION_LIST"]) ;?>
<? //print_r($arResult) ;?>
<h1>Новое объявление</h1>
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
                <h3>Фотографии</h3><span>(не более 10)</span>
                <div class="cardPact-EditText">  
                    <div class="cardPact-EditText-Descript">                        
                        <div class="editbox" contenteditable="true" style="min-height: 0px;">
                            <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
                        </div>                        
                    </div>
                    <h3>Название</h3><span>(режим редактирования)</span>           
                    <div class="cardPact-EditText-Descript">                        
                        <div class="editbox" contenteditable="true">
                            <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
                        </div>                        
                    </div>
                    <h3>Описание</h3><span>(режим редактирования)</span>
                    <div class="cardPact-EditText-Сonditions">                        
                        <div class="editbox" contenteditable="true">
                            <?=$arResult["PROPERTY"]["CONDITIONS_PACT"]["VALUE"]["TEXT"]?>
                        </div>                        
                    </div>
                    <h3>Условия</h3><span>(режим редактирования)</span>
                </div>
            </div>
        <div class="col-lg-4 col-md-4 col-sm-4 cardPact-rightPanel">
            <h1><span id="cardPact-EditText-Summ" contenteditable="true"><?=$arResult["PROPERTY"]["SUMM_PACT"]["VALUE"]?></span> <div style="float:right;">руб.</div></h1>
            <h3>Сумма</h3><span>(укажите единицы)</span>
            <div class="selectbox">
                <div id="param_selected_category" class="view_text">Выбор категории <span class="glyphicon glyphicon-chevron-down"></span></div>
                <div class="select_category">                    
                    <ul id="choice_category">
                    <? foreach($arResult["INFOBLOCK_SECTION_LIST"] as $item){
                        ?><li style="margin-left:<?=$item["DEPTH_LEVEL"]?>0px;"><a href="#" data-id="<?=$item['NAME'];?>"><?=$item['NAME'];?></a></li><?
                    }?>
                    </ul>
                </div>
            </div>
            <h3>Категория</h3><span>(Выберите подходящую категорию)</span>
            <div class="selectbox">
                <div id="param_selected_activ_date" class="view_text">Активно до: <date></date><span class="glyphicon glyphicon-calendar"></span></div>
            </div>
            <h3>Дата активности объявления</h3><span>(По умолчанию 10дней)</span>
            <button class="btn btn-nfk" id="save_ad" style="margin-top:50px;">Сохранить</button>         
            
            
        </div>            
    </div>
</div>