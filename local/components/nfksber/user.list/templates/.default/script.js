$(document).ready(function(){
    $(document).on('click', '.search-peaople__button',function(){
        let login = $(this).data('login');
        $('.login__input').val(login);
    });

    $(document).on('click', '.submit_message', function(){
        let form = $('#message_user');
        let url = form.attr('action');
        let data = form.serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){                
                $result = JSON.parse(result);
                if($result['TYPE']=='ERROR'){
                    form.find('textarea').val('');
                    form.parents('.modal-content').eq(0).find('button.close').click();
                    showResult('#popup-error','Ошибка сохранения', $result['VALUE']);
                }
                if($result['TYPE']=='SUCCESS'){
                    form.find('textarea').val('');
                    form.parents('.modal-content').eq(0).find('button.close').click();
                    showResult('#popup-success', 'Изменения сохранены');
                }
            },

        });
    });

});