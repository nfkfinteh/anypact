/*Всплывающее окно регистрации*/
var regpopup_btn_close_win = document.getElementById('regpopup_close');
var regpopup_bg = document.getElementById('regpopup_bg');
var regpopup_open_btn = document.getElementById('reg_button');
var regpopup_btn_open_aut = document.getElementById('regpopup_btn_aut');
var regpopup_form_autorisation = document.getElementById('regpopup_autarisation');
var regpopup_btn_open_reg = document.getElementById('regpopup_btn_reg');
var regpopup_form_registration = document.getElementById('regpopup_registration');
var smscode = document.getElementById('smscode');

window.onload = function() {
    //Закрываем окно
    regpopup_btn_close_win.onclick = function(event) {
        regpopup_bg.style.display = 'none';
    };
    // открываем окно
    regpopup_open_btn.onclick = function(event) {
        regpopup_bg.style.display = 'block';
    };
    /*/ открываем форму авторизации
    regpopup_btn_open_aut.onclick = function() {
        console.log('открываем авторизацию');
        regpopup_form_autorisation.style.display = 'block';
        regpopup_form_registration.style.display = 'none';
        return false;
    };
    // открываем форму регистрации
    regpopup_btn_open_reg.onclick = function(event) {
        console.log('открываем регистрацию');
        regpopup_form_autorisation.style.display = 'none';
        regpopup_form_registration.style.display = 'block';
        return false;
    };*/

    var button_send_contract = document.getElementById('send_contract');
    var popup_send_sms = document.getElementById('send_sms');
    var max_time = document.getElementById('timer_n');
    var id_contract = max_time.getAttribute('id-con');
    var id_contraegent = max_time.getAttribute('id-cont');
    var button_send_contract_owner = document.getElementById('send_contract_owner');

    smscode.value = null;
    // подписывает сторона Б
    if (!!button_send_contract) {
        let canvas = document.getElementById('canvas');
        //console.log(contract_text);
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
                        popup_send_sms.style.display = 'none';
                    }, 3000);
                }
                max_time.innerHTML = f(s);

                var input_sms_code = getValue();
                var valid_code = Number(input_sms_code);

                if (valid_code == 777777) {
                    clearInterval(t);
                    smscode.blur();
                    let contract_text = canvas.innerHTML;
                    contract_text = contract_text.replace(/&/g, "%");
                    var xhr = new XMLHttpRequest();
                    var params = 'id=' + encodeURIComponent(id_contract) + '&contr=' + encodeURIComponent(id_contraegent) + '&smscode=' + encodeURIComponent(valid_code) + '&text_contract=' + contract_text;
                    xhr.open('POST', '/response/ajax/send_contract.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                            console.log(xhr.responseText);
                        };
                    };
                    xhr.send(params);
                    popup_send_sms.style.display = 'none';
                    document.location.href = 'http://anypact.nfksber.ru/my_pacts/';
                }
            }, 1000);


        }
    }

    if (!!button_send_contract_owner) {
        button_send_contract_owner.onclick = function(x) {
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
                    }, 3000);
                }
                max_time.innerHTML = f(s);

                var input_sms_code = getValue();
                var valid_code = Number(input_sms_code);

                if (valid_code == 55555) {
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
                            console.log(xhr.responseText);
                        };
                    };
                    xhr.send(params);
                    popup_send_sms.style.display = 'none';
                    document.location.href = 'http://anypact.nfksber.ru/my_pacts/';
                }
            }, 1000);


        }
    }


    function getValue() {
        var input_sms_code = smscode.value;
        return input_sms_code;
    }


}