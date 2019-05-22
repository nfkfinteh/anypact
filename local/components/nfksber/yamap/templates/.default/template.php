<? print_r($arResult['MAP_DATA']);?>
<div class="map-space">
			<!--YM-->
				<!--YMap--->
				<style>
				[class*="ymaps-2"][class*="-ground-pane"] {
					filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
					-webkit-filter: grayscale(100%);
				}
				.ymaps-2-1-73-searchbox-button-text, .ymaps-2-1-73-searchbox-button{
					background: #ff6416 !important;
    				color: white !important;
				}
				.ymaps-2-1-63-gotoymaps{
					display: none !important;
				}
				</style>
					<script src="https://api-maps.yandex.ru/2.1/?apikey=<ваш API-ключ>&lang=ru_RU" type="text/javascript">
					</script>					
						<div id="map" style="width: <?=$arResult['MAP_WIDTH']?>; height: <?=$arResult['MAP_HEIGHT']?>">
						</div>
						<script type="text/javascript">
                            var mapData = <?=CUtil::PhpToJSObject($arResult['MAP_DATA'])?>;
							// Функция ymaps.ready() будет вызвана, когда
							// загрузятся все компоненты API, а также когда будет готово DOM-дерево.
							ymaps.ready(init);
							function init(){ 
								// Создание карты.    
								var myMap = new ymaps.Map("map", {
									center: [<?=$arResult['CENTER_MAP']?>],
									zoom: 10,
									controls: [],
                                    width: 100,
                                    height: 100
								});
								myMap.behaviors.disable('scrollZoom');

								for(var data in mapData){

                                    myPlacemark = new ymaps.Placemark(mapData[data].geometry.coordinates, {
                                        balloonContent: mapData[data].properties.balloonContent,
                                    }, {
                                        iconLayout: 'default#imageWithContent',
                                        iconImageHref: '<?=$this->__folder?>/img/map_icon.png',
                                        iconImageSize: [30, 30],
                                        iconImageOffset: [-15, -15],
                                        iconContentOffset: [30, 30],
                                    });

                                    //Добавляем метки
                                    myMap.geoObjects.add(myPlacemark);
                                }

							}
						</script>					
			<!--//YM-->
		</div>