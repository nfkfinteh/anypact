<?
if(!empty($arResult['FORM_SDELKA']['adCity'])){
    $city = $arResult['FORM_SDELKA']['adCity'];
}elseif(!empty($arParams['LOCATION'])){
    $city = $arParams['LOCATION'];
}
$this->addExternalCss(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/ui/trumbowyg.min.css");
$this->addExternalCss(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/plugins/table/ui/trumbowyg.table.min.css");
$this->addExternalJS(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/trumbowyg.min.js");
$this->addExternalJS(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/langs/ru.min.js");
$this->addExternalJS(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/plugins/history/trumbowyg.history.min.js");
$this->addExternalJS(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/plugins/pasteimage/trumbowyg.pasteimage.min.js");
$this->addExternalJS(SITE_TEMPLATE_PATH."/module/trumbowyg/dist/plugins/table/trumbowyg.table.min.js");
?>
<h1>Новое предложение</h1>
<div class="tender cardPact">
    <form id="save_ad">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="cardPact__item">
                    <div class="cardPact__title">
                        <h3>Название</h3>
                    </div>
                    <span>(введите описание сделки и/или предмета сделки)</span>
                    <div>
                        <input type="text" class="editbox" id="ad_name" value="<?=$arResult['FORM_SDELKA']['adName']?>" name="ad_name">
                    </div>
                </div>
                <div class="cardPact__item">
                    <div class="cardPact__title">
                        <h3>Фотографии</h3>
                    </div>
                    <span>(не более 20)</span>
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
                </div>
                <div class="cardPact__item">
                    <div class="cardPact__title">
                        <h3>Описание</h3>
                    </div>
                    <span>(добавьте краткое описание сделки или предмета сделки в произвольной форме)</span>
                    <div class="cardPact-EditText-Descript">
                        <textarea class="editbox" id="ad_descript"><?=$arResult['FORM_SDELKA']['adDescript']?></textarea>
                    </div>
                </div>
                <div class="cardPact__item">
                    <div class="cardPact__title">
                        <h3>Условия</h3>
                    </div>
                    <span>(добавьте описание любых важных для вас Условий совершения сделки, например, необходима ли предоплата и т.п.)</span>
                    <div class="cardPact-EditText-Сonditions">
                        <textarea class="editbox" id="ad_condition" data-code="<?=$arResult['PROPERTY']['CONDITIONS_PACT']['CODE']?>"><?=$arResult['FORM_SDELKA']['adCondition']?></textarea>
                    </div>
                </div>
                <div class="cardPact__item">
                    <div class="editbox-wrap">
                        <div class="cardPact__title">
                            <h3>Город</h3>
                        </div>
                        <span>(обязательно укажите населенный пункт)</span>
                        <select id="LOCATION_CITY" name="LOCATION_CITY" class="selectbox-select select-bottom js-location-city" placeholder="Выберите город">
                            <option value="">Выбор города</option>
                            <? foreach($arResult['LIST_CITY'] as $item):?>
                                <option value="<?=$item?>" <?if($city == $item):?>selected<?endif?>>
                                    <?=$item?>
                                </option>
                            <? endforeach?>
                        </select>
                    </div>
                </div>

                <div class="wrap-map_adress">
                    <div class="cardPact__title">
                        <h3>Местоположение</h3>
                    </div>
                    <span>(желательно также указать адрес)</span>
                    <div id="header" class="search-map_input">
                        <input type="text" id="suggest" class="input-search_map" placeholder="Введите адрес">
                        <input type="hidden" id="COORDINATES_AD" name="COORDINATES_AD" value="<?=$arResult['FORM_SDELKA']['adCoordinates']?>">
                        <button type="button" id="check-button_map" class="btn btn-nfk btn-search_map">Поиск</button>
                    </div>
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
                    <div class="cardPact__title">
                        <h3>Сумма</h3>
                    </div>
                    <span>(укажите единицы)</span>
                    <div class="scardPact-rightPanel__sum">
                        <input type="text" class="editbox_sum js-number" id="cardPact-EditText-Summ" data-code="<?=$arResult['PROPERTY']['SUMM_PACT']['CODE']?>" value="<?=$arResult['FORM_SDELKA']['adSum']?>">
                        <div style="float:right;">руб.</div>
                    </div>
                </div>

                <div class="cardPact__item">
                    <div class="cardPact__title">
                        <h3>Указать телефон</h3> 
                        <button class="onActive" active="" data-block-id="phone_block" data-value-id="17">
                            <img src="<?=SITE_TEMPLATE_PATH?>/image/DontActive.png" />
                            <input name="SHOW_PHONE" id="SHOW_PHONE" type="hidden" value=""/>
                        </button>
                    </div>
                </div>

                <div class="cardPact__item" id="phone_block" style="display: none;">
                    <div class="cardPact__title">
                        <h3>Номер телефона</h3>
                    </div>
                    <div>
                        <input type="text" class="editbox js-mask__phone" id="DEAL_PHONE" name="DEAL_PHONE" value="<?=$arResult['PROPERTY']['DEAL_PHONE']['VALUE']?>">
                    </div>
                </div>
                
                <div class="cardPact__item">
                    <div class="cardPact__title">
                        <h3>Категория</h3>
                    </div>
                    <span>(Выберите подходящую категорию)</span>
                    <div class="selectbox">
                        <div id="param_selected_category" class="view_text" data="close" data-id="<?=$arResult['FORM_SDELKA']['adSection']?>">
                            Выбор категории
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div class="select_category">
                            <ul id="choice_category">
                                <?
                                $arChild = array();
                                $div = 0;
                                ?>
                                <? foreach($arResult["INFOBLOCK_SECTION_LIST"] as $item){?>
                                    <?if(isset($item['CHILD'])){?>
                                        <li>
                                            <a href="#" class="category-parent" data-parent-id="<?=$item['ID'];?>"><?=$item['NAME'];?> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
                                            
                                        </li>
                                        <?
                                        foreach($item['CHILD'] as $child){
                                            $arChild[$child] = $item['ID'];
                                        }
                                        ?>
                                        <div class="category-childs" data-parent-id="<?=$item['ID'];?>">
                                        <?$div++;?>
                                    <?}else{?>
                                        <li>
                                            <a href="#" class="category-select" data-id="<?=$item['ID'];?>"><?=$item['NAME'];?></a>
                                        </li>
                                    <?}?>
                                    <?php
                                    if(isset($arChild[$item['ID']])){
                                        unset($arChild[$item['ID']]);
                                        if(array_search($item['IBLOCK_SECTION_ID'], $arChild) === false && array_search($item['ID'], $arChild) === false){
                                            echo "</div>";
                                            $div--;
                                        }
                                    }
                                    if($div > 0 && empty($arChild)){
                                        for($i = 1; $i <= $div; $i++)
                                            echo "</div>";
                                        $div = 0;
                                    }
                                    ?>
                                <?}?>
                            </ul>
                        </div>
                    </div>
                    <input name="CATEGORY" value="<?=$arResult['FORM_SDELKA']['adSection']?>" type="hidden" class="param_selected_category__input" style="width: 0; height: 0;">
                </div>

                <div class="cardPact__item">
                    <div class="cardPact__title">
                        <h3>Бессрочно</h3> 
                        <button class="onActive" active="Y" data-block-id="date_block" data-value-id="18">
                            <img src="<?=SITE_TEMPLATE_PATH?>/image/Active.png" />
                            <input name="INDEFINITELY" id="INDEFINITELY" type="hidden" value="18"/>
                        </button>
                    </div>
                </div>

                <div class="cardPact__item" id="date_block" style="display: none;">
                    <div class="cardPact__title">
                        <h3>Дата активности объявления</h3>
                    </div>
                    <span>(По умолчанию 10 дней)</span>
                    <div class="selectbox">
                        <div id="param_selected_activ_date" class="view_text">
                            <div class="date-text">Активно до:</div>
                            <div class="date-input">
                                <input type="text" id="param_selected_activ_date_input" name="ACTIVE_DATE" value="<?=$arResult['FORM_SDELKA']['date']?>" disabled >
                                <span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>
                    </div>
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
                            "ACTION_VARIABLE" => "action",
                            "OUTPUT_FIELD_NAME" => "ACCESS_USER",
                            "INPUT_NAME" => "SELECTED_USER",
                        )
                    );
                    ?>
                </div>
                <div class="cardPact__item">
                    <?if(empty($arResult['DOGOVOR'])):?>
                        <button class="btn btn-nfk" id="add_dogovor" data-url="/contract/?NEW_DEAL=Y">Добавить договор</button>
                    <?else:?>
                        <div style="margin-bottom: 15px;"><img src="<?=SITE_TEMPLATE_PATH;?>/image/doc_ready_ico.png" style="max-width: 40px;"><span>Договор загружен</span></div>
                        <button class="btn btn-nfk" id="add_dogovor" data-url="/contract/?NEW_DEAL=Y">Заменить договор</button>
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
<script>
    function removeTrumbowygTags(){
        let el = this;
        $(el).trumbowyg('disable');
        $.ajax({
            url: '/response/ajax/check_text.php',
            method: 'POST',
            dataType: 'json',
            data: {
                sessid: BX.bitrix_sessid(),
                text: $(el).trumbowyg('html')
            },
            success: function(result){
                $(el).trumbowyg('html', result);
                $(el).trumbowyg('enable');
            }
        });
    }
    $.trumbowyg.svgPath = "<?=SITE_TEMPLATE_PATH?>/module/trumbowyg/dist/ui/icons.svg";
    var editorSettings = {
        btns: [
            ['historyUndo','historyRedo'],
            ['strong', 'em'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['insertImage', 'link'],
            ['table'],
            ['horizontalRule'],
            ['removeformat'],
            ['fullscreen']
        ],
        defaultLinkTarget: '_blank',
        lang: 'ru'
    };
    $('#ad_descript').trumbowyg(editorSettings);
    $('#ad_condition').trumbowyg(editorSettings);
    $('#ad_descript').trumbowyg().on('tbwpaste', removeTrumbowygTags);
    $('#ad_condition').trumbowyg().on('tbwpaste', removeTrumbowygTags);
</script>