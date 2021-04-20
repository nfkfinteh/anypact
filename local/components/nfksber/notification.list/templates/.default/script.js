var inProgress = false;
var page = 2;

function sendAjaxNotification(action, success, data = {}, type = 'POST', additionalURL = '', dataType = 'json') {

    var beginData = {
        via_ajax: 'Y',
        action: action,
        sessid: BX.bitrix_sessid(),
        SITE_ID: NL_component.siteID,
        signedParamsString: NL_component.signedParamsString
    };
    Object.assign(beginData, data);

    $.ajax({
        method: type,
        url: NL_component.ajaxUrl + additionalURL,
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
    });
}

function loadMoreNotification(not_scroll = false) {
    if (not_scroll == true || ((($(this).scrollTop() + $(this).height()) >= ($(this)[0].scrollHeight - 200)) && !inProgress)) {
        var func = function (result) {
            inProgress = false;
            if (result != false) {
                var $result = $.parseHTML(result['HTML']);
                bindNotEvents($result);
                if ($('#notification_list .list-person-conversation').length > 0)
                    $('#notification_list .list-person-conversation').append($result);
            }
            if(result['NOT_LOAD_MORE'] == "Y")
                $('#notification_list .list-person-conversation').off('scroll', loadMoreNotification);
            page++;
        };
        sendAjaxNotification('loadMoreNotification', func, {}, 'POST', '?nav-notification=page-' + page);
    }
}

function bindNotEvents(node){
    $(node).find('.not-read').mouseenter(function () {
        var $el = $(this);
        var func = function (result) {
            if (result !== false) {
                $el.removeClass('not-read');
                if (result == 0)
                    $('#notification_btn .notification_count').remove();
                else if (result > 9)
                    $('#notification_btn .notification_count span').text();
                else
                    $('#notification_btn .notification_count span').text(result);
            }
        };
        sendAjaxNotification('readNotific', func, { id: $el.data('id') });
    });
    $(node).find('.not-delete').click(function () {
        var $el = $(this).parents('.person-conversation');
        newAnyPactPopUp({
            TITLE: 'Удалить уведомление',
            BODY: '<p>Вы действительно хотите <b>удалить уведомление</b>?<br><br>Отменить это действие будет <b>невозможно</b>.</p>',
            BUTTONS: [
                {
                    NAME: 'Отмена',
                    SECONDARY: 'Y',
                    CLOSE: 'Y'
                },
                {
                    NAME: 'Удалить',
                    CLOSE: 'Y',
                    CALLBACK: (function () {
                        var func = function (result) {
                            if (result !== false) {
                                if (result == 0)
                                    $('#notification_list .list-person-conversation').html('<div class="no-notification"><img src="/local/templates/anypact/image/dont_chat.png" alt="Уведомления отсутствуют"><p>Уведомления отсутствуют</p></div>');
                                else
                                    $el.remove();
                                if($('#notification_list .list-person-conversation').find('.person-conversation').length < 5 && result >= 5)
                                    loadMoreNotification(true);
                            }
                        };
                        sendAjaxNotification('deleteNotific', func, { id: $el.data('id') });
                    })
                }
            ]
        });
    });
}

$(document).ready(function () {
    bindNotEvents($('#notification_list'));
    $('#delete-all-note').click(function () {
        newAnyPactPopUp({
            TITLE: 'Удалить все уведомления',
            BODY: '<p>Вы действительно хотите <b>удалить все уведомления</b>?<br><br>Отменить это действие будет <b>невозможно</b>.</p>',
            BUTTONS: [
                {
                    NAME: 'Отмена',
                    SECONDARY: 'Y',
                    CLOSE: 'Y'
                },
                {
                    NAME: 'Удалить',
                    CLOSE: 'Y',
                    CALLBACK: (function () {
                        var func = function (result) {
                            if (result !== false)
                                $('#notification_list .list-person-conversation').html('<div class="no-notification"><img src="/local/templates/anypact/image/dont_chat.png" alt="Уведомления отсутствуют"><p>Уведомления отсутствуют</p></div>');
                        };
                        sendAjaxNotification('deleteNotificAll', func, {}, 'POST', '?nav-notification=1');
                    })
                }
            ]
        });
    });
    $('#notification_list .list-person-conversation').on('scroll', loadMoreNotification);
    $('#notification_btn').click(function () {
        var $content = $('#' + $(this).data('content-id'));
        $content.parent().toggleClass('open');
    });
    $('body').click(function(e){
        if($(e.target) != $('.right-menu') && $(e.target).parents('.right-menu').length < 1)
            $('.right-menu.open').toggleClass('open');
    });
});