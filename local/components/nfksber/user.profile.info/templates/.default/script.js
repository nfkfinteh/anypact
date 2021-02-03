$(document).ready(function(){
    $(document).on('click', '.search-people__button',function(e){
        e.preventDefault;
        var id = $(this).data('id');
        var data = {
            TITLE: 'Новое сообщение',
            BODY: '<form id="message_user" action="/response/ajax/add_new_messag_user.php"><input class="id__input" type="hidden" name="id" value="'+id+'"><div><textarea id="textMessage" class="message-text-input custom-scroll" name="message-text" placeholder="Введите сообщение" data-emojiable="true" data-emoji-input="unicode"></textarea></div></form>',
            BUTTONS: [
                {
                    NAME: 'Отмена',
                    SECONDARY: 'Y',
                    CLOSE: 'Y'
                },
                {
                    NAME: 'Отправить',
                    CALLBACK: (function(){
                        let form = $('#message_user');
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
                                    preload('hide');
                                    showResult('#popup-error','Ошибка! ', $result['VALUE']);
                                }
                                if($result['TYPE']=='SUCCESS'){
                                    preload('hide');
                                    showResult('#popup-success', $result['VALUE']);
                                }
                            },
                            error: function (a,b,c) {
                                console.log(a);
                                console.log(b);
                                console.log(c);
                            }
                        });
                    }),
                    CLOSE: 'Y'
                }
            ],
            ONLOAD: (function(){
                window.emojiPicker = new EmojiPicker({
                    emojiable_selector: '[data-emojiable=true]',
                    assetsPath: '/local/templates/anypact/img/',
                    popupButtonClasses: 'fa fa-smile-o'
                });
                window.emojiPicker.discover();
            })
        };
        newAnyPactPopUp(data);
        return false;
    });

    $(document).on('click', '.js-add-frends', function(e){
        e.preventDefault();
        let id = $(this).attr('data-id');
        if(id) {
            let this_btn = $(this);
            preload('show');
            $.ajax({
                type: 'POST',
                url: '/response/ajax/add_frends.php',
                data: {'id':id,'action':'add'},
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
                            $(this_btn).parent().append('<div class="not_auth-error"><div class="triangle" style="display: block; z-index: 1;">▲</div><a href="#" class="js-delete-frends" data-id="'+id+'">Отменить заявку</a></div>');
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
        let id = $(this).attr('data-id');
        if(id) {
            let this_btn = $(this);
            if(!this_btn.hasClass('btn-nfk')){
                this_btn = $(this).parents('.request_sent').children('.btn-nfk');
                $(this).parent().remove();
            }
            preload('show');
            $.ajax({
                type: 'POST',
                url: '/response/ajax/add_frends.php',
                data: {'id':id,'action':'delete'},
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
        let id = $(this).attr('data-id');
        if(id) {
            let this_btn = $(this);
            preload('show');
            $.ajax({
                type: 'POST',
                url: '/response/ajax/add_blacklist.php',
                data: {'id':id,'action':'add'},
                success: function (result) {
                    $result = JSON.parse(result);
                    if ($result['TYPE'] == 'ERROR') {
                        preload('hide');
                        console.log($result['VALUE']);
                    }
                    if ($result['TYPE'] == 'SUCCESS') {
                        let btnF = $('.js-delete-frends');
                        if(!btnF.hasClass('btn-nfk')){
                            var btnFriends = $(btnF).parents('.request_sent').children('.btn-nfk');
                            $(btnF).parent().remove();
                        }else{
                            var btnFriends = btnF;
                        }
                        if(btnFriends.length > 0){
                            $(btnFriends).removeClass('js-delete-frends');
                            $(btnFriends).removeClass('disabled');
                            $(btnFriends).addClass('js-add-frends');
                            $(btnFriends).text('Добавить в друзья');
                        }
                        $(this_btn).addClass('js-delete-blacklist');
                        $(this_btn).removeClass('js-add-blacklist');
                        $(this_btn).text('Разблокировать');
                        $('.black-list-show_hide').hide();
                        showResult('#popup-success','Пользователь заблокирован');
                        preload('hide');
                    }
                },
            });
        }
    });

    $(document).on('click', '.js-delete-blacklist', function(){
        let id = $(this).attr('data-id');
        if(id) {
            let this_btn = $(this);
            preload('show');
            $.ajax({
                type: 'POST',
                url: '/response/ajax/add_blacklist.php',
                data: {'id':id,'action':'delete'},
                success: function (result) {
                    $result = JSON.parse(result);
                    if ($result['TYPE'] == 'ERROR') {
                        preload('hide');
                        console.log($result['VALUE']);
                    }
                    if ($result['TYPE'] == 'SUCCESS') {
                        $(this_btn).removeClass('js-delete-blacklist');
                        $(this_btn).addClass('js-add-blacklist');
                        $(this_btn).text('Заблокировать');
                        $('.black-list-show_hide').show();
                        showResult('#popup-success','Пользователь разблокирован');
                        preload('hide');
                    }
                },
            });
        }
    });
});