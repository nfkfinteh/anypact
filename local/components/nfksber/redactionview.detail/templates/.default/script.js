var button_send_contract = document.getElementById('send_contract');

function loadTextBox(el) {
    let variable = $(el);
    let parent_var = variable.parent('edbox');
    let text_box = parent_var.children('useredittext')
    let content_text_box = text_box.text();
    let context_input = variable.val();
    if (context_input == '') {
        context_input = content_text_box;
    }
    text_box.text(context_input);
    variable.remove();
}

$(document).ready(function() {
    // отозвать подпись
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
        let url = '/response/ajax/active_pact.php';        
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

        });

        return false;
    });

    $('useredittext').on('click', function() {
        let select_box = $(this);
        let dom_nodes = $($.parseHTML('<input type="text" class="input_text" />'));
        select_box.after(dom_nodes);
        $(dom_nodes).focus();

    });

    // попап с подписанием
    $('#popup_send_contract').on('click', function(){
        $('#send_sms').css('display', 'block');
    });
    // закрытие попапа с подписанием
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

    // ввод текста во всплывающем окне
    $(document).on('focusout', '.input_text', function() {
        let variable = $(this);
        loadTextBox(variable);
    });
    $("body").keypress(function(e) {
        if (e.which == 13) {
            let variable = $('.input_text');
            loadTextBox(variable);
        }
    });

    //своя редакция (подгрузка доски с интрументами)
    $(document).on('click', '#new_redaction', function(){
        let url = '/response/ajax/new_redaction.php';
        let id = $(this).attr('data-id_element');        
        $('#new_redaction').attr('href', '/'); 
        $('#send_contract').attr('data', 'edit')
        let data = {
            ELEMENT_ID: id
        };

        $(this).hide();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                $('.js-dogovor').html(result);
            },

        });

    });

    $('#signpopup_close_deal').on('click', function () {
        $('#dealDeleteWarning').hide();
    });

    $('#close_sign_popup_deal').on('click', function () {
        $('#dealDeleteWarning').hide();
    });

});