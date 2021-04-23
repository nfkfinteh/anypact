$(document).ready(function () {
    $('.propmption_popup').mousedown(function(e){
        if(e.target == this)
            $(this).hide();
    });
    $('.propmption_popup .close').mousedown(function(){
        $('.propmption_popup').hide();
    });
});