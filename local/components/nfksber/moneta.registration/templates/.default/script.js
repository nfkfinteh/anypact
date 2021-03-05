function errorBorder(dom){
    $(dom).css('border', '2px solid red');
    setTimeout(function(){
        $(dom).css('border', 'none');
    }, 5000);
}

$(document).ready(function(){

    $(".reg-wallet-overflow_close").click(function () {
        $(".reg-wallet-overflow").fadeOut()
        $('body').css({'overflow': 'auto', 'padding-right': '0'});
        $('.reg-wallet-overflow').css('overflow-y', 'hidden');
    });
    $(document).mouseup(function (e) {
        var container = $(".reg-wallet-overflow");
        if (container.has(e.target).length === 0){
            container.fadeOut();
            $('body').css({'overflow': 'auto', 'padding-right': '0'});
            $('.reg-wallet-overflow').css('overflow-y', 'hidden');
        }
    });

    $(document).on('submit', 'form[name="moneta_reg_form"]', function(e){

        e.preventDefault;

        var form = $(this);

        if($(form).find('input[name="D_S"]').length == 0 && $(form).find('input[name="SNILS"]').val().replace(/(-)|( )/g,"").length != 11){
            errorBorder($(form).find('input[name="SNILS"]'));
            showResult('#popup-error','Ошибка! Поле СНИЛС не заполнено');
            return false;
        }else{
            $(form).find('input[name="SNILS"]').css('border', 'none');
        }

        if($(form).find('input[name="D_I"]').length == 0 && $(form).find('input[name="INN"]').val().replace(/(-)|( )/g,"").length != 12){
            errorBorder($(form).find('input[name="INN"]'));
            showResult('#popup-error','Ошибка! Поле ИНН не заполнено');
            return false;
        }else{
            $(form).find('input[name="INN"]').css('border', 'none');
        }

        if($(form).find('input[name="DEPARTMENT"]').val().replace(/(-)|( )/g,"").length != 6){
            errorBorder($(form).find('input[name="DEPARTMENT"]'));
            showResult('#popup-error','Ошибка! Поле Код подразделения не заполнено');
            return false;
        }else{
            $(form).find('input[name="DEPARTMENT"]').css('border', 'none');
        }

        if($(form).find('input[name="PAYMENT_PASS"]').val().length < 5){
            errorBorder($(form).find('input[name="PAYMENT_PASS"]'));
            showResult('#popup-error','Ошибка! Поле Платежный пароль должен состоять только из цифр, минимум из пяти');
            return false;
        }else{
            $(form).find('input[name="PAYMENT_PASS"]').css('border', 'none');
        }

        if($(form).find('input[name="PAYMENT_PASS"]').val() !== $(form).find('input[name="PAYMENT_PASS_REPEAT"]').val()){
            errorBorder($(form).find('input[name="PAYMENT_PASS_REPEAT"]'));
            showResult('#popup-error','Ошибка! Платежные пароли не совпадают');
            return false;
        }else{
            $(form).find('input[name="PAYMENT_PASS_REPEAT"]').css('border', 'none');
        }

        preload('show');
        $.ajax({
            type: $(form).attr('method'),
            url: MR_component.ajaxUrl,
            data: {
                via_ajax: 'Y',
                action: 'registerMoneta',
                sessid: BX.bitrix_sessid(),
                SITE_ID: MR_component.siteID,
                signedParamsString: MR_component.signedParamsString,
                data: $(form).serializeArray()
            },
            success: function(result){                
                $result = JSON.parse(result);
                if($result['STATUS']=='ERROR'){
                    preload('hide');
                    showResult('#popup-error','Ошибка! ', $result['ERROR_DESCRIPTION']);
                    if($result['ERROR_TYPE'] == "not_full_snils")
                        errorBorder($(form).find('input[name="SNILS"]'));
                    if($result['ERROR_TYPE'] == "not_full_inn")
                        errorBorder($(form).find('input[name="INN"]'));
                    if($result['ERROR_TYPE'] == "not_full_phone")
                        errorBorder($(form).find('input[name="PHONE"]'));
                    if($result['ERROR_TYPE'] == "not_full_department")
                        errorBorder($(form).find('input[name="DEPARTMENT"]'));
                    if($result['ERROR_TYPE'] == "wrong_pass_data")
                        errorBorder($(form).find('input[name="PAYMENT_PASS"]'));
                    if($result['ERROR_TYPE'] == "wrong_pass_repeat")
                        errorBorder($(form).find('input[name="PAYMENT_PASS_REPEAT"]'));
                }else if($result['STATUS']=='SUCCESS'){
                    preload('hide');
                    $(".reg-wallet-overflow_close").click();
                    var data = {
                        TITLE: 'Подверждение телефона',
                        BODY: '<div id="phone_success_body"><button class="flat_button">Выслать код подверждения</button><div id="code_status"></div></div>',
                        BUTTONS: [
                            {
                                NAME: 'Закрыть',
                                CLOSE: 'Y'
                            },
                        ],
                        ONLOAD: (function(){
                            $('#phone_success_body button').click(function(){
                                var button = $(this);
                                $(button).parent().append('<div class="preloader__image"></div>');
                                $(button).hide();
                                $(button).parent().find('#code_status').hide();
                                $.ajax({
                                    url: '/response/ajax/check_phone.php',
                                    method: 'POST',
                                    dataType: 'json',
                                    data: {
                                        sessid: BX.bitrix_sessid(),
                                        action: 'sendMoneta'
                                    },
                                    success: function(result){
                                        if(result.STATUS.toLowerCase() == 'send'){
                                            var html = $.parseHTML( result.DATA );
                                            startSMSSendTimer($(html).find('#sms_send_timer'), 60);
                                            $(html).find('#send_code').click(function(){
                                                var code = $(this).parent().find('input[name="CODE"]').val();
                                                if(code.length != 6){
                                                    $("#code_status").html("Поле кода пустое");
                                                }else{
                                                    $("#code_status").html("");
                                                    $.ajax({
                                                        url: '/response/ajax/check_phone.php',
                                                        method: 'POST',
                                                        dataType: 'json',
                                                        data: {
                                                            sessid: BX.bitrix_sessid(),
                                                            action: 'checkMoneta',
                                                            code: code
                                                        },
                                                        success: function(result){
                                                            if(result.STATUS.toLowerCase() == 'success'){
                                                                $('#phone_success_body').html("Телефонный номер прошел проверку");
                                                                $('#save_phone_error').hide();
                                                                setTimeout(function(){
                                                                    window.location.replace('/profile/wallet/');
                                                                }, 5000);
                                                            }else if(result.STATUS == 'error'){
                                                                $("#code_status").html(result.ERROR_MESSAGE);
                                                            }
                                                        },
                                                        error: function(a,b,c){
                                                            console.log(c);
                                                        }
                                                    });
                                                }
                                            });
                                            $('#phone_success_body').html(html);
                                        }else if(result.STATUS.toLowerCase() == 'error'){
                                            $(button).parent().find('.preloader__image').remove();
                                            $(button).show();
                                            $(button).parent().find('#code_status').show();
                                            $("#code_status").html(result.ERROR_MESSAGE);
                                        }else if(result.STATUS.toLowerCase() == 'profile_checked'){
                                            $('#phone_success_body').html("Вы уже прошли идентификацию профиля на сервисе Монета");
                                            $('#save_phone_error').hide();
                                            setTimeout(function(){
                                                window.location.replace('/profile/wallet/');
                                            }, 5000);
                                        }
                                    },
                                    error: function(a,b,c){
                                        console.log(a);
                                        console.log(b);
                                        console.log(c);
                                    }
                                });
                            });
                        })
                    };
                    newAnyPactPopUp(data);
                }
            },
            error: function (a,b,c) {
                console.log(a);
                console.log(b);
                console.log(c);
                preload('hide');
                showResult('#popup-error','Ошибка! Неизвестная ошибка, повторите позже');
            }
        });

        return false;
    });

    $('.hidden-value').on('click', function(){
        if($(this).hasClass('hidden-value')){
            $(this).val('');
            $(this).prop('disabled', false);
            $(this).focus();
            $(this).removeClass('hidden-value');
            if($(this).attr('name') == "SNILS"){
                $(this).inputmask({ mask:'999-999-999 99'});
                $(this).parent().find('input[name="D_S"]').remove();
            }
            if($(this).attr('name') == "INN")
                $(this).parent().find('input[name="D_I"]').remove();
        }
    });
    if($('form[name="moneta_reg_form"] input.hidden-value[name="SNILS"]').length < 1)
        $('form[name="moneta_reg_form"] input[name="SNILS"]').inputmask({ mask:'999-999-999 99'});

    $('form[name="moneta_reg_form"] input[name="DEPARTMENT"]').inputmask({ mask:'999-999'});
});