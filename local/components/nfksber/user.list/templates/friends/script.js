$(document).ready(function(){

    function updateBlackList(){
        let formData = {
            'ajax_result':'y'
        };

        $.ajax({
          type:'POST',
          url: window.location.href,
          data: formData,
          success:function(data){
              $('.blacklist').html(data);
          },
          error:function(data){
              console.log(data); //ошибки сервера
          }
      });
    }


    $(document).on('click', '.search-people__button',function(){
        var id = $(this).data('id');
        var data = {
            TITLE: 'Новое сообщение',
            BODY: '<form id="message_user" action="/response/ajax/add_new_messag_user.php"><input class="id__input" type="hidden" name="id" value="'+id+'"><div><textarea class="message-text-input custom-scroll" id="textMessage" name="message-text" placeholder="Введите сообщение" data-emojiable="true" data-emoji-input="unicode"></textarea></div></form>',
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
    });

    $(document).on('click', '.js-add-frends', function(){
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
                        console.log($result['VALUE']);
                    }
                    if ($result['TYPE'] == 'SUCCESS') {
                        uploadIncomingFriends();
                        $(this_btn).parent().find('.js-delete-frends').remove();
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
        let id = $(this).attr('data-id');
        if(id) {
            let this_btn = $(this);
            preload('show');
            $.ajax({
                type: 'POST',
                url: '/response/ajax/add_frends.php',
                data: {'id':id,'action':'delete'},
                success: function (result) {
                    $result = JSON.parse(result);
                    if ($result['TYPE'] == 'ERROR') {
                        preload('hide');
                        console.log($result['VALUE']);
                    }
                    if ($result['TYPE'] == 'SUCCESS') {
                        if($(this_btn).parent().find('.js-add-frends').length > 0){
                            $(this_btn).remove();
                            uploadIncomingFriends();
                        }else{
                            $(this_btn).removeClass('js-delete-frends');
                            $(this_btn).addClass('js-add-frends');
                            $(this_btn).children('img').attr({'src':'/local/templates/anypact/image/people-search-add-people.png','title':'Добавить в друзья','alt':'Добавить в друзья'});
                            if(window.location.pathname == '/friends/') $(this_btn).parents('.view-item').remove();
                        }
                        preload('hide');
                    }
                },
            });
        }
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
                        uploadIncomingFriends();
                        $(this_btn).addClass('js-delete-blacklist');
                        $(this_btn).removeClass('js-add-blacklist');
                        $(this_btn).children('img').attr({'src':'/local/templates/anypact/image/black-list.png','title':'Удалить из черного списка','alt':'Удалить из черного списка'});
                        $(this_btn).parent().find('');
                        updateBlackList();
                        if($(this_btn).parent().find('.js-add-frends').length > 0){
                            if($(this_btn).parent().find('.js-delete-frends').length > 0){
                                $(this_btn).parent().find('.js-delete-frends').remove();
                            }
                            var add_btn = $(this_btn).parent().find('.js-add-frends');
                        }
                        if($(this_btn).parent().find('.js-delete-frends').length > 0){
                            var add_btn = $(this_btn).parent().find('.js-delete-frends');
                        }
                        if(add_btn !== undefined){
                            add_btn.removeClass('js-delete-frends');
                            add_btn.addClass('js-add-frends');
                            add_btn.children('img').attr({'src':'/local/templates/anypact/image/people-search-add-people.png','title':'Добавить в друзья','alt':'Добавить в друзья'});

                            add_btn.hide();
                        }

                        preload('hide');
                    }
                },
            });
        }
    });

    $(document).on('click', '.js-delete-blacklist', function(){
        let id = $(this).attr('data-id'),
            type = $(this).attr('data-type');

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
                        if(type=='list_black'){
                            let btnBlackList = $('#blacklist_'+id);
                            btnBlackList.removeClass('js-delete-blacklist');
                            btnBlackList.addClass('js-add-blacklist');
                            btnBlackList.children('img').attr({'src':'/local/templates/anypact/image/black-list-add.png','title':'Добавить в черный список','alt':'Добавить в черный список'});
                            updateBlackList();
                        }else{
                            $(this_btn).removeClass('js-delete-blacklist');
                            $(this_btn).addClass('js-add-blacklist');
                            $(this_btn).children('img').attr({'src':'/local/templates/anypact/image/black-list-add.png','title':'Добавить в черный список','alt':'Добавить в черный список'});
                            updateBlackList();
                        }
                        if($(this_btn).parent().find('.js-add-frends').length > 0){
                            $(this_btn).parent().find('.js-add-frends').show();
                        }
                        preload('hide');
                    }
                },
            });
        }
    });

    $(document).on('click', '.btn-blacklist', function () {
        if($(this).hasClass('active')){
            $('.blacklist').hide();
            $('.js-friends__list').show();
            $(this).removeClass('active');
            $(this).text('Черный список');
        }else{
            $('.js-friends__list').hide();
            $('.blacklist').show();
            $(this).addClass('active');
            $(this).text('Список друзей');
        }
    })



});