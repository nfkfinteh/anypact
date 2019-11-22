function setBlock(arData){
    let url = '/response/ajax/send_block.php';
    var check;

    $.ajax({
        type: "POST",
        url: url,
        data: arData,
        async: false,
        success: function (result) {
            $result = JSON.parse(result);
            if($result.STATUS=='ERROR'){
                console.log($result.VALUE);
                check =  true;
            }
            else if($result.STATUS=='SUCCESS'){
                if($result.VALUE=='Y'){
                    check =  true;
                }
                else{
                    check =  false;
                }

            }
            else{
                console.log('Не получен статус выполнения блокировки');
                check =  true;
            }
        }
    });

    return check;
}

function getStatusBlock(arData){
    let url = '/response/ajax/send_block.php';
    var check;

    $.ajax({
        type: "POST",
        url: url,
        data: arData,
        async: false,
        success: function (result) {
            $result = JSON.parse(result);
            if ($result.STATUS == 'SUCCESS') {
                if ($result.VALUE == 'Y') {
                    check = true;
                } else {
                    check = false;
                }
            } else {
                console.log('Не получен статус выполнения блокировки');
                check = true;
            }
        }
    });
    return check;
}

function getURLReport(url, params){
    let xhr = new XMLHttpRequest();    
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(params);
    
    return xhr.responseText;
}

async function getAutorisation(login, password){
    
    var url = '/response/ajax/autorisation_user.php'
    
    var mainData = JSON.stringify({
        LOGIN  : login,
        PASSWORD : password,        
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

async function getFree(strObject, type){
    
    var url = '/response/ajax/check_login.php'
    
    var mainData = JSON.stringify({
        CHECK_TEXT  : strObject,
        CHECK_TYPE  : type,
    });

    var formData = new FormData();
        formData.append( 'checkin', mainData );

    
    const response = await fetch(url, {
        method: 'post',
        body:formData
    });
    const data = await response.text();
    return data
}

function errBorder(errObject, type){  
    switch (type) {
        case 'error':
            errObject.style.cssText = "border: solid 1px red;";
            errObject.focus();                        
        break;    
        case 'succes':
            errObject.style.cssText = "border: none;";            
        break;
    }
    
}

function errBorderNoBlock(errObject, type){  
    switch (type) {
        case 'error':
            errObject.style.cssText = "border: solid 1px red;";
            //errObject.focus();                        
        break;    
        case 'succes':
            errObject.style.cssText = "border: none;";            
        break;
    }
    
}

window.onload = function() {
    /*Всплывающее окно регистрации*/
    if(document.getElementById('regpopup_registration')){
        
        var regpopup_btn_close_win = document.getElementById('regpopup_close');
        var regpopup_bg = document.getElementById('regpopup_bg');
        var regpopup_open_btn = document.getElementById('reg_button');
        var regpopup_btn_open_aut = document.getElementById('regpopup_btn_aut');
        var regpopup_form_autorisation = document.getElementById('regpopup_autarisation');
        var regpopup_btn_open_reg = document.getElementById('regpopup_btn_reg');
        var regpopup_form_registration = document.getElementById('regpopup_registration');
        var open_reg_form = document.getElementById('open_reg_form');        
        
        document.getElementById('user_password_fild').value = '';
        document.getElementById('user_login_fild').value = '';

        //Закрываем окно
        regpopup_btn_close_win.onclick = function(event) {
            regpopup_bg.style.display = 'none';
            console.log('Закрытие окна регистрации')
            let clear_input = document.forms.regform
            let i = 0
            while (i<4) {
                clear_input.elements[i].value = ''
                i++
            }            
            location.reload()
        };
        // открываем окно
        if(document.getElementById('reg_button')){
            regpopup_open_btn.onclick = function(event) {
                regpopup_bg.style.display = 'block';
            };
        }
        // открываем форму авторизации
        regpopup_btn_open_aut.onclick = function() {
            regpopup_form_autorisation.style.display = 'block';
            regpopup_form_registration.style.display = 'none';
            return false;
        };
        // открываем форму регистрации
        regpopup_btn_open_reg.onclick = function(event) {
            regpopup_form_autorisation.style.display = 'none';
            regpopup_form_registration.style.display = 'block';
            return false;
        };

        if(document.getElementById('open_reg_form')){
            open_reg_form.onclick = function(event) {
                console.log("Форма регистрации");
                regpopup_bg.style.display = 'block';
                regpopup_form_autorisation.style.display = 'none';
                regpopup_form_registration.style.display = 'block';
                return false;
            };
        }
        // поверяем водимый логин на уникальность
        document.getElementById('user_login_fild').onblur = function(event){
            var fild_login  = this;
            let login       = fild_login.value;            
            let url         = '/response/ajax/check_login.php';
            let type        = 'login';            
            var pass_fild   = document.getElementById('user_password_fild');
            /*
            if(login.length > 0){                
                var res = getFree(login, type).then(function(data) {
                    $result = JSON.parse(data);
                    if($result['TYPE']=='ERROR'){
                        document.getElementById('message_error_login').innerHTML = '&#8226; Логин занят';
                        errBorder(fild_login, 'error');
                        pass_fild.disabled = true;                       
                    }
                    if($result['TYPE']=='SUCCES'){
                        document.getElementById('message_error_login').innerHTML = '';
                        errBorder(fild_login, 'succes');
                        pass_fild.disabled = false;
                        pass_fild.focus();                                             
                    }
                });
            }  */          
        };
        
        // проверяем пароль на длинну
        document.getElementById('user_password_fild').onblur = function(event){
            var conpass_fild    = this; 
            window.value_fild  = this.value;
            var con_pass_fild   = document.getElementById('user_con_password_fild');
            let length_pass = 9;
            if(value_fild.length >= length_pass){
                con_pass_fild.disabled = false;
                //con_pass_fild.focus();
                errBorder(conpass_fild, 'succes');
                document.getElementById('message_error_login').innerHTML = '<div style="color:green"">&#10004; Отлично! Пароль нужной длинны</div>';
            }else {
                errBorder(conpass_fild, 'error');
                document.getElementById('message_error_login').innerHTML = '&#9888; Пароль должен быть не менее '+length_pass+' символов';
            }
        }

        // проверяем пароль на повторение
        document.getElementById('user_con_password_fild').onblur = function(event){
            var conpass_fild    = this; 
            var con_value_fild  = this.value;
            var con_pass_fild   = document.getElementById('user_email_fild');
            var submit_button_aut_user = document.getElementById('submit_button_registration');

            if(con_value_fild === value_fild){
                con_pass_fild.disabled = false;
                submit_button_aut_user.disabled = false;
                //con_pass_fild.focus();
                //submit_button_aut_user.focus(); 
                errBorder(conpass_fild, 'succes');
                document.getElementById('message_error_login').innerHTML = '<div style="color:green"">&#10004; Пароль совпадает</div>';
            }else {
                errBorderNoBlock(conpass_fild, 'error');
                document.getElementById('message_error_login').innerHTML = '&#9888; Пароль не совпадает';  
            }
        }

        // проверка почты 
        document.getElementById('user_email_fild').onblur = function(event){
            var fild_email  = this;
            let email       = fild_email.value;
            let url         = '/response/ajax/check_login.php';
            let type        = 'email';
            var submit_button_aut_user = document.getElementById('submit_button_registration');
            var pass_fild   = document.getElementById('user_password_fild');
            var fild_login  = document.getElementById('user_login_fild');
            
            if(email.length > 0){                
                var res = getFree(email, type).then(function(data) {
                    $result = JSON.parse(data);
                    if($result['TYPE']=='ERROR'){                        
                        document.getElementById('message_error_login').innerHTML = '&#8226; Почтовый ящик уже используется';
                        errBorder(fild_email, 'error');
                        pass_fild.disabled = true;
                    }
                    if($result['TYPE']=='SUCCES'){                        
                        document.getElementById('message_error_login').innerHTML = '<div style="color:green"">&#10004; Почтовый ящик не зарегистрирован</div>';
                        errBorder(fild_email, 'succes');
                        //submit_button_aut_user.disabled = false;
                        pass_fild.disabled = false;
                        pass_fild.focus();
                        fild_login.value = fild_email.value                                          
                        //submit_button_aut_user.focus();                       
                    }
                });
            }            
        };

        // авторизация пользователя и вывод ошибок
        document.getElementById('submit_button_aut_user').onclick  = function(){
          let login = document.getElementById('user_aut_login').value
          let password  = document.getElementById('user_aut_pass').value          
          var res = getAutorisation(login, password).then(function(data) {
                $result = JSON.parse(data);
                if($result['TYPE']=='ERROR'){
                    document.getElementById('message_error_aut').innerHTML = '&#8226; '+$result['VALUE'];
                }
                if($result['TYPE']=='SUCCES'){
                    location.reload();
                }
            });
        };
    }

    var button_send_contract = document.getElementById('send_contract');
    var popup_send_sms = document.getElementById('send_sms');
    var max_time = document.getElementById('timer_n');
    if(document.getElementById('send_contract')){
        var id_contract = max_time.getAttribute('id-con');
        var id_contraegent = max_time.getAttribute('id-cont');
        var button_send_contract_owner = document.getElementById('send_contract_owner');
        var smscode = document.getElementById('smscode');
        var idIblock = max_time.getAttribute('id-iblock');
        var break_sign = getStatusBlock({'contract':id_contract, 'contragent':id_contraegent, 'action':'get'});
    }

    // подписание контракта
    if(document.getElementById('send_contract')){
        smscode.value = null;
        // подписывает сторона Б
        if (!!button_send_contract) {
                          
            // нажатие на кнопку подписания
            button_send_contract.onclick = function(e) {                
                popup_send_sms.style.display = 'block';
                smscode.focus();

                var sec = max_time.innerHTML;
                if (sec < 1) {
                    max_time.innerHTML = 80;
                }

                var t = setInterval(function() {

                    function f(x) {
                        return (x / 100).toFixed(2).substr(2)
                    }
                    s = max_time.innerHTML;
                    s--;

                    if (s < 0) {
                        s = max_time.getAttribute('long');
                        clearInterval(t);
                        //inner_sms_popap.innerHTML = '<h4 style="font-size: 16px;">Срок действия кода истек, повторите процедуру подписания</h4>';
                        setTimeout(function() {
                            //setBlock({'contract':id_contract, 'contragent':id_contraegent, 'status':'', 'action':'set'});
                            popup_send_sms.style.display = 'none';
                        }, 3000);
                    }
                    max_time.innerHTML = f(s);
                    var input_sms_code = getValue();
                    var valid_code = Number(input_sms_code);

                    if (valid_code == 777777) {                        
                        clearInterval(t);
                        smscode.blur();                        
                        // получаем текст договора (возможно с изменениями)
                        let canvas = document.getElementById('canvas');
                        //нужно очистить от & и спец символов 
                        let contract_text_f = canvas.innerHTML;
                        contract_text = contract_text_f.replace(/&nbsp;/gi,'');    
                        let isImg = '';
                        
                        // запрос на сервер
                        var xhr = new XMLHttpRequest();
                        var params = 'id=' + encodeURIComponent(id_contract) + '&contr=' + encodeURIComponent(id_contraegent) +
                            '&smscode=' + encodeURIComponent(valid_code) + '&text_contract=' + contract_text + '&isImg=' + isImg+
                            '&status='+button_send_contract.getAttribute('data');
                        xhr.open('POST', '/response/ajax/send_contract.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                                //console.log(xhr.responseText);
                            };
                        };
                        xhr.send(params);

                        break_sign = setBlock({'contract':id_contract, 'contragent':id_contraegent, 'status':'', 'action':'set'});
                        popup_send_sms.style.display = 'none';
                        document.location.href = '/my_pacts/';
                    }
                }, 1000);

            }
        }
    }
    if (!!button_send_contract_owner) {

        button_send_contract_owner.onclick = function(x) {

            break_sign = getStatusBlock({'contract':id_contract, 'contragent':id_contraegent, 'action':'get'});
            if(break_sign) {
                console.log('Подписывает другая сторона');
                return;
            }

            popup_send_sms.style.display = 'block';
            smscode.focus();

            var sec = max_time.innerHTML;
            if (sec < 1) {
                max_time.innerHTML = 80;
            }

            var t = setInterval(function() {

                function f(x) {
                    return (x / 100).toFixed(2).substr(2)
                }
                s = max_time.innerHTML;
                s--;

                if (s < 0) {
                    s = max_time.getAttribute('long');
                    clearInterval(t);
                    //inner_sms_popap.innerHTML = '<h4 style="font-size: 16px;">Срок действия кода истек, повторите процедуру подписания</h4>';
                    setTimeout(function() {
                        popup_send_sms.style.display = 'none';
                        //setBlock({'contract':id_contract, 'contragent':id_contraegent, 'status':'', 'action':'set'});
                    }, 3000);
                }
                max_time.innerHTML = f(s);

                var input_sms_code = getValue();
                var valid_code = Number(input_sms_code);

                break_sign = getStatusBlock({'contract':id_contract, 'contragent':id_contraegent, 'action':'get'});
                if(break_sign) {
                    clearInterval(t);
                    popup_send_sms.style.display = 'none';
                    alert('Подписывает другая сторона');
                    return false;
                }

                if (valid_code == 55555 && !break_sign) {
                    //блокируем действие над контрактом в момент его подписания
                    break_sign = setBlock({'contract':id_contract, 'contragent':id_contraegent, 'status':'Y', 'action':'set'});
                    console.log(break_sign);

                    clearInterval(t);
                    smscode.blur();
                    var id = button_send_contract_owner.getAttribute('data-id');
                    var id_contraegent = button_send_contract_owner.getAttribute('data-user');
                    var xhr = new XMLHttpRequest();
                    var params = 'id=' + encodeURIComponent(id) + '&owner=' + encodeURIComponent(id_contraegent) + '&smscode=' + valid_code;
                    xhr.open('POST', '/response/ajax/send_owner.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                            //console.log(xhr.responseText);
                        };
                    };
                    xhr.send(params);
                    break_sign = setBlock({'contract':id_contract, 'contragent':id_contraegent, 'status':'', 'acttion':'get'});

                    popup_send_sms.style.display = 'none';
                    document.location.href = '/my_pacts/';
                }
            }, 1000);


        }
    }


    function getValue() {
        var input_sms_code = smscode.value;
        return input_sms_code;
    }


}