<div class="city-choose" id="city_choose">
    <div class="container">
        <button class="city-choose-btn-close">Закрыть&nbsp;&nbsp;&nbsp;х</button>
        <h2>Выберите город</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="row-column">
                    <?foreach ($arResult['CITY'] as $city):?>
                        <div class="col-6 col-sm-4 col-md-6 col-xl-2">
                            <button class="city-choose-btn-city <?if($city['PROPERTY_BOLD_VALUE']=='Y'):?>font-weight-bold<?endif?>"><?=$city['NAME']?></button>
                        </div>
                    <?endforeach?>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        <span class="city-choose-form-header">Или введите в поле</span>
            <form class="sity-submit">
                <div class="row">
                    <div class="col-md-6"><input type="text" class="sity-submit_input" placeholder="Введите город или населенный пункт (например &quot;Санкт-Петербург&quot;)"></div>
                    <div class="col-md-6"><button class="btn btn-nfk-invert city-choose-btn-choose">Выбрать</button></div>
                </div>
            </form>
    </div>
</div>
<?if($arResult['NEED_GEO']){?>
    <?
    $signer = new \Bitrix\Main\Security\Sign\Signer;
    $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'location');
    ?>  
    <script>
        var L_component = {
            params: <?=CUtil::PhpToJSObject($arParams)?>,
            signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
            siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
            ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
            templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
        };
        $(document).ready(function(){
            getLocation();
        });
    </script>
<?}?>