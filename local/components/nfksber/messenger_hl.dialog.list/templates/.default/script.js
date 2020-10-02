var inProgress = false;
var page = 2;

function setAttr(prmName,val){
    var res = '';
	var d = location.href.split("#")[0].split("?");
	var base = d[0];
	// var query = d[1];
	// if(query) {
	// 	var params = query.split("&");
	// 	for(var i = 0; i < params.length; i++) {
	// 		var keyval = params[i].split("=");
	// 		if(keyval[0] != prmName) {
	// 			res += params[i] + '&';
	// 		}
	// 	}
	// }
	res += prmName + '=' + val;
	window.location.href = base + '?' + res;
	return false;
}

function loadMoreDialogs() {
    // высота окна + высота прокрутки больше или равны высоте всего документа
    if ((($(this).scrollTop() + $(this).height()) >= ($(this)[0].scrollHeight - 200)) && !inProgress) {
        $.ajax({
            url: MDL_component.ajaxUrl + '?nav-dialog=page-' + page,
            method: 'POST',
            dataType: 'html',
            data: {
                via_ajax: 'Y',
                action: 'loadMoreDialogs',
                sessid: BX.bitrix_sessid(),
                SITE_ID: MDL_component.siteID,
                signedParamsString: MDL_component.signedParamsString
            },
            beforeSend: function() {
                inProgress = true;
            }
        }).done(function(result){
            if (result.length > 0){
                inProgress = false;
                if($('#dialog_list .list-person-conversation').length > 0)
                    $('#dialog_list .list-person-conversation').append(result);
                page++;
            }
        });
    }
}

$(document).ready(function(){
    $('#dialog_list .list-person-conversation').height(document.documentElement.clientHeight - $('#dialog_list .title-button-block').outerHeight(true));

    $('form[name="new_dialog"]').submit(function(e){
        e.preventDefault;
        var serializeArray = $(this).serializeArray();
        var arData = {};
        for(var i in serializeArray){
            if(arData[serializeArray[i].name] !== undefined){
                if(typeof (arData[serializeArray[i].name]) != "object")
                    arData[serializeArray[i].name] = new Array(arData[serializeArray[i].name]);
                arData[serializeArray[i].name].push(serializeArray[i].value);
            } 
            else
                arData[serializeArray[i].name] = serializeArray[i].value;
        }
        BX.ajax(
            {
                url: MDL_component.ajaxUrl,
                method: 'POST',
                dataType: 'json',
                data: {
                    via_ajax: 'Y',
                    action: 'addDialog',
                    sessid: BX.bitrix_sessid(),
                    SITE_ID: MDL_component.siteID,
                    signedParamsString: MDL_component.signedParamsString,
                    data: arData
                },
                onsuccess: function(result){
                    if(result.STATUS == "SUCCESS"){
                        $('#dialog_new').parent().html(result.DATA);
                        setAttr('chat', result.DIALOG_ID);
                        $('#dialog_list .list-person-conversation').height(document.documentElement.clientHeight - $('#dialog_list .title-button-block').outerHeight(true));
                    }
                },
                onfailure: function(a, b, c){
                    console.log(a);
                    console.log(b);
                    console.log(c);
                }
            }
        );
        return false;
    });

    $('.edit_user_photo').click(function(e){
        e.preventDefault;
        var $uploadCrop;
        var body = '<div id="upload_avatar_viwe"><p>Вы можете загрузить изображение в формате JPG, GIF или PNG.</p>';
        body += '<center><label class="flat_button" for="upload_avatar">Загрузить изображение</label><input type="file" style="display:none;" id="upload_avatar" accept="image/*"></center>';
        body += '</div>';
        body += '<div id="croppie_img_editor_viwe" style="display:none;"><p>Выбранная область будет показываться в списке диалогов.<br>Если изображение ориентировано неправильно, фотографию можно повернуть.</p>';
        body += '<div id="croppie_img_editor"></div>';
        body += '</div>';
        var data = {
            TITLE: 'Загрузка аватара беседы',
            BODY: body,
            BUTTONS: [
                {
                    NAME: 'Отмена',
                    SECONDARY: 'Y',
                    CLOSE: 'Y'
                },
                {
                    NAME: 'Сохранить',
                    CALLBACK: (function(){
                        $uploadCrop.croppie('result', {
                            type: 'canvas',
                            size: 'viewport'
                        }).then(function (resp) {
                            $('.edit_user_photo').html('<img src="'+resp+'">');
                            $('input[name="DISCUSSION[AVATAR]"]').val(resp);
                            var removeImg = document.createElement("div");
                            $(removeImg).addClass('delete-img');
                            $(removeImg).click((function(){
                                $('.edit_user_photo').html('<img src="/local/templates/anypact/img/user_profile_no_foto.png">');
                                $('input[name="DISCUSSION[AVATAR]"]').val("");
                                $('.delete-img').remove();
                                $('input[name="DISCUSSION[AVATAR]"]').change();
                            }));
                            $('.edit_user_photo').parent().prepend(removeImg);
                            $('input[name="DISCUSSION[AVATAR]"]').change();
                        });
                    }),
                    CLOSE: 'Y'
                }
            ],
            ONLOAD:(function(){
                function readFile(input) {
                    if (input.files && input.files[0]) { 
                        var reader = new FileReader(); 
                        reader.onload = function (e) { 
                            $uploadCrop.croppie("bind", { 
                                url: e.target.result 
                            }).then(function(){ 
                                console.log("jQuery bind complete"); 
                            }); 
                        }; 
                        reader.readAsDataURL(input.files[0]); 
                    } else { 
                        console.log("Sorry - youre browser doesnt support the FileReader API"); 
                    }
                }
                $uploadCrop = $("#croppie_img_editor").croppie({
                    enableExif: true, 
                    viewport: {
                        width: 200, 
                        height: 200, 
                        type: "circle"
                    },
                    boundary: {
                        width: 300, 
                        height: 300
                    }
                });
                $("#upload_avatar").on("change", function () { 
                    readFile(this); 
                    $('#upload_avatar_viwe').hide();
                    $('#croppie_img_editor_viwe').show();
                });
            })
        };
        newAnyPactPopUp(data);
        return false;
    });

    $('#dialog_list .list-person-conversation').on('scroll', loadMoreDialogs);

    if(typeof MDL_component !== 'undefined')
        setInterval(function() {

            var arData = [];

            $('#dialog_list .person-conversation').each(function(){
                var read = "R";
                if($(this).find('.unread-message').length > 0){
                    if($(this).find('.unread-message-count').length > 0){
                        read = $(this).find('.unread-message-count').text();
                    }else{
                        read = "N";
                    }
                }
                arData.push({
                    ID: $(this).data('chat-id'),
                    NO_READ: read,
                    DATE: $(this).find('.person-conversation-date').text(),
                    TEXT: $(this).find('.person-conversation-message').html(),
                });
            });
            BX.ajax(
                {
                    url: MDL_component.ajaxUrl,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        via_ajax: 'Y',
                        action: 'uploadDialogs',
                        sessid: BX.bitrix_sessid(),
                        SITE_ID: MDL_component.siteID,
                        signedParamsString: MDL_component.signedParamsString,
                        data: arData
                    },
                    onsuccess: function(result){
                        if(result.STATUS == "SUCCESS"){
                            if(result.DATA.length > 0){
                                for(let i in result.DATA){
                                    if(result.DATA[i].ID > 0){
                                        if(result.DATA[i].DATE !== undefined)
                                            $('#dialog_list a.person-conversation[data-chat-id="'+result.DATA[i].ID+'"]').find('.person-conversation-date').text(result.DATA[i].DATE);
                                        if(result.DATA[i].NO_READ !== undefined){
                                            if(result.DATA[i].NO_READ == "R"){
                                                $('#dialog_list a.person-conversation[data-chat-id="'+result.DATA[i].ID+'"]').find('.unread-message').remove();
                                            }else if(result.DATA[i].NO_READ == "N"){
                                                if($('#dialog_list a.person-conversation[data-chat-id="'+result.DATA[i].ID+'"]').find('.unread-message').length > 0){
                                                    $('#dialog_list a.person-conversation[data-chat-id="'+result.DATA[i].ID+'"]').find('.unread-message').removeClass('unread-message-count');
                                                    $('#dialog_list a.person-conversation[data-chat-id="'+result.DATA[i].ID+'"]').find('.unread-message').text('');
                                                }else{
                                                    $('#dialog_list a.person-conversation[data-chat-id="'+result.DATA[i].ID+'"]').find('.dialog-message-block').append('<div class="unread-message"></div>');
                                                }
                                            }else{
                                                if($('#dialog_list a.person-conversation[data-chat-id="'+result.DATA[i].ID+'"]').find('.unread-message').length > 0){
                                                    if(!$('#dialog_list a.person-conversation[data-chat-id="'+result.DATA[i].ID+'"]').find('.unread-message').hasClass('unread-message-count'))
                                                        $('#dialog_list a.person-conversation[data-chat-id="'+result.DATA[i].ID+'"]').find('.unread-message').addClass('unread-message-count');
                                                    $('#dialog_list a.person-conversation[data-chat-id="'+result.DATA[i].ID+'"]').find('.unread-message').text(result.DATA[i].NO_READ);
                                                }else{
                                                    $('#dialog_list a.person-conversation[data-chat-id="'+result.DATA[i].ID+'"]').find('.dialog-message-block').append('<div class="unread-message unread-message-count">'+result.DATA[i].NO_READ+'</div>');
                                                }
                                            }
                                        }
                                        if(result.DATA[i].TEXT !== undefined)
                                            $('#dialog_list a.person-conversation[data-chat-id="'+result.DATA[i].ID+'"]').find('.person-conversation-message').html(result.DATA[i].TEXT);
                                    }
                                }
                            }
                        }
                    },
                    onfailure: function(a, b, c){
                        console.log(a);
                        console.log(b);
                        console.log(c);
                    }
                }
            );
        },1000*28);
    
    $('#dialog_list .list-person-conversation .person-conversation.active').prev('.person-conversation').css('border-bottom', 'none');

});