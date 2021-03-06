$(document).ready(function() {

    var select_lcation_city =  $('#LOCATION_CITY').selectize({
        sortField: 'text'
    });
    var control_location_city = select_lcation_city[0].selectize;
    var city = adData['CITY'];

    //Приватность
    $(document).on('click', '.onActive', function(e){        
        var buttonActive = $(this).attr('active');
        var blockId = $(this).attr('data-block-id');
        var valueId = $(this).attr('data-value-id');

        e.preventDefault();

        if(buttonActive == "Y"){
            $(this).children('img').attr('src', '/local/templates/anypact/image/DontActive.png');
            $(this).children('input').val("");
            $(this).attr('active', '');
        }else{
            $(this).children('img').attr('src', '/local/templates/anypact/image/Active.png');
            $(this).children('input').val(valueId);
            $(this).attr('active', 'Y');
            
        }

        if(blockId !== undefined && blockId.length > 0)
            $('#'+blockId).toggle(300);

        return false;
    });

    // Раскрытие списка
    $('#choice_category li a.category-parent').on('click', function() {
        var id = $(this).attr('data-parent-id');
        $(this).toggleClass('category-section-open');
        $('#choice_category .category-childs[data-parent-id="'+id+'"]').slideToggle();
        return false;
    });

    // Выбор категории
    $('#choice_category li a.category-select').on('click', function() {
        let selected_item = $(this);
        let selected_item_text = selected_item.text();
        let selected_item_id = selected_item.attr('data-id');
        $('.select_category').slideToggle();
        $('#param_selected_category').html(selected_item_text + '<span class="glyphicon glyphicon-chevron-down"></span>');
        $('#param_selected_category').attr('data-id', selected_item_id);

        //валидация формы
        $('.param_selected_category__input').val(selected_item_id);
        $('.param_selected_category__input').removeClass('validate-error');
        if(selected_item_id) $('.param_selected_category__input').parents('.cardPact__item').eq(0).find('.error-message').remove();
        return false;
    });

    if($('#param_selected_category').attr('data-id')){
        let activeSection = $('#choice_category li a[data-id='+$('#param_selected_category').attr('data-id')+']');
        if(activeSection) activeSection.click();
    }

    // открытие списка категорий
    $('#param_selected_category').on('click', function() {
        $(this).find('.glyphicon').toggleClass('transform-rotate-180deg');
        $('.select_category').slideToggle();
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
            if(arFiles.length  < 20){
                var wrap = document.createElement('div');
                var img = document.createElement('img');
                var div = document.createElement('div');
                var block = document.createElement('div');

                wrap.setAttribute('class', 'cardPact-box-BoxMainImg');
                wrap.setAttribute('data-id', file.name);

                img.setAttribute('class', 'cardPact-box-BoxPrewImg-img');
                img.setAttribute('src', e.target.result);

                div.setAttribute('class', 'CardPact-box-edit-block-rem_img');

                block.setAttribute('class', 'cardPact-box-edit-rem_img');
                block.innerHTML = ['<span>-</span>'].join('');

                div.insertBefore(block, null);

                wrap.insertBefore(img, null);
                
                wrap.insertBefore(div, null);

                document.getElementById('cardPact-box-BoxPrewImg').insertBefore(wrap, null);

                arFiles[file.name] = file;
                arFiles.length++;

                if(arFiles.length  >= 20){
                    $('.cardPact-box-edit').hide();
                }
            }
        });
        reader.readAsDataURL(file);
    }


    //добавление изображения
    $('.cardPact-box-edit').on( 'click', function( event ){
        $('#filePicture').click();
    });

    //удаление изображения
    $(document).on('click', '.CardPact-box-edit-block-rem_img',  function(){
        var item = $(this).parents('.cardPact-box-BoxMainImg').eq(0);
        var id = $(item).attr('data-id');

        delete arFiles[id];
        arFiles.length--;

        if(arFiles.length  < 20){
            if($('.cardPact-box-edit').css('display') == 'none'){
                $('.cardPact-box-edit').show();
            }
        }

        item.remove();

    });

    $('#param_selected_activ_date').on('click', function(){
        BX.calendar({node:this, field:'ACTIVE_DATE', form: '', bTime: false})
    });

    $(document).on('input', '#suggest', function(){
        displayButton();
    });

    function getFormData(){
        let arID = new Array();
        if($('#PRIVATE').val() == 10) {
            $('input[name="SELECTED_USER[]"]').each(function(){
                arID.push($(this).val());
            });
        }
        let arResult = {
            adName : $('#ad_name').val(),
            adDescript : $('#ad_descript').val(),
            adCondition : $('#ad_condition').val(),
            adSum : $('#cardPact-EditText-Summ').val(),
            date : $('#param_selected_activ_date_input').val(),
            adSection : $('#param_selected_category').attr('data-id'),
            adCity : $('#LOCATION_CITY').val(),
            adCoordinates : $('#COORDINATES_AD').val(),
            DOGOVOR_KEY : $('#DOGOVOR_KEY').val(),
            PRIVATE : $('#PRIVATE').val(),
            PRICE_ON_REQUEST : $('#PRICE_ON_REQUEST').val(),
            SHOW_PHONE : $('#SHOW_PHONE').val(),
            DEAL_PHONE : $('#DEAL_PHONE').val(),
            INDEFINITELY : $('#INDEFINITELY').val(),
            ACCESS_USER : arID,
        };
        return arResult;
    }

    $(document).on('submit', '#save_ad', function(e) {
        e.preventDefault();
        var res = getURLData().then(function(data) {
            $result = JSON.parse(data);
            if($result['TYPE']=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCES'){
                preload('hide');
                showResult('#popup-success','Объявление создано');
                setTimeout(function(){
                    window.location.href = "/my_pacts/edit_my_pact/?ELEMENT_ID="+$result['VALUE']+"&ACTION=EDIT";
                }, 300);

            }
                /*let box = document.getElementById('inner')
                box.innerHTML = data*/
        });


        async function getURLData() {
            let arFormData = getFormData(),
                url = '/response/ajax/add_new_ad.php',
                prop = {};

            preload('show');

            prop['LOCATION_CITY'] = arFormData.adCity;
            prop['COORDINATES_AD'] = arFormData.adCoordinates;
            prop['CONDITIONS_PACT'] = arFormData.adCondition;
            prop['PRIVATE'] = arFormData.PRIVATE;
            prop['PRICE_ON_REQUEST'] = arFormData.PRICE_ON_REQUEST;
            prop['SHOW_PHONE'] = arFormData.SHOW_PHONE;
            prop['DEAL_PHONE'] = arFormData.DEAL_PHONE;
            prop['INDEFINITELY'] = arFormData.INDEFINITELY;

            if(prop['COORDINATES_AD'].length>0){
                let arCoordinate = prop['COORDINATES_AD'].split(',');
                prop['LONG'] = arCoordinate[0];
                prop['LAT'] = arCoordinate[1];
            }

            if(arFormData.adSum.length<=0 || isNaN(arFormData.adSum)) {
                $("#cardPact-EditText-Summ").val(0);
                arFormData.adSum = 0;
            }
            prop['SUMM_PACT'] = arFormData.adSum;

            var mainData = JSON.stringify({
                MODIFIED_BY  : adData['USER_ID'],
                IBLOCK_SECTION_ID : arFormData.adSection,
                IBLOCK_ID : 3,
                PROPERTY_VALUES : prop,
                NAME : arFormData.adName,
                ACTIVE : "N",
                DETAIL_TEXT : {'TEXT': arFormData.adDescript, 'TYPE': 'html'},
                DATE_ACTIVE_TO : arFormData.date,
                DOGOVOR_KEY : arFormData.DOGOVOR_KEY
            });

            var formData = new FormData();

            formData.append( 'main', mainData );

            // ничего не делаем если files пустой
            if( typeof arFiles != 'undefined' ){
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
        let startCoordinates = $('#COORDINATES_AD').val();

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
            if(startCoordinates.length>0){
                coords = startCoordinates.split(',');
            }
            else{
                coords = firstGeoObject.geometry.getCoordinates();
            }

            var firstGeoObjectGlobal;
            map = new ymaps.Map('map', {
                center: coords,
                zoom: 12,
                controls: ['zoomControl']
            });

            if(startCoordinates.length>0){
                placemark = createPlacemark(coords);
                map.geoObjects.add(placemark);
                getAddress(coords);
            }

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
        //$('.input-search_map').css('width', '70%');
    }

    $(document).on('click', '#add_dogovor', function(e){
        e.preventDefault();
        let url = $(this).attr('data-url'),
            arFormData = getFormData(),
            form = '';

        $.each(arFormData, function (name, val) {
            if(val!=undefined){
                form +=name+'='+val+'&';
            }
        });
        form = encodeURIComponent(form);
        url += '&form=' + form;
        location.href = url;
    });

    //валидация для простых полей формы
    $("#save_ad").validate({
        rules: {
            ad_name: {
                required: true
            },
            LOCATION_CITY: {
                required: true
            },
            CATEGORY:{
                required: true
            }
        },
        messages: {
            ad_name: 'Поле обязательно для заполнения',
            LOCATION_CITY: 'Поле обязательно для заполнения',
            CATEGORY: 'Поле обязательно для заполнения',
        },
        ignore: ".ignore-validate, :hidden",
        onsubmit: true,
        showErrors: function(errorMap, errorList) {
            let that = this.lastActive

            if(errorList.length>0){
                for (let i = 0; i < errorList.length; i++){
                    let messaage = errorList[i].message;
                    if(!$(errorList[i].element).hasClass('validate-error')){
                        $(errorList[i].element).addClass('validate-error');
                        if($(errorList[i].element).attr('name') == 'CATEGORY'){
                            $(errorList[i].element).parents('.cardPact__item').eq(0).find('.cardPact__title').before('<span class="error-message">'+messaage+'</span>');
                        }
                        else{
                            $(errorList[i].element).parents('.cardPact__item').eq(0).find('h3').after('<span class="error-message">'+messaage+'</span>');
                        }
                    }
                }
            }
            else{
                $(that).removeClass('validate-error');
                $(that).parents('.cardPact__item').eq(0).find('.error-message').remove();
            }
        }
    });
});