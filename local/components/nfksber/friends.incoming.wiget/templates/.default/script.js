function uploadIncomingFriends(){
    BX.ajax(
        {
            url: FIW_component.ajaxUrl,
            method: 'POST',
            dataType: 'html',
            data: {
                via_ajax: 'Y',
                action: 'uploadIncomingFriends',
                sessid: BX.bitrix_sessid(),
                SITE_ID: FIW_component.siteID,
                signedParamsString: FIW_component.signedParamsString
            },
            onsuccess: function(result){
                $result = JSON.parse(result);
                if($result['STATUS']=='SUCCESS'){
                    if($('.global-incoming_friends-count').length > 0){
                        if($result['COUNT'] == 0)
                            $('.global-incoming_friends-count').remove();
                        else if($('.global-incoming_friends-count').text().trim() != $result['COUNT'])
                            $('.global-incoming_friends-count').text($result['COUNT']);
                    }else{
                        if($result['COUNT'] > 0){
                            if($('#navbarSupportedContent').length > 0)
                                $('#navbarSupportedContent').append('<div class="global-incoming_friends-count">'+$result['COUNT']+'</div>');
                            if($('.freind-count').length > 0)
                                $('.freind-count').append('<div class="global-incoming_friends-count">'+$result['COUNT']+'</div>');
                        }
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

$(document).ready(function(){
    setInterval(uploadIncomingFriends, 1000*29);
});