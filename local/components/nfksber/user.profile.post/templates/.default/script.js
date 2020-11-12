var inProgress = false;
var page = 2;

var arImgExpansion = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
var arDocExpansion = ['docx', 'doc', 'docm', 'dotx', 'dot', 'dotm', 'xlsx', 'xls', 'xlsm', 'xltx', 'xlt', 'xltm', 'xlsb', 'xlam', 'xla', 'pptx', 'ppt', 'pptm', 'ppsx', 'pps', 'ppsm', 'potx', 'pot', 'potm', 'ppam', 'ppa', 'csv', 'pdf'];


function ajaxRequest(action, callback, arData = Array(), post = "", additionalURL = "") {
    $.ajax({
        url: UPP_component.ajaxUrl + additionalURL,
        method: 'POST',
        dataType: 'html',
        data: {
            via_ajax: 'Y',
            action: action,
            sessid: BX.bitrix_sessid(),
            SITE_ID: UPP_component.siteID,
            signedParamsString: UPP_component.signedParamsString,
            DATA: arData,
            post_id: post
        },
        beforeSend: function() {
            inProgress = true;
        }
    }).done(function(result){
        inProgress = false;
        if (result.length > 0){
            callback(result);
        }
    });
}

function bindDevelopmentPost(htmlDom){
    $(htmlDom).find('form').submit(function(e){
        e.preventDefault;
        var el = this;
        preload('show');
        var post_id = $(el).parents('.new-profile-posts_container').eq(0).data('id');
        var serializeArray = $(el).serializeArray();
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
        ajaxRequest("newComment", (function(result){
            if(result != 'false'){
                $result = $.parseHTML(result);
                bindDevelopmentLike($result);
                if($(el).parents('.new-profile-send-post .reply-post-send').prev('.more-info.more-info-link').length > 0){
                    if($(el).parents('.new-profile-posts_container').eq(0).find('#post_comments_'+post_id).find('.more-info.more-info-link').length < 1){
                        $(el).parents('.new-profile-posts_container').eq(0).find('#post_comments_'+post_id).append($(el).parents('.new-profile-send-post .reply-post-send').prev('.more-info.more-info-link').clone(true));
                    }
                }

                $('#post_comments_'+post_id).append($result);
                var postCommBtn = $(el).parents('.new-profile-posts_container').find('.post-tools_item .tool-mess').eq(0);
                $(postCommBtn).next().text(parseInt($(postCommBtn).next().text()) + 1);

                if($(el).parents('.new-profile-posts_container').eq(0).find('form.form_input_file .comment-answer-to .del-answer-contener').length > 0)
                    $(el).parents('.new-profile-posts_container').eq(0).find('form.form_input_file .comment-answer-to').eq(0).html('');
                
                clearForm(el);
            }
            preload('hide');
        }), arData, post_id);
        return false;
    });
    $(htmlDom).find('input[name="img"]').change(function(){
        uploadFile(this);
    });
    $(htmlDom).find('input[name="doc"]').change(function(){
        uploadFile(this);
    });
    $(htmlDom).find('.more-info-link').click(function(e){
        e.preventDefault;
        var moreBTN = this;
        var post_id = $(moreBTN).parents('.new-profile-posts_container').eq(0).data('id');
        ajaxRequest('loadAllComments', (function(result){
            if(result != 'false'){
                $result = $.parseHTML(result);
                bindDevelopmentLike($result);
                $(moreBTN).parents('.new-profile-posts_container').eq(0).find('.more-info-link').remove();
                $(htmlDom).find('#post_comments_'+post_id).html($result);
            }
        }), [], post_id, '?nav-comment-'+post_id+'=page-all');
        return false;
    });
    $(htmlDom).find('.post-tools_item .tool-reply-mess').click(function(){
        $(this).toggleClass('active');
        $(this).next().toggleClass('hide');
    });
    $(htmlDom).find('.delete-post').click(function(){
        var post_id = $(this).parents('.new-profile-posts_container').eq(0).data('id');
        var data = {
            TITLE: 'Удалить публикацию',
            BODY: '<p>Вы действительно хотите <b>удалить публикацию</b> в этой стене?<br><br>Отменить это действие будет <b>невозможно</b>.</p>',
            BUTTONS: [
                {
                    NAME: 'Отмена',
                    SECONDARY: 'Y',
                    CLOSE: 'Y'
                },
                {
                    NAME: 'Удалить',
                    CALLBACK: (function(){
                        preload('show');
                        BX.ajax(
                            {
                                url: UPP_component.ajaxUrl,
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    via_ajax: 'Y',
                                    action: 'deletePost',
                                    sessid: BX.bitrix_sessid(),
                                    SITE_ID: UPP_component.siteID,
                                    signedParamsString: UPP_component.signedParamsString,
                                    post_id: post_id
                                },
                                onsuccess: function(result){
                                    if(result.STATUS == "SUCCESS"){
                                        $('.new-profile-wall .new-profile-posts_container[data-id="'+post_id+'"]').remove();
                                        if($('.new-profile-wall .new-profile_block.new-profile_block_posts').find('.new-profile-posts_container').length < 1){
                                            $('.new-profile-wall .new-profile_block.new-profile_block_posts h2').remove();
                                            $('.new-profile-wall .new-profile_block.new-profile_block_posts #post_wall').remove();
                                        }
                                        showResult('#popup-success', 'Публикация удалена');
                                    }else{
                                        showResult('#popup-error','Ошибка удаления', "Не удалось удалить данную публикацию");
                                    }
                                    preload('hide');
                                },
                                onfailure: function(a, b, c){
                                    console.log(a);
                                    console.log(b);
                                    console.log(c);
                                    showResult('#popup-error','Ошибка удаления', "Не удалось удалить данную публикацию");
                                    preload('hide');
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
}

function bindDevelopmentLike(htmlDom){
    $(htmlDom).find('.tool-like').click(function(){
        var el = this;
        var arData = {};

        arData.entity_id = $(el).parents('[js-like-selector]').eq(0).data('id');
        arData.entity_type = $(el).parents('[js-like-selector]').eq(0).data('type');
        
        ajaxRequest("like", (function(result){
            $result = $.parseJSON(result);
            if($result.STATUS !== undefined && $result.STATUS == 'SUCCESS'){
                if($result.RESULT == 'like'){
                    $(el).addClass('active');
                    var like = parseInt($(el).next('span').text()) + 1;
                    
                }else if($result.RESULT == 'unlike'){
                    $(el).removeClass('active');
                    var like = parseInt($(el).next('span').text()) - 1;
                }
                if(like !== undefined)
                    $(el).next('span').text(like);
            }
        }), arData);
    });
    $(htmlDom).find('.comment-answer').click(function(e){
        e.preventDefault;
        var btn = this;
        $(btn).parents('.new-profile-posts_container').eq(0).find('form.form_input_file .comment-answer-to').html('');
        var link = $(btn).parents('[js-like-selector]').eq(0).find('.wall-photo a').eq(0).attr('href');
        var id = link.slice(18);
        var fio = $(btn).parents('[js-like-selector]').eq(0).find('.reply-wall-post-block h5').eq(0).text();
        var html = $.parseHTML('<div class="del-answer-contener"><div class="del-answer"></div></div>В ответ пользователю <a href="'+link+'" target="_blank">'+fio+'</a><input type="hidden" name="ANSWER_TO" value="'+id+'">');
        $(html).find('.del-answer').click(function(){
            $(this).parent().parent().html('');
        });
        $(btn).parents('.new-profile-posts_container').eq(0).find('form.form_input_file .comment-answer-to').html(html);
        return false;
    });
    $(htmlDom).find('.delete-comment').click(function(){
        var delBtn = this;
        var comment_id = $(this).parents('[js-like-selector]').eq(0).data('id');
        var data = {
            TITLE: 'Удалить комментарий',
            BODY: '<p>Вы действительно хотите <b>удалить комментарий</b> из этой публикации?<br><br>Отменить это действие будет <b>невозможно</b>.</p>',
            BUTTONS: [
                {
                    NAME: 'Отмена',
                    SECONDARY: 'Y',
                    CLOSE: 'Y'
                },
                {
                    NAME: 'Удалить',
                    CALLBACK: (function(){
                        preload('show');
                        BX.ajax(
                            {
                                url: UPP_component.ajaxUrl,
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    via_ajax: 'Y',
                                    action: 'deleteComment',
                                    sessid: BX.bitrix_sessid(),
                                    SITE_ID: UPP_component.siteID,
                                    signedParamsString: UPP_component.signedParamsString,
                                    comment_id: comment_id
                                },
                                onsuccess: function(result){
                                    if(result.STATUS == "SUCCESS"){
                                        $(delBtn).parents('[js-like-selector][data-id="'+comment_id+'"]').eq(0).remove();
                                        showResult('#popup-success', 'Комментарий удален');
                                    }else{
                                        showResult('#popup-error','Ошибка удаления', "Не удалось удалить данный комментарий");
                                    }
                                    preload('hide');
                                },
                                onfailure: function(a, b, c){
                                    console.log(a);
                                    console.log(b);
                                    console.log(c);
                                    showResult('#popup-error','Ошибка удаления', "Не удалось удалить данный комментарий");
                                    preload('hide');
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
}

function clearForm(form){
    $(form).find('textarea').val('');
    $(form).find('input').val('');
    $(form).find('.preview-img-block').html('');
}

function loadMorePosts(){
    if((($(window).scrollTop()+$(window).height())+250) >= $(document).height() && !inProgress){
        ajaxRequest('loadMorePosts', (function(result){
            if(result != 'false'){
                $result = $.parseHTML(result);
                bindDevelopmentPost($result);
                bindDevelopmentLike($result);
                $('.new-profile-wall .new-profile_block.new-profile_block_posts #post_wall').append($result);
                page++;
            }else{
                $(window).off('scroll', loadMorePosts);
            }
        }), [], "", '?nav-post=page-'+page);
    }
}

function uploadFile(input){
    preload('show');
    var file_count = 0;
    $(input).parents('.form_input_file').eq(0).find('input[name="FILE_ID"]').each(function(){
        file_count++;
    });
    if(file_count > 10)
        return false;
    var formData = new FormData();
    formData.append('via_ajax', "Y");
    formData.append('sessid', BX.bitrix_sessid());
    formData.append('action', "addFile");
    formData.append('SITE_ID', UPP_component.siteID);
    formData.append('signedParamsString', UPP_component.signedParamsString);
    formData.append('file_type', $(input).attr('name'));
    jQuery.each($(input)[0].files, function(i, file) {
        var arr = file.name.split('.');
        var expansion = arr[arr.length - 1];
        if(arImgExpansion.indexOf(expansion) != -1 || arDocExpansion.indexOf(expansion) != -1){
            if(file.size < 5242880){
                if(file_count < 10){
                    formData.append(i, file);
                    file_count++;
                }
            }else{
                showResult('#popup-error','Размер файла не должен превышать 5МБ');
            }
        }else{
            showResult('#popup-error','Не подходящее расширение файла');
        }
    });
    $.ajax(
        {
            url: UPP_component.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,	
            data: formData,
            success: function(result){
                if(result.STATUS == "SUCCESS"){
                    for(var file of result.DATA){
                        var previewImg = document.createElement("div");
                        $(previewImg).addClass('preview-img');
                        var arr = file.FILE.ORIGINAL_NAME.split('.');
                        var expansion = arr[arr.length - 1];
                        
                        if(arImgExpansion.indexOf(expansion) != -1){
                            var img = new Image;
                            img.src = file.FILE.SRC;
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
                        }else if(arDocExpansion.indexOf(expansion) != -1){
                            var img = document.createElement("img");
                            img.src = '/local/templates/anypact/image/icon-file.png';
                            var fileName = document.createElement("div");
                            $(fileName).addClass('preview-file-name');
                            $(fileName).text(file.FILE.ORIGINAL_NAME);
                            $(previewImg).append(fileName);
                        }
                        img.title = file.FILE.ORIGINAL_NAME;
                        
                        var deleteUploadImgBtn = document.createElement("div");
                        $(deleteUploadImgBtn).addClass('delete-upload-img-btn');
                        $(deleteUploadImgBtn).attr('data-file-id', file.ID);
                        $(deleteUploadImgBtn).click(function(){
                            var delBtn = this;
                            var id = $(delBtn).attr('data-file-id');
                            ajaxRequest("deleteFile", (function(result){
                                $(delBtn).parent().remove();
                            }), id);
                        });

                        var inputFile = document.createElement("input");
                        $(inputFile).attr('type', 'hidden');
                        $(inputFile).attr('name', 'FILE_ID');
                        $(inputFile).attr('value', file.ID);
                        
                        $(previewImg).append(deleteUploadImgBtn);
                        $(previewImg).append(img);
                        $(previewImg).append(inputFile);

                        $(input).parents('.form_input_file').eq(0).find('.preview-img-block').append(previewImg);
                    }
                }
                preload('hide');
            },
            error: function(a, b, c){
                console.log(a);
                console.log(b);
                console.log(c);
            }
        }
    );
}

$(document).ready(function(){

    $('#new_post').submit(function(e){
        e.preventDefault;

        var el = this;

        preload('show');

        var serializeArray = $(el).serializeArray();
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

        ajaxRequest("newPost", (function(result){
            if(result != 'false'){
                $result = $.parseHTML(result);
                if($('.new-profile-wall .new-profile_block.new-profile_block_posts h2').length < 1)
                    $('.new-profile-wall .new-profile_block.new-profile_block_posts').append('<h2>Публикации</h2><div id="post_wall"></div>');
                bindDevelopmentPost($result);
                bindDevelopmentLike($result);
                $('.new-profile-wall .new-profile_block.new-profile_block_posts #post_wall').prepend($result);
                clearForm(el);
            }
            preload('hide');
        }), arData);

        return false;
    });

    bindDevelopmentPost($('.new-profile-wall'));
    bindDevelopmentLike($('.new-profile-wall'));

    $(window).on('scroll', loadMorePosts);
});