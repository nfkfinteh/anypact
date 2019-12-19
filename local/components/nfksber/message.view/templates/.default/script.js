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

        var res = responseRoute(arrParams).then(function(data) {
            location.reload()
        });
        
    }

    $(document).on('click', '.js-chat_delete', function(){
        $.post(
            "/response/ajax/delete_chat_user.php", {
                id: id,
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            let result = JSON.parse(data);
            if(result['TYPE']=='ERROR'){
                console.log(result);
            }
            if(result['TYPE']=='SUCCESS'){
                location.href = '/my_pacts/';
            }
        }
    });


});