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

    $(document).on('click', '.js-add-frends', function(e){
        e.preventDefault();
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
                        if($result['ST'] == 'NEW'){
                            $(this_btn).removeClass('js-add-frends');
                            $(this_btn).addClass('disabled');
                            $(this_btn).text('Заявка отправлена');
                            $(this_btn).parent().append('<div class="not_auth-error"><span class="triangle" style="display: block; z-index: 1;">▲</span><a href="#" class="js-delete-frends" data-login="'+login+'">Отменить заявку</a></div>');
                        }else{
                            $(this_btn).addClass('js-delete-frends');
                            $(this_btn).removeClass('js-add-frends');
                            $(this_btn).text('Удалить из друзей');
                        }
                        preload('hide');
                        showResult('#popup-success','Добавлен в друзья');
                    }
                },
            });
        }
        return false;
    });

    $(document).on('click', '.js-delete-frends', function(e){
        e.preventDefault();
        let login = $(this).attr('data-login');
        if(login) {
            let this_btn = $(this);
            if(!this_btn.hasClass('btn-nfk')){
                this_btn = $(this).parents('.request_sent').children('.btn-nfk');
                $(this).parent().remove();
            }
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
                        $(this_btn).removeClass('disabled');
                        $(this_btn).addClass('js-add-frends');
                        $(this_btn).text('Добавить в друзья');
                        preload('hide');
                        showResult('#popup-success','Удален из друзей');
                    }
                },
            });
        }
        return false;
    });

    $(document).on('change', '.company.list__select', function(){
        let idCompany = $(this).val(),
            wrap_btn = $('.js-company__btn'),
            idUser = wrap_btn.attr('data-user'),
            currentCompany,
            html;

        for (i in bitrixCompanyList) {
            let company = bitrixCompanyList[i];
            if (company.ID == idCompany) {
                currentCompany = company;
                break;
            }
        }
        if(currentCompany.STAFF_NO_ACTIVE){
            html = '<a href="#" class="btn btn-nfk btn-uprofile disabled">' + 'Заявка на модерации' + '</a>';
            wrap_btn.html(html);
        }
        else if(currentCompany.STAFF){
            html = '<a href="#" class="btn btn-nfk btn-uprofile js-delete-staff" data-company="'+ currentCompany.ID +'">' + 'Удалить представителя' + '</a>';
            wrap_btn.html(html);
        }
        else{
            html = '<a href="#" class="btn btn-nfk btn-uprofile js-add-staff" data-company="'+ currentCompany.ID +'">' + 'Сделать представителем' + '</a>';
            wrap_btn.html(html);
        }
    });

    $(document).on('change', '.deal.list__select', function(){
        let idDeal = $(this).val(),
            wrap_btn = $('.js-deal__btn'),
            idUser = wrap_btn.attr('data-user'),
            currentDeal,
            html;

        for (i in bitrixDealList) {
            let deal = bitrixDealList[i];
            if (deal.ID == idDeal) {
                currentDeal = deal;
                break;
            }
        }
        if(currentDeal.ACCESS){
            html = '<a href="#" class="btn btn-nfk btn-uprofile js-delete-access" data-deal="'+ currentDeal.ID +'">' + 'Закрыть доступ' + '</a>';
            wrap_btn.html(html);
        }
        else{
            html = '<a href="#" class="btn btn-nfk btn-uprofile js-add-access" data-deal="'+ currentDeal.ID +'">' + 'Предоставить доступ' + '</a>';
            wrap_btn.html(html);
        }
    });

    $(document).on('click', '.js-add-access', function(e){
        e.preventDefault();
        let data = {
            idUser : $('.js-deal__btn').attr('data-user'),
            idDeal : $(this).attr('data-deal'),
            action : 'add'
        };
        let that = $(this);
        preload('show');
        $.ajax({
            type:'POST',
            url: '/response/ajax/access.php',
            data: data,
            dataType: 'JSON',
            success: function(data){
                preload('hide');
                if(data['TYPE']=='SUCCESS'){
                    //that.text('Заявка на модерации');
                    that.removeClass('js-add-access');
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

    $(document).on('click', '.js-delete-access', function(e){
        e.preventDefault();
        let data = {
            idUser : $('.js-deal__btn').attr('data-user'),
            idDeal : $(this).attr('data-deal'),
            action : 'delete'
        };
        let that = $(this);
        preload('show');
        $.ajax({
            type:'POST',
            url: '/response/ajax/access.php',
            data: data,
            dataType: 'JSON',
            success: function(data){
                preload('hide');
                if(data['TYPE']=='SUCCESS'){
                    //that.text('Заявка на модерации');
                    that.removeClass('js-add-access');
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

    $(document).on('click', '.js-add-staff', function(e){
        e.preventDefault();
        let data = {
            idUser : $('.js-company__btn').attr('data-user'),
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

    $(document).on('click', '.js-delete-staff', function(e){
        e.preventDefault();
        let data = {
            idUser : $('.js-company__btn').attr('data-user'),
            idCompany : $(this).attr('data-company'),
            action : 'delete'
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

    $(document).on('click', '.js-add-blacklist', function(){
        let login = $(this).attr('data-login');
        if(login) {
            let this_btn = $(this);
            preload('show');
            $.ajax({
                type: 'POST',
                url: '/response/ajax/add_blacklist.php',
                data: {'login':login,'action':'add'},
                success: function (result) {
                    $result = JSON.parse(result);
                    if ($result['TYPE'] == 'ERROR') {
                        preload('hide');
                        console.log($result['VALUE']);
                    }
                    if ($result['TYPE'] == 'SUCCESS') {
                        $(this_btn).addClass('js-delete-blacklist');
                        $(this_btn).removeClass('js-add-blacklist');
                        $(this_btn).text('Удалить из ЧС');
                        $('.black-list-show_hide').hide();
                        showResult('#popup-success','Пользователь добавлен в ЧС');
                        preload('hide');
                    }
                },
            });
        }
    });

    $(document).on('click', '.js-delete-blacklist', function(){
        let login = $(this).attr('data-login');
        if(login) {
            let this_btn = $(this);
            preload('show');
            $.ajax({
                type: 'POST',
                url: '/response/ajax/add_blacklist.php',
                data: {'login':login,'action':'delete'},
                success: function (result) {
                    $result = JSON.parse(result);
                    if ($result['TYPE'] == 'ERROR') {
                        preload('hide');
                        console.log($result['VALUE']);
                    }
                    if ($result['TYPE'] == 'SUCCESS') {
                        $(this_btn).removeClass('js-delete-blacklist');
                        $(this_btn).addClass('js-add-blacklist');
                        $(this_btn).text('Добавить в ЧС');
                        $('.black-list-show_hide').show();
                        showResult('#popup-success','Пользователь удален из ЧС');
                        preload('hide');
                    }
                },
            });
        }
    });
});