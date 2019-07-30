async function responseSmev(arrParams){
    var url = 'requestSMEV/rest.php'
    
    var formData = new FormData();
        formData.append( 'arrParams', arrParams);

    const response = await fetch(url, {
        method  : 'post',
        body    : formData,
    });
    const data = await response.text();
    return data
}

 

window.onload = function() {
    
    document.getElementById('firstRequest').onclick = function(e){
        
        let form = document.getElementById('formFirstRequestSMEV');//document.querySelector('form');
 
        let arrParams = JSON.stringify({
            passportSeries  : form.elements.passportSeries.value,
            passportNumber  : form.elements.passportNumber.value,
            firstname       : form.elements.firstname.value,
            lastname        : form.elements.lastname.value,
            middlename      : form.elements.middlename.value,
            snils           : form.elements.snils.value,
            inn             : form.elements.inn.value
        });
        
        console.log(arrParams)

        var res = responseSmev(arrParams).then(function(data) {
            console.log(data)
            $result = JSON.parse(data)            
            document.getElementById('RequestText').innerHTML = $result.success      
        });
    }

    document.getElementById('request').onclick = function(e){
        
        let idMessage = document.getElementById('idRequestMessage').value       
        
        var arrParams = JSON.stringify({
            ID  : idMessage,
            
        });      
        var res = responseSmev(arrParams).then(function(data) {
            console.log(data)
            $result = JSON.parse(data)
            console.log($result.success)            
            let resultRequest = $result.success
            if(resultRequest){
                document.getElementById('RequestText').innerHTML = $result.response.description+'  -  '+$result.response.messageId  
            }     
        });


    }   
}