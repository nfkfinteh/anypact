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

    $('#save_ad').on('click', function() {

        var res = getURLData().then(function(data) {
            $result = JSON.parse(data);
            if($result['TYPE']=='ERROR'){
                console.log($result['VALUE']);
                alert($result['VALUE']);
            }
            if($result['TYPE']=='SUCCES'){
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
            let adSum = document.getElementById('cardPact-EditText-Summ').textContent;
            let date = document.getElementById('param_selected_activ_date_input').value;
            let adSection = $('#param_selected_category').attr('data-id');
            let prop = {};

            //html контент
            let arAdDescript = {};
            let aradCondition = {};

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
});