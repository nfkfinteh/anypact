$(document).ready(function(){
    $(document).on('click', '.btn-category', function(){
        let body  = $('#ajax_profile');
        let that = $(this);
        let state = that.attr('data-state');
        let current_state = $('.btn-category.active').attr('data-state');
        let type = that.attr('data-type');
        let url = location.protocol + '//' + location.host + location.pathname;
        console.log(url);
        let user_id = that.attr('data-user');
        let data = {
            'AJAX_SDEL': 'Y',
            'STATE_SDEL': state,
            'ID': user_id,
            'type': type
        };
        $('.btn-category').removeClass('active');
        that.addClass('active');

        if(current_state !=state){
            $.post(url, data, function(data) {
                body.html(data);
            });
        }
    });

    $(document).on('click', '.submit_message', function(){
        let form = $(this).parents('.modal-content').eq(0).find('form');
        let url = form.attr('action');
        let data = form.serialize();
        preload('show');

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                $result = JSON.parse(result);
                if($result['TYPE']=='ERROR'){
                    form.find('textarea').val('');
                    form.parents('.modal-content').eq(0).find('button.close').click();
                    preload('hide');
                    showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
                }
                if($result['TYPE']=='SUCCESS'){
                    form.find('textarea').val('');
                    form.parents('.modal-content').eq(0).find('button.close').click();
                    preload('hide');
                    showResult('#popup-success', 'Изменения сохранены');
                }
            },

        });
    });

    $(document).on('click', '.js-add-frends', function(){
        let login = $(this).attr('data-login');
        if(login) {
            let this_btn = $(this);
            preload('show');
            $.ajax({
                type: 'POST',
                url: '/response/ajax/add_frends.php',
                data: {'login':login,'action':'add'},
                success: function (result) {
                    $result = JSON.parse(result);
                    if ($result['TYPE'] == 'ERROR') {
                        preload('hide');
                        showResult('#popup-error','Ошибка сохранения');
                        console.log($result['VALUE']);
                    }
                    if ($result['TYPE'] == 'SUCCESS') {
                        $(this_btn).addClass('js-delete-frends');
                        $(this_btn).removeClass('js-add-frends');
                        $(this_btn).text('Удалить из друзей');
                        preload('hide');
                        showResult('#popup-success','Добавлен в друзья');
                    }
                },
            });
        }
    });

    $(document).on('click', '.js-delete-frends', function(){
        let login = $(this).attr('data-login');
        if(login) {
            let this_btn = $(this);
            preload('show');
            $.ajax({
                type: 'POST',
                url: '/response/ajax/add_frends.php',
                data: {'login':login,'action':'delete'},
                success: function (result) {
                    $result = JSON.parse(result);
                    if ($result['TYPE'] == 'ERROR') {
                        preload('hide');
                        showResult('#popup-error','Ошибка сохранения');
                        console.log($result['VALUE']);
                    }
                    if ($result['TYPE'] == 'SUCCESS') {
                        $(this_btn).removeClass('js-delete-frends');
                        $(this_btn).addClass('js-add-frends');
                        $(this_btn).text('Добавить в друзья');
                        preload('hide');
                        showResult('#popup-success','Удален из друзей');
                    }
                },
            });
        }
    });

    $(document).on('click', '.js-add-staff', function(e){
        e.preventDefault();
        let data = {
            idUser : $(this).attr('data-user'),
            idCompany : $(this).attr('data-company'),
            action : 'add'
        };
        let that = $(this);
        preload('show');
        $.ajax({
            type:'POST',
            url: '/response/ajax/staff.php',
            data: data,
            dataType: 'JSON',
            success: function(data){
                preload('hide');
                if(data['TYPE']=='SUCCESS'){
                    that.text('Заявка на модерации');
                    that.removeClass('js-add-staff');
                    that.addClass('disabled');
                    that.attr('disabled', true);
                    showResult('#popup-success', data['VALUE']);
                }else if(data['TYPE']=='ERROR'){
                    showResult('#popup-error','Ошибка сохранения', data['VALUE']);
                }
            },
            error:function(data){
                preload('hide');
                console.log(data); //ошибки сервера
            }
        });
    });
});