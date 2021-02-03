/*
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
*/

$(document).ready(function(){
    console.log('window - onload'); // 4th
    $('input[name="DATE_REGISTER_FROM"]').on('click', function(){
        BX.calendar({node:this, field:'DATE_REGISTER_FROM', form: '', bTime: false})
    });
    $('input[name="DATE_REGISTER_TO"]').on('click', function(){
        BX.calendar({node:this, field:'DATE_REGISTER_TO', form: '', bTime: false})
    });
});