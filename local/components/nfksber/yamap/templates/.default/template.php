<div class="map-space">
    <!--YM-->
    <script src="https://api-maps.yandex.ru/2.1/?apikey=08f051a6-35f1-4392-a988-5024961ee1a8&lang=ru_RU" type="text/javascript">
    </script>
        <div id="map" style="width: <?=$arResult['MAP_WIDTH']?>; height: <?=$arResult['MAP_HEIGHT']?>">
        </div>
        <script type="text/javascript">
            var iblock = <?=CUtil::PhpToJSObject($arResult['IBLOCK_ID'])?>;
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

                    // Создание карты.
                    var myMap = new ymaps.Map("map", {
                        center: coords,
                        zoom: 11,
                        controls: ['zoomControl']
                    });

                    let selectedPane = new ymaps.pane.MovablePane(myMap, {zIndex : 420});
                    myMap.panes.append('selected', selectedPane);

                    urlParams = new URLSearchParams(window.location.search);
                    params = {};

                    urlParams.forEach((p, key) => {
                        params[key] = p;
                    });

                    var loadingObjectManager = new ymaps.LoadingObjectManager('/response/ajax/map.php'+'?bbox=%b&iblock='+iblock+'&parent='+params.PARENT_SECTION,
                        {
                            clusterize: false,
                            clusterHasBalloon: false,
                            geoObjectOpenBalloonOnClick: true,
                            geoObjectIconLayout: 'default#imageWithContent',
                            geoObjectIconImageHref: '<?=SITE_TEMPLATE_PATH//$this->__folder?>/img/map_icon.png',
                            geoObjectIconImageSize: [30, 30],
                            geoObjectIconImageOffset: [-15, -15],
                            geoObjectIconContentOffset: [30, 30],
                            //geoObjectIconContentLayout: MyIconContentLayout,
                            //geoObjectBalloonContentBody:
                        });

                    myMap.geoObjects.add(loadingObjectManager);

                    myMap.controls.remove('geolocationControl');
                    myMap.controls.remove('searchControl');
                    myMap.controls.remove('trafficControl');
                    myMap.controls.remove('typeSelector');
                    myMap.controls.remove('fullscreenControl');
                    myMap.controls.remove('rulerControl');
                    myMap.behaviors.disable(['scrollZoom']);
                });
            }
        </script>
    <!--//YM-->
</div>