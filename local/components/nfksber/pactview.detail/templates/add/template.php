<?
if(!empty($arResult['FORM_SDELKA']['adCity'])){
    $city = $arResult['FORM_SDELKA']['adCity'];
}elseif(!empty($arParams['LOCATION'])){
    $city = $arParams['LOCATION'];
}
?>
<h1>Новое предложение</h1>
<div class="tender cardPact">
    <form id="save_ad">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="cardPact__item">
                    <div>
                        <input type="text" class="editbox" id="ad_name" value="<?=$arResult['FORM_SDELKA']['adName']?>" name="ad_name">
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
                    <span>(не более 20)</span>
                </div>
                <div class="cardPact__item">
                    <div class="cardPact-EditText-Descript">
                        <textarea class="editbox" id="ad_descript"><?=$arResult['FORM_SDELKA']['adDescript']?></textarea>
                    </div>
                    <div class="cardPact__title">
                        <h3>Описание</h3>
                    </div>
                    <span>(добавьте краткое описание сделки или предмета сделки в произвольной форме)</span>
                </div>
                <div class="cardPact__item">
                    <div class="cardPact-EditText-Сonditions">
                        <textarea class="editbox" id="ad_condition" data-code="<?=$arResult['PROPERTY']['CONDITIONS_PACT']['CODE']?>"><?=$arResult['FORM_SDELKA']['adCondition']?></textarea>
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
                                <option value="<?=$item?>" <?if($city == $item):?>selected<?endif?>>
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
                        <input type="hidden" id="COORDINATES_AD" name="COORDINATES_AD" value="<?=$arResult['FORM_SDELKA']['adCoordinates']?>">
                        <button type="button" id="check-button_map" class="btn btn-nfk btn-search_map">Поиск</button>
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
                    <div class="cardPact__title">
                        <h3>Цена по запросу</h3> 
                        <button class="onActive" active="" data-block-id="price_block" data-value-id="16">
                            <img src="<?=SITE_TEMPLATE_PATH?>/image/DontActive.png" />
                            <input name="PRICE_ON_REQUEST" id="PRICE_ON_REQUEST" type="hidden" value=""/>
                        </button>
                    </div>
                </div>

                <div class="cardPact__item" id="price_block">
                    <div class="scardPact-rightPanel__sum">
                        <input type="text" class="editbox_sum js-number" id="cardPact-EditText-Summ" data-code="<?=$arResult['PROPERTY']['SUMM_PACT']['CODE']?>" value="<?=$arResult['FORM_SDELKA']['adSum']?>">
                        <div style="float:right;">руб.</div>
                    </div>
                    <div class="cardPact__title">
                        <h3>Сумма</h3>
                    </div>
                    <span>(укажите единицы)</span>
                </div>
                <div class="cardPact__item">
                    <div class="selectbox">
                        <div id="param_selected_category" class="view_text" data="close" data-id="<?=$arResult['FORM_SDELKA']['adSection']?>">
                            Выбор категории
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div class="select_category">
                            <ul id="choice_category">
                                <? foreach($arResult["INFOBLOCK_SECTION_LIST"] as $item){?>
                                <li style="margin-left:<?=$item["DEPTH_LEVEL"]?>0px;">
                                    <a href="#" data-id="<?=$item['ID'];?>"><?=$item['NAME'];?></a>
                                </li>
                                <?}?>
                            </ul>
                        </div>
                    </div>
                    <div class="cardPact__title">
                        <h3>Категория</h3>
                    </div>
                    <span>(Выберите подходящую категорию)</span>
                    <input name="CATEGORY" value="<?=$arResult['FORM_SDELKA']['adSection']?>" type="hidden" class="param_selected_category__input" style="width: 0; height: 0;">
                </div>

                <div class="cardPact__item">
                    <div class="selectbox">
                        <div id="param_selected_activ_date" class="view_text">
                            <div class="date-text">Активно до:</div>
                            <div class="date-input">
                                <input type="text" id="param_selected_activ_date_input" name="ACTIVE_DATE" value="<?=$arResult['FORM_SDELKA']['date']?>" disabled >
                                <span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>
                    </div>
                    <div class="cardPact__title">
                        <h3>Дата активности объявления</h3>
                    </div>
                    <span>(По умолчанию 10 дней)</span>
                </div>
                <div class="cardPact__item">
                    <div class="cardPact__title">
                        <h3>Приватность</h3> 
                        <button class="onActive" active="" data-block-id="user_select" data-value-id="10">
                            <img src="<?=SITE_TEMPLATE_PATH?>/image/DontActive.png" />
                            <input name="PRIVATE" id="PRIVATE" type="hidden" value=""/>
                        </button>
                    </div>
                    <span>(Скрыть от других пользователей)</span>
                </div>
                <div class="cardPact__item" id="user_select">
                    <?
                    $APPLICATION->IncludeComponent(
                        "nfksber:user.select",
                        "",
                        Array(
                            "IBLOCK_ID" => $arResult["INFOBLOCK_ID"],
                            "ELEMENT_ID" => $arResult["ELEMENT_ID"],
                            "ACTION_VARIABLE" => "action"
                        )
                    );
                    ?>
                </div>
                <div class="cardPact__item">
                    <?if(empty($arResult['DOGOVOR'])):?>
                        <button class="btn btn-nfk" id="add_dogovor" data-url="/my_pacts/add_new_dogovor/?ADD=ADD">Добавить договор</button>
                    <?else:?>
                        <div style="margin-bottom: 15px;"><img src="<?=SITE_TEMPLATE_PATH;?>/image/doc_ready_ico.png" style="max-width: 40px;"><span>Договор загружен</span></div>
                        <button class="btn btn-nfk" id="add_dogovor" data-url="/my_pacts/add_new_dogovor/?ADD=ADD">Заменить договор</button>
                        <input type="hidden" id="DOGOVOR_KEY" value="<?=$arResult['DOGOVOR_KEY_CASHE']?>">
                    <?endif?>
                </div>
            </div>
        </div>
    </form>
</div>
<?
$jsParams = [
    'USER_ID'=> $arResult['USER_ID'],
    'CITY' => $city
];
?>
<script type="text/javascript">
    var adData = <?=CUtil::PhpToJSObject($jsParams)?>;
</script>