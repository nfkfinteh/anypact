<pre>
<? //print_r($arResult["PROPERTY"]["ID_DOGOVORA"]) ;?>
<? print_r($arResult["PROPERTY"]["UNCLUDE_FILE"]) ;?>
</pre>
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
                                <?if(!empty($url_img["URL"])):?>
                                    <div class="sp-slide">
                                        <img class="sp-image" src="<?=$url_img["URL"]?>">
                                        <img class="sp-thumbnail" src="<?=$url_img["URL"]?>">
                                    </div>
                                <?endif?>
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
            <button class="btn btn-nfk " id="save_summ" style="margin-top:30px;">Сохранить</button>
            <!--Срок объявления -->
            <h4>Объявление активно до: <? print_r($arResult["ELEMENT"]["DATE_ACTIVE_TO"]); ?></h4>
            <button class="btn btn-nfk" id="up_date_active">Продлить на 10 дней</button>
            <!-- Добавление договора -->
            <h4>Вы можете добавить договор из шаблона или загрузить свой</h4>
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
            <!--Автоматическое удаление объявления-->
            <div class="form-group form-checkbox" style="padding-left: 21px;">            
                <? if($arResult["PROPERTY"]["AV_DELETE"]["VALUE"] == "Y"){ ?>
                    <input checked type="checkbox" id="avtomatic_delete" name="hide_profile" style="width: 19px;height: 26px;float: left">                    
                <?}else {?>
                    <input type="checkbox" id="avtomatic_delete" name="hide_profile" style="width: 19px;height: 26px;float: left">
                <?}?>
                <label for="hide_profile" style="padding: 0 0 44px 20px;float: left; width: 94%;">автоматически удалить предложение после заключения сделки</label>
                <input type="hidden" name="UF_HIDE_PROFILE" value="0" class="hide_profile_input">
            </div>
            <!--Приложения к договору-->
            <h4>Добавить приложение в виде файла(опционально)</h4>
            <!-- <button class="btn btn-nfk">Загрузить файл</button>             -->
            <?if(!empty($arResult["PROPERTY"]["UNCLUDE_FILE"])){?>
                <? foreach($arResult["PROPERTY"]["UNCLUDE_FILE"] as $Item){?>
                    <a href="<?=$Item["URL"]?>" class="cardPact-rightPanel-url" target="_blank" style="float: left;width: 74%;">
                        <img src="<?=SITE_TEMPLATE_PATH?>/image/icon-contract.png" > Дополнительный файл
                    </a> 
                    <button class="btn btn-nfk delete_unclude_file" style="float: left;width: 25%;" data="<?=$Item["ID"]?>" data-file ="<?=$Item["ID"]?>">Удалить</button>
                <?}?>
            <?}?>
            <form method = "post" enctype = 'multipart/form-data' action="<?=$_SERVER['REQUEST_URI']?>&nonsense=1" >
            <?echo CFile::InputFile("IMAGE_ID", 20, $str_IMAGE_ID);?>
            <input class="btn btn-nfk" type="submit" value="Сохранить">
            </form>

            <?

            $testiruem = Array(
                "name" => $_FILES["IMAGE_ID"]["name"],
                "size" => $_FILES["IMAGE_ID"]["size"],
                "tmp_name" => $_FILES["IMAGE_ID"]["tmp_name"],
                "type" => "",
                "old_file" => "",
                "del" => "Y",
                "MODULE_ID" => "iblock"
            );
            $poluchaem_adress = CFile::SaveFile($testiruem, "dopfile");

            
            $ELEMENT_ID     = $arResult["ELEMENT"]["ID"];
            $PROPERTY_CODE  = "MAIN_FILES";
            $PROPERTY_VALUE = $testiruem;
            CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $PROPERTY_CODE, $PROPERTY_VALUE);

            if (!empty($_FILES)) {
                header("Refresh:0");
            }

            // if ($poluchaem_adress>0):
            //print_r ('<br/>ID файла: '.$poluchaem_adress.'<br/>');
            // echo CFile::ShowImage($poluchaem_adress, 200, 200, "border=0", "", true);
            // дальше уменьшим картинку до 50 на 50
            //$photosmall = CFile::ResizeImageGet($poluchaem_adress, array('width'=>'50', 'height' => '50'), BX_RESIZE_IMAGE_PROPORTIONAL, true); // получится пропорциональна оригиналу
            //print_r ('<img border="0" src="'.$photosmall["src"].'"/><br/>');
            //print_r ('ссылка на файл: <input value="'.$photosmall["src"].'"/>');
            //endif;
            ?>

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