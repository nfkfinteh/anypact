$(document).ready(function(){
    $(".no-reg-wallet-js").click(function () {
        $(".reg-wallet-overflow").fadeIn()
        $('body').css({'overflow': 'hidden', 'padding-right': '17px'});
        $('.reg-wallet-overflow').css('overflow-y', 'scroll');
    });
});