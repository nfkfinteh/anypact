var inProgress = false;
var page = 2;
var dateTime = BX.date.format("d.m.Y H:i:s");

var arFiles = new Array();

var arImgExpansion = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
var arExpansion = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'docx', 'doc', 'docm', 'dotx', 'dot', 'dotm', 'xlsx', 'xls', 'xlsm', 'xltx', 'xlt', 'xltm', 'xlsb', 'xlam', 'xla', 'pptx', 'ppt', 'pptm', 'ppsx', 'pps', 'ppsm', 'potx', 'pot', 'potm', 'ppam', 'ppa', 'csv', 'pdf'];

function clearInput(){
    $('#textMessage').val('');
    $('.emoji-wysiwyg-editor').html('');
    $('.preview-img-block').html("");
}

function loadMoreMessages() {

    // высота окна + высота прокрутки больше или равны высоте всего документа
    if (($(this).scrollTop() <= 300) && !inProgress) {
        $.ajax({
            url: MML_component.ajaxUrl + '?nav-message=page-' + page,
            method: 'POST',
            dataType: 'html',
            data: {
                via_ajax: 'Y',
                action: 'loadMoreMessages',
                sessid: BX.bitrix_sessid(),
                SITE_ID: MML_component.siteID,
                signedParamsString: MML_component.signedParamsString
            },
            beforeSend: function() {
                inProgress = true;
            }
        }).done(function(result){
            if (result.length > 0){
                inProgress = false;
                if($('#simple_scroll_bar .message-list').length > 0)
                    $('#simple_scroll_bar .message-list').prepend(result);
                page++;
            }
        });
    }
}

function viewDetailIMG(){
    var src = $(this).data('original-image-src');
    var data = {
        TITLE: 'Просмотр изображения',
        BODY: '<a class="detail-img-view" target="__blank" href="'+src+'"><img style="max-height: 600px; max-width: 100%;" src="'+src+'"></a>',
        BUTTONS: [
            {
                NAME: 'Закрыть',
                CLOSE: 'Y'
            },
        ]
    };
    newAnyPactPopUp(data);
}

function uploadMessages(){
    BX.ajax(
        {
            url: MML_component.ajaxUrl,
            method: 'POST',
            dataType: 'html',
            data: {
                via_ajax: 'Y',
                action: 'uploadMessages',
                sessid: BX.bitrix_sessid(),
                SITE_ID: MML_component.siteID,
                signedParamsString: MML_component.signedParamsString,
                date: dateTime
            },
            onsuccess: function(result){
                $result = JSON.parse(result);
                if($result['STATUS']=='SUCCESS'){
                    dateTime = $result['DATE'];
                    if($result['MESSAGES'].length > 0 && $result['MESSAGE_IDS'].length > 0){
                        for(var id in $result['MESSAGE_IDS']){
                            $('#simple_scroll_bar .message-list .message-block[data-id="'+$result['MESSAGE_IDS'][id]+'"]').remove();
                        }
                        $('.message-list-block #simple_scroll_bar .message-list').append($result['MESSAGES']);
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
}

function readMessage(){
    BX.ajax(
        {
            url: MML_component.ajaxUrl,
            method: 'POST',
            dataType: 'html',
            data: {
                via_ajax: 'Y',
                action: 'readMessage',
                sessid: BX.bitrix_sessid(),
                SITE_ID: MML_component.siteID,
                signedParamsString: MML_component.signedParamsString,
            },
            onsuccess: function(result){
                $result = JSON.parse(result);
                if($result['STATUS']=='SUCCESS'){
                    $('.message-list .message-block-left .not-read').removeClass('not-read');
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

$(document).ready(function(){

    if(typeof MML_component !== 'undefined')
        readMessage();

    if($('.message-list').length > 0)
        $('.message-list-block .message-list').height(document.documentElement.clientHeight - $('.message-list-block .title-button-block').outerHeight(true) - $('#new_message_form .message-chat-input').outerHeight(true));
    if($('.message-list').length > 0)
        $('.message-list')[0].scrollTop = $('.message-list')[0].scrollHeight;

    $('#delete_all_message').click(function(eventObject){
        var data = {
            TITLE: 'Удалить все сообщения',
            BODY: '<p>Вы действительно хотите <b>удалить всю переписку</b> в этой беседе?<br><br>Отменить это действие будет <b>невозможно</b>.</p>',
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
                                url: MML_component.ajaxUrl,
                                method: 'POST',
                                dataType: 'html',
                                data: {
                                    via_ajax: 'Y',
                                    action: 'deleteAllMessages',
                                    sessid: BX.bitrix_sessid(),
                                    SITE_ID: MML_component.siteID,
                                    signedParamsString: MML_component.signedParamsString,
                                },
                                onsuccess: function(result){
                                    window.location.replace('/list_message/');
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
                }
            ]
        };
        newAnyPactPopUp(data);
    });

    $(document).on('keydown', '.message-input .emoji-wysiwyg-editor', 'return', function(){
        if($(this).text().trim().length > 0){
            $(this).change();
            $(this).parents('form').submit();
        }
        return false
    });

    //вызов окна добавление файлов
    $(document).on('click', '#addFile', function(){
        $('#uploadFile').click();
    });

    $('#uploadFile').on('change', function(){
        prerenderIMG(this);
    });

    function prerenderIMG(input) {
        
        var files = input.files;
        var del = false;

        if (files && files[0]) {
            for (let file of files) {
                if(arFiles.length < 10){
                    var arr = file.name.split('.');
                    var expansion = arr[arr.length - 1];
                    if(expansion.length < 1)
                        continue;

                    if(arExpansion.indexOf(expansion) != -1){
                        arFiles.push(file.name);

                        var previewImg = document.createElement("div");
                        $(previewImg).addClass('preview-img');
                        if(arImgExpansion.indexOf(expansion) != -1){
                            var img = new Image;
                            img.src = URL.createObjectURL(file);
                            img.style = "max-height: 600px; max-width: 100%;";
                            var previewOverflow = document.createElement("div");
                            $(previewOverflow).addClass('preview-overflow');
                            var viewImgBtn = document.createElement("div");
                            $(viewImgBtn).addClass('view-img-btn');
                            $(viewImgBtn).click(function(){
                                var data = {
                                    TITLE: 'Просмотр изображения',
                                    BODY: '<div class="detail-img-view">'+img.outerHTML+'</div>',
                                    BUTTONS: [
                                        {
                                            NAME: 'Закрыть',
                                            CLOSE: 'Y'
                                        },
                                    ]
                                };
                                newAnyPactPopUp(data);
                            });
                            $(previewImg).append(previewOverflow);
                            $(previewImg).append(viewImgBtn);
                        }else{
                            var img = document.createElement("img");
                            img.src = '/local/templates/anypact/image/icon-file.png';
                            var fileName = document.createElement("div");
                            $(fileName).addClass('preview-file-name');
                            $(fileName).text(file.name);
                            $(previewImg).append(fileName);
                        }
                        img.title = file.name;
                        
                        var deleteUploadImgBtn = document.createElement("div");
                        $(deleteUploadImgBtn).addClass('delete-upload-img-btn');
                        $(deleteUploadImgBtn).attr('data-file-name', file.name);
                        $(deleteUploadImgBtn).click(function(){
                            var name = $(this).attr('data-file-name');
                            for (var i in arFiles) {
                                if(arFiles[i] == name){
                                    arFiles.splice(i, 1);
                                    $(this).parent().remove();
                                    break;
                                }
                            }
                        });
                        
                        $(previewImg).append(deleteUploadImgBtn);
                        $(previewImg).append(img);

                        $('.preview-img-block').append(previewImg);
                    }
                }
            }
        }
    }

    $('#dialog_setting').click(function(){
        $(this).children('.dialog-setting-menu').toggle();
        $(this).find('svg').toggleClass('fillFF6600');
    });

    $(document).click(function(event) {
        if ($(event.target).closest("#dialog_setting").length) return;
        $('#dialog_setting').children('.dialog-setting-menu').hide();
        $('#dialog_setting').find('svg').removeClass('fillFF6600');
        event.stopPropagation();
    });
   
    $('#new_message_form').submit(function(e){
        e.preventDefault;
        var serializeArray = $(this).serializeArray();
        var arData = {};
        for(var i in serializeArray){
            if(arData[serializeArray[i].name] !== undefined){
                if(typeof (arData[serializeArray[i].name]) != "object"){
                    arData[serializeArray[i].name] = new Array(arData[serializeArray[i].name]);
                }
                arData[serializeArray[i].name].push(serializeArray[i].value);
            } 
            else
                arData[serializeArray[i].name] = serializeArray[i].value;
        }
        if(arFiles.length > 0){
            arData.NEED_FILES = arFiles;
            arData.ADD_FILE = "Y";
        }
        BX.ajax(
            {
                url: MML_component.ajaxUrl,
                method: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,	
                data: {
                    via_ajax: 'Y',
                    action: 'addMessage',
                    sessid: BX.bitrix_sessid(),
                    SITE_ID: MML_component.siteID,
                    signedParamsString: MML_component.signedParamsString,
                    data: arData
                },
                onsuccess: function(data){
                    let result = JSON.parse(data);
                    if(result.STATUS == "SUCCESS"){
                        uploadMessages();
                        if(arData.ADD_FILE != "Y"){
                            var html = $.parseHTML( result.DATA );
                            $(html).find('.message-text__file img.message-text__img').click(viewDetailIMG);
                            $('.message-list').append(html);
                            $('#dialog_list a[data-chat-id="'+result.DIALOG_ID+'"]').find('.person-conversation-message').html('Вы: <span>'+result.MESSAGE_TEXT+'</span>');
                            $('#dialog_list a[data-chat-id="'+result.DIALOG_ID+'"]').find('.person-conversation-date').text(result.DATE_CREATE);
                            $('.message-list')[0].scrollTop = $('.message-list')[0].scrollHeight;
                            clearInput();
                        }else if(result.REQUEST_ID.length > 0){
                            var formData = new FormData();
                            formData.append('REQUEST_ID', result.REQUEST_ID);
                            formData.append('via_ajax', "Y");
                            formData.append('sessid', BX.bitrix_sessid());
                            formData.append('action', "addFile");
                            formData.append('SITE_ID', MML_component.siteID);
                            formData.append('signedParamsString', MML_component.signedParamsString);
                            jQuery.each($('#uploadFile')[0].files, function(i, file) {
                                formData.append(i, file);
                            });
                            $.ajax(
                                {
                                    url: MML_component.ajaxUrl,
                                    method: 'POST',
                                    dataType: 'json',
                                    cache: false,
                                    contentType: false,
                                    processData: false,	
                                    data: formData,
                                    success: function(result){
                                        if(result.STATUS == "SUCCESS"){
                                            var html = $.parseHTML( result.DATA );
                                            $(html).find('.message-text__file img.message-text__img').click(viewDetailIMG);
                                            $('.message-list').append(html);
                                            $('#dialog_list a[data-chat-id="'+result.DIALOG_ID+'"]').find('.person-conversation-message').html('Вы: <span>'+result.MESSAGE_TEXT+'</span>');
                                            $('#dialog_list a[data-chat-id="'+result.DIALOG_ID+'"]').find('.person-conversation-date').text(result.DATE_CREATE);
                                            $('.message-list')[0].scrollTop = $('.message-list')[0].scrollHeight;
                                            clearInput();
                                        }
                                    },
                                    error: function(a, b, c){
                                        console.log(a);
                                        console.log(b);
                                        console.log(c);
                                    }
                                }
                            );
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

        return false;
    });

    $('#simple_scroll_bar .message-list').on('scroll', loadMoreMessages);

    $('#textMessage').on('focus', readMessage);

    $('#discussion_users span').click(function(){
        $('.discussion-user').toggle();
    });
    $('body').on('click', function(event){
        if ($(event.target).closest("#discussion_users span").length) return;
        if ($(event.target).closest(".discussion-user").length) return;
        $('.discussion-user').hide();
    });

    $('.message-text__file img.message-text__img').click(viewDetailIMG);

    if(typeof MML_component !== 'undefined')
        setInterval(uploadMessages, 1000*23);

});