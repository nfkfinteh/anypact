$(document).ready(function(){
    // отозвать подпись
    /*$('#recall_sign').on('click', function(e){
        console.log('Отзыв подписи')
        let id = $(this).attr('data');
        
        e.preventDefault();
        let url = '/response/ajax/deactive_send.php';        
        let data = {
            IDItem: id            
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                console.log(result);
                if(result==1){
                    document.location.replace('/my_pacts/')
                }
            },

        });

        return false;
    });*/

    $('#recall_sign').on('click', function(e){

        $('#dealDeleteWarning').show();
        
        console.log('Отзыв подписи')
        //let id = $(this).attr('data');
        
        e.preventDefault();
        /*let url = '/response/ajax/active_pact.php';        
        let data = {
            IDElement: id,
            Active: 'N'
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                console.log(result);
                if(result==1){
                    document.location.replace('/my_pacts/')
                }
            },

        });*/

        return false;
    });

    $('#delete_deal').on('click', function(e){
        e.preventDefault();

        let id = $(this).attr('data');
        let url = '/response/ajax/deactive_send.php';        
        let data = {
            IDItem: id            
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                console.log(result);
                if(result==1){
                    document.location.replace('/my_pacts/')
                }
            },

        });

        return false;
    });

    // подписать
    
    $('#sign_contract').on('click', function(e){
        console.log('Подписание')
        $('#send_sms').css('display', 'block')

        return false;
    });

    // предупреждение о подписании
    $('#send_contract_owner').on('click', function(e){
        console.log('popup sign')
        $('#send_sms').css('display', 'block')
    });

    // закрыть  окно подписания
    $('#signpopup_close').on('click', function(e){
        console.log('popup sign')
        $('#send_sms').css('display', 'none')
    });

    $('#close_sign_popup, #signpopup_close').on('click', function(){
        $('#send_sms').css('display', 'none');
    });

    $('#reg_button_deal').on('click', function () {
        $('#send_sms').css('display', 'none');
        $('#regpopup_bg_deal').css('display', 'block');
    });

    $('#regpopup_close_deal').on('click', function () {
        $('#regpopup_bg_deal').css('display', 'none');
    });

    $('#signpopup_close_deal').on('click', function () {
        $('#dealDeleteWarning').hide();
    });

    $('#close_sign_popup_deal').on('click', function () {
        $('#dealDeleteWarning').hide();
    });

});



