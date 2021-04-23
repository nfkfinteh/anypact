$(document).ready(function () {
    $('.propmption_popup').mousedown(function(e){
        console.log(e);
        if(e.target == this)
            $(this).hide();
    });
    $('.propmption_popup .close').mousedown(function(){
        $('.propmption_popup').hide();
    });
});