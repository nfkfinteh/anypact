$(document).ready(function() {
    var check = 0;
    var ID_Object = $("#params_object").attr("data")

    $('#select-city').selectize({
        sortField: 'text'
    });

    $("#save_descript").on('click', function() {
        var text_descript = $(".cardPact-EditText-Descript .editbox").html().trim();
        cntDescript = $(".cardPact-EditText-Descript .editbox").text().trim().length;

        if(cntDescript==0) {
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
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCESS'){
                showResult('#popup-success', 'Изменения сохранены');
            }
        }
    });

    $("#save_conditions").on('click', function() {
        var text_descript = $(".cardPact-EditText-Сonditions .editbox").html().trim();
        cntDescript = $(".cardPact-EditText-Сonditions .editbox").text().trim().length;

        if(cntDescript==0) {
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
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCESS'){
                showResult('#popup-success', 'Изменения сохранены');
            }
        }
    });
    $("#save_summ").on('click', function() {
        var text_descript = $("#cardPact-EditText-Summ").text().trim();
        text_descript = Number(text_descript);
        if(text_descript.length<=0 || isNaN(text_descript)) {
            console.log('lol');
            $("#cardPact-EditText-Summ").text(0);
            text_descript = 0;
        }

        var city = $('#select-city').val();
        $.post(
            "/response/ajax/up_pact_text.php", {
                text: text_descript,
                city: city,
                id_element: ID_Object,
                atrr_text: 'summ'
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
    });

    // автоматическое удаление объявления
    $("#avtomatic_delete").on('click', function(){        
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

    });

    // Продление срока объявления
    $("#up_date_active").on('click', function(){
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
                showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
            }
            if($result['TYPE']=='SUCCESS'){
                $('.date-active').text($result['DATA']);
                showResult('#popup-success', 'Срок объявления продлен');
            }
        }

    });

    /*dop file*/
    $(document).on('submit', '.dop-file__form', function(e){
        e.preventDefault();
        var formData = new FormData(this);
        var mainData = JSON.stringify({
            id_element: ID_Object,
            atrr_text: 'add_incl_file'
        });

        formData.append( 'arr', mainData );

        var res = getURLData().then(function(data) {
            if(data=='ERROR'){
                showResult('#popup-error','Ошибка сохранения');
            }
            else{
                $('.list-dopfile').html(data);
                showResult('#popup-success', 'Изменения сохранены');
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

        $.post(
            "/response/ajax/up_pact_dopfile.php",
            mainData,
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            if(data=='ERROR'){
                showResult('#popup-error','Ошибка сохранения');
            }
            else{
                $('.list-dopfile').html(data);
                showResult('#popup-success', 'Изменения сохранены');
            }
        }
    });

    var arFiles = [];

    //добавление изображения
    $('#filePicture').on('change', function () {
        var files = this.files;
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
            }

            updateImage(formData);
        }
        //$('#cardPact-box-edit').empty();

        /*for (var i = 0; i < files.length; i++) {
            preview(files[i]);
        }*/


        this.value = '';
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
        var res = getURLData().then(function(data) {
            if(data=='ERROR'){
                showResult('#popup-error','Ошибка сохранения');
            }
            else{
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
});