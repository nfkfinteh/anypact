$(document).ready(function(){
    var copyWalletBtn = document.querySelector('#copyText');  
    copyWalletBtn.addEventListener('click', function(event) {  
        var walletNumber = document.querySelector('#wallet-number');  
        var range = document.createRange();  
        range.selectNode(walletNumber);  
        window.getSelection().addRange(range);  
            
        document.execCommand('copy');   
            
        window.getSelection().removeAllRanges();
        var tooltip = document.getElementById("myTooltip");
        tooltip.innerHTML = "Скопировано";

        window.getSelection().removeAllRanges();
    });
    $('#moneta_deposit_btn').click(function(){
        var newPop = newAnyPactPopUp({
            TITLE: 'Пополнить счет',
            BODY: '<div><input type="text" class="js-number" name="amount" placeholder="Сумма пополнения"></div>',
            BUTTONS: [
                {
                    NAME: 'Отмена',
                    SECONDARY: 'Y',
                    CLOSE: 'Y'
                },
                {
                    NAME: 'Пополнить',
                    CALLBACK: (function(){
                        preload('show');
                        var amount = $('.new-pu-body input[name="amount"]').val();
                        if(amount >= 10){
                            BX.ajax(
                                {
                                    url: MWI_component.ajaxUrl,
                                    method: 'POST',
                                    dataType: 'json',
                                    data: {
                                        via_ajax: 'Y',
                                        action: 'depositSum',
                                        sessid: BX.bitrix_sessid(),
                                        SITE_ID: MWI_component.siteID,
                                        signedParamsString: MWI_component.signedParamsString,
                                        amount: amount,
                                    },
                                    onsuccess: function(result){

                                        preload('hide');
                                        newPop.parent('.new-pu-overflow').remove();
                                        if($('.new-pu-overflow').length < 1)
                                            $('body').css("overflow", "auto");

                                        if(result['STATUS']=='ERROR')
                                            showResult('#popup-error','Ошибка! ', result['ERROR_DESCRIPTION']);

                                        if(result['STATUS']=='SUCCESS')
                                            newAnyPactPopUp({
                                                TITLE: 'Пополнить счет',
                                                BODY: '<div>Для пополнения счета перейдите по <a href="https://www.payanyway.ru/assistant.htm?operationId='+result['DATA']+'&paymentSystem.unitId=card&paymentSystem.limitIds=card&followup=true&return_to_shop=https%3A%2F%2Fnew-anypact.ru%2Fprofile%2Fwallet%2F%3FSuccessfulDebit%3DY%26operationId%3D'+result['DATA']+'">ссылке</a></div>',
                                                BUTTONS: [
                                                    {
                                                        NAME: 'Закрыть',
                                                        SECONDARY: 'Y',
                                                        CLOSE: 'Y'
                                                    }
                                                ]
                                            });
                                    },
                                    onfailure: function(a, b, c){
                                        console.log(a);
                                        console.log(b);
                                        console.log(c);
                                        showResult('#popup-error','Ошибка! Неизвестная ошибка');
                                    }
                                }
                            );
                        }else{
                            showResult('#popup-error','Ошибка! Сумма пополнения должна быть больше 9 рублей');
                        }
                    })
                }
            ]
        });
    });
    if($('.wallet-header p').text() != $('.balance .balance-col span').text()) $('.wallet-header p').text($('.balance .balance-col span').text());

    $('#profile_identify').click(function(e){
        e.preventDefault;
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
        return false;
    });

    if($('#moneta_withdrawal_btn').length > 0){
        $('#moneta_withdrawal_btn').click(function(e){
            e.preventDefault;
            
            BX.ajax({
                url: MWI_component.ajaxUrl,
                method: 'POST',
                dataType: 'json',
                data: {
                    via_ajax: 'Y',
                    action: 'getWithdrawal',
                    sessid: BX.bitrix_sessid(),
                    SITE_ID: MWI_component.siteID,
                    signedParamsString: MWI_component.signedParamsString,
                },
                onsuccess: function(result){
                    if(result['STATUS']=='SUCCESS'){
                        var withdrawalPopup = newAnyPactPopUp({
                            TITLE: 'Вывод средств',
                            BODY: result['HTML'],
                            BUTTONS: [
                                {
                                    NAME: 'Отмена',
                                    SECONDARY: 'Y',
                                    CLOSE: 'Y'
                                },
                                {
                                    NAME: 'Снять',
                                    CALLBACK: (function(){

                                        preload('show');

                                        var amount = $('form[name="WITHDRAWAL"] input[name="amount"]').val();
                                        // var payment_pass = $('form[name="WITHDRAWAL"] input[name="payment_pass"]').val();
                                        var cart_number = $('form[name="WITHDRAWAL"] input[name="cart_number"]').val();
                                        var cart_id = $('form[name="WITHDRAWAL"] select[name="cart_id"]').val();
                                        if(amount < 40){
                                            showResult('#popup-error','Ошибка! Сумма вывода должна быть больше 39 рублей');
                                            return false;
                                        }
                                        // if(payment_pass.length < 5){
                                        //     showResult('#popup-error','Неверный платежный пароль');
                                        //     return false;
                                        // }
                                        BX.ajax({
                                            url: MWI_component.ajaxUrl,
                                            method: 'POST',
                                            dataType: 'json',
                                            data: {
                                                via_ajax: 'Y',
                                                action: 'makeWithdrawal',
                                                sessid: BX.bitrix_sessid(),
                                                SITE_ID: MWI_component.siteID,
                                                signedParamsString: MWI_component.signedParamsString,
                                                amount: amount,
                                                // payment_pass: payment_pass,
                                                cart_number: cart_number,
                                                cart_id: cart_id,
                                            },
                                            onsuccess: function(result){
                                                preload('hide');
                                                withdrawalPopup.parent('.new-pu-overflow').remove();
                                                if($('.new-pu-overflow').length < 1)
                                                    $('body').css("overflow", "auto");
                                                if(result["STATUS"] == "WRONG")
                                                    showResult('#popup-error', 'Ошибка! ',result['ERROR_DESCRIPTION']);
                                                else
                                                    showResult('#popup-error',"Операция на вывод средств создан, деньги поступят на счет до 3 дней");
                                            },
                                            onfailure: function(a, b, c){
                                                console.log(a);
                                                console.log(b);
                                                console.log(c);
                                                showResult('#popup-error','Ошибка! Неизвестная ошибка');
                                            }
                                        });
                                    })
                                }
                            ],
                            ONLOAD: (function(){
                                $('form[name="WITHDRAWAL"] > select[name="cart_id"]').change(function(){
                                    if($(this).val() > 0){
                                        $('form[name="WITHDRAWAL"] > input[name="cart_number"]').hide();
                                        $('form[name="WITHDRAWAL"] > input[name="cart_number"]').attr('disabled',true);
                                    }else{
                                        $('form[name="WITHDRAWAL"] > input[name="cart_number"]').show();
                                        $('form[name="WITHDRAWAL"] > input[name="cart_number"]').attr('disabled',false);
                                    }
                                });
                            })
                        });
                    }
                },
                onfailure: function(a, b, c){
                    console.log(a);
                    console.log(b);
                    console.log(c);
                    showResult('#popup-error','Ошибка! Неизвестная ошибка');
                }
            });
            
            return false;
        })
    }
    if($('#moneta_transfer_btn').length > 0){
        $('#moneta_transfer_btn').click(function(e){
            e.preventDefault;

            var transferPopup = newAnyPactPopUp({
                TITLE: 'Перевод',
                BODY: '<div><form name="TRANSFER"><input type="text" class="js-number" name="acc_id" placeholder="Номер кошелька"><input type="text" class="js-number" name="amount" placeholder="Сумма перевода"></form></div>',
                BUTTONS: [
                    {
                        NAME: 'Отмена',
                        SECONDARY: 'Y',
                        CLOSE: 'Y'
                    },
                    {
                        NAME: 'Перевести',
                        CALLBACK: (function(){

                            preload('show');

                            var amount = $('form[name="TRANSFER"] input[name="amount"]').val();
                            // var payment_pass = $('form[name="TRANSFER"] input[name="payment_pass"]').val();
                            var acc_id = $('form[name="TRANSFER"] input[name="acc_id"]').val();
                            if(amount < 10){
                                showResult('#popup-error','Ошибка! Сумма перевода должна быть больше 9 рублей');
                                return false;
                            }
                            // if(payment_pass.length < 5){
                            //     showResult('#popup-error','Неверный платежный пароль');
                            //     return false;
                            // }
                            if(acc_id.length < 2){
                                showResult('#popup-error','Неверный номер счета');
                                return false;
                            }
                            BX.ajax({
                                url: MWI_component.ajaxUrl,
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    via_ajax: 'Y',
                                    action: 'makeTransfer',
                                    sessid: BX.bitrix_sessid(),
                                    SITE_ID: MWI_component.siteID,
                                    signedParamsString: MWI_component.signedParamsString,
                                    amount: amount,
                                    // payment_pass: payment_pass,
                                    acc_id: acc_id,
                                },
                                onsuccess: function(result){
                                    preload('hide');
                                    transferPopup.parent('.new-pu-overflow').remove();
                                    if($('.new-pu-overflow').length < 1)
                                        $('body').css("overflow", "auto");
                                    if(result["STATUS"] == "WRONG")
                                        showResult('#popup-error', 'Ошибка! ',result['ERROR_DESCRIPTION']);
                                    else
                                        showResult('#popup-error',"Деньги были переведены");
                                },
                                onfailure: function(a, b, c){
                                    console.log(a);
                                    console.log(b);
                                    console.log(c);
                                    showResult('#popup-error','Ошибка! Неизвестная ошибка');
                                }
                            });
                        })
                    }
                ]
            });

            return false;
        });
    }

});

function showPaymentPopup(text){
    newAnyPactPopUp({
        TITLE: 'Статус пополнения',
        BODY: '<div>'+text+'</div>',
        BUTTONS: [
            {
                NAME: 'Закрыть',
                CLOSE: 'Y'
            },
        ]
    });
}