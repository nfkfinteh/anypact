function leaveDiscussion(){
    var arData = {
        TITLE: 'Выйти из беседы',
        BODY: '<p>Покинув беседу, Вы не будете получать новых сообщений от участников.<br>Вы сможете вернуться при наличии свободных мест</p>',
        BUTTONS: [
            {
                NAME: 'Отмена',
                SECONDARY: 'Y',
                CLOSE: 'Y'
            },
            {
                NAME: 'Выйти из беседы',
                CALLBACK: (function(){
                    BX.ajax(
                        {
                            url: MDD_component.ajaxUrl,
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                via_ajax: 'Y',
                                action: 'leaveDialog',
                                sessid: BX.bitrix_sessid(),
                                SITE_ID: MDD_component.siteID,
                                signedParamsString: MDD_component.signedParamsString
                            },
                            onsuccess: function(result){
                                if(result.STATUS == "SUCCESS"){
                                    if($('#edit_discussion').length > 0)
                                        $('#edit_discussion').remove();
                                    if($('#leave_discussion').length > 0)
                                        $('#leave_discussion').remove();
                                    var joinDialogDiv = document.createElement("div");
                                    $(joinDialogDiv).addClass('menu-item');
                                    $(joinDialogDiv).text('Вернуться в беседу');
                                    $(joinDialogDiv).attr('id', 'join_discussion');
                                    $(joinDialogDiv).click(joinDiscussion);
                                    $('.dialog-setting-menu').prepend(joinDialogDiv);
                                    window.location.reload();
                                }
                            },
                            onfailure: function(a, b, c){
                                console.log(a);
                                console.log(b);
                                console.log(c);
                            }
                        }
                    );
                }),
                CLOSE: 'Y'
            },
        ]
    };
    newAnyPactPopUp(arData);
}

function joinDiscussion(){
    BX.ajax(
        {
            url: MDD_component.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            data: {
                via_ajax: 'Y',
                action: 'joinDialog',
                sessid: BX.bitrix_sessid(),
                SITE_ID: MDD_component.siteID,
                signedParamsString: MDD_component.signedParamsString
            },
            onsuccess: function(result){
                if(result.STATUS == "SUCCESS"){
                    if($('#join_discussion').length > 0)
                        $('#join_discussion').remove();
                    var leaveDialogDiv = document.createElement("div");
                    $(leaveDialogDiv).addClass('menu-item');
                    $(leaveDialogDiv).text('Покинуть беседу');
                    $(leaveDialogDiv).attr('id', 'leave_discussion');
                    $(leaveDialogDiv).click(leaveDiscussion);
                    $('.dialog-setting-menu').prepend(leaveDialogDiv);
                    window.location.reload();
                }
            },
            onfailure: function(a, b, c){
                console.log(a);
                console.log(b);
                console.log(c);
            }
        }
    );
}

function changeAvatar(){
    var avatar = $('input[name="DISCUSSION[AVATAR]"]').val();
    BX.ajax(
        {
            url: MDD_component.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            data: {
                via_ajax: 'Y',
                action: 'changeAvatar',
                sessid: BX.bitrix_sessid(),
                SITE_ID: MDD_component.siteID,
                signedParamsString: MDD_component.signedParamsString,
                avatar: avatar
            },
            onsuccess: function(result){
				if(result.STATUS == 'SUCCESS')
					$('#dialog_list a.person-conversation.active .person-conversation-photo img').attr('src', avatar);
            },
            onfailure: function(a, b, c){
                console.log(a);
                console.log(b);
                console.log(c);
            }
        }
    );
}

$(document).ready(function(){
    var body = $('#dialog_edit');
    var data = {
        BODY: body,
        BUTTONS: [
            {
                NAME: 'Закрыть',
                SECONDARY: 'Y',
                CLOSE: 'Y'
            }
        ]
    };
    $('input[name="DISCUSSION[NAME]"]').on("change keypress", function(){
        if($('.name-allow-change').length < 1){
            var el = $(this);
            var allowChange = document.createElement("div");
            $(allowChange).addClass("name-allow-change");
            $(allowChange).text("✔");
            $(allowChange).click((function(){
                BX.ajax(
                    {
                        url: MDD_component.ajaxUrl,
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            via_ajax: 'Y',
                            action: 'changeName',
                            sessid: BX.bitrix_sessid(),
                            SITE_ID: MDD_component.siteID,
                            signedParamsString: MDD_component.signedParamsString,
                            name: el.val()
                        },
                        onsuccess: function(result){
                            if(result.STATUS == "SUCCESS"){
                                $(allowChange).remove();
                                $('#dialog_list a.person-conversation.active .person-conversation-name strong').text(el.val());
                            }
                        },
                        onfailure: function(a, b, c){
                            console.log(a);
                            console.log(b);
                            console.log(c);
                        }
                    }
                );
            }));
            $(this).parent().append(allowChange);
        }
    })
    $('input[name="DISCUSSION[AVATAR]"]').on("change", changeAvatar);
    $('#edit_discussion').click(function(){
        data.TITLE = 'Изменение беседы';
        newAnyPactPopUp(data);
    });
    $('#leave_discussion').click(leaveDiscussion);
    $('#join_discussion').click(joinDiscussion);
	
	$('.user_profile_form_editdata_foto .delete-img').click((function(){
		$('.edit_user_photo').html('<img src="/local/templates/anypact/img/user_profile_no_foto.png">');
		$('input[name="DISCUSSION[AVATAR]"]').val("");
		$('.delete-img').remove();
		$('input[name="DISCUSSION[AVATAR]"]').change();
	}));
});