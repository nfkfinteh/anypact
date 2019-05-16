$(document).ready(function() {
    $('#choice_category li a').on('click', function() {
        let selected_item = $(this);
        let selected_item_text = selected_item.text();
        let selected_item_id = selected_item.attr('data-id');
        $('.select_category').css('display', 'none');
        $('#param_selected_category').html(selected_item_text + '<span class="glyphicon glyphicon-chevron-down"></span>');
        $('#param_selected_category').attr('data-id', selected_item_id);
        return false;
    });

    $('#param_selected_category').on('click', function() {

        $('.select_category').css({ 'display': 'table', 'height': '0px' });
        $('.select_category').animate({ 'height': '100%' }, 500);
    });


    var arFiles = [];

    //функция для показа превью изображений
    function handleFileSelect(evt) {
        var files = evt.target.files;
        for (var i = 0, f; f = files[i]; i++) {
            if (!f.type.match('image.*')) {
                continue;
            }

            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    var img = document.createElement('img');
                    img.setAttribute('class', 'cardPact-box-BoxPrewImg-img');
                    img.setAttribute('src', e.target.result);

                    document.getElementById('cardPact-box-BoxPrewImg').insertBefore(img, null);
                };
            })(f);
            reader.readAsDataURL(f);
        }
    }


    $('#filePicture').on('change', function(e){

        e.stopPropagation();
        e.preventDefault();

        handleFileSelect(e);
        $.merge(arFiles, this.files);

    });

    $('.cardPact-box-edit').on( 'click', function( event ){
        $('#filePicture').click();
    });

    $('#param_selected_activ_date').on('click', function(){



        BX.calendar({node:this, field:'ACTIVE_DATE', form: '', bTime: true, bHideTime: true})
    });

    $('#save_ad').on('click', function() {

        var res = getURLData().then(function(data) {
            if(data){
                console.log(data);
                alert(data);
            }

                /*let box = document.getElementById('inner')
                box.innerHTML = data*/
        });


        //var text = ;
        async function getURLData() {
            var url = '/response/ajax/add_new_ad.php'
            let adName = document.getElementById('ad_name').textContent;
            let adDescript = document.getElementById('ad_descript').innerHTML;
            let adCondition = document.getElementById('ad_condition').innerHTML;
            let adSum = document.getElementById('cardPact-EditText-Summ').textContent;
            let date = document.getElementById('param_selected_activ_date_input').value;
            let adSection = $('#param_selected_category').attr('data-id');
            let prop = {};


            //поля
            if($.trim(adName).length != 0){
                adName = $.trim(adName);
            }
            else{
                alert('Название обязательно');
                return;
            }

            if(adSection === undefined){
                alert('Раздел обязателен');
                return;
            }

            adDescript = $.trim(adDescript);

            //свойства
            if($.trim(adCondition).length != 0){
                prop['CONDITIONS_PACT'] = $.trim(adCondition);
            }
            if($.trim(adSum).length != 0){
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
                    ACTIVE : "Y",
                    DETAIL_TEXT : adDescript,
                    DATE_ACTIVE_TO : date,
                });

                var formData = new FormData();

                formData.append( 'main', mainData );

                // заполняем объект данных файлами в подходящем для отправки формате
                $.each( arFiles, function( key, value ){
                    formData.append( key, value );
                });

            }


            const response = await fetch(url, {
                method: 'post',
                body:formData
            });
            const data = await response.text();
            return data
        }

    });
});