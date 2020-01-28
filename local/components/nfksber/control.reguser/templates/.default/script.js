async function SendUserPay(ParamsPay){
    
    var url = '/response/ajax/autorisation_user.php'
    
    var mainData = JSON.stringify({
        //LOGIN  : login,
        //PASSWORD : password,        
    });

    var formData = new FormData();
        formData.append( 'main', mainData );

    
    const response = await fetch(url, {
        method: 'post',
        body:formData
    });
    const data = await response.text();
    return data
}

window.onload = function(){
    console.log('Страница регистрации пользователей');
    let ButtonSendPay = document.getElementsByClassName('buttonSebdPay');

    ButtonSendPay.onclick = function(){
        console.log(ButtonSendPay);
    }
        
};