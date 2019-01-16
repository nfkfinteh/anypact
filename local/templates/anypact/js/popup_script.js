/*Всплывающее окно регистрации*/
var regpopup_btn_close_win = document.getElementById('regpopup_close');
var regpopup_bg = document.getElementById('regpopup_bg');
var regpopup_open_btn = document.getElementById('reg_button');
var regpopup_btn_open_aut = document.getElementById('regpopup_btn_aut');
var regpopup_form_autorisation = document.getElementById('regpopup_autarisation');
var regpopup_btn_open_reg = document.getElementById('regpopup_btn_reg');
var regpopup_form_registration = document.getElementById('regpopup_registration');

console.log(regpopup_btn_open_aut);
window.onload = function() {
    //Закрываем окно
    regpopup_btn_close_win.onclick = function(event) {
        regpopup_bg.style.display = 'none';
    };
    // открываем окно
    regpopup_open_btn.onclick = function(event) {
        regpopup_bg.style.display = 'block';
    };
    // открываем форму авторизации
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
    };
}