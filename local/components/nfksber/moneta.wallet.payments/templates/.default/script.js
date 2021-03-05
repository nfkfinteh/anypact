function cartAction(cart_id = 0){
    var cart_name = '';
    var cart_number = '';
    var title = 'Добавление карты';
    var del_button = '';
    if(cart_id > 0){
        var cart_name = $('.bank-cards-col[data-id='+cart_id+'] .bank-card_item h5').text();
        var cart_number = $('.bank-cards-col[data-id='+cart_id+'] .bank-card_item p').text();
        var title = 'Обновление карты';
        var del_button = '<button id="delete_cart" class="flat_button mt-4 ml-0">Удалить</button>';
    }
    console.log(cart_name);
    console.log(cart_number);

    console.log(cart_id);
    var newPop = newAnyPactPopUp({
        TITLE: title,
        BODY: '<div><form name="cart" data-id="'+cart_id+'"><input type="text" maxlength="50" name="cart_name" value="'+cart_name+'" placeholder="Название карты"><input type="text" name="cart_number" value="'+cart_number+'" placeholder="Номер карты"></form></div>'+del_button,
        BUTTONS: [
            {
                NAME: 'Отмена',
                SECONDARY: 'Y',
                CLOSE: 'Y'
            },
            {
                NAME: 'Сохранить',
                CALLBACK: (function(){
                    var cart_name = $('form[name="cart"] input[name="cart_name"]').val();
                    var cart_number = $('form[name="cart"] input[name="cart_number"]').val();
                    if(cart_name.length == 0 || cart_name.length > 50){
                        showResult('#popup-error','Ошибка! ', 'Название карты не должно быть пустым и должен быть менее 50 символов');
                    }else if(cart_number.length != 19){
                        showResult('#popup-error','Ошибка! ', 'Не заполнен номер карты');
                    }else{
                        BX.ajax(
                            {
                                url: MWP_component.ajaxUrl,
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    via_ajax: 'Y',
                                    action: 'cartOperation',
                                    sessid: BX.bitrix_sessid(),
                                    SITE_ID: MWP_component.siteID,
                                    signedParamsString: MWP_component.signedParamsString,
                                    cart_id: cart_id,
                                    cart_name: cart_name,
                                    cart_number: cart_number,
                                },
                                onsuccess: function(result){
                                    if(result['STATUS']=='ERROR'){
                                        showResult('#popup-error','Ошибка! ', result['ERROR_DESCRIPTION']);
                                    }
                                    if(result['STATUS']=='SUCCESS'){
                                        var html = $.parseHTML(result['HTML']);
                                        $(html).click(function(){
                                            var cart_id = $(this).data('id');
                                            cartAction(cart_id);
                                        });
                                        if(result['TYPE']=='ADD'){
                                            $('#cart_items').append(html);
                                            if($('#cart_items').children().length > 4)
                                                $('#add_cart').remove();
                                            showResult('#popup-error','Карта добавлена');
                                        }else if(result['TYPE']=='UPDATE'){
                                            $('#cart_items').find('.bank-cards-col[data-id="'+result['CART_ID']+'"]').replaceWith(html);
                                            showResult('#popup-error', 'Карта обновлена');
                                        }
                                        
                                        newPop.parent('.new-pu-overflow').remove();
                                        if($('.new-pu-overflow').length < 1)
                                            $('body').css("overflow", "auto");
                                        
                                    }
                                },
                                onfailure: function(a, b, c){
                                    console.log(a);
                                    console.log(b);
                                    console.log(c);
                                    showResult('#popup-error','Ошибка! Неизвестная ошибка');
                                }
                            }
                        );
                    }
                })
            }
        ],
        ONLOAD: (function(){
            $('form[name="cart"] input[name="cart_number"]').inputmask({ mask:'9999 9999 9999 9999'});
            if($('#delete_cart').length > 0){
                $('#delete_cart').click(function(){
                    newPop.parent('.new-pu-overflow').remove();
                    if($('.new-pu-overflow').length < 1)
                        $('body').css("overflow", "auto");
                    var delCartPop = newAnyPactPopUp({
                        TITLE: 'Удаление карты',
                        BODY: '<div><p>Вы действительно хотите <b>удалить карту</b>?<br><br>Отменить это действие будет <b>невозможно</b>.</p></div>',
                        BUTTONS: [
                            {
                                NAME: 'Отмена',
                                SECONDARY: 'Y',
                                CLOSE: 'Y'
                            },
                            {
                                NAME: 'Удалить',
                                CALLBACK: (function(){
                                    BX.ajax(
                                        {
                                            url: MWP_component.ajaxUrl,
                                            method: 'POST',
                                            dataType: 'json',
                                            data: {
                                                via_ajax: 'Y',
                                                action: 'deleteCart',
                                                sessid: BX.bitrix_sessid(),
                                                SITE_ID: MWP_component.siteID,
                                                signedParamsString: MWP_component.signedParamsString,
                                                cart_id: cart_id
                                            },
                                            onsuccess: function(result){
                                                if(result['STATUS']=='ERROR'){
                                                    showResult('#popup-error','Ошибка! ', result['ERROR_DESCRIPTION']);
                                                }
                                                if(result['STATUS']=='SUCCESS'){
                                                    $('#cart_items').find('.bank-cards-col[data-id="'+cart_id+'"]').remove();
                                                    if($('#cart_items').children().length < 5 && $('#add_cart').length < 1){
                                                        var add_btn = '<div class="bank-cards-col" id="add_cart"><div class="bank-card_item"><img src="/local/templates/anypact/image/add-card.svg" alt="" id="addCard"></div><div class="bank-card_item"><p>Добавить счет или карту</p></div></div>';
                                                        add_btn = $.parseHTML(add_btn);
                                                        $(add_btn).click(function(){cartAction(0);});
                                                        $('.bank-cards').append(add_btn);
                                                    }
                                                    showResult('#popup-error','Карта удалена');
                                                }
                                            },
                                            onfailure: function(a, b, c){
                                                console.log(a);
                                                console.log(b);
                                                console.log(c);
                                                showResult('#popup-error','Ошибка! Неизвестная ошибка');
                                            }
                                        }
                                    );
                                }),
                                CLOSE: 'Y',
                            }
                        ]
                    });
                });
            }
        })
    });
}

$(document).ready(function(){
    $('.bank-cards-col').click(function(){
        var cart_id = $(this).data('id');
        cartAction(cart_id);
    });
})