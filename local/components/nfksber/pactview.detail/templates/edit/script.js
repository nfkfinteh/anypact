$(document).ready(function() {
    var check = 0;
    var ID_Object = $("#params_object").attr("data")
    var select_lcation_city =  $('#LOCATION_CITY').selectize({
        sortField: 'text'
    });
    var control_location_city = select_lcation_city[0].selectize;

    //Сохранение Приватности
    $(document).on('click', '.onActive', function(e){        
        var buttonActive = $(this).attr('active');
        var blockId = $(this).attr('data-block-id');
        var valueId = $(this).attr('data-value-id');
        var type = $(this).find('input').attr('name');
        var self = this;

        e.preventDefault();

        if(buttonActive != "Y"){
            var val = valueId;
        }else{
            var val = "";
        }

        switch(type){
            case 'PRICE_ON_REQUEST':
                var postObj =  {                
                    id_element: ID_Object,
                    atrr_text: 'up_priceOnRequest',
                    PRICE_ON_REQUEST: val
                };
                break;
            case 'PRIVATE':
                var postObj =  {                
                    id_element: ID_Object,
                    atrr_text: 'up_private',
                    PRIVATE: val
                };
                break;
            case 'SHOW_PHONE':
                var postObj =  {                
                    id_element: ID_Object,
                    atrr_text: 'up_showPhone',
                    SHOW_PHONE: val
                };
                break;
        }

        console.log(postObj);

        if(typeof postObj !== "undefined" && postObj !== null){
            $.post(
                "/response/ajax/up_pact_text.php", postObj,
                onAjaxSuccess
            );
        }


        function onAjaxSuccess(data) {
            $result = JSON.parse(data);
            if($result['TYPE']=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCESS'){
                if(buttonActive == "Y"){
                    $(self).children('img').attr('src', '/local/templates/anypact/image/DontActive.png');
                    $(self).children('input').val("");
                    $(self).attr('active', '');
                }else{
                    $(self).children('img').attr('src', '/local/templates/anypact/image/Active.png');
                    $(self).children('input').val(valueId);
                    $(self).attr('active', 'Y');
                }
                if(blockId !== undefined && blockId.length > 0)
                    $('#'+blockId).toggle(300);
                preload('hide');
                showResult('#popup-success', 'Изменения сохранены');
            }
        }

        return false;
    });

    //Сохранение номера телефона
    $(document).on('click', '#save_deal_phone', function(e){        
        e.preventDefault();

        var ID_Object = $("#params_object").attr("data");

        var deal_phone = $("#DEAL_PHONE").val();
        
        if($('#SHOW_PHONE').val() == 17) {
            $.post(
                "/response/ajax/up_pact_text.php", {                    
                    id_element: ID_Object,
                    atrr_text: 'deal_phone',
                    DEAL_PHONE: deal_phone
                },
                onAjaxSuccess
            );
        }

        function onAjaxSuccess(data) {
            $result = JSON.parse(data);
            if($result['TYPE']=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCESS'){
                preload('hide');
                showResult('#popup-success', 'Изменения сохранены');
            }
        }

        return false;
    });    

    //Сохранение пользователей которм будет отображаться сделка
    $(document).on('click', '#save_user_select', function(e){        
        e.preventDefault();

        var ID_Object = $("#params_object").attr("data")

        var arUser = new Array();
        $('input[name="SELECTED_USER[]"]').each(function(){
            arUser.push($(this).val());
        });

        console.log();

        if($('#PRIVATE').val() == 10) {
            $.post(
                "/response/ajax/up_pact_text.php", {                    
                    id_element: ID_Object,
                    atrr_text: 'access_user',
                    ACCESS_USER: arUser
                },
                onAjaxSuccess
            );
        }

        function onAjaxSuccess(data) {
            $result = JSON.parse(data);
            if($result['TYPE']=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCESS'){
                preload('hide');
                showResult('#popup-success', 'Изменения сохранены');
            }
        }

        return false;
    });

    $("#save_descript").on('click', function() {
        var text_descript = $("#ad_descript").val();
        cntDescript = $("#ad_descript").val().trim().length;

        preload('show');
        if(cntDescript==0) {
            preload('hide');
            showResult('#popup-error','Ошибка сохранения', 'Поле обязательно для заполнения');
            return;
        }

        $.post(
            "/response/ajax/up_pact_text.php", {
                text: text_descript,
                id_element: ID_Object,
                atrr_text: 'descript'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            $result = JSON.parse(data);
            if($result['TYPE']=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCESS'){
                preload('hide');
                showResult('#popup-success', 'Изменения сохранены');
            }
        }
    });

    $("#save_conditions").on('click', function() {
        var text_descript = $("#ad_condition").val();
        cntDescript = $("#ad_condition").val().trim().length;
        preload('show');
        if(cntDescript==0) {
            preload('hide');
            showResult('#popup-error','Ошибка сохранения', 'Поле обязательно для заполнения');
            return;
        }

        $.post(
            "/response/ajax/up_pact_text.php", {
                text: text_descript,
                id_element: ID_Object,
                atrr_text: 'conditions'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            $result = JSON.parse(data);
            if($result['TYPE']=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCESS'){
                preload('hide');
                showResult('#popup-success', 'Изменения сохранены');
            }
        }
    });
    $("#save_summ").on('click', function() {
        var text_descript = $("#cardPact-EditText-Summ").text().trim();
        text_descript = Number(text_descript);
        if(text_descript.length<=0 || isNaN(text_descript)) {
            $("#cardPact-EditText-Summ").text(0);
            text_descript = 0;
        }

        var city = $('#select-city').val();

        preload('show');
        $.post(
            "/response/ajax/up_pact_text.php", {
                text: text_descript,
                id_element: ID_Object,
                atrr_text: 'summ'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            $result = JSON.parse(data);
            if($result['TYPE']=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCESS'){
                preload('hide');
                showResult('#popup-success', 'Изменения сохранены');
            }
        }
    });

    // автоматическое удаление объявления
   /* $("#avtomatic_delete").on('click', function(){
        let auto_delete_button = $(this).prop("checked");
        let auto_delete_params

        if(auto_delete_button){
            auto_delete_params = 'Y'
        }else{
            auto_delete_params = 'N'
        }

        $.post(
            "/response/ajax/up_pact_text.php", {
                text: auto_delete_params,
                id_element: ID_Object,
                atrr_text: 'aut_delete'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            $result = JSON.parse(data);
            if($result['TYPE']=='ERROR'){
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCESS'){
                showResult('#popup-success', 'Изменения сохранены');
            }
        }

    });*/

    // Продление срока объявления
    $("#up_date_active").on('click', function(){
        preload('show');
        $.post(
            "/response/ajax/up_pact_text.php", {                    
                id_element: ID_Object,
                atrr_text: 'up_date_active'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            $result = JSON.parse(data);
            if($result['TYPE']=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCESS'){
                preload('hide');
                $('.date-active').text($result['DATA']);
                showResult('#popup-success', 'Срок объявления продлен');
            }
        }

    });

    /*all_save*/
    $(document).on('submit', '.all-save_form', function(e){
        e.preventDefault();
        preload('show');
        var DETAIL_TEXT,
            prop = {},
            auto_delete_button = $('#avtomatic_delete').prop("checked");

        DETAIL_TEXT = $(".cardPact-EditText-Descript .editbox").html().trim();
        prop['CONDITIONS_PACT'] = $(".cardPact-EditText-Сonditions .editbox").html().trim();

        prop['SUMM_PACT'] = $("#cardPact-EditText-Summ").text().trim();
        prop['SUMM_PACT'] = Number(prop['SUMM_PACT']);
        if(prop['SUMM_PACT'].length<=0 || isNaN(prop['SUMM_PACT'])) {
            $("#cardPact-EditText-Summ").text(0);
            prop['SUMM_PACT'] = 0;
        }

        if(auto_delete_button){
            prop['AV_DELETE'] = 'Y';
        }else{
            prop['AV_DELETE'] = 'N';
        }

        prop['LOCATION_CITY'] = $('#LOCATION_CITY').val();
        prop['COORDINATES_AD'] = $('#COORDINATES_AD').val();
        if(prop['COORDINATES_AD'].length>0){
            let arCoordinate = prop['COORDINATES_AD'].split(',');
            prop['LONG'] = arCoordinate[0];
            prop['LAT'] = arCoordinate[1];
        }

        /*if(DETAIL_TEXT.length==0 || prop['CONDITIONS_PACT'].length==0) {
            showResult('#popup-error','Ошибка сохранения', 'Поле обязательно для заполнения');
            return;
        }*/

        var formData = new FormData(this);
        var mainData = JSON.stringify({
            id_element: ID_Object,
            DETAIL_TEXT: DETAIL_TEXT,
            PROPERTY: prop,
            atrr_text: 'all_save'
        });


        formData.append( 'arr', mainData );

        var res = getURLData().then(function(data) {
            result = JSON.parse(data);
            if(result['TYPE']=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения');
            }
            else{
                preload('hide');
                showResult('#popup-success', 'Изменения сохранены');
                location.reload();
            }
        });

        async function getURLData() {
            var url = '/response/ajax/up_pact_dopfile.php'

            const response = await fetch(url, {
                method: 'post',
                body:formData
            });
            const data = await response.text();
            return data
        }
    });

    // удаление загруженных файлов
    $(document).on('click', '.delete_unclude_file', function(){
        let id_value_el = $(this).attr('data');
        let id_file = $(this).attr('data-file');
        let mainData = new Object();;
        mainData.arr = JSON.stringify({
            id_file: id_file,
            id_value: id_value_el,
            id_element: ID_Object,
            atrr_text: 'delete_incl_file'
        });

        preload('show');

        $.post(
            "/response/ajax/up_pact_dopfile.php",
            mainData,
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            if(data=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения');
            }
            else{
                $('.list-dopfile').html(data);
                preload('hide');
                showResult('#popup-success', 'Изменения сохранены');
            }
        }
    });

    var arFiles = [];

    //добавление изображения
    $('#filePicture').on('change', function () {
        arFiles = $('.cardPact-box-edit-rem_img');
        var files = this.files;

        console.log(arFiles);
        console.log(files);

        if(arFiles.length <= 20){
            // ничего не делаем если files пустой
            if( typeof arFiles != 'undefined' ){
                var mainData = JSON.stringify({
                    id_element: ID_Object,
                    atrr_text: 'add'
                });

                var formData = new FormData();

                formData.append( 'arr', mainData );

                // заполняем объект данных файлами в подходящем для отправки формате
                for (var id in files) {
                    formData.append(id, files[id]);
                    arFiles.length++;
                    if(arFiles.length >= 20){
                        break;
                    }
                }

                updateImage(formData);
            }
            //$('#cardPact-box-edit').empty();

            /*for (var i = 0; i < files.length; i++) {
                preview(files[i]);
            }*/


            this.value = '';
        }
    });

    // Создание превью
    function preview(file) {
        var reader = new FileReader();

        reader.addEventListener('load', function(e) {
            let slide = '<div class="sp-slide">' +
                    '<img class="sp-image" src="'+e.target.result+'">' +
                '</div> ';
            let thumb = '<img class="sp-thumbnail" src="'+e.target.result+'">' +
                '<span class="cardPact-box-edit-rem_img" data-id="'+file.name+'">-</span>';
            let slider = $( '#my-slider' ).data( 'sliderPro' );
            let thumbAdd = '<img id="cardPact-box-edit-add_img" class="sp-thumbnail" src="/local/templates/anypact/image/add_img.png">';


            $(slide).appendTo('#my-slider .sp-slides');
            $("#cardPact-box-edit-add_img").parent().remove();
            $(thumb).appendTo($('#my-slider .sp-thumbnails'));
            $(thumbAdd).appendTo($('#my-slider .sp-thumbnails'));
            slider.update();

            slider.gotoSlide(slider.getTotalSlides()-1);

            arFiles[file.name] = file;


        });
        reader.readAsDataURL(file);
    }

    $(document).on('input', '#suggest', function(){
        displayButton();
    });

    //добавление изображения
    $(document).on( 'click', '.js-add_img', function( event ){
        $('#filePicture').click();
    });

    //удаление изображения
    $(document).on('click', '.cardPact-box-edit-rem_img',  function(){

        let id_value_el = $(this).attr('data-id');
        let mainData = JSON.stringify({
            id_value: id_value_el,
            id_element: ID_Object,
            atrr_text: 'delete'
        });
        var formData = new FormData();

        formData.append( 'arr', mainData );

        updateImage(formData);


    });

    function updateImage(arData){
        // var id_element = $(".cardPact-box").attr("data");
        preload('show');
        var res = getURLData().then(function(data) {
            if(data=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения');
            }
            else{
                preload('hide');
                showResult('#popup-success', 'Изменения сохранены');
                let slider = $( '#my-slider' ).data( 'sliderPro' );
                slider.destroy();
                $('#my-slider').html(data);

                initSlider();
                slider = $( '#my-slider' ).data( 'sliderPro' );
                slider.gotoSlide(slider.getTotalSlides()-1);
            }
        });


        async function getURLData() {
            var url = '/response/ajax/up_pact_img.php'

            const response = await fetch(url, {
                method: 'post',
                body:arData
            });
            const data = await response.text();
            return data
        }
    }

    /*slider*/
    function initSlider(){
        $( '#my-slider' ).sliderPro({
            width : "100%",
            aspectRatio : 1.6, //соотношение сторон
            loop : false,
            autoplay : false,
            fade : true,
            thumbnailWidth : 164,
            thumbnailHeight : 101,
            imageScaleMode: 'contain',
            //thumbnailPointer : true,
            keyboard : false,
            breakpoints: {
                450: {
                    thumbnailWidth : 82,
                    thumbnailHeight : 50
                }
            }
        });
    }
    initSlider();

    var coordinatesForm = [];
    var cityForm = '';

    ymaps.ready(init);
    function init() {
        // Подключаем поисковые подсказки к полю ввода.
        var map,
            placemark,
            addressLine,
            city = $('#LOCATION_CITY').val(),
            startCoordinates = $('#COORDINATES_AD').val();

        if(!city) city = adData['CITY'];
        if(!city) city = 'Москва';

        var suggestView = new ymaps.SuggestView('suggest', {
            provider:{
                suggest:(function(request, options){
                    return ymaps.suggest(document.getElementById('LOCATION_CITY').value +", " + request);
                })
            }
        });

        ymaps.geocode(city, {
            results: 1
        }).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0), coords;
            if(startCoordinates.length>0){
                coords = startCoordinates.split(',');
            }
            else{
                coords = firstGeoObject.geometry.getCoordinates();
            }

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
            map.geoObjects.remove(placemark);
            placemark = '';
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