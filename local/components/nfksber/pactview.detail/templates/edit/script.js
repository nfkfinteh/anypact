$(document).ready(function() {
    var check = 0;
    var ID_Object = $("#params_object").attr("data")

    $("#save_descript").on('click', function() {

        var text_descript = $(".cardPact-EditText-Descript .editbox").html();        
        $.post(
            "/response/ajax/up_pact_text.php", {
                text: text_descript,
                id_element: ID_Object,
                atrr_text: 'descript'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {

        }
    });

    $("#save_conditions").on('click', function() {
        var text_descript = $(".cardPact-EditText-Сonditions .editbox").html();        
        $.post(
            "/response/ajax/up_pact_text.php", {
                text: text_descript,
                id_element: ID_Object,
                atrr_text: 'conditions'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {

        }
    });
    $("#save_summ").on('click', function() {
        var text_descript = $("#cardPact-EditText-Summ").text();        
        $.post(
            "/response/ajax/up_pact_text.php", {
                text: text_descript,
                id_element: ID_Object,
                atrr_text: 'summ'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {

        }
    });

    // автоматическое удаление объявления
    $("#avtomatic_delete").on('click', function(){        
        let auto_delete_button = $(this).prop("checked");
        console.log(auto_delete_button)
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
            location.reload();
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
            console.log(data)
            //location.reload();
        }

    });

    // удаление загруженных файлов
    $('.delete_unclude_file').on('click', function(){      
        let id_value_el = $(this).attr('data');
        let id_file = $(this).attr('data-file');
        $.post(
            "/response/ajax/up_pact_text.php", {                    
                id_file: id_file,
                id_value: id_value_el,
                id_element: ID_Object,
                atrr_text: 'delete_incl_file'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            //console.log(data)
            location.reload();
        }

    });

    var arFiles = [];

    //добавление изображения
    $('#filePicture').on('change', function () {
        var files = this.files;
        console.log(files)        
        console.log('ИД Об '+ID_Object)
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
        console.log(formData)
        $('#cardPact-box-edit').empty();

        for (var i = 0; i < files.length; i++) {
            //preview(files[i]);
        }

        this.value = '';
    });

    // Создание превью
    function preview(file) {
        var reader = new FileReader();

        reader.addEventListener('load', function(e) {

            if(check == 0){
                var wrap = document.createElement('div');
                var img = document.createElement('img');
                var div = document.createElement('div');

                wrap.setAttribute('class', 'cardPact-box-BoxMainImg');

                //img.setAttribute('data-id', file.name);
                img.setAttribute('src', e.target.result);

                div.setAttribute('id', 'cardPact-box-edit-rem_img');
                div.innerHTML = ['<span>-</span>'].join('');

                wrap.insertBefore(img, null);
                wrap.insertBefore(div, null);

                //document.getElementById('cardPact-box-edit').insertBefore(wrap, null);


                arFiles[file.name] = file;
            }
            else{
                var img = document.createElement('img');

                //img.setAttribute('data-id', file.name);
                img.setAttribute('src', e.target.result);
                img.setAttribute('class', 'cardPact-box-BoxPrewImg-img');

                //document.getElementById('cardPact-box-BoxPrewImg').insertBefore(img, null);

                arFiles[file.name] = file;
            }

            check++;

        });
        reader.readAsDataURL(file);
    }


    //добавление изображения
    $(document).on( 'click', '#cardPact-box-edit-add_img', function( event ){
        $('#filePicture').click();
    });

    //удаление изображения
    $(document).on('click', '#cardPact-box-edit-rem_img',  function(){
        var item = $(this).parents('.cardPact-box-BoxMainImg').eq(0).find('img');
        var id = $(item).attr('data-id');
        var newImg = document.createElement('img');
        var ar_keys;
        var addimg = "<div id='cardPact-box-edit-add_img'>" +
            "<span>+</span>" +
            "</div>";
        var id_element = $(".cardPact-box").attr("data");

        delete arImg[id];

        ar_keys = Object.keys(arImg);

        var mainData = JSON.stringify({
            id_element: id_element,
            atrr_text: 'delete',
            detailUrl: arImg[ar_keys[0]],
            detailID: ar_keys[0],
        });

        var formData = new FormData();

        formData.append( 'arr', mainData );

        updateImage(formData);


        if(ar_keys.length > 0){
            item.remove();
            newImg.setAttribute('src', arImg[ar_keys[0]]);
            newImg.setAttribute('data-id', ar_keys[0]);
            $(newImg).prependTo('.cardPact-box-BoxMainImg');
            let remImg = $('.cardPact-box-BoxPrewImg-img').filter(function(index){
                let idImg = $(this).attr('data-id');
                return idImg == ar_keys[0];
            });
            remImg.remove();
        }
        else{
            $('.cardPact-box-BoxMainImg').remove();
            $(addimg).prependTo('.cardPact-box-edit');
        }

    });

    function updateImage(arData){
        // var id_element = $(".cardPact-box").attr("data");
        console.log(arData);
        var res = getURLData().then(function(data) {
            $result = JSON.parse(data);
            if($result['TYPE']=='ERROR'){
                console.log(data);
            }
            if($result['TYPE']=='SUCCES'){

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

});