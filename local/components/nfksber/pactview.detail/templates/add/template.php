<? //print_r($arResult["INFOBLOCK_SECTION_LIST"]) ;?>
<? //print_r($arResult) ;?>
<h1>Новое предложение</h1>
<div class="tender cardPact">
    <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8">            
                <div class="cardPact-box" data="<?=$arResult["ELEMENT"]["ID"]?>">
                    <div class="cardPact-box-edit" data-code="<?=$arResult['PROPERTY']['IMG_FILE']['CODE']?>">
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
                    <input id='filePicture' type="file" multiple="multiple" accept=".txt,image/*" style="display: none">
                    <div class="cardPact-box-BoxPrewImg" id="cardPact-box-BoxPrewImg">
                        <?// изображения 
                            $arr_img = $arResult["PROPERTY"]["IMG_FILE"];                    
                            if(!empty($arResult["PROPERTY"]["IMG_FILE"])){
                                foreach ($arr_img as $url_img){?>
                                    <?if($url_img["URL"]):?>
                                        <img src="<?=$url_img["URL"]?>" class="cardPact-box-BoxPrewImg-img"/>
                                    <?endif?>

                                    <?
                                }
                            }
                        ?>
                    </div>  
                </div>
                <h3>Фотографии</h3><span>(не более 10)</span>
                <div class="cardPact-EditText">  
                    <div class="cardPact-EditText-Descript" id="ad_name">
                        <div class="editbox" contenteditable="true" style="min-height: 0px;">
                            <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
                        </div>                        
                    </div>
                    <h3>Название</h3><span>(введите описание сделки и/или предмета сделки)</span>           
                    <div class="cardPact-EditText-Descript">                        
                        <div class="editbox" contenteditable="true" id="ad_descript">
                            <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
                        </div>                        
                    </div>
                    <h3>Описание</h3><span>(добавьте краткое описание сделки или предмета сделки в произвольной форме)</span>
                    <div class="cardPact-EditText-Сonditions">
                        <div class="editbox" contenteditable="true" id="ad_condition" data-code="<?=$arResult['PROPERTY']['CONDITIONS_PACT']['CODE']?>">
                            <?=$arResult["PROPERTY"]["CONDITIONS_PACT"]["VALUE"]["TEXT"]?>
                        </div>                        
                    </div>
                    <h3>Условия</h3><span>(добавьте описание любых важных для вас Условий совершения сделки, например, необходима ли предоплата и т.п.)</span>
                </div>
            </div>
        <div class="col-lg-4 col-md-4 col-sm-4 cardPact-rightPanel">
            <h1>
                <span id="cardPact-EditText-Summ" contenteditable="true" data-code="<?=$arResult['PROPERTY']['SUMM_PACT']['CODE']?>">
                    <?=$arResult["PROPERTY"]["SUMM_PACT"]["VALUE"]?>
                </span>
                <div style="float:right;">руб.</div>
            </h1>
            <h3>Сумма</h3><span>(укажите единицы)</span>
            <div class="selectbox">
                <div id="param_selected_category" class="view_text" data="close">
                    Выбор категории 
                    <span class="glyphicon glyphicon-chevron-down"></span>
                </div>
                <div class="select_category">                    
                    <ul id="choice_category">
                    <? foreach($arResult["INFOBLOCK_SECTION_LIST"] as $item){
                        ?><li style="margin-left:<?=$item["DEPTH_LEVEL"]?>0px;"><a href="#" data-id="<?=$item['ID'];?>"><?=$item['NAME'];?></a></li><?
                    }?>
                    </ul>
                </div>
            </div>
            <h3>Категория</h3><span>(Выберите подходящую категорию)</span>
            <div class="selectbox">
                <div id="param_selected_activ_date" class="view_text">Активно до: <input type="text" id="param_selected_activ_date_input" name="ACTIVE_DATE" value="" disabled ><span class="glyphicon glyphicon-calendar"></span></div>
            </div>
            <h3>Дата активности объявления</h3><span>(По умолчанию 10дней)</span>
            <button class="btn btn-nfk" id="save_ad" style="margin-top:50px;">Сохранить</button>         
            
            
        </div>            
    </div>
</div>
<?
$jsParams = [
    'USER_ID'=> $arResult['USER_ID']
];
?>
<script type="text/javascript">
    var adData = <?=CUtil::PhpToJSObject($jsParams)?>;
</script>