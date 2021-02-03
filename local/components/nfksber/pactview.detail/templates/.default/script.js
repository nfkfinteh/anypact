$(document).ready(function(){

    $(document).on('click', '.submit_message', function(){
        let form = $(this).parents('.modal-content').find('form');
        let url = form.attr('action');
        let data = form.serialize();
        preload('show');
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                $result = JSON.parse(result);
                if($result['TYPE']=='ERROR'){
                    preload('hide');
                    console.log($result['VALUE']);
                }
                if($result['TYPE']=='SUCCESS'){
                    form.find('textarea').val('');
                    form.parents('.modal-content').eq(0).find('button.close').click();
                    preload('hide');
                    showResult('#popup-success', $result['VALUE']);
                }
            },

        });
    });

    $('#show_phone').on('click', function(el){
        el.preventDefault;
        var el_id = $(this).attr('data-pact-id');
        var elNode = $(this);
        $.ajax({
            type: 'POST',
            url: '/response/ajax/get_user_phone.php',
            data:{
                EL_ID: el_id,
                sessid: BX.bitrix_sessid()
            },
            success: function(result){
                $result = JSON.parse(result);
                if($result['TYPE']=='ERROR'){
                    console.log($result['VALUE']);
                }
                if($result['TYPE']=='SUCCESS'){
                    elNode.attr('href', "tel:"+$result['VALUE'].replace(new RegExp("[- ()]",'g'), ''));
                    elNode.text($result['VALUE']);
                    elNode.off();
                    elNode.css('font-size', '24px');
                }
            },

        });
        return false;
    });

});