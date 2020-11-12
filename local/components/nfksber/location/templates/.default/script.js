function getLocation(){
    BX.ajax(
        {
            url: L_component.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            data: {
                via_ajax: 'Y',
                action: 'getLocation',
                sessid: BX.bitrix_sessid(),
                SITE_ID: L_component.siteID,
                signedParamsString: L_component.signedParamsString
            },
            onsuccess: function(result){
                if(result.STATUS == "SUCCESS"){
                    $('#header .header_item span.location').text(result.CITY_NAME);
                    set_cookie('CITY_ANYPACT', result.CITY_NAME);
                    var city = result.CITY_NAME;
                    var wait = setInterval(function(){
                        if($('#map').children('ymaps').length > 0){
                            $('#map').children('ymaps').remove();
                            ymaps.ready(init);
                            clearInterval(wait);
                        }
                    }, 100);
                    setTimeout(function(){
                        clearInterval(wait);
                    },1000);
                    // window.location.reload();
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