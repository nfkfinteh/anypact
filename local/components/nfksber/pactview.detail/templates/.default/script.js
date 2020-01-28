$(document).ready(function(){

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
                    preload('hide');
                    console.log($result['VALUE']);
                }
                if($result['TYPE']=='SUCCESS'){
                    form.find('textarea').val('');
                    form.parents('.modal-content').eq(0).find('button.close').click();
                    preload('hide');
                    showResult('#popup-success', 'Сообщение отправлено');
                }
            },

        });
    });

});