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
						<div id="map" style="width: 100%; height: 635px">
						</div>
						<script type="text/javascript">
							// Функция ymaps.ready() будет вызвана, когда
							// загрузятся все компоненты API, а также когда будет готово DOM-дерево.
							ymaps.ready(init);
							function init(){ 
								// Создание карты.    
								var myMap = new ymaps.Map("map", {
									// Координаты центра карты.
									// Порядок по умолчанию: «широта, долгота».
									center: [55.76, 37.64],
									// Уровень масштабирования. Допустимые значения:
									// от 0 (весь мир) до 19.
									zoom: 7,
									controls: []
								});
								myMap.behaviors.disable('scrollZoom');
							}
						</script>					
			<!--//YM-->
		</div>