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