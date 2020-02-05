async function responseRoute(arrParams){
    var url = '/response/ajax/up_message_user.php'
    var formData = new FormData();
        formData.append( 'arrParams', arrParams);

    const response = await fetch(url, {
        method  : 'post',
        body    : formData,
        headers: {
            //'X-CSRF-Token': token
        }
    });
    const data = await response.text();
    return data
}

function upListMessage(){
    $.ajax({
        type: 'POST',
        url: document.location.href,
        data: {
            'ACTION': 'up_message'
        },
        success: function (data) {
            $('.message-list').html(data);
        },
        error: function (data) {
            console.log(data); //ошибки сервера
        }
    });
}

$(document).ready(function() {
    let url     = new URL(window.location.href)
    let searchParams = new URLSearchParams(url.search.substring(1))        
    let id      = searchParams.get("id")    
    let TextMes = document.getElementById('textMessage')

    // нажатие кнопки отправки сообщения 
    let ButtonSendMessage = document.getElementById('sendMessage')

    ButtonSendMessage.onclick = function(){
        let Params      = new Object()        
        Params.IDMess   = id
        Params.message  = TextMes.value
        let arrParams   = JSON.stringify(Params)

        if(Params.message.length>0){
            preload('show');
            var res = responseRoute(arrParams).then(function(data) {
                upListMessage();
                $('#textMessage').val('');
                preload('hide');
            });
        }
    }

    $(document).on('click', '.js-chat_delete', function(){
        preload('show');
        $.post(
            "/response/ajax/delete_chat_user.php", {
                id: id,
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            let result = JSON.parse(data);
            if(result['TYPE']=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения');
                console.log(result);
            }
            if(result['TYPE']=='SUCCESS'){
                preload('hide');
                showResult('#popup-success','Переписка удалена');
                location.href = '/list_message/';
            }
        }
    });

    //обновление списка сообщений каждые 5 сек
    setInterval(function() {
        upListMessage();
    },1000*5);
});