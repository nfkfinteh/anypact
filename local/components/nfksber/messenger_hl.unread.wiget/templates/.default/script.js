$(document).ready(function(){
    setInterval((function(){
        BX.ajax(
            {
                url: MUW_component.ajaxUrl,
                method: 'POST',
                dataType: 'html',
                data: {
                    via_ajax: 'Y',
                    action: 'uploadCountMessages',
                    sessid: BX.bitrix_sessid(),
                    SITE_ID: MUW_component.siteID,
                    signedParamsString: MUW_component.signedParamsString
                },
                onsuccess: function(result){
                    $result = JSON.parse(result);
                    if($result['STATUS']=='SUCCESS'){
                        if($result['COUNT']){
                            if($('.global-unread-message-count').length > 0){
                                if($result['COUNT'] == 0)
                                    $('.global-unread-message-count').remove();
                                else if($('.global-unread-message-count').text().trim() != $result['COUNT'])
                                    $('.global-unread-message-count').text($result['COUNT']);
                            }else
                                if($result['COUNT'] > 0 && $('#navbarSupportedContent').length > 0)
                                    $('#navbarSupportedContent').append('<div class="global-unread-message-count">'+$result['COUNT']+'</div>');
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
    }), 1000*27);
});