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