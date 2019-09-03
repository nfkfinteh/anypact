$(document).ready(function(){

    $(document).on('click', '.submit_message', function(){
        let form = $('#message_user');
        let url = form.attr('action');
        let data = form.serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                $result = JSON.parse(result);
                if($result['TYPE']=='ERROR'){
                    console.log($result['VALUE']);
                    alert($result['VALUE']);
                }
                if($result['TYPE']=='SUCCESS'){
                    form.find('textarea').val('');
                    form.parents('.modal-content').eq(0).find('button.close').click();
                }
            },

        });
    });

    $( '#my-slider' ).sliderPro({
        width : "100%",
        aspectRatio : 1.6, //соотношение сторон
        loop : false,
        autoplay : false,
        fade : true,
        thumbnailWidth : 164,
        thumbnailHeight : 101,
        breakpoints: {
            450: {
                thumbnailWidth : 82,
                thumbnailHeight : 50
            }
        }
    });

});