function sendAjaxContract(action, success, data = {}, type = 'POST', additionalURL = '', dataType = 'json') {
    preload('show');
    var beginData = {
        via_ajax: 'Y',
        action: action,
        sessid: BX.bitrix_sessid(),
        SITE_ID: CA_component.siteID,
        signedParamsString: CA_component.signedParamsString
    };
    Object.assign(beginData, data);

    $.ajax({
        method: type,
        url: CA_component.ajaxUrl + additionalURL,
        data: beginData,
        dataType: dataType,
        success: success,
        error: function (a, b, c) {
            console.log(a);
            console.log(b);
            console.log(c);
        },
        beforeSend: function () {
            inProgress = true;
        }
    }).done(function() {
        preload('hide');
    });
}

function pasteEditorText(text) {
    let selection = window.getSelection();
    if($(selection.focusNode)[0] == $('.trumbowyg-editor')[0] || $(selection.focusNode).parents('.trumbowyg-editor').length > 0){
        let range = selection.getRangeAt(0);
        range.deleteContents();
        let node = document.createElement('div');
        node.innerHTML = text;
        range.insertNode(node);
        $('#editor').trumbowyg('toggle');
        $('#editor').trumbowyg('toggle');
    }
}

function setUserFields(type, second = false) {
    if(type == 'seller'){
        var opposite = 'customer';
        var text = 'Покупателя';
    }else if(type == 'customer'){
        var opposite = 'seller';
        var text = 'Продавца';
    }

    $.each(userData, function(code, data){
        if($('.'+type+'_'+code).length > 0){
            $('.'+type+'_'+code).text(data.VALUE);
            if(!second && $('.'+opposite+'_'+code).length > 0)
                $('.'+opposite+'_'+code).text('['+data.NAME+' '+text+']');
        }
        if(code == 'FIO'){
            if($('.fullname'+type).length > 0){
                $('.fullname'+type).text(data.VALUE);
                if(!second && $('.fullname'+opposite).length > 0)
                    $('.fullname'+opposite).text('['+data.NAME+' '+text+']');
            }
        }
    });
    $('#editor').trumbowyg('toggle');
    $('#editor').trumbowyg('toggle');
}

var editorSettings = {
    btns: [
        ['historyUndo','historyRedo'],
        ['strong', 'em'],
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['unorderedList', 'orderedList'],
        ['table'],
        ['horizontalRule'],
        ['removeformat'],
        ['fullscreen']
    ],
    defaultLinkTarget: '_blank',
    lang: 'ru',
    autogrow: true
};
var funcReload = function (result) {    
    if(result.MESSAGE !== undefined)
        showResult('#popup-error',result.MESSAGE);

    if(result.HTML !== undefined){
        var $html = $.parseHTML(result.HTML);
        var $left_menu = $.parseHTML($($html).find('#contract_menu').html());
        var $editor = $.parseHTML($($html).find('#editor_block').html());

        if($('#contract_menu').length > 0){
            bindEditorEvents($left_menu);
            $('#contract_menu').html($left_menu);
        }
        if($('#editor_block').length > 0){
            $('#editor_block').html($editor);
            bindEditorEvents($editor);
        }
    }
    if(result.SCRIPT !== undefined){
        eval(result.SCRIPT);
    }
};

function bindEditorEvents(node){
    if($(node).find('#save_redaction').length > 0){
        $(node).find('#save_redaction').click(function(){
            sendAjaxContract('saveRedaction', funcReload, {TEXT: $(editor).val()});
        });
    }
    if($(node).find('#delete_redaction').length > 0){
        $(node).find('#delete_redaction').click(function(){
            newAnyPactPopUp({TITLE: jsText.DELETE_REDACTION.TITLE, BODY: jsText.DELETE_REDACTION.TEXT,
                BUTTONS: [
                    {
                        NAME: jsText.CLOSE,
                        SECONDARY: 'Y',
                        CLOSE: 'Y'
                    },
                    {
                        NAME: jsText.DELETE_REDACTION.BUTTON,
                        CLOSE: 'Y',
                        CALLBACK: (function () {
                            sendAjaxContract('deleteRedaction', funcReload);
                        })
                    }
                ]
            });
        });
    }
    if($(node).find('#edit_contract').length > 0){
        $(node).find('#edit_contract').click(function(){
            sendAjaxContract('editContract', funcReload);
        });
    }
    if($(node).find('#sign_contract').length > 0){
        $(node).find('#sign_contract').click(function(){
            newAnyPactPopUp({TITLE: jsText.SIGN_CONTRACT.TITLE, BODY: jsText.SIGN_CONTRACT.TEXT,
                BUTTONS: [
                    {
                        NAME: jsText.CLOSE,
                        SECONDARY: 'Y',
                        CLOSE: 'Y'
                    },
                    {
                        NAME: jsText.SIGN_CONTRACT.BUTTON,
                        CLOSE: 'Y',
                        CALLBACK: (function () {
                            sendAjaxContract('signContract', funcReload);
                        })
                    }
                ]
            });
        });
    }
    if($(node).find('.link_template').length > 0){
        $(node).find('.link_template').click(function(){
            sendAjaxContract('selectTree', funcReload, {ID: $(this).data('id')});
        });
    }
    if($(node).find('.js-select-pattern').length > 0){
        $(node).find('.js-select-pattern').click(function(e){
            e.preventDefault();
            sendAjaxContract('getPatterns', funcReload);
            return false;
        });
    }
    if($(node).find('.tree-element').length > 0){
        $(node).find('.tree-element').click(function(e){
            e.preventDefault();
            sendAjaxContract('selectPattern', funcReload, {ID: $(this).data('id')});
            return false;
        });
    }
    if($(node).find('#uploadbtn').length > 0){
        $(node).find('#uploadbtn').change(function(e){

            var formData = new FormData();
            formData.append('via_ajax', "Y");
            formData.append('sessid', BX.bitrix_sessid());
            formData.append('action', "uploadFile");
            formData.append('SITE_ID', CA_component.siteID);
            formData.append('signedParamsString', CA_component.signedParamsString);
            formData.append("file", $(this).prop('files')[0]);

            preload('show');

            $.ajax({
                url: CA_component.ajaxUrl,
                method: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,	
                data: formData,
                success: funcReload,
                error: function (a, b, c) {
                    console.log(a);
                    console.log(b);
                    console.log(c);
                },
                beforeSend: function () {
                    inProgress = true;
                }
            }).done(function() {
                preload('hide');
            });
        });
    }
    if($(node).find('#select_type_user').length > 0){
        $(node).find('#select_type_user').on('change', function() {
            setUserFields($(this).val());
        });
        setTimeout(function() {
            $(node).find('#select_type_user').change();
        }, 100);
    }
    if($(node).find('.js-btn-rquised').length > 0){
        $(node).find('.js-btn-rquised').click(function(){
            var data_ins = 
                '<table>' +
                '<thead>' +
                '<tr>' +
                '<td colspan="2">Исполнитель</td>' +
                '<td colspan="2">Заказчик</td>' +
                '</tr>' +
                '</thead>' +
                '<tbody>' +
                '<tr>' +
                '<td>ФИО</td>' +
                '<td>[____]</td>' +
                '<td>ФИО</td>' +
                '<td>[____]</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Адрес</td>' +
                '<td>[____]</td>' +
                '<td>Адрес</td>' +
                '<td>[____]</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Телефон</td>' +
                '<td>[____]</td>' +
                '<td>Телефон</td>' +
                '<td>[____]</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Расчетные реквизиты</td>' +
                '<td>[____]</td>' +
                '<td>Расчетные реквизиты</td>' +
                '<td>[____]</td>' +
                '</tr>' +
                '</tbody>' +
                '</table>';
            pasteEditorText(data_ins);
        });
    }
    if($(node).find('.js-btn-fio').length > 0){
        $(node).find('.js-btn-fio').click(function(){
            pasteEditorText(userData.FIO.VALUE);
        });
    }
    if($(node).find('.js-btn-address').length > 0){
        $(node).find('.js-btn-address').click(function(){
            pasteEditorText(userData.ADDRESS.VALUE);
        });
    }
    if($(node).find('.js-btn-fio-contr').length > 0){
        $(node).find('.js-btn-fio-contr').click(function(){
            pasteEditorText('<span style="background: cornflowerblue;">@ФИО_КОНТРАГЕНТА@</span>');
        });
    }
    if($(node).find('.js-btn-adress-contr').length > 0){
        $(node).find('.js-btn-adress-contr').click(function(){
            pasteEditorText('<span style="background: cornflowerblue;">@АДРЕС_КОНТРАГЕНТА@</span>');
        });
    }    
    if($(editor).length > 0){
        $(editor).trumbowyg(editorSettings);
        $(editor).trumbowyg().on('tbwpaste', removeTrumbowygTags);
    }
}
function removeTrumbowygTags(){
    let el = this;
    $(el).trumbowyg('disable');
    $.ajax({
        url: '/response/ajax/check_text.php',
        method: 'POST',
        dataType: 'json',
        data: {
            sessid: BX.bitrix_sessid(),
            text: $(el).trumbowyg('html')
        },
        success: function(result){
            $(el).trumbowyg('html', result);
            $(el).trumbowyg('enable');
        }
    });
}
$(document).ready(function(){
    bindEditorEvents('.cardDogovor');
});