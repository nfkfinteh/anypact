function on_contenteditable(element) {
    var element_atr = element.attr('contenteditable');
    console.log(element_atr);
    if (element_atr == 'true') {
        element.attr('contenteditable', false);
        return false;
    } else {
        element.attr('contenteditable', true);
        return true;
    }
}

////////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function() {

    //setHeaderFullName();

    // попап с подписанием
    $('#popup_send_contract').on('click', function(){
        console.log('окно для подписания')
        $('#send_sms').css('display', 'block');
    });
    // закрытие попапа с подписанием
    $('#close_sign_popup, #signpopup_close').on('click', function(){
        $('#send_sms').css('display', 'none');
    });

    // подписание договора с измененным текстом
    $('#sign_edit_contract').on('click', function(){
        let textContract = $('#canvas').html();
        let idContract = $(this).attr('data');
        let url = '/response/ajax/send_edit_contract_esia.php';

        console.log()
        let data = {
            IDContract: idContract,
            TextContract: textContract,
            status:0
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                console.log(result);
                document.location.replace('/profile/aut_esia.php?ID_SENDITEM='+result)
            },

        });
    });

    // разрешение редактирование текста
    $('#btn-edit').on('click', function() {
        var canvas = $('#canvas');
        var onof = on_contenteditable(canvas);
        var span_icon = $(this).find('span');

        if (onof) {
            $(this).css("backgroundColor", "#ff6416");
            $(span_icon).css("color", "#fff");
        } else {
            $(this).css("backgroundColor", "#fff");
            $(span_icon).css("color", "#ff6416");
        }

    });

});