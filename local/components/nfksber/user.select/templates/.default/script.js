function bindSelectUser(){
    var id = $(this).attr('data-id');
    var el_html = $(this).clone();
    var user_photo = $(el_html).children('.user-photo');
    var user_fio = $(el_html).children('.user-fio');
    var aOne = document.createElement('a');
    aOne.href = '/profile_user/?ID='+id;
    aOne.target = '_blank';
    aOne.className = 'd-flex align-items-center profile-link';
    $(aOne).prepend(user_photo);
    $(aOne).append(user_fio);
    $(el_html).prepend(aOne);
    $(el_html).children('.user-photo').remove();
    $(el_html).children('.user-fio').remove();
    $(el_html).append('<div class="user-delete"></div><input name="SELECTED_USER[]" type="hidden" value="'+id+'"/>');
    if($(this).hasClass('selected')){
        $(this).removeClass('selected');
        $('.select-user-list .user-el[data-id="'+id+'"]').remove();
    }else{
        $(this).addClass('selected');
        $('.select-user-list').prepend(el_html);
        $(el_html).children('.user-delete').on('click', function(){
            var id = $(this).parent('.user-el').attr('data-id');
            $(this).parent('.user-el').remove();
            $('.select-user-popup .user-el[data-id="'+id+'"]').removeClass('selected');
        });
    }
}

$(document).ready(function(){
    $(document).on('click', '.search-peaople__button',function(){
        let login = $(this).data('login');
        $('.login__input').val(login);
    });

    $('#us_name').on('click', function(){
        $('.select-user-popup').css('visibility', "visible");
        $('.select-user-popup').css('height', "500px");
        $(this).addClass('focus');
    });

    $(document).click(function(event) {
        if ($(event.target).closest("#us_name").length) return;
        if ($(event.target).closest(".select-user-popup").length) return;
        $('.select-user-popup').css('height', "0px");
        setTimeout(function(){
            $("#us_name").removeClass('focus');
            $('.select-user-popup').css('visibility', "hidden");
        }, 500);
        event.stopPropagation();
    });

    $('.select-user-popup .user-el').on('click', bindSelectUser);

    $('.user-delete').on('click', function(){
        var id = $(this).parent('.user-el').attr('data-id');
        $(this).parent('.user-el').remove();
        $('.select-user-popup .user-el[data-id="'+id+'"]').removeClass('selected');
    });
    $('#us_name').on('change paste keyup', function(eventObject){
        let arKey = [8, 46, 32, 49, 50, 51, 52, 53, 54, 55, 56, 57, 48, 189, 187, 81, 87, 69, 82, 84, 89, 85, 73, 79, 80, 219, 221, 65, 83, 68, 70, 71, 72, 74, 75, 76, 186, 222, 220, 226, 90, 88, 67, 86, 66, 78, 77, 188, 190, 191, 111, 106, 109, 103, 104, 105, 107, 100, 101, 102, 97, 98, 99, 96, 110, 13, 192];
        if (arKey.indexOf( eventObject.which ) != -1) {
            var search = $(this).val();
            BX.ajax(
                {
                    url: US_component.ajaxUrl,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        via_ajax: 'Y',
                        action: 'searchUser',
                        sessid: BX.bitrix_sessid(),
                        SITE_ID: US_component.siteID,
                        signedParamsString: US_component.signedParamsString,
                        filter: search
                    },
                    onsuccess: function(result){
                        $('.select-user-popup').html(result);
                        let arId = new Array();
                        $('.select-user-list .user-el').each(function(){
                            arId.push($(this).attr('data-id'));
                        });
                        $('.select-user-popup .user-el').each(function(){
                            if(arId.indexOf($(this).attr('data-id')) != -1){
                                $(this).addClass('selected');
                            }
                        });
                        $('.select-user-popup .user-el').on('click', bindSelectUser);
                    },
                    onfailure: function(a, b, c){
                        console.log(a);
                        console.log(b);
                        console.log(c);
                    }
                }
            );
        }
    });

});