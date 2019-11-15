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
    //////
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

    // попап с подписанием
    $('#popup_send_contract').on('click', function(){
        console.log('окно для подписания')
        $('#send_sms').css('display', 'block');
    });
    // закрытие попапа с подписанием
    $('#close_sign_popup, #signpopup_close').on('click', function(){
        $('#send_sms').css('display', 'none');
    });

    ///////

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