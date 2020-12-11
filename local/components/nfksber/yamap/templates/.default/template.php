<div class="map-space">
    <!--YM-->
    <script src="https://api-maps.yandex.ru/2.1/?apikey=08f051a6-35f1-4392-a988-5024961ee1a8&lang=ru_RU" type="text/javascript">
    </script>
        <div id="map" style="width: <?=$arResult['MAP_WIDTH']?>; height: <?=$arResult['MAP_HEIGHT']?>">
        </div>
        <?
        $signer = new \Bitrix\Main\Security\Sign\Signer;
        $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'yamap');
        ?>
        <script>
            var YAMAP_component = {
                params: <?=CUtil::PhpToJSObject($arParams)?>,
                signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
                siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
                ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
                templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
            };
        </script>
        <script type="text/javascript">
            var iblock = <?=CUtil::PhpToJSObject($arResult['IBLOCK_ID'])?>;
            var points = <?=CUtil::PhpToJSObject($arResult['POINTS'])?>;
            var city = "<?=$arParams['LOCATION']?>";
            if(!city) city = 'Москва';
            // Функция ymaps.ready() будет вызвана, когда
            // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
            ymaps.ready(init);
            function init(){
                ymaps.geocode(city, {
                    results: 1
                }).then(function (res) {
                    var firstGeoObject = res.geoObjects.get(0);
                    var coords = firstGeoObject.geometry.getCoordinates();

                    var bound = firstGeoObject.properties._data.boundedBy;

                    // Создание карты.
                    var myMap = new ymaps.Map("map", {
                        center: coords,
                        zoom: 11,
                        //controls: ['zoomControl']
                    });

                    // urlParams = new URLSearchParams(window.location.search);
                    // params = {};

                    // urlParams.forEach((p, key) => {
                    //     params[key] = p;
                    // });

                    // var loadingObjectManager = new ymaps.LoadingObjectManager('/response/ajax/map.php'+'?bbox=%b&iblock='+iblock+'&parent='+params.PARENT_SECTION,
                    //     {
                    //         clusterize: false,
                    //         clusterHasBalloon: false,
                    //         geoObjectOpenBalloonOnClick: true,
                    //         geoObjectIconLayout: 'default#imageWithContent',
                    //         geoObjectIconImageHref: '<?=SITE_TEMPLATE_PATH//$this->__folder?>/img/map_icon.png',
                    //         geoObjectIconImageSize: [30, 30],
                    //         geoObjectIconImageOffset: [-15, -15],
                    //         geoObjectIconContentOffset: [0, 0],
                    //         //geoObjectIconContentLayout: MyIconContentLayout,
                    //         //geoObjectBalloonContentBody:
                    //     });

                    // myMap.geoObjects.add(loadingObjectManager);
                    $.ajax({
                        url: YAMAP_component.ajaxUrl,
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            via_ajax: 'Y',
                            action: 'loadPoints',
                            sessid: BX.bitrix_sessid(),
                            SITE_ID: YAMAP_component.siteID,
                            signedParamsString: YAMAP_component.signedParamsString,
                            COORDINATES: bound
                        },
                        success: function(result){
                            if (result.length > 0){
                                points = result;
                                for (let i in points) {
                                    myMap.geoObjects.add(new ymaps.Placemark(points[i].geo, {
                                        balloonContent : points[i].balloonContent
                                    }, {
                                        iconLayout : "default#imageWithContent",
                                        iconImageHref : '<?=SITE_TEMPLATE_PATH//$this->__folder?>/img/map_icon.png',
                                        iconImageSize : [30, 30],
                                        iconImageOffset : [-15, -15],
                                        iconContentOffset : [0, 0],
                                    }));
                                }
                            }
                        },
                        error: function(a, b, c){
                            console.log(a);
                            console.log(b);
                            console.log(c);
                        }
                    });

                    myMap.controls.remove('geolocationControl');
                    myMap.controls.remove('searchControl');
                    myMap.controls.remove('trafficControl');
                    myMap.controls.remove('typeSelector');
                    myMap.controls.remove('fullscreenControl');
                    myMap.controls.remove('rulerControl');
                    myMap.behaviors.disable(['scrollZoom']);

                    myMap.events.add('boundschange', function(e){
                        if (e.get('newZoom') !== e.get('oldZoom')) {
                            var bound = e.originalEvent.newBounds;
                            console.log(bound);
                            $.ajax({
                                url: YAMAP_component.ajaxUrl,
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    via_ajax: 'Y',
                                    action: 'loadPoints',
                                    sessid: BX.bitrix_sessid(),
                                    SITE_ID: YAMAP_component.siteID,
                                    signedParamsString: YAMAP_component.signedParamsString,
                                    COORDINATES: bound
                                },
                                success: function(result){
                                    if (result.length > 0){
                                        points = result;
                                        for (let i in points) {
                                            myMap.geoObjects.add(new ymaps.Placemark(points[i].geo, {
                                                balloonContent : points[i].balloonContent
                                            }, {
                                                iconLayout : "default#imageWithContent",
                                                iconImageHref : '<?=SITE_TEMPLATE_PATH//$this->__folder?>/img/map_icon.png',
                                                iconImageSize : [30, 30],
                                                iconImageOffset : [-15, -15],
                                                iconContentOffset : [0, 0],
                                            }));
                                        }
                                    }
                                },
                                error: function(a, b, c){
                                    console.log(a);
                                    console.log(b);
                                    console.log(c);
                                }
                            });
                        }
                    });

                });
            }
        </script>
    <!--//YM-->
</div>