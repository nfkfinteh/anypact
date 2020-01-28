$(document).ready(function() {

    var select_lcation_city =  $('#LOCATION_CITY').selectize({
        sortField: 'text'
    });
    var control_location_city = select_lcation_city[0].selectize;
    var city = adData['CITY'];


    // Выбор категории
    $('#choice_category li a').on('click', function() {
        let selected_item = $(this);
        let selected_item_text = selected_item.text();
        let selected_item_id = selected_item.attr('data-id');
        $('.select_category').css('display', 'none');
        $('#param_selected_category').html(selected_item_text + '<span class="glyphicon glyphicon-chevron-down"></span>');
        $('#param_selected_category').attr('data-id', selected_item_id);
        return false;
    });

    // открытие списка категорий
    $('#param_selected_category').on('click', function() {
        let status = $(this).attr("data");
        if(status == 'close'){
            // открытие списка
            $('.select_category').css({ 'display': 'table', 'height': '0px' });
            $(this).attr("data", "open");
        }else {
            // закрытие списка
            $('.select_category').css({ 'display': 'none', 'height': '0px' });            
            $(this).attr("data", "close");
        }
        
    });

    var arFiles = [];


    $('#filePicture').on('change', function () {
        var files = this.files;

        for (var i = 0; i < files.length; i++) {
            preview(files[i]);
        }

        this.value = '';
    });

    // Создание превью
    function preview(file) {
        var reader = new FileReader();
        reader.addEventListener('load', function(e) {

            var wrap = document.createElement('div');
            var img = document.createElement('img');
            var div = document.createElement('div');

            wrap.setAttribute('class', 'cardPact-box-BoxMainImg');
            wrap.setAttribute('data-id', file.name);

            img.setAttribute('class', 'cardPact-box-BoxPrewImg-img');
            img.setAttribute('src', e.target.result);

            div.setAttribute('class', 'cardPact-box-edit-rem_img');
            div.innerHTML = ['<span>-</span>'].join('');

            wrap.insertBefore(img, null);
            wrap.insertBefore(div, null);

            document.getElementById('cardPact-box-BoxPrewImg').insertBefore(wrap, null);

            arFiles[file.name] = file;

        });
        reader.readAsDataURL(file);
    }


    //добавление изображения
    $('.cardPact-box-edit').on( 'click', function( event ){
        $('#filePicture').click();
    });

    //удаление изображения
    $(document).on('click', '.cardPact-box-edit-rem_img',  function(){
        var item = $(this).parents('.cardPact-box-BoxMainImg').eq(0);
        var id = $(item).attr('data-id');

        delete arFiles[id];

        item.remove();

    });

    $('#param_selected_activ_date').on('click', function(){
        BX.calendar({node:this, field:'ACTIVE_DATE', form: '', bTime: true, bHideTime: true})
    });

    $(document).on('input', '#suggest', function(){
        displayButton();
    });

    $('#save_ad').on('click', function() {

        preload('show');
        var res = getURLData().then(function(data) {
            $result = JSON.parse(data);
            if($result['TYPE']=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCES'){
                preload('hide');
                window.location.href = "/my_pacts/edit_my_pact/?ELEMENT_ID="+$result['VALUE']+"&ACTION=EDIT";
            }

                /*let box = document.getElementById('inner')
                box.innerHTML = data*/
        });


        //var text = ;
        async function getURLData() {
            var url = '/response/ajax/add_new_ad.php'
            let adName = document.getElementById('ad_name').textContent;
            let adDescript = document.getElementById('ad_descript').innerText;
            let adCondition = document.getElementById('ad_condition').innerText;
            let adSum = document.getElementById('cardPact-EditText-Summ').textContent.trim();
            let date = document.getElementById('param_selected_activ_date_input').value;
            let adSection = $('#param_selected_category').attr('data-id');
            let adCity = $('#LOCATION_CITY').val();
            let adCoordinates = $('#COORDINATES_AD').val();
            let prop = {};

            preload('show');

            prop['LOCATION_CITY'] = adCity;
            prop['COORDINATES_AD'] = adCoordinates;

            //html контент
            let arAdDescript = {};
            let aradCondition = {};

            //поля
            if($.trim(adName).length != 0){
                adName = $.trim(adName);
            }
            else{
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', 'Название обязательно');
                return;
            }

            if(adSection === undefined){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', 'Раздел обязателен');
                return;
            }

            if(adCity.length == 0){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', 'Город обязателен');
                return;
            }

            if($.trim(adDescript).length != 0){
                adDescript = $.trim(adDescript);
                arAdDescript = {'TEXT': adDescript, 'TYPE': 'html'};
            }

            //свойства
            if($.trim(adCondition).length != 0){
                adCondition = $.trim(adCondition);
                aradCondition = {'TEXT': adCondition, 'TYPE': 'html'};
                prop['CONDITIONS_PACT'] = aradCondition;
            }

            if(adSum.length<=0 || isNaN(adSum)) {
                $("#cardPact-EditText-Summ").text(0);
                adSum = 0;
            }
            if(adSum.length != 0) {
                prop['SUMM_PACT'] = $.trim(adSum);
            }
            // ничего не делаем если files пустой
            if( typeof arFiles != 'undefined' ){

                var mainData = JSON.stringify({
                    MODIFIED_BY  : adData['USER_ID'],
                    IBLOCK_SECTION_ID : adSection,
                    IBLOCK_ID : 3,
                    PROPERTY_VALUES : prop,
                    NAME : adName,
                    ACTIVE : "N",
                    DETAIL_TEXT : arAdDescript,
                    DATE_ACTIVE_TO : date,
                });

                var formData = new FormData();

                formData.append( 'main', mainData );

                // заполняем объект данных файлами в подходящем для отправки формате
                for (var id in arFiles) {
                    formData.append(id, arFiles[id]);
                }

            }


            const response = await fetch(url, {
                method: 'post',
                body:formData
            });
            const data = await response.text();
            return data
        }

    });

    var coordinatesForm = [];
    var cityForm = '';

    ymaps.ready(init);
    function init() {
        if(!city){
            city = 'Москва';
        }

        // Подключаем поисковые подсказки к полю ввода.
        var suggestView = new ymaps.SuggestView('suggest', {
                provider:{
                    suggest:(function(request, options){
                        return ymaps.suggest(document.getElementById('LOCATION_CITY').value +", " + request);
                    })
                }
            }),
            map,
            placemark,
            addressLine;

        ymaps.geocode(city, {
            results: 1
        }).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);
            var coords = firstGeoObject.geometry.getCoordinates();
            var firstGeoObjectGlobal;
            map = new ymaps.Map('map', {
                center: coords,
                zoom: 12,
                controls: ['zoomControl']
            });

            // событие клика на крату
            map.events.add('click', function (e) {
                var coords = e.get('coords');

                // Если метка уже создана – просто передвигаем ее.
                if (placemark) {
                    placemark.geometry.setCoordinates(coords);
                }
                // Если нет – создаем.
                else {
                    placemark = createPlacemark(coords);
                    map.geoObjects.add(placemark);
                    // Слушаем событие окончания перетаскивания на метке.
                    placemark.events.add('dragend', function () {
                        getAddress(placemark.geometry.getCoordinates());
                    });
                }

                getAddress(coords);
                hideButton();
            });

        });

        // При клике по кнопке запускаем верификацию введёных данных.
        $(document).on('click', '#check-button_map', function (e) {
            geocode();
        });

        //при смене города изменяем центрирование карты
        $(document).on('change', 'select.js-location-city', function(){
            city = $(this).val();
            changeCity(city);
            //стираем значение ранее установленных координат
            $('#COORDINATES_AD').val('');
            $('#suggest').val('');
        });

        function changeCity(city){
            ymaps.geocode(city, {
                results: 1
            }).then(function (res) {
                var firstGeoObject = res.geoObjects.get(0);
                var coords = firstGeoObject.geometry.getCoordinates();
                map.setCenter(coords, 12);

            });
        }
        function geocode() {
            // Забираем запрос из поля ввода.
            var request = $('#suggest').val();
            // Геокодируем введённые данные.
            ymaps.geocode(request).then(function (res) {
                var obj = res.geoObjects.get(0),
                    error, hint;

                if (obj) {
                    switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                        case 'exact':
                            break;
                        case 'number':
                        case 'near':
                        case 'range':
                            error = 'Адрес не найден. Уточните адрес или укажите его на карте';
                            hint = 'Уточните номер дома';
                            break;
                        case 'street':
                            error = 'Адрес не найден. Уточните адрес или укажите его на карте';
                            hint = 'Уточните номер дома';
                            break;
                        case 'other':
                        default:
                            error = 'Адрес не найден. Уточните адрес или укажите его на карте';
                            hint = 'Уточните адрес';
                    }
                } else {
                    error = 'Адрес не найден. Уточните адрес или укажите его на карте';
                    hint = 'Уточните адрес';
                }

                // Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
                if (error) {
                    showError(error);
                } else {
                    showResult(obj);
                }
            }, function (e) {
                console.log(e);
                showError('Адрес не найден. Уточните адрес или укажите его на карте');
            })

        }
        function showResult(obj) {
            // Удаляем сообщение об ошибке, если найденный адрес совпадает с поисковым запросом.
            $('#suggest').removeClass('input_error');
            $('#notice').css('display', 'none');

            var mapContainer = $('#map'),
                bounds = obj.properties.get('boundedBy'),
                // Рассчитываем видимую область для текущего положения пользователя.
                mapState = ymaps.util.bounds.getCenterAndZoom(
                    bounds,
                    [mapContainer.width(), mapContainer.height()]
                ),
                // Сохраняем полный адрес для сообщения под картой.
                address = [obj.getCountry(), obj.getAddressLine()].join(', '),
                // Сохраняем укороченный адрес для подписи метки.
                shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');

            //Сохраняем координаты и горд для сохранения в инфоблок
            coordinatesForm = mapState.center;
            cityForm = obj.getLocalities()[0];
            $('#COORDINATES_AD').val(coordinatesForm);

            // Убираем контролы с карты.
            mapState.controls = ['zoomControl'];
            // Создаём карту.
            createMap(mapState, shortAddress);
        }
        function showError(message) {
            coordinatesForm = [];
            cityForm = '';
            $('#COORDINATES_AD').val(coordinatesForm);
            $('#notice').text(message);
            $('#suggest').addClass('input_error');
            $('#notice').css('display', 'block');
        }
        function createMap(state, caption) {
            // Если карта еще не была создана, то создадим ее и добавим метку с адресом.
            if (!map) {
                map = new ymaps.Map('map', state);
                placemark = new ymaps.Placemark(
                    map.getCenter(), {
                        iconCaption: caption,
                        balloonContent: caption
                    }, {
                        iconLayout: 'default#imageWithContent',
                        iconImageHref: '/local/templates/anypact/img/map_icon.png',
                        iconImageSize: [30, 30],
                        iconImageOffset: [-15, -15],
                        iconContentOffset: [30, 30],
                    });
                map.geoObjects.add(placemark);
                // Если карта есть, то выставляем новый центр карты и меняем данные и позицию метки в соответствии с найденным адресом.
            } else {
                map.setCenter(state.center, state.zoom);

                if(!placemark){
                    placemark = new ymaps.Placemark(
                        map.getCenter(), {
                            iconCaption: caption,
                            balloonContent: caption
                        }, {
                            iconLayout: 'default#imageWithContent',
                            iconImageHref: '/local/templates/anypact/img/map_icon.png',
                            iconImageSize: [30, 30],
                            iconImageOffset: [-15, -15],
                            iconContentOffset: [30, 30],
                        });
                    map.geoObjects.add(placemark);
                }

                placemark.geometry.setCoordinates(state.center);
                placemark.properties.set({iconCaption: caption, balloonContent: caption});
            }
        }

        // Создание метки.
        function createPlacemark(coords) {
            return new ymaps.Placemark(coords, {
                iconCaption: 'поиск...'
            }, {
                iconLayout: 'default#imageWithContent',
                iconImageHref: '/local/templates/anypact/img/map_icon.png',
                iconImageSize: [30, 30],
                iconImageOffset: [-15, -15],
                iconContentOffset: [30, 30],
            });
        }

        // Определяем адрес по координатам (обратное геокодирование).
        function getAddress(coords) {
            placemark.properties.set('iconCaption', 'поиск...');
            ymaps.geocode(coords).then(function (res) {
                var firstGeoObjectGlobal = res.geoObjects.get(0);
                placemark.properties.set({
                        // Формируем строку с данными об объекте.
                        iconCaption: [
                            // Название населенного пункта или вышестоящее административно-территориальное образование.
                            firstGeoObjectGlobal.getLocalities().length ? firstGeoObjectGlobal.getLocalities() : firstGeoObjectGlobal.getAdministrativeAreas(),
                            // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                            firstGeoObjectGlobal.getThoroughfare() || firstGeoObjectGlobal.getPremise()
                        ].filter(Boolean).join(', '),
                        // В качестве контента балуна задаем строку с адресом объекта.
                        balloonContent: firstGeoObjectGlobal.getAddressLine()
                    });

                addressLine = firstGeoObjectGlobal.getAddressLine();
                coordinatesForm = coords;
                cityForm = firstGeoObjectGlobal.getLocalities()[0];
                $('#suggest').val(addressLine);
                $('#COORDINATES_AD').val(coordinatesForm);

            });
        }
    }

    function hideButton(){
        $('#check-button_map').hide();
        $('.input-search_map').css('width', '100%');
    }

    function displayButton(){
        $('#check-button_map').show();
        $('.input-search_map').css('width', '70%');
    }
});