$(document).ready(function(){
    var button_send_contract = document.getElementById('send_contract');

    window.onload = function() {
        console.log('подпись!');

        button_send_contract.onclick = function(e) {
            console.log(e);
        }

    };
});



