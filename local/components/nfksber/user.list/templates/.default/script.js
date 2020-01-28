$(document).ready(function(){
    $(document).on('click', '.search-peaople__button',function(){
        let login = $(this).data('login');
        $('.login__input').val(login);
    });

    $(document).on('click', '.submit_message', function(){
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
                        console.log($result['VALUE']);
                    }
                    if ($result['TYPE'] == 'SUCCESS') {
                        $(this_btn).addClass('js-delete-frends');
                        $(this_btn).removeClass('js-add-frends');
                        $(this_btn).children('img').attr({'src':'/local/templates/anypact/image/people-search-delete-people.png','title':'Удалить из друзей','alt':'Удалить из друзей'});
                        preload('hide');
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
                        console.log($result['VALUE']);
                    }
                    if ($result['TYPE'] == 'SUCCESS') {
                        $(this_btn).removeClass('js-delete-frends');
                        $(this_btn).addClass('js-add-frends');
                        $(this_btn).children('img').attr({'src':'/local/templates/anypact/image/people-search-add-people.png','title':'Добавить в друзья','alt':'Добавить в друзья'});
                        if(window.location.pathname == '/friends/') $(this_btn).parents('.view-item').remove();
                        preload('hide');
                    }
                },
            });
        }
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
                        $(this_btn).children('img').attr({'src':'/local/templates/anypact/image/black-list.png','title':'Удалить из черного списка','alt':'Удалить из черного списка'});
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
                        $(this_btn).children('img').attr({'src':'/local/templates/anypact/image/black-list-add.png','title':'Добавить в черный список','alt':'Добавить в черный список'});
                        preload('hide');
                    }
                },
            });
        }
    });

});