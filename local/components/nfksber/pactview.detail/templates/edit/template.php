<div id="params_object" style="display:none" data="<?=$arResult["ELEMENT"]["ID"]?>"></div>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
<div class="tender cardPact">
    <div class="row">        
        <div class="col-lg-8 col-md-8 col-sm-8">
            <!--//Слайдер изображений -->
            <div class="slider-sdelka" id="my-slider">
                <div class="sp-slides">
                    <?// изображения 
                        $arr_img = $arResult["PROPERTY"]["IMG_FILE"];
                        if(!empty($arResult["PROPERTY"]["IMG_FILE"])):
                            foreach ($arr_img as $url_img){?>
                                <?if(!empty($url_img["URL"])):?>
                                    <div class="sp-slide">
                                        <img class="sp-image" src="<?=$url_img["URL"]?>">
                                        <span class="cardPact-box-edit-rem_img" data-id="<?=$url_img['PROPERTY']['PROPERTY_VALUE_ID']?>">-</span>
                                    </div>
                                <?endif?>
                            <?}?>
                        <?else:?>
                            <div class="sp-slide">
                                <img class="sp-image js-add_img" src="<?=SITE_TEMPLATE_PATH?>/image/add_img.png">
                            </div>
                        <?endif?>
                </div>
                <div class="sp-thumbnails">
                    <?// изображения
                    $arr_img = $arResult["PROPERTY"]["IMG_FILE"];
                    if(!empty($arResult["PROPERTY"]["IMG_FILE"])){
                        foreach ($arr_img as $url_img){?>
                            <?if(!empty($url_img["URL"])):?>
                                <img class="sp-thumbnail" src="<?=$url_img["URL"]?>">
                            <?endif?>
                        <?}
                    }?>
                    <img id="cardPact-box-edit-add_img" class="sp-thumbnail js-add_img" src="<?=SITE_TEMPLATE_PATH?>/image/add_img.png">
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

                <div class="editbox-wrap">
                    <h3>Город</h3><span>(обязательно укажите населенный пункт)</span>
                    <select id="LOCATION_CITY" name="LOCATION_CITY" class="selectbox-select select-bottom js-location-city" placeholder="Выберите город" >
                        <option value="">Выбор города</option>
                        <? foreach($arResult['LIST_CITY'] as $item):?>
                            <option value="<?=$item?>" <?if($arResult['PROPERTY']['LOCATION_CITY']['VALUE'] == $item):?>selected<?endif?>>
                                <?=$item?>
                            </option>
                        <? endforeach?>
                    </select>
                </div>                
            </div>

            <?/*
            <select id="select-city" class="selectbox-select" placeholder="Выбор города">
                <?if(!empty($arResult['PROPERTY']['LOCATION_CITY']['VALUE'])):?>
                    <option value=""><?=$item?>Выбор города</option>
                <?endif?>
                <? foreach($arResult['LIST_CITY'] as $item):?>
                    <?if($arResult['PROPERTY']['LOCATION_CITY']['VALUE'] == $item):?>
                        <option value="<?=$item?>" selected><?=$item?></option>
                    <?else:?>
                        <option value="<?=$item?>"><?=$item?></option>
                    <?endif?>
                <? endforeach?>
            </select>
            */?>

            <div class="wrap-map_adress">
                <h3>Местоположение</h3><span>(желательно также указать адрес)</span>
                <div id="header" class="search-map_input">
                    <input type="text" id="suggest" class="input-search_map" placeholder="Введите адрес">
                    <input type="hidden" id="COORDINATES_AD" name="COORDINATES_AD" value="<?=$arResult['PROPERTY']['COORDINATES_AD']['VALUE']?>">
                    <button type="submit" id="check-button_map" class="btn btn-nfk btn-search_map">Поиск</button>
                </div>
                <p id="notice" class="error_form"></p>
                <div id="map" style="height: 400px"></div>
            </div>

        </div>
        <!-- Правая часть карточки -->
        <div class="col-lg-4 col-md-4 col-sm-4 cardPact-rightPanel">
            <h1><span id="cardPact-EditText-Summ" contenteditable="true"><?=$arResult["PROPERTY"]["SUMM_PACT"]["VALUE"]?></span> руб.</h1>

            <button class="btn btn-nfk " id="save_summ" style="margin-top:30px;">Сохранить</button>
            <!--Срок объявления -->
            <h4>Объявление активно до: <span class="date-active"><?=$arResult["ELEMENT"]["DATE_ACTIVE_TO"]?></span></h4>
            <button class="btn btn-nfk" id="up_date_active">Продлить на 10 дней</button>
            <div class="cardPact__item">
                    <div class="cardPact__title">
                        <h3>Приватность</h3>
                        <?if($arResult['PROPERTY']['PRIVATE']['VALUE'] == 10):?>
                            <button class="onActive" private="Y">
                                <img src="<?=SITE_TEMPLATE_PATH?>/image/Active.png" />
                                <input name="PRIVATE" id="PRIVATE" type="hidden" value="10"/>
                            </button>
                        <?else:?>
                            <button class="onActive" private="">
                                <img src="<?=SITE_TEMPLATE_PATH?>/image/DontActive.png" />
                                <input name="PRIVATE" id="PRIVATE" type="hidden" value=""/>
                            </button>
                        <?endif;?>
                    </div>
                    <h4>(Скрыть от других пользователей)</h4>
                </div>
            <!-- Добавление договора -->
            <h4>Вы можете добавить договор из шаблона или загрузить свой</h4>
                <?
                $disable_a = "";
                if (!empty($arResult["PROPERTY"]["ID_DOGOVORA"]["VALUE"])){
                    echo '<div style="margin-bottom: 15px;"><img src="'.SITE_TEMPLATE_PATH.'/image/doc_ready_ico.png" style="max-width: 40px;"><span>Договор загружен</span></div>';
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
                <label for="avtomatic_delete" class="label-title" style="">автоматически удалить предложение после заключения сделки</label>
                <input type="hidden" name="UF_HIDE_PROFILE" value="0" class="hide_profile_input">
            </div>
            <!--Приложения к договору-->
            <h4>Добавить приложение в виде файла(опционально)</h4>
            <div class="list-dopfile">
                <?if(!empty($arResult["PROPERTY"]["UNCLUDE_FILE"])):?>
                    <? foreach($arResult["PROPERTY"]["UNCLUDE_FILE"] as $Item):?>
                        <a href="<?=$Item["URL"]?>" class="cardPact-rightPanel-url" target="_blank" style="float: left;width: 74%;">
                            <img src="<?=SITE_TEMPLATE_PATH?>/image/icon-contract.png" > <?=$Item['NAME']?>
                        </a>
                        <button class="btn btn-nfk delete_unclude_file" style="float: left;width: 25%;" data="<?=$Item["ID"]?>" data-file ="<?=$Item["ID"]?>">Удалить</button>
                    <?endforeach?>
                <?endif?>
            </div>

            <form method="post" enctype = 'multipart/form-data' action="<?=$_SERVER['REQUEST_URI']?>&nonsense=1" class="all-save_form">
                <?echo CFile::InputFile("IMAGE_ID", 20, $str_IMAGE_ID);?>
                <button class="btn btn-nfk" type="submit">Сохранить</button>
            </form>

            <?

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
<?
$jsParams = [
    'USER_ID'=> $arResult['USER_ID'],
    'CITY' => $arParams['LOCATION']
];
?>
<script>
    var adData = <?=CUtil::PhpToJSObject($jsParams)?>;
    var arImg = <?=CUtil::PhpToJSObject($arResult['JS_DATA']['IMG_FILE'])?>
</script>