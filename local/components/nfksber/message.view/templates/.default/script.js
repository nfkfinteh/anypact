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

    let url = new URL(window.location.href)
    let searchParams = new URLSearchParams(url.search.substring(1))        
    let id = searchParams.get("id")

    let Params       = new Object()        
        Params.route    = 'signCompany'
        Params.IDMess   = id
        let arrParams   = JSON.stringify(Params)
    
    var res = responseRoute(arrParams).then(function(data) {
        console.log(data)
        //$result = JSON.parse(data);
    });
});