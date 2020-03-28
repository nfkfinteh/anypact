<h1>Новое предложение</h1>
<div class="tender cardPact">
    <form id="save_ad">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="cardPact__item">
                    <div>
                        <input type="text" class="editbox" id="ad_name" value="" name="ad_name">
                    </div>
                    <div class="cardPact__title">
                        <h3>Название</h3>
                    </div>
                    <span>(введите описание сделки и/или предмета сделки)</span>
                </div>
                <div class="cardPact__item">
                    <div class="cardPact-box cardPact-box_img" data="<?=$arResult["ELEMENT"]["ID"]?>">
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
                    <div class="cardPact__title">
                        <h3>Фотографии</h3>
                    </div>
                    <span>(не более 10)</span>
                </div>
                <div class="cardPact__item">
                    <div class="cardPact-EditText-Descript">
                        <textarea class="editbox" id="ad_descript"></textarea>
                    </div>
                    <div class="cardPact__title">
                        <h3>Описание</h3>
                    </div>
                    <span>(добавьте краткое описание сделки или предмета сделки в произвольной форме)</span>
                </div>
                <div class="cardPact__item">
                    <div class="cardPact-EditText-Сonditions">
                        <textarea class="editbox" id="ad_condition" data-code="<?=$arResult['PROPERTY']['CONDITIONS_PACT']['CODE']?>"></textarea>
                    </div>
                    <div class="cardPact__title">
                        <h3>Условия</h3>
                    </div>
                    <span>(добавьте описание любых важных для вас Условий совершения сделки, например, необходима ли предоплата и т.п.)</span>
                </div>
                <div class="cardPact__item">
                    <div class="editbox-wrap">
                        <select id="LOCATION_CITY" name="LOCATION_CITY" class="selectbox-select select-bottom js-location-city" placeholder="Выберите город">
                            <option value="">Выбор города</option>
                            <? foreach($arResult['LIST_CITY'] as $item):?>
                                <option value="<?=$item?>" <?if($arParams['LOCATION'] == $item):?>selected<?endif?>>
                                    <?=$item?>
                                </option>
                            <? endforeach?>
                        </select>
                    </div>
                    <div class="cardPact__title">
                        <h3>Город</h3>
                    </div>
                    <span>(обязательно укажите населенный пункт)</span>
                </div>

                <div class="wrap-map_adress">
                    <div id="header" class="search-map_input">
                        <input type="text" id="suggest" class="input-search_map" placeholder="Введите адрес">
                        <input type="hidden" id="COORDINATES_AD" name="COORDINATES_AD" value="">
                        <button type="submit" id="check-button_map" class="btn btn-nfk btn-search_map">Поиск</button>
                    </div>
                    <div class="cardPact__title">
                        <h3>Местоположение</h3>
                    </div>
                    <span>(желательно также указать адрес)</span>
                    <p id="notice" class="error_form"></p>
                    <div id="map" style="height: 400px"></div>
                    <button class="btn btn-nfk" id="save_ad__button" style="margin-top:50px;" type="submit">Сохранить</button>
                    <div id="footer">
                        <div id="messageHeader"></div>
                        <div id="message"></div>
                    </div>
                </div>

            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 cardPact-rightPanel">

                <div class="cardPact__item">
                    <div class="scardPact-rightPanel__sum">
                        <input type="text" class="editbox_sum js-number" id="cardPact-EditText-Summ" data-code="<?=$arResult['PROPERTY']['SUMM_PACT']['CODE']?>">
                        <div style="float:right;">руб.</div>
                    </div>
                    <div class="cardPact__title">
                        <h3>Сумма</h3>
                    </div>
                    <span>(укажите единицы)</span>
                </div>
                <div class="cardPact__item">
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
                    <div class="cardPact__title">
                        <h3>Категория</h3>
                    </div>
                    <span>(Выберите подходящую категорию)</span>
                    <input name="CATEGORY" value="" class="param_selected_category__input" style="width: 0; height: 0;">
                </div>

                <div class="cardPact__item">
                    <div class="selectbox">
                        <div id="param_selected_activ_date" class="view_text">Активно до: <input type="text" id="param_selected_activ_date_input" name="ACTIVE_DATE" value="" disabled ><span class="glyphicon glyphicon-calendar"></span></div>
                    </div>
                    <div class="cardPact__title">
                        <h3>Дата активности объявления</h3>
                    </div>
                    <span>(По умолчанию 10 дней)</span>
                </div>
                <div class="cardPact__item">
                    <button class="btn btn-nfk" id="add_dogovor" data-url="/my_pacts/add_my_dogovor/?EDIT=ADD">Добавить договор</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?
$jsParams = [
    'USER_ID'=> $arResult['USER_ID'],
    'CITY' => $arParams['LOCATION']
];
?>
<script type="text/javascript">
    var adData = <?=CUtil::PhpToJSObject($jsParams)?>;
</script>